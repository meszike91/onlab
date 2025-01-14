@extends('layouts.layout')

@section('content')
<ul class="registration"> 
    @foreach($registrations as $registration)
        <li> 
            <a href= '/registrations/{{$registration->id}}'>
            <h2>Felhasználó: {{$registration->user->name}}</h2>
            <div>Esemény neve: {{$registration->event->name}}</div>
            <div>Felnőttek száma: {{$registration->adult_headcount}}</div>
            <div>Gyermekek száma: {{$registration->child_headcount}}</div>
            </a>
        </li>
    @endforeach
</ul>
@endsection