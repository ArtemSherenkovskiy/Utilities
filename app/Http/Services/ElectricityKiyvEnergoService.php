<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 04.12.2015
 * Time: 12:18
 */

namespace App\Http\Services;


use App\Http\Services\BasicService;

class ElectricityKiyvEnergoUserInfo
{
    /**
     * @var
     * true for people living in residential houses
     * false for people living in the countryside
     */
    public $localiton;

    /**'
     * @var
     * true for home with electric cooker
     * false otherwise
     */
    public $electric_cooker;


    /**
     * @var
     * float between 0.0 and 1.0,
     * this value describe discount for this vendor
     */
    public $relief;


    /**
     * @var
     * false for houses without gas supplies and without (or with not working) central heat supply, or with electric heat supply
     * true otherwise
     */
    public $heat_supply;


    /**
     * @var
     * true for large or foster families or for orphanage
     * false otherwise
     */
    public $child_support;


    /**
     * @var
     * true for houses with common metric device
     * false otherwise
     */
    public $common_metric;


    /**
     * @var
     * true hostels with true $common_metric
     * false otherwise
     */
    public $common_metric_hostel;




    public function __constructor()
    {
        $this->localiton = true;
        $this->electric_cooker = true;
        $this->relief = 0.0;
        $this->heat_supply = true;
        $this->child_support = false;
        $this->common_metric = false;
        $this->common_metric = false;
    }

    /**
     * ElectricityInfo constructor.
     * @param $localiton
     * @param $electric_cooker
     * @param $relief
     * @param $heat_supply
     * @param $child_support
     * @param $common_metric
     * @param $common_metric_hostel
     */
    public function __construct1($localiton, $electric_cooker, $relief, $heat_supply, $child_support, $common_metric, $common_metric_hostel)
    {
        $this->localiton = $localiton;
        $this->electric_cooker = $electric_cooker;
        $this->relief = $relief;
        $this->heat_supply = $heat_supply;
        $this->child_support = $child_support;
        $this->common_metric = $common_metric;
        $this->common_metric_hostel = $common_metric_hostel;
    }


}


class ElectricityKiyvEnergoMonthInfo
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


class ElectricityKiyvEnergoService extends BasicService
{
    /**
     * constant contains alias  of this
     */
    const VENDOR_ALIAS = "Electricity";
    const VENDOR_NAME = "Электроэнергия";
    const COMPANY_NAME = "КиевЭнерго";

    const COST_FIRST = 45.60;
    const COST_SECOND = 78.90;
    const COST_THIRD = 147.90;

    const MAX_FIRST_CITY = 100.0;
    const MAX_FIRST_COUNTYSIDE = 150.0;
    const MAX_SECOND = 600.0;
    const MAX_THIRD = 3600.0;

    private $user_info;

    public function __construct()
    {
        $this->user_info = new ElectricityKiyvEnergoUserInfo();
    }

    /**
     * ElectricityVendor constructor.
     * @param $user_info
     */
    public function __construct1(ElectricityKiyvEnergoUserInfo $user_info)
    {
        $this->user_info = $user_info;
    }


    public function layout()
    {
        return 'ElectricityKiyvEnergoService';
        // TODO: Implement layout() method.
    }

    public function info()
    {
        // TODO: Implement info() method.
    }

    public function create_user_info()
    {
        // TODO: Implement createUserInfo() method.
    }

    /**
     * @param $counter_values_array
     * $counter_values_array[0] must contains last month counter value
     * $counter_values_array[1] must contains current counter value
     * @return int
     * return costs of used energy
     *
     */
    public function calculate($counter_values_array)
    {
        $energy_used = $counter_values_array[1] - $counter_values_array[0];
        $energy_cost = 0;
        if($this->user_info->common_metric)
        {
            if($this->user_info->common_metric_hostel)
            {
                $energy_cost += $energy_used * self::COST_FIRST;
            }
            else
            {
                $energy_cost += $energy_used * self::COST_SECOND;
            }
        }
        elseif($this->user_info->child_support)
        {
            $energy_cost += $energy_used * self::COST_FIRST;
        }
        elseif($this->user_info->heat_supply)
        {
            if($energy_used > self::MAX_THIRD)
            {
                $energy_cost += self::MAX_THIRD * self::COST_FIRST;
                $energy_used -= self::MAX_THIRD;
                $energy_cost += $energy_used * self::COST_THIRD;
            }
            else
            {
                $energy_cost += $energy_used * self::COST_FIRST;
            }
        }
        elseif($this->user_info->localiton)
        {
            $energy_cost += self::calculateLocationCost(self::MAX_FIRST_CITY, self::MAX_SECOND, $energy_used);
        }
        else
        {
            $energy_cost += self::calculateLocationCost(self::MAX_FIRST_COUNTYSIDE, self::MAX_SECOND, $energy_used);
        }
        return $energy_cost / 100;
    }

    private function calculateLocationCost($max_first, $max_second, $energy_used)
    {
        $cost = 0;
        if($energy_used > $max_first)
        {
            $cost += $max_first * self::COST_FIRST;
            if($energy_used > $max_second)
            {
                $cost += self::COST_SECOND * ($max_second - $max_first);
                $energy_used -= $max_second;
                $cost += $energy_used * self::COST_THIRD;
            }
            else
            {
                $cost += self::COST_SECOND * ($energy_used - $max_first);
            }
        }
        else
        {
            $cost += $energy_used * self::COST_FIRST;
        }
        return $cost;
    }


}