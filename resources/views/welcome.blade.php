@extends('test_layout')
@section('content')
    <div class="ui hidden transition information">
        <h1 class="ui inverted centered header">
            Рассчитывайте услуги прямо здесь
        </h1>
        <p class="ui centered lead">Все, что вам нужно присоединиться к нам<br/></p>
        <div class="ui grid container">


            <div class="centered stretched row" >

                <div class="eight wide column">
                    <button href="#" class="large basic inverted animated fade ui button" onclick="reg()">
                        <div class="visible content">Присоедениться</div>
                        <div class="hidden content">Зарегистрироваться</div>
                    </button>

                    @yield("register-modal")

                    <div class="ui horizontal divider">
                        Или
                    </div>
                    <form class="ui form" method="post" action="{{route('loginPost')}}">
                        <div class="field">

                            <p><label for="">Почта</label></p>
                            <div class="ui left icon input">
                                <input type="email" name="email" placeholder="email">
                                <i class="user icon"></i>
                            </div>
                        </div>
                        <div class="field">
                            <p><label for="">Пароль</label></p>

                            <div class="ui left icon input">
                                <input type="password" name="password" placeholder="Password">
                                <i class="lock icon"></i>
                            </div>

                        </div>
                        @if(session('errors'))
                            <div class="ui red message">{{session('errors')}}</div>
                        @endif
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="ui grid container"> <div class="centered row">
                                <button class="large basic inverted animated fade ui button" type="submit">

                                    <div class="visible content">Войти</div>   <div class="hidden content">Войти</div>            </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>




        </div>
    </div>
    </div>

    <div class="ui vertical feature segment">
        <div class="ui centered page grid">
            <div class="fourteen wide column">
                <div class="ui three column center aligned stackable divided grid">
                    <div class="column column-feature">
                        <div class="ui icon header">
                            <i class="flaticon-connecting icon"></i>
                            Рассчет
                        </div>
                        <p>Создайте список сервисов для ежемесячного подсчета</p>

                    </div>
                    <div class="column column-feature">
                        <div class="ui icon header">
                            <i class="flaticon-calendar icon"></i>
                            История
                        </div>
                        <p>Просматривайте историю использования услуг</p>

                    </div>
                    <div class="column column-feature">
                        <div class="ui icon header">
                            <i class="flaticon-speech icon"></i>
                            Сообщество
                        </div>
                        <p>Оставляйте отзывы и комментарии</p>

                    </div>
                </div>



            </div>
        </div>
    </div>








    <div class="ui recent-works vertical segment">
        <div class="ui very relaxed stackable centered page grid">
            <div class="row">
                <div class="eight wide centered column">
                    <h1 class="center aligned ui inverted header">
                        Recent Works
                    </h1>
                    <div class="ui horizontal divider"><i class="white flaticon-camera icon"></i></div>
                    <p class="ui centered lead">Checkout Our Recently Completed Works<br>you will be amazed!.</p>
                </div>
            </div>
            <div class="fourteen wide column">
                <div class="ui three column aligned stackable divided grid">



                    <div class="column">

                        <div class="ui card" data-html="<div class='header'>User Rating</div><div class='content'><div class='ui star rating'><i class='active icon'></i><i class='active icon'></i><i class='active icon'></i><i class='icon'></i><i class='icon'></i></div></div>">
                            <div class="image">
                                <img src="images/totoro-horizontal.jpg">
                            </div>
                            <div class="content">
                                <div class="header">My Neighbor Totoro</div>
                                <div class="description">
                                    Two sisters move to the country with their father in order to be closer to their hospitalized mother, and discover the surrounding trees are inhabited by magical spirits.
                                </div>
                            </div>
                            <div class="ui two bottom attached buttons">
                                <div class="ui button">
                                    <i class="flaticon-plus icon"></i>
                                    Queue
                                </div>
                                <div class="ui pink button">
                                    <i class="flaticon-play icon"></i>
                                    Watch
                                </div>
                            </div>
                        </div>

                    </div>



                    <div class="column">

                        <div class="ui card" data-html="<div class='header'>User Rating</div><div class='content'><div class='ui star rating'><i class='active icon'></i><i class='active icon'></i><i class='active icon'></i><i class='icon'></i><i class='icon'></i></div></div>">
                            <div class="image">
                                <img src="images/totoro-horizontal.jpg">
                            </div>
                            <div class="content">
                                <div class="header">My Neighbor Totoro</div>
                                <div class="description">
                                    Two sisters move to the country with their father in order to be closer to their hospitalized mother, and discover the surrounding trees are inhabited by magical spirits.
                                </div>
                            </div>
                            <div class="ui two bottom attached buttons">
                                <div class="ui button">
                                    <i class="flaticon-plus icon"></i>
                                    Queue
                                </div>
                                <div class="ui pink button">
                                    <i class="flaticon-play icon"></i>
                                    Watch
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="column">

                        <div class="ui card" data-html="<div class='header'>User Rating</div><div class='content'><div class='ui star rating'><i class='active icon'></i><i class='active icon'></i><i class='active icon'></i><i class='icon'></i><i class='icon'></i></div></div>">
                            <div class="image">
                                <img src="images/totoro-horizontal.jpg">
                            </div>
                            <div class="content">
                                <div class="header">My Neighbor Totoro</div>
                                <div class="description">
                                    Two sisters move to the country with their father in order to be closer to their hospitalized mother, and discover the surrounding trees are inhabited by magical spirits.
                                </div>
                            </div>
                            <div class="ui two bottom attached buttons">
                                <div class="ui button">
                                    <i class="flaticon-plus icon"></i>
                                    Queue
                                </div>
                                <div class="ui pink button">
                                    <i class="flaticon-play icon"></i>
                                    Watch
                                </div>
                            </div>
                        </div>

                    </div>




                </div>
            </div>
        </div>
    </div>


    <div class="ui vertical segment">
        <div class="ui stackable center aligned page grid">
            <div class="row">
                <div class="eight wide column">
                    <h1 class="ui header">
                        Мы в других сообществах
                    </h1><div class="ui horizontal divider"><i class="flaticon-settings icon"></i></div>
                    <p class="ui centered lead">

                    </p>
                    <br/>
                </div>
            </div>
            <div class="four column logo row">
                <div class="column">
                    <div class="ui shape">
                        <div class="sides">
                            <div class="active side">
                                <i class="huge flaticon-facebook icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-google icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-twitter icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-pinterest icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-google icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-more icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="ui shape">
                        <div class="sides">
                            <div class="side">
                                <i class="huge flaticon-google icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-more icon"></i>
                            </div>
                            <div class="active side">
                                <i class="huge flaticon-twitter icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-facebook icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-google icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-twitter icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column">
                    <div class="ui shape">
                        <div class="sides">
                            <div class="active side">
                                <i class="huge flaticon-facebook icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-google icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-twitter icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-pinterest icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-google icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-more icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="ui shape">
                        <div class="sides">
                            <div class="side">
                                <i class="huge flaticon-google icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-more icon"></i>
                            </div>
                            <div class="active side">
                                <i class="huge flaticon-twitter icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-facebook icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-google icon"></i>
                            </div>
                            <div class="side">
                                <i class="huge flaticon-twitter icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop