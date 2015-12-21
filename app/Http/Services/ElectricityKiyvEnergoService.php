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
    public $location;

    /**'
     * @var
     * true for home with electric cooker
     * false otherwise
     */
    public $electric_cooker;


    /**
     * @var
     * float between 0.0 and 1.0,
     * this value describe discount for this service
     */
    public $relief;

    /**
     * @var
     * integer contains number of energy you have with discount
     * which contains in relief variable
     */
    public $num_of_relief_energy;


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
        $this->location = true;
        $this->electric_cooker = true;
        $this->relief = 0.0;
        $this->num_of_relief_energy = 0;
        $this->heat_supply = true;
        $this->child_support = false;
        $this->common_metric = false;
        $this->common_metric = false;
    }

    /**
     * ElectricityInfo constructor.
     * @param $location
     * @param $electric_cooker
     * @param $relief
     * @param $num_of_relief_energy
     * @param $heat_supply
     * @param $child_support.
     * @param $common_metric
     * @param $common_metric_hostel
     */
    public function __construct1($location, $electric_cooker, $relief, $num_of_relief_energy, $heat_supply, $child_support, $common_metric, $common_metric_hostel)
    {
        $this->location = $location;
        $this->electric_cooker = $electric_cooker;
        $this->relief = $relief;
        $this->num_of_relief_energy = $num_of_relief_energy;
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
    const SERVICE_ALIAS = "Electricity";
    const SERVICE_NAME = "Электроэнергия";
    const VENDOR_ALIAS = "KiyvEnergo";
    const VENDOR_NAME = "КиевЭнерго";


    const COST_FIRST = 45.60;
    const COST_SECOND = 78.90;
    const COST_THIRD = 147.90;

    const MAX_FIRST_CITY = 100.0;
    const MAX_FIRST_COUNTRYSIDE = 150.0;
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


    public function before_calculate_layout()
    {

    }

    public function info()
    {
        // TODO: Implement info() method.
    }

    public function create_user_info_view()
    {
        $answer = '<div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="location">
            <label>Я живу в городе или ПГТ.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="electric_cooker">
            <label>У меня дома электроплита.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="heat_supply">
            <label>У меня дома электроотопление/отсутствует центральное отпление.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="child_support">
            <label>Мы многодетная семья.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="common_metric">
            <label>Мой дом рассчитывается с энергоснабжающей организацией по общему расчетному прибору учета.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="common_metric_hostel">
            <label>Общежитиям (подпадающим под определение «население, которое рассчитывается с энергоснабжающей организацией по общему расчетному прибору учета»)</label>
            </div>
            </div>
            <div class="two fields">
            <div class="ui input">
             <input type="text" placeholder="Размер скидки в %">
             </div>
            <div class="ui input">
            <input type="text" placeholder="Объем льготной воды в куб.м">
            </div>
            </div>';
        return view('services/create_service')->with(['layout'=> $answer]);
    }

    public function create_user_info_view_with_info()
    {
        // TODO: Implement create_user_info_view_with_info() method.
    }

    public function safe($request)
    {
        // TODO: Implement safe() method.
    }

    public function safe_history($request)
    {
        // TODO: Implement safe_history() method.
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
                $energy_cost += $energy_used * self::COST_FIRST - self::calculate_relief_with_no_cost_change(self::COST_FIRST, $energy_used);
            }
            else
            {
                $energy_cost += $energy_used * self::COST_SECOND - self::calculate_relief_with_no_cost_change(self::COST_SECOND, $energy_used);
            }
        }
        elseif($this->user_info->child_support)
        {
            $energy_cost += $energy_used * self::COST_FIRST - self::calculate_relief_with_no_cost_change(self::COST_FIRST, $energy_used);
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
            $energy_cost -= self::calculate_relief_with_one_cost_change(self::MAX_THIRD, self::COST_FIRST, self::COST_THIRD, $energy_used);

        }
        elseif($this->user_info->location)
        {
            $energy_cost += self::calculate_location_cost(self::MAX_FIRST_CITY, self::MAX_SECOND, $energy_used) - self::calculate_relief_with_two_cost_change(self::MAX_FIRST_CITY, self::MAX_SECOND, $energy_used);
        }
        else
        {
            $energy_cost += self::calculate_location_cost(self::MAX_FIRST_COUNTRYSIDE, self::MAX_SECOND, $energy_used) - self::calculate_relief_with_two_cost_change(self::MAX_FIRST_COUNTRYSIDE, self::MAX_SECOND, $energy_used);
        }
        return $energy_cost / 100;
    }

    public function successful_calculate_layout($calculate_values)
    {
        // TODO: Implement successful_calculate_layout() method.
    }

    private function calculate_location_cost($max_first, $max_second, $energy_used)
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

    private function calculate_relief_with_two_cost_change($max_first, $max_second, $energy_used)
    {
        $relief_cost = 0;
        if($this->user_info->num_of_relief_energy <= $max_first)
        {
            if($energy_used <= $this->user_info->num_of_relief_energy)
            {
                $relief_cost = $energy_used * self::COST_FIRST * $this->user_info->relief;
            }
            else
            {
                $relief_cost = $this->user_info->num_of_relief_energy * self::COST_FIRST * $this->user_info->relief;
            }
        }
        elseif($max_second < $this->user_info->num_of_relief_energy)
        {
            if($energy_used <= $this->user_info->num_of_relief_energy)
            {
                $relief_cost = self::calculate_location_cost($max_first, $max_second, $energy_used) * $this->user_info->relief;
            }
            else
            {
                $relief_cost = self::calculate_location_cost($max_first, $max_second, $this->user_info->num_of_relief_energy) * $this->user_info->relief;
            }
        }
        else
        {
            if($energy_used <= $this->user_info->num_of_relief_energy)
            {
                $relief_cost = ((($energy_used - $max_first < 0) ? $energy_used : $max_first) * self::COST_FIRST + (($energy_used - $max_first < 0) ? 0 : $energy_used - $max_first) * self::COST_SECOND) * $this->user_info->relief;
            }
            else
            {
                $relief_cost = ($max_first * self::COST_FIRST + ($this->user_info->num_of_relief_energy - $max_first) * self::COST_SECOND) * $this->user_info->relief;
            }
        }
        return $relief_cost;
    }
    private function calculate_relief_with_one_cost_change($limit, $cost_before, $cost_after, $energy_used)
    {
        $relief_cost = 0;
        if($this->user_info->num_of_relief_energy <= $limit)
        {
            $relief_cost = (($energy_used <= $this->user_info->num_of_relief_energy) ? $energy_used : $this->user_info->num_of_relief_energy) * $cost_before * $this->user_info->relief;
        }
        else
        {
            if($energy_used <= $limit)
            {
                $relief_cost = $energy_used * $cost_before * $this->user_info->relief;
            }
            else
            {
                $relief_cost = $limit * $cost_before * $this->user_info->relief;
                $relief_cost += (($energy_used <= $this->user_info->num_of_relief_energy) ? $energy_used - $limit : $this->user_info->num_of_relief_energy - $limit) * $cost_after * $this->user_info->relief;
            }
        }
        return $relief_cost;
    }
    private function calculate_relief_with_no_cost_change($cost, $energy_used)
    {
        return (($energy_used <= $this->user_info->num_of_relief_energy) ? $energy_used : $this->user_info->num_of_relief_energy) * $cost * $this->user_info->relief;
    }


}