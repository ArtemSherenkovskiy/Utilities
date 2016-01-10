@extends('test_layout')
@section('content')
    </div>
   <div class="ui container grid">
       <div class="centered row">
          <div class="ui segment">
              <form class="ui form" action="{{route('calculate')}}" method="post">
                  {!! $input_form !!}
                  <input type="hidden" name="id" value="{{$service_id}}">
                  {!! csrf_field() !!}
                  <button class="ui primary button" type="submit">Рассчитать</button>
                  <button class="ui secondary button" type="reset">Очистить</button>
              </form>
          </div>
       </div>
   </div>
@stop