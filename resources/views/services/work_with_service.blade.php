@extends("test_layout")
@section("content")
</div>

<div class="ui container grid">
    <div class="stretched row">
        <h2> {{App\Service::where('id',$service_id)->first()['service_name']}}</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur delectus doloremque dolores et expedita hic, libero nemo nesciunt praesentium provident quibusdam suscipit tenetur voluptate. Amet blanditiis, commodi earum mollitia perferendis provident, quas quasi qui repellat reprehenderit ut, veniam. Dolorem dolores eius harum itaque natus nesciunt, nulla possimus suscipit tenetur unde?</p>
    </div>
   <div class="stretched centered row">
       <div class="ui feature segment" id="calculate_segment">
    <a href="{{route('editService',['id'=>$service_id])}}" class="ui button">Изменить данные</a>
    <a href="{{route('beforeCalculate',['id'=>$service_id])}}" class="ui button">Расчитать</a>
    </div>
   </div>
</div>
@endsection