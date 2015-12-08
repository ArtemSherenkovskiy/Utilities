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
<form class="ui form" method="post" action="auth/login ">
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
    <div class="ui grid container"> <div class="centered row">
            <button class="ui button" type="submit">Submit</button>
        </div>
    </div>

</form>
 <div class="ui horizontal divider">
    Or
  </div>
    <div class="ui grid container"> <div class="centered row">
            <button class="ui big green labeled icon button" onclick="reg()">
                <i class="signup icon"></i>
                Sign Up
            </button>
            @yield("register-modal")
        </div>
    </div>

</div>
 </div>
 </div>
 <div class="centered streched row">
 <div class="column"><img class="ui medium centered image" src="https://image.inforesist.org/uploads/1383938477_kommunal_platezh-430x243.jpg" alt=""/></div>
 </div>
     <div class="row">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab accusamus amet atque consectetur culpa doloribus eaque, earum eos ex hic impedit inventore iste maiores minima molestiae nemo numquam omnis porro quas, quidem quo quod quos ullam voluptate voluptatem! Ab accusamus amet corporis cum debitis, doloremque dolorum error explicabo fugiat illo ipsa maiores maxime nemo nulla, odit pariatur quaerat quia quos rem sit soluta veniam voluptates. Aliquid commodi distinctio doloribus et ex, id illum impedit ipsum iure minima modi nemo nesciunt non odio omnis provident, qui quidem, quis ratione recusandae sequi tempora veritatis voluptates. Cupiditate explicabo perferendis porro saepe sequi? Aperiam at atque consectetur culpa deserunt dicta eligendi, error excepturi exercitationem explicabo, in ipsa itaque, nobis quam quia quod repellendus sapiente sunt temporibus ullam. Aliquid aut dignissimos eos, est exercitationem expedita impedit itaque, iure, laudantium magni nam repudiandae similique? Amet at corporis eius eligendi exercitationem expedita hic illo, ipsam itaque minima non numquam quaerat quas recusandae, reprehenderit vero voluptates? At culpa cum dicta distinctio ex fugit, magni, maxime molestiae perspiciatis porro repellat sint, voluptatem voluptatibus! Consectetur, consequuntur cupiditate dignissimos dolor dolores eos est fuga, illum iusto labore, magni maxime quae quaerat quasi quibusdam quis ratione tenetur veniam? Accusantium alias amet aspernatur aut blanditiis commodi corporis debitis dicta dignissimos, dolorum ducimus, eius eligendi enim esse explicabo facilis iste libero neque nesciunt nobis obcaecati perferendis quisquam quo quod ratione recusandae rem repellendus reprehenderit repudiandae tempora velit veniam voluptate voluptatibus. Ad cumque dolorum, hic illo impedit ipsa ipsam iusto officia recusandae, rem sequi, totam velit voluptates. Consequatur ducimus enim impedit ipsam ipsum iusto labore, nam nihil nulla possimus quasi reiciendis reprehenderit soluta tenetur vero voluptas voluptatum. Asperiores debitis dignissimos doloremque doloribus ducimus excepturi laboriosam laborum, maiores maxime minima nam neque nihil omnis pariatur placeat praesentium provident ratione rerum saepe sapiente sequi sit sunt suscipit temporibus ullam veniam voluptas? Ex iure maiores nemo nulla quos reprehenderit repudiandae voluptatibus. Ad aliquam architecto ea magnam nobis omnis perspiciatis quas, quia quis quod recusandae rem repudiandae, sequi? Adipisci aliquam aliquid aperiam at aut autem consectetur cum doloribus ea error exercitationem facere fuga incidunt iste itaque iusto labore magni nobis nulla numquam obcaecati odit officiis praesentium quam, quidem quisquam quo quos ratione sapiente voluptate. Accusantium aliquid aspernatur, assumenda delectus, eaque fugit, id itaque libero nobis nostrum odio optio repellat similique tempora tempore vel voluptatibus. Alias beatae eligendi, enim in itaque magni necessitatibus pariatur tenetur voluptates. Accusantium aut, excepturi illum iure modi necessitatibus nemo nobis quaerat! Beatae, ducimus, enim! Accusamus alias atque commodi cum cupiditate deleniti ea eligendi, est hic laudantium minus nesciunt nobis non, rem repellat sequi tenetur, veniam! Ab harum laboriosam quis reprehenderit vitae. Aspernatur, quae, voluptas. A ab architecto consectetur cum dicta doloremque eius esse facere illum impedit ipsum labore laudantium minima molestias mollitia nam natus necessitatibus nemo nostrum numquam officia omnis perferendis quibusdam quidem quod quos, reiciendis repellat sunt ullam voluptate. Accusantium ad, aliquam, architecto culpa dolorum, ea exercitationem explicabo facere in magni modi nemo obcaecati praesentium quia quibusdam totam vitae! Corporis ipsa optio possimus rerum?</div>
 </div>
@stop