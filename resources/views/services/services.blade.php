@extends("test_layout")
@section("content")

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

                            @foreach(\App\Service::where('service_alias','=',$service->service_alias)->get() as $current_service)
                            <div class="description">
                                <a class="" href="{{route('service',['id' => $current_service->id])}}">{{\App\Vendor::find($current_service->vendor_id)->vendor_name}}</a>
                            </div>
                            @endforeach

                    </div>


                    @endforeach
                </div>
            </div>
        </div>
</div>
    </div>
@stop