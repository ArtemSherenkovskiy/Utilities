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

                        <a class="ui inverted red huge button" href="{{route('workWithService',['id'=>$userService->service_id])}}">
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
                @if(count($services)>=1)

                    @foreach($services as $service)

                    <div class="ui card" id="service_card">
                        <div class="content">
                            <div class="header">
                                <p>{{$service['service_name']}}</p>
                            </div>
                        </div>
                        @foreach(\App\Service::where('service_alias', '=', $service->service_alias)->get() as $service_with_alias)
                        <a class="ui inverted bottom attached red button" href="{{route('service',['id' => $service_with_alias->id])}}">
                            <p>{{App\Vendor::find($service_with_alias->vendor_id)->vendor_name}}  </p>
                        </a>
                        @endforeach
                    </div>


                    @endforeach
                    @else
                    <div class="centered row">
                        <h2>Вы добавили все доступные сервисы</h2>
                    </div>
                    @endif
                </div>
            </div>
        </div>
            </div>
</div>
    </div>
@stop