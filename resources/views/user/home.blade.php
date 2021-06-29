@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Regisztrációim:') }}</div>

                <div class="card-body">
                <ul> 
                    @foreach($registrations as $registration)
                        <li> 
                            <a href= 'registrations/{{$registration->id}}'>
                            <h2>Esemény: {{$registration->event->name}}</h2>
                            <div>Esemény időpontja: {{$registration->event->date}}</div>
                            <div>Felnőttek száma: {{$registration->adult_headcount}}</div>
                            <div>Gyermekek száma: {{$registration->child_headcount}}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection