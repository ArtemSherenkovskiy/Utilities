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
        $dbAnswer = DB::select('SELECT * FROM vendors WHERE id = ?', [$id]);
        $className = ((array) $dbAnswer[0]);
        $vendorName = 'App\Http\Vendors\\'.$className['name'];
        $bv = new $vendorName;
        return $bv->layout();


    }
}