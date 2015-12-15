<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 27.10.2015
 * Time: 18:29
 */

namespace App\Http\Services;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Service;



class ServiceControl
{

    public static function generate($id)
    {

        if(null == $id)
        {
            $output = array();
            $services = DB::table('services')->groupBy('service_name')->get();
            foreach($services as $service)
            {
                $vendors = DB::table("services")
                    ->leftJoin("vendors", "services.vendor_id", "=", "vendors.id")
                    ->select("vendors.vendor_name")
                    ->where("services.service_alias", "=", $service->service_alias  )
                    ->get();
                $vendor_name = array();
                foreach($vendors as $vendor)
                {

                    array_push($vendor_name, $vendor->vendor_name);
                }
                $output[$service->service_name] = $vendor_name;

            }
            var_dump(serialize($output));
        }
        else
        {
            $dbAnswer = DB::select('SELECT services.service_alias, vendors.vendor_alias FROM services LEFT JOIN vendors ON services.vendor_id = vendors.id WHERE services.id = ?', [$id]);
            if(count($dbAnswer) > 0)
            {
                $arrFromDbAnswer = ((array)$dbAnswer[0]);
                $vendorName = 'App\Http\Services\\' . $arrFromDbAnswer['service_alias']. $arrFromDbAnswer['vendor_alias'] . 'Service';
                $bv = new $vendorName($id);
                //$bv->create_user_info();
                return $bv->layout();

            }
            else
            {
                return view('errorVendor');
            }
        }


    }
}