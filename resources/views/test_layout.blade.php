<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properities -->
    <title>Рассчет услуг</title>

    <link rel="stylesheet" type="text/css" href="semantic/semantic.css">
    <link rel="stylesheet" type="text/css" href="css/homepage.css">
    <link rel="stylesheet" type="text/css" href="iconfonts/flaticon.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.js"></script>
    <script src="semantic/semantic.js"></script>
    <script src="script/homepage.js"></script>
    <script src="script/scripts.js"></script>
    <script>
        $(function(){
            $('.ui.card').popup();
        });
    </script>
</head>
<body id="home">
<div class="ui inverted masthead centered segment">
    <div class="ui page grid">
        <div class="column">



            <div class="ui secondary menu">
                <div class="logo item">
                    Utilites
                </div>
                @if(\Auth::guest())
                    <a class="button item">
                        <i class="home icon"></i> Возможности
                    </a>
                    <a class="button item">
                        <i class="grid layout icon"></i> Услуги
                    </a>
                    <a class="button item">
                        <i class="info icon"></i> О нас
                    </a>
                @else
                    <a class="ui animated button item">
                        <div class="hidden content"><i class="edit icon"></i></div>
                        <div class="visible content">Личный кабинет
                        </div>
                    </a>
                    <a class="ui animated button item">
                        <div class="hidden content"><i class="grid layout icon"></i></div>
                        <div class="visible content">Сервисы
                        </div>
                    </a>

                    <div class="right menu">
                        <a href="{{route('logout')}}" class="item button">Выйти</a>
                        <div class="item">
                            <div class="ui icon input">
                                <input placeholder="Search..." type="text">
                                <i class="flaticon-position link icon"></i>
                            </div>
                        </div>

                    </div>

                @endif


            </div>




            </div>
        </div>


@yield('content')


<div class="ui inverted footer vertical segment center">
    <div class="ui stackable center aligned page grid">
        <div class="four column row">

            <div class="column">
                <h5 class="ui inverted header">Courses</h5>
                <div class="ui inverted link list">
                    <a class="item">Registration</a>
                    <a class="item">Course Calendar</a>
                    <a class="item">Professors</a>
                </div>
            </div>
            <div class="column">
                <h5 class="ui inverted header">Library</h5>
                <div class="ui inverted link list">
                    <a class="item">A-Z</a>
                    <a class="item">Most Popular</a>
                    <a class="item">Recently Changed</a>
                </div>
            </div>
            <div class="column">
                <h5 class="ui inverted header">Community</h5>
                <div class="ui inverted link list">
                    <a class="item">BBS</a>
                    <a class="item">Careers</a>
                    <a class="item">Privacy Policy</a>
                </div>
            </div>

            <div class="column">
                <h5 class="ui inverted header">Designed By</h5>
                <addr>
                    <a class="item" href="http://scripteden.com"><img src="images/scripteden-logo-g.png" alt="Logo" style="height:20px" /></a>  <br/>
                    <a href="http://scripteden.com/downloads/bootstrap/">Bootstrap Templates</a>           <br/>
                    <a href="http://scripteden.com/downloads/semantic-ui/">Semantic UI Templates</a>
                </addr>


            </div>
        </div>



    </div>
</div>
</body>

</html>
