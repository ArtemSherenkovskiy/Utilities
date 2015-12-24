<?php

namespace App\Http\Controllers;

use App\History;
use App\UserService;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Service;
use App\Vendor;
use App\Http\Services;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $service;
    public function index()
    {
        //$userServices = UserService::where('user_id',\Auth::user()->id)->get();
        //
        //\Log::error($userServices);

        $user_services = UserService::all();
        $user_services_id = [];
        foreach($user_services as $user_service)
        {
            $user_services_id[]=$user_service->service_id;
        }
        $services =  Service::whereNotIn('id',$user_services_id)->groupBy('service_alias')->orderBy('service_name', 'asc')->get();

        //\Log::error($vendors);
         return view('services/services')->with(['services'=>$services,'user_services'=>$user_services]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    public function getService($id=null)
    {
        $this->service  = Services\ServiceControl::generate($id);
        //Cache::put('service',$this->service,10);
        //view()->share('id',$id);
        return $this->service->create_user_info_view();

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //\Log::error('service object'.isset($this->service));
        //
        // $service_name = DB::select('SELECT service_alias,vendor_alias from user_service JOIN vendors where user_service.id ='.$id,';');

        $this->service  =Services\ServiceControl::generate($request->input('id'));
        $this->service->safe($request->input());
        return  redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editService($service_id)
    {
        //
        $this->service  =Services\ServiceControl::generate($service_id);
        return $this->service->create_user_info_view_with_info();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
