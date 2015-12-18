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
        assertEquals(\App\Http\Services\ServiceControl::generate(0), 'errorVendor');
        assertStringEndsWith('</html>',\App\Http\Services\ServiceControl::generate(1));
        assertNotEmpty(\App\Http\Services\ServiceControl::generate(null));

    }

    public function testDataBase()
    {
        $service = new \App\Service(['service_alias' => 'Internet', 'service_name' => 'Интернет', 'vendor_id' => 1);
        $service->save();
        $service_from_db = App\Http\Service::whereRaw("service_alias = 'Internet' and vendor_id = 1")->get()[0];
        assertEquals($service->id, $service_from_db->id);
    }
}
