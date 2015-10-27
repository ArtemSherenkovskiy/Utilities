<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Pizda</title>
    </head>
    <body>

        {{--header--}}
        <div class="header">

        </div>

        {{--navigation--}}
        @if(Auth::guest())
        <div class="nav">
auth guest
              </div>
        @else
          <div class="nav">
user - {{Auth::user()->name}}
                </div>
        @endif
        {{--main information--}}
        <div class="content">
        @yield('content')
        </div>

        {{--footer--}}
        <div class="footer">

        </div>
    </body>
</html>

