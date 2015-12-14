<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 27.10.2015
 * Time: 18:29
 */

namespace App\Http\Services;
use DB;
use App\Service;



class ServiceControl
{

    public static function generate($id)
    {

        if(null == $id)
        {
                $services = Service::get();
            foreach($services as $service)
            {
                echo $service->service_name . PHP_EOL;
            }
        }
        else
        {
            $dbAnswer = DB::select('SELECT services.service_alias, vendors.vendor_alias FROM services LEFT JOIN vendors ON services.vendor_id = vendors.id WHERE services.id = ?', [$id]);
            if(count($dbAnswer) > 0)
            {
                $arrFromDbAnswer = ((array)$dbAnswer[0]);
                var_dump($arrFromDbAnswer);
                $vendorName = 'App\Http\Services\\' . $arrFromDbAnswer['service_alias']. $arrFromDbAnswer['vendor_alias'] . 'Service';
                $bv = new $vendorName;
                return $bv->calculate(array(0,160));

            }
            else
            {
                return view('errorVendor');
            }
        }


    }
}