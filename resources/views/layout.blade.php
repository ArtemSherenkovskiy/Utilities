<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Raschet</title>
        <script src="/js/jquery-2.1.3.min.js"></script>
       <link rel="stylesheet" href="semantic/semantic.css">
        <link rel="stylesheet" href="css/style.css">
       <link rel="script" href="semantic/semantic.js"

    </head>
    <body>

        {{--header--}}
        <div class="header">
       <div class="ui inverted labeled attached large menu">
         <div class="ui container">
        <div class="header item" id="logo">
          <i class="calculator icon"></i>Расчет
          </div>
           <a class="item">
             <i class="home icon"></i> Возможности
           </a>
           <a class="item">
             <i class="grid layout icon"></i> Услуги
           </a>
           <a class="red item">
             <i class="info icon"></i> О нас
           </a>

           </div>
           <div class="right item">
             <div class="ui input"><input type="text" placeholder="Search..."></div>
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

