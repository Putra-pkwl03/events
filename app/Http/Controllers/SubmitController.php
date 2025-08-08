<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Event;
use App\Models\EventSubmission;
use App\Models\EventSubmissionAnswer;
use Carbon\Carbon;
use Imagick;
use ImagickDraw;
use ImagickPixel;

class SubmitController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $query = $request->input('query');
    
        $upcomingEvents = Event::where('event_date', '>=', $now)
            ->orderBy('event_date', 'asc')
            ->limit(4)
            ->get();
        
        return view('partisipan.home', compact('upcomingEvents'));
    }

    public function show(Request $request)
    {
        $now = Carbon::today();
        $query = $request->input('query');
        $kategori = $request->input('kategori');

        // Ambil 8 event pertama
        $upcomingEvents = Event::where('event_date', '>=', $now)
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->when($kategori, function ($q) use ($kategori) {
                $q->where('category', $kategori);
            })
            ->orderBy('event_date', 'asc')
            ->take(8)
            ->get();

        return view('partisipan.event', compact('upcomingEvents', 'query', 'kategori'));
    }

    public function loadMore(Request $request)
    {
        $now = Carbon::today();
        $query = $request->input('query');
        $kategori = $request->input('kategori');
        $offset = intval($request->input('offset', 0));
        $limit = intval($request->input('limit', 4));

        $events = Event::where('event_date', '>=', $now)
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->when($kategori, function ($q) use ($kategori) {
                $q->where('category', $kategori);
            })
            ->orderBy('event_date', 'asc')
            ->skip($offset)
            ->take($limit)
            ->get();

        $view = view('partials._card_list', ['events' => $events])->render();

        return response()->json([
            'html' => $view,
            'count' => $events->count(),
        ]);
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $now = Carbon::now();
        // Ambil semua event dari hari ini ke depan
        $upcomingEvents = Event::whereDate('event_date', '>=', $now)
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->orderBy('event_date', 'asc')
            ->get();
            
        
        return view('partisipan.event', [
            'upcomingEvents' => $upcomingEvents,
            'query' => $query,
        ]);
    }

    public function form(Event $event)
    {
        $event->load('fields');
        return view('partisipan.event.form', compact('event'));
    }

    public function submit(Request $request, Event $event)
    {
        // 1. Validasi
        $rules = [];
        foreach ($event->fields as $field) {
            $rules[$field->label] = $field->required ? 'required|string' : 'nullable|string';
        }
        $validated = $request->validate($rules);

        // 2. Simpan submission
        $submission = $event->submissions()->create([
            'submitted_at' => now(),
            'token' => Str::uuid(),
            'is_present' => false
        ]);

        // 3. Simpan jawaban partisipan
        foreach ($event->fields as $field) {
            EventSubmissionAnswer::create([
                'submission_id' => $submission->id,
                'field_id' => $field->id,
                'value' => $validated[$field->label] ?? ''
            ]);
        }

        // 4. Generate QR Code PNG mentah
        $checkinUrl = config('app.url') . route('absen.checkin', ['token' => $submission->token], false);
        $rawQr = QrCode::format('png')->size(300)->generate($checkinUrl);

        // 5. Bungkus QR Code dengan frame + teks menggunakan Imagick
        $qr = new Imagick();
        $qr->readImageBlob($rawQr);

        $width = 500;
        $height = 650;
        $canvas = new Imagick();
        $canvas->newImage($width, $height, new ImagickPixel('white'));
        $canvas->setImageFormat('png');

        // Gambar judul
        $draw = new ImagickDraw();
        $draw->setFillColor('black');
        $draw->setFontSize(24);
        $draw->setFontWeight(600);
        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
        $canvas->annotateImage($draw, $width/2, 40, 0, "QR Code Kehadiran");

        // Gambar nama event
        $draw->setFontSize(20);
        $canvas->annotateImage($draw, $width/2, 80, 0, $event->title);

        // Tempel QR di tengah
        $qrX = ($width - $qr->getImageWidth()) / 2;
        $qrY = 120;
        $canvas->compositeImage($qr, Imagick::COMPOSITE_DEFAULT, $qrX, $qrY);

        // Nama pendaftar di bawah QR
        $namaPendaftar = $validated[$event->fields->first()->label] ?? 'Pendaftar';
        $draw->setFontSize(18);
        $canvas->annotateImage($draw, $width/2, $qrY + $qr->getImageHeight() + 40, 0, "Nama: {$namaPendaftar}");

        // Instruksi
        $draw->setFontSize(14);
        $canvas->annotateImage($draw, $width/2, $qrY + $qr->getImageHeight() + 70, 0, "Tunjukkan QR ini saat hadir di lokasi.");

        // 6. Simpan hasil akhir
        $fileName = 'qrcodes/' . $submission->token . '_framed.png';
        Storage::disk('public')->put($fileName, $canvas->getImageBlob());

        // 7. Simpan path QR ke database
        $submission->update([
            'qr_code_path' => $fileName
        ]);

        $qrPath = asset('storage/' . $fileName);

        // 8. Redirect balik
        return redirect()
            ->route('partisipan.event.form', $event->id)
            ->with([
                'qr_wrapped' => 'storage/' . $fileName, // path relative
                'qr_link' => $checkinUrl,
                'nama_pendaftar' => $namaPendaftar
            ]);
    }

}
