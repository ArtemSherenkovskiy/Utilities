@extends('test_layout')
@section('content')
    </div>
    <form class="ui form" method="post"  action="{{route('saveToHistory')}}">
        {!! $result_form !!}
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{$service_id}}"/>
        <button class="ui primary button" type="submit">Сохранить в истории</button>
        <a class="ui primary button" href="{{route('home')}}">Вернуться к серивисам</a>
    </form>
@stop