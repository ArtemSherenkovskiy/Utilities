@extends('layout')
@section('content')
 <div class="ui grid container">
 <div class="row"></div>
 <div class="row"></div>

 <div class="centered stretched row" >
 <div class="eight wide column">
<div class="ui segment">
 <h2>Надоело считать вручную - тогда присоединяйся к нам</h2>

 </div>
 </div>
 <div class="eight wide column">
<div class="ui segment">
<form class="ui form">
  <div class="field">
    <label>Username</label>
            <div class="ui left icon input">
              <input type="text" name="username" placeholder="Username">
              <i class="user icon"></i>
  </div>
  </div>
  <div class="field">
    <label>Password</label>
    <input type="password" name="password" placeholder="Password">
  </div>


 <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <button class="ui button" type="submit">Submit</button>
</form>
 <div class="ui horizontal divider">
    Or
  </div>
  <div class="ui big green labeled icon button">
        <i class="signup icon"></i>
        Sign Up
      </div>
</div>
 </div>
 </div>
 <div class="centered streched row">
 <div class="column"><img class="ui medium centered image" src="https://image.inforesist.org/uploads/1383938477_kommunal_platezh-430x243.jpg" alt=""/></div>
 </div>
 </div>
@stop