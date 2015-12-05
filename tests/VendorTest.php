<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Vendors\EnergyVendor;

class VendorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $enr_vendor = new EnergyVendor();

        $this->assertEquals($enr_vendor->layout(),'Implemented method from BasicVendor');
    }
}
