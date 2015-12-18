@extend('layout')
@section('content')
    <form class="ui form">
        {!! $input_form !!}
        <button class="ui primary button" type="submit">Рассчитать</button>
        <button class="ui secondary button" type="reset">Очистить</button>
    </form>
@stop