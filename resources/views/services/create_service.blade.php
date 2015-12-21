@extends("test_layout")
@section("content")
    </div>
<div class="ui vertical segment">
   <div class="ui very relaxed stackable centered page grid">
<div class="centered row">
<div class="six wide column">
<form class="ui form" action="{{route('saveService')}}" method="post">
    {!!$layout!!}
     {!! csrf_field() !!}
<button class="ui primary button" type="submit">
    Сохранить
</button>
<button class="ui button">
    Стереть
</button>
</form>
</div>
</div>
</div>
</div>
@stop