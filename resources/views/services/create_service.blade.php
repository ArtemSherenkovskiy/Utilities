@extends("layout")
@section("content")
<div class="ui container grid">
<div class="row"></div>
<div class="centered row">
<div class="six wide column">
<form class="ui form" action="{{route('saveService')}}" method="post">
    {!!$layout!!}
     {!! csrf_field() !!}
<button class="ui primary button" type="submit">
    Сохранить
</button>
<button class="ui button" type="reset">
    Стереть
</button>
</form>
</div>

</div>
</div>
@stop