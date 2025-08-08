<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventField;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('fields')->latest()->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

public function store(Request $request)
{
    $request->validate([
        'title'      => 'required|string',
        'category'   => 'required|string', 
        'event_date' => 'required|date',
        'poster'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'fields'     => 'required|array',
    ]);

    // Upload poster
    $posterPath = null;
    if ($request->hasFile('poster')) {
        $posterPath = $request->file('poster')->store('posters', 'public');
    }

    $event = Event::create([
        'title'       => $request->title,
        'category'    => $request->category,
        'description' => $request->description,
        'event_date'  => $request->event_date,
        'poster'      => $posterPath,
    ]);

    foreach ($request->fields as $field) {
        EventField::create([
            'event_id' => $event->id,
            'label'    => $field['label'],
            'type'     => $field['type'],
            'options'  => !empty($field['options']) ? explode(',', $field['options']) : null,
        ]);
    }

    return redirect()->route('admin.dashboard')->with('success', 'Event created successfully');
}


    public function show($id)
    {
        $event = Event::with('fields')->findOrFail($id);

        return view('admin.events.show', compact('event'));
    }
}
