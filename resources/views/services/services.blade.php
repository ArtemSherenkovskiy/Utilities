@extends("layout")
@section("content")
<div class="ui grid container">
<div class="row">


      <div class="ui button" onclick=reg()>
      Добавить услугу

      </div>
      <div class="ui modal">
      <div class="ui container">
        <div class="ui cards">

        @foreach($services as $service)

        <div class="ui card">
         <div class="content">
                <div class="header">
                {{$service['service_name']}}</div>
                </div>
                <div class="description">
               {{App\Vendor::find($service->vendor_id)->description}}
                </div>
                </div>





        @endforeach
      </div>
</div>
       </div>
      </div>
       </div>
@stop