@extends("welcome")
@section("register-modal")
    <div class="ui small modal">
        <div class="header">Регистрация</div>
        <div class="content">

           <div class="ui segment">
               <form class="ui form" method="post" action="auth/register">
                   {!! csrf_field() !!}
                   <div class="two fields">
                       <div class="field">
                           <label>Имя</label>
                           <input type="text" name="first_name" value="{{old('first_name')}}">
                       </div>
                       <div class="field">
                           <label>Фамилия</label>
                           <input type="text" name="second_name" value="{{old('second_name')}}">
                       </div>
                   </div>
                   <div class="field">
                       <label>Email</label>
                       <input type="email" name="email" value="{{old('email')}}">
                   </div>
                   <div class="field">
                       <label>Пароль</label>
                       <input type="password" name="password">
                   </div>
                   <div class="field">
                       <label>Подтверждение пароля</label>
                       <input type="password" name="confirm_password">
                   </div>
                    <div class="ui horizontal divider">Необезательное</div>
                   <div class="field">
                       <label>Количество проживающих в квартире</label>
                       <input type="text" name="num_of_people">
                   </div>
                   <div class="field">
                       <label>Площадь квартиры</label>
                       <input type="text" name="flat_square">
                   </div>
                   <button type="submit" class="ui button">Продолжить</button>
               </form>
           </div>
        </div>
    </div>
@endsection
