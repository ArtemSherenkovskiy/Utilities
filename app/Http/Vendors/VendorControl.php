<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 27.10.2015
 * Time: 18:29
 */

namespace App\Http\Vendors;
use DB;


include 'EnergyVendor.php';

class VendorControl
{
    public static function generate($id)
    {
        if(null == $id)
        {
            $dbAnswer = DB::select('SELECT * FROM vendors');
            var_dump($dbAnswer);
            $answer = "";
            foreach($dbAnswer as $dbRow)
            {
                //need to create links
            }
        }
        else
        {
            $dbAnswer = DB::select('SELECT * FROM vendors WHERE id = ?', [$id]);
            if(count($dbAnswer) > 0)
            {
                $arrFromDbAnswer = ((array)$dbAnswer[0]);
                $vendorName = 'App\Http\Vendors\\' . $arrFromDbAnswer['name'];
                $bv = new $vendorName;
                return $bv->layout();
            }
            else
            {
                return view('errorVendor');
            }
        }


    }
}