<form method="post" action="/registrations/{{$event->id}}/{{Auth::user()->id}}">
    @csrf

    <div>
    <label> Regisztráció {{$event->name}}  eseményre</label>
    </div>

<div>
  <label for="aduld_headcount">Hány felnőttel érkezik?</label><br>
  <input type="number" id="headcount" name="adult_headcount"><br>

  <label for="child_headcount">Hány gyermekkel érkezik?</label><br>
  <input type="number" id="headcount" name="child_headcount"><br>

</div>
<div>
<input type="submit">
</div>

</form>