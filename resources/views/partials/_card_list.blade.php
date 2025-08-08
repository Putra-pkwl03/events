@foreach ($events as $event)
    @include('partials._card', ['event' => $event])
@endforeach
