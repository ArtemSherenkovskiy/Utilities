<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
    <head>
        <meta charset="UTF-8">
        <title>Raschet</title>
        <script src="script/jquery-2.1.3.min.js"></script>
       <link rel="stylesheet" href="semantic/semantic.css">
        <link rel="stylesheet" href="css/style.css">
       <script src="semantic/semantic.js"></script>
       <script src="script/scripts.js"></script>

    </head>
    <body>

        {{--header--}}
        <div class="ui sticky">
       <div class="ui inverted labeled attached large menu">

        <div class="header item" id="logo">
          <i class="calculator icon"></i>Расчет
          </div>
          @if(\Auth::guest())
           <a class="item">
             <i class="home icon"></i> Возможности
           </a>
           <a class="button item">
             <i class="grid layout icon"></i> Услуги
           </a>
           <a class="red item">
             <i class="info icon"></i> О нас
           </a>
           @else
           <a class="ui animated button item" ><div class="hidden content"><i class="edit icon"></i></div>
             <div class="visible content">Личный кабинет
                       </div>
            </a>
            <a class="ui animated button item" ><div class="hidden content"><i class="grid layout icon"></i></div>
             <div class="visible content">Сервисы
                       </div>
            </a>

            <a href="{{route('logout')}}" class="ui right item button">Выйти</a>

           @endif

           <div class="right item">
             <div class="ui input"><input type="text" placeholder="Поиск"></div>

           </div>
         </div>
       </div>
        </div>

        {{--navigation--}}

        {{--main information--}}
        <div class="content">

        @yield('content')
        </div>


        {{--footer--}}
        <div class="footer">

        </div>
    </body>
</html>

