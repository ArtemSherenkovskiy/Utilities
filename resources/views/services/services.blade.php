@extends("layout")
@section("content")
<div class="ui grid container">
    <div class="row">


        <div class="ui button" onclick=reg()>
            Добавить услугу

        </div>
        <div class="ui modal">
            <div class="ui segment  ">
                <div class="ui list">

                    @foreach($services as $service)

                    <div class="ui item">
                        <div class="content">
                            <div class="header">
                                {{$service['service_name']}}
                            </div>
                        </div>
                        <div class="description">
                            <a class="" href="{{route('service',['id'=>App\Vendor::find($service->vendor_id)->id])}}">
                            {{App\Vendor::find($service->vendor_id)->vendor_name}}
                            </a>
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