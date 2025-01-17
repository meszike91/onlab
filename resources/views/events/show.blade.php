@extends('layouts.layout')

@section('content')
<div class="event-show">
    <h1> {{$event->name}} </h1>
    <div> Leírás: {{$event->description}} </div>
    <div> Létszám: {{$event->headcount}} fő </div>
    <div> Szabad helyek: {{$event->free_places()}}</div>
    @can('update', $event)
    <div> Jelenlévő gyerekek: {{$event->reserved_childs()}}</div>
    @endcan
    @can('update', $event)
    <div> Jelenlévő felnőttek: {{$event->reserved_adults()}}</div>
    @endcan
    <div> Időpont: {{$event->date}} </div>
    <div> Helyszín: {{$event->location}} </div>
<div>
    
    <ul class="button-row">
        @if($event->isActive()==true)
        <li>
            <form action="/registrations/create/{{$event->id}}">
            <button class="button" type="submit">Regisztráció</button>
            </form>
        </li>
        @endif
        @can('update', $event)
        <li>
            <form action="/events/{{$event->id}}/edit">
                <button class="button" type="submit">Szerkesztés</button>
            </form>
        </li>
        @endcan
        @can('delete', $event)
        <li>
            <form method="post" action="/events/{{$event->id}}"> 
                @csrf
                @method('DELETE')
                <button class="button" type="submit">Törlés</button>
            </form>
        </li>
        @endcan
    </ul>

    @if($event->google_maps)
        <h2>Térkép:</h2>
        {!!$event->google_maps!!}
    @endif

@endsection
