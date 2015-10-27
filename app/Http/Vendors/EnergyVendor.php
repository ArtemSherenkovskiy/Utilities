<?php

/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 27.10.2015
 * Time: 18:32
 */

namespace App\Http\Vendors;


class EnergyVendor extends BasicVendor
{

    function __construct()
    {
        
    }

    public function layout()
    {
        return 'Implemented method from BasicVendor';
    }

}