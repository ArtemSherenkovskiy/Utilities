@extends("test_layout")
@section("content")
</div>
<div class="ui vertical segment">
   <div class="ui very relaxed stackable centered page grid">
<div class="centered row">
<div class="eight wide column">
<form class="ui form" action="{{route('saveService')}}" method="post">
    <div class="ui container">
    {!! csrf_field() !!}
    {!!$layout!!}
        <input type="hidden" name="id" value="{{$id}}">
        <div class="inline field" id="create_service_buttons">
            <button class="ui button" type="submit">
                Сохранить
            </button>
            <button class="ui button" type="reset">
                Стереть
            </button>
        </div>
        <div class="inline field"></div>



    </div>
</form>
</div>
</div>
</div>
</div>
@stop