@extends("test_layout")
@section("content")
    </div>
    <div class="ui vertical segment">
        <div class="ui very relaxed stackable centered page grid">

        <div class="ui button" onclick=reg()>
            Добавить услугу

        </div>
        <div class="ui modal">
            <div class="ui segment  ">
                <div class="ui bulleted list">

                    @foreach($services as $service)

                    <div class="ui item">
                        <div class="content">
                            <div class="header">
                                {{$service['service_name']}}
                            </div>
                        </div>
                        <div class="description">
                            <a class="" href="{{route('service',['id' => $service->id])}}">
                           <p>{{App\Vendor::find($service->vendor_id)->vendor_name}}  </p>
                            </a>
                            <p>{{App\Vendor::find($service->vendor_id)->description}} </p>
                        </div>
                    </div>


                    @endforeach
                </div>
            </div>
        </div>
</div>
    </div>
@stop