<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 07.12.2015
 * Time: 11:55
 */

namespace App\Http\Services;

class HotWaterKiyvEnergoUserInfo
{
    /**
     * @var
     * true if you have dryer for towel
     * false otherwise
     */
    public $dryer;

    /**
     * @var
     * float between 0.0 and 1.0,
     * this value describe discount for this vendor
     */
    public $relief;

    /**
     * @var
     * integer contains number of energy you have with discount
     * which contains in relief variable
     */
    public $numOfReliefHotWater;
}

class HotWaterKiyvEnergoMonthInfo
{
    /**
     * @var
     * value on your electricity counter that month (in kW*hours)
     */
    public $counter_value;

    /**
     * @var
     * value of money you paid that month
     */
    public $paid_value;
}

class HotWaterKiyvEnergoService extends BasicService
{
    const SERVICE_ALIAS = "HotWater";
    const SERVICE_NAME = "√ор€чее водоснабжение";
    const VENDOR_ALIAS = "KiyvEnergo";
    const VENDOR_NAME = " иевЁнерго";

    const COST_WITH_DRYER = 40.92;
    const COST_WITHOUT_DRYER = 37.91;

    public function layout()
    {
        // TODO: Implement layout() method.
    }

    public function info()
    {
        // TODO: Implement info() method.
    }

    public function create_user_info()
    {
        // TODO: Implement create_user_info() method.
    }

    public function calculate($info_array)
    {
        // TODO: Implement calculate() method.
    }


}