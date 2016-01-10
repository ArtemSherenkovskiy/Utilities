<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 04.12.2015
 * Time: 12:18
 */

namespace App\Http\Services;


use App\History;
use App\Service;
use App\User;
use App\UserService;
use App\Vendor;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
    public function __construct($location = true, $electric_cooker = false, $relief = 0.0, $num_of_relief_energy = 0.0, $heat_supply = true, $child_support = false, $common_metric = false, $common_metric_hostel = false)
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

    /**
     * ElectricityKiyvEnergoMonthInfo constructor.
     * @param $counter_value
     * @param $paid_value
     */
    public function __construct($counter_value, $paid_value)
    {
        $this->counter_value = $counter_value;
        $this->paid_value = $paid_value;
    }


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
    private $user_service_info;
    private $user_service_id;
    private $guest;

    public function __construct()
    {
        $vendor_id = Vendor::where('vendor_alias','=',self::VENDOR_ALIAS)->first()->id;
        $this->service_id = Service::whereRaw('service_alias = \''. self::SERVICE_ALIAS . '\' and vendor_id = ' . $vendor_id)->first()->id;
        if(Auth::guest())
        {
            $this->guest = true;
            $this->user_service_info = null;
        }
        else
        {
            $this->guest = false;
            $this->user_info = Auth::user();
            $user_service = UserService::whereRaw('user_id = ' . $this->user_info->id . ' and service_id = ' . $this->service_id)->first();
            if(null === $user_service)
            {
                $this->user_service_info = null;
                $this->user_service_id = 0;
            }
            else
            {
                $this->user_service_info = unserialize($user_service->user_info);
                $this->user_service_id = $user_service->id;
            }
        }
    }

    /**
     * ElectricityVendor constructor.
     * @param $user_info
     */


    public function before_calculate_layout()
    {

    }

    public function info()
    {
        // TODO: Implement info() method.
    }

    public function create_user_info_view()
    {
        $answer = '<div class="header">' . self::SERVICE_NAME . '>
            <div class="inline field">
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
            <div class="field">
             <input type="text" placeholder="Размер скидки в %" name="relief">
             </div>
            <div class="field">
            <input type="text" placeholder="Объем льготной воды в куб.м" name="num_of_relief_energy">
            </div>
            </div>';
        return view('services/create_service')->with(['layout'=> $answer, 'id' => $this->service_id]);
    }

    public function create_user_info_view_with_info()
    {
        $answer = '<div class="header">' . self::SERVICE_NAME . '>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="location" ' . ($this->user_service_info->location ? 'checked' : '') . '>
            <label>Я живу в городе или ПГТ.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="electric_cooker" ' . ($this->user_service_info->electric_cooker ? 'checked' : '') . '>
            <label>У меня дома электроплита.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="heat_supply" ' . ($this->user_service_info->heat_supply ? 'checked' : '') . '>
            <label>У меня дома электроотопление/отсутствует центральное отпление.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="child_support" ' . ($this->user_service_info->child_support ? 'checked' : '') . '>
            <label>Мы многодетная семья.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="common_metric" ' . ($this->user_service_info->common_metric ? 'checked' : '') . '>
            <label>Мой дом рассчитывается с энергоснабжающей организацией по общему расчетному прибору учета.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="common_metric_hostel" ' . ($this->user_service_info->common_metric_hostel ? 'checked' : '') . '>
            <label>Общежитиям (подпадающим под определение «население, которое рассчитывается с энергоснабжающей организацией по общему расчетному прибору учета»)</label>
            </div>
            </div>
            <div class="two fields">
            <div class="field">
             <input type="text" placeholder="Размер скидки в %" name="relief" value="' . $this->user_service_info->relief * 100 . '">
             </div>
            <div class="field">
            <input type="text" placeholder="Объем льготной воды в куб.м" name="num_of_relief_energy" value="' . $this->user_service_info->num_of_relief_energy . '">
            </div>
            </div>';
        return view('services/create_service')->with(['layout'=> $answer, 'id' => $this->service_id]);
    }

    public function safe($request)
    {
        $request = $this->validate_info_request($request);
        if(!$this->guest)
        {
            if(null === $this->user_service_info)
            {
                $this->user_service_info = new ElectricityKiyvEnergoUserInfo($request['location'], $request['electric_cooker'], $request['relief']  / 100.0, $request['num_of_relief_energy'], $request['heat_supply'], $request['child_support'], $request['common_metric'], $request['common_metric_hostel']);
                $user_service = new UserService();
                $user_service->user_id = $this->user_info->id;
                $user_service->service_id = $this->service_id;
                $user_service->user_info = serialize($this->user_service_info);
                var_dump($request);
                var_dump($this->user_service_info);
                $user_service->save();
            }
            else
            {
                $this->user_service_info = new ElectricityKiyvEnergoUserInfo($request['location'], $request['electric_cooker'], $request['relief']  / 100.0, $request['num_of_relief_energy'], $request['heat_supply'], $request['child_support'], $request['common_metric'], $request['common_metric_hostel']);
                $user_service = UserService::find($this->user_service_id);
                $user_service->user_info = serialize($this->user_service_info);
                $user_service->save();
            }
        }
        else
        {
            if(null === $this->user_service_info)
            {
                $this->user_service_info = new HotWaterKiyvEnergoUserInfo($request['counter'], $request['dryer'], $request['relief'], $request['num_of_relief_hot_water']);
            }
        }

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

    private function validate_info_request($request)
    {
        if(array_key_exists('location', $request))
        {
            $request['location'] = true;
        }
        else
        {
            $request['location'] = false;
        }
        if(array_key_exists('electric_cooker', $request))
        {
            $request['electric_cooker'] = true;
        }
        else
        {
            $request['electric_cooker'] = false;
        }
        if(array_key_exists('relief', $request))
        {
            $request['relief'] = (float)$request['relief'];
        }
        else
        {
            $request['relief'] = 0;
        }
        if(array_key_exists('num_of_relief_energy', $request))
        {
            $request['num_of_relief_energy'] = (float)$request['num_of_relief_energy'];
        }
        else
        {
            $request['num_of_relief_energy'] = 0;
        }
        if(array_key_exists('heat_supply', $request))
        {
            $request['heat_supply'] = true;
        }
        else
        {
            $request['heat_supply'] = false;
        }
        if(array_key_exists('child_support', $request))
        {
            $request['child_support'] = true;
        }
        else
        {
            $request['child_support'] = false;
        }
        if(array_key_exists('common_metric', $request))
        {
            $request['common_metric'] = true;
        }
        else
        {
            $request['common_metric'] = false;
        }
        if(array_key_exists('common_metric_hostel', $request))
        {
            $request['common_metric_hostel'] = true;
        }
        else
        {
            $request['common_metric_hostel'] = false;
        }
        return $request;

    }

}