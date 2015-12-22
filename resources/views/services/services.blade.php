@extends("test_layout")
@section("content")
    </div>

    <div class="ui vertical segment">
        <div class="ui very relaxed stackable page grid">
            <div class="row">
            <h2 class="ui header">Здраствуйте {{\Auth::user()->first_name}} {{\Auth::user()->second_name}}</h2>
            </div>
            <div class="stretched row">
                <div class="ui bulleted aligned list">
                    <div class="ui header">
                        Ваши услуги:
                    </div>
                    @foreach($user_services->where('user_id',\Auth::user()->id)  as $userService )

                        <a class="ui inverted red huge button" href="{{route('editService',['id'=>$userService->service_id])}}">
                            {{App\Service::where('id',$userService->service_id)->first()['service_name']}}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        </div>
            <div class="ui inverted vertical segment">
        <div class="ui very relaxed centered container grid">

        <div class="ui very huge button" onclick=reg()>
            Добавить услугу

        </div>
        <div class="ui modal">
            <div class="ui vertical segment  ">
                <div class="ui very relaxed centered stackable page grid">
                <div class="ui cards">

                    @foreach($services as $service)

                    <div class="ui card" id="service_card">
                        <div class="content">
                            <div class="header">
                                <p>{{$service['service_name']}}</p>
                            </div>
                        </div>
                        <div class="description">

                                        <p>{{App\Vendor::find($service->vendor_id)->description}} </p>
                        </div>
                        <a class="ui inverted bottom attached red button" href="{{route('service',['id' => $service->id])}}">
                            <p>{{App\Vendor::find($service->vendor_id)->vendor_name}}  </p>
                        </a>
                    </div>


                    @endforeach
                </div>
            </div>
        </div>
            </div>
</div>
    </div>
@stop