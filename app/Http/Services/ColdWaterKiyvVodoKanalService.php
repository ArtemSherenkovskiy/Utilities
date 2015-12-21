<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 10.12.2015
 * Time: 23:44
 */

namespace App\Http\Services;

use App\UserService;
use App\History;
use App\Service;
use App\User;

use App\Vendor;
use DB;

class ColdWaterKiyvVodoKanalUserInfo
{
    /**
     * @var
     * true if you have counter
     * false otherwise
     */
    public $counter;

    /**
     * @var
     * integer which contains vendor_id of user's hot water service
     */
    public $hot_water_vendor;

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
    public $num_of_relief_water;



    public function __construct()
    {
        $this->counter = false;
        $this->hot_water_vendor = false;
        $this->relief = 0.0;
        $this->num_of_relief_water = 0.0;
    }
    public function __construct1($counter, $hot_water_vendor, $relief, $num_of_relief_water)
    {
        $this->counter = $counter;
        $this->hot_water_vendor = $hot_water_vendor;
        $this->relief = $relief;
        $this->num_of_relief_water = $num_of_relief_water;
    }
}

class ColdWaterKiyvVodoKanalMonthInfo
{
    /**
     * @var
     * value on your cold water counter current month
     */
    public $counter_value;

    /**
     * @var
     * value of cold water consumed
     */
    public $water_consumed_value;

    /**
     * @var
     * value of outgoing of hot water
     */
    public $hot_water_outgoing_value;


    /**
     * @var
     * value of money you paid that month
     */
    public $paid_value;

    /**
     * ColdWaterKiyvVodoKanalMonthInfo constructor.
     * @param $counter_value
     * @param $water_consumed_value
     * @param $hot_water_outgoing_value
     * @param $paid_value
     */
    public function __construct1($counter_value, $water_consumed_value, $hot_water_outgoing_value, $paid_value)
    {
        $this->counter_value = $counter_value;
        $this->water_consumed_value = $water_consumed_value;
        $this->hot_water_outgoing_value = $hot_water_outgoing_value;
        $this->paid_value = $paid_value;
    }


    public function __construct()
    {
        $this->counter_value = 0.0;
        $this->water_consumed_value = 0.0;
        $this->hot_water_outgoing_value = 0.0;
        $this->paid_value = 0.0;
    }


}

class ColdWaterKiyvVodoKanalService extends BasicService
{
    const SERVICE_ALIAS = "ColdWater";
    const VENDOR_ALIAS = "KiyvVodoKanal";

    const COST_OUTGOING = 4.092;
    const COST_WATER = 4.596;
    const COST_WATER_WITH_OUTGOING = self::COST_OUTGOING + self::COST_WATER;
    const HOT_WATER_ALIAS = "HotWater";


    private $service_id;
    private $user_service_info;
    private $user_service_id;
    private $user_info;
    private $guest;


    public function __construct()
    {
        $vendor_id = Vendor::where('vendor_alias','=',self::VENDOR_ALIAS)->get()->id;
        $this->service_id = Service::whereRaw('servise_alias = '. self::SERVICE_ALIAS . ' and vendor_id = ' . $vendor_id)->get()->id;
        if(Auth::guest())
        {
            $this->guest = true;
            $this->user_service_info = null;
        }
        else
        {
            $this->guest = false;
            $this->user_info = Auth::user();
            $user_services = UserService::whereRaw('user_id = '. $this->user_info->id .' and service_id = ' . $this->service_id);
            if(count($this->user_service_info) <= 0)
            {
                $this->user_service_info = null;
                $this->user_service_id = 0;
            }
            else
            {
                $this->user_service_info = unserialize($user_services[0]->user_info);
                $this->user_service_id = $user_services[0]->id;
            }
        }
    }


    public function before_calculate_layout()
    {
        if($this->user_service_info === null)
        {
            throw new ServiceException("Cold water KiyvVodoKanal    , before_calculate_layout is null");
        }
        $current_hot_water = 0;
        if($this->user_service_info->hot_water_vendor != 0)
        {
            $hot_water_services = Service::whereRaw('vendor_id = '. $this->user_service_info->hot_water_vendor . ' and service_alias = ' . self::SERVICE_ALIAS)->get();
            if(null == $hot_water_services)
            {
                throw new ServiceException('Cold water KiyvVodoKanal, unknown hot water service');
            }
            $hot_water_service_id = $hot_water_services[0]->id;
            $hot_water_user_services = UserService::whereRaw('user_id = ' . $this->user_info->id . ' and service_id = ' . $hot_water_service_id)->get();
            if(count($hot_water_user_services) == 0)
            {
                //create layout with info, that current user doesn't have hot water service
                // with choosen vendor
                throw new ServiceException('Cold water KiyvVodoKanal, unknown hot water user_service');
            }
            $hot_water_user_service_id = $hot_water_user_services[0]->id;
            $time = strtotime(date('Y-m-01 00:00:00'));
            $hot_water_histories = History::whereRaw('user_service_id = ' . $hot_water_user_service_id . 'and time_period = ' . $time)->get();
            if(count($hot_water_histories) == 0)
            {
                //create layout that user need to calculate hot water firstly
            }
            elseif(count($hot_water_histories) == 1)
            {
                $current_hot_water = $hot_water_histories[0]->consumed_value;
            }
        }
        if($this->user_service_info->counter)
        {
            $previous_history_element =  History::where('user_service_id', '=', $this->user_service_id)->max('time_period');
            if(null == $previous_history_element)
            {
                $previous_counter = 0;
            }
            else
            {
                $previous_counter = unserialize($previous_history_element->history_item)->counter;
            }
            $answer = ' <div class="field">
                            <label>Показания счетчика в прошлом месяце</label>
                            <input type="text" name="previous_counter" value="' . $previous_counter . '">
                        </div>
                        <div class="field">
                            <label>Текущие показания счетчика</label>
                            <input type="text" name="current_counter" value="' . $previous_counter . '">
                        </div>
                        <div class="field">
                            <label>Использовано горячей воды:</label>
                            <input type="text" name="hot_water_outgoing" value="' . $current_hot_water . '">
                        </div>';
        }
        else
        {
            $answer = ' <div class="field">
                            <label>Количество использованной воды</label>
                            <input type="text" name="water_volume" value="' . 0 . '">
                        </div>
                        <div class="field">
                            <label>Использовано горячей воды:</label>
                            <input type="text" name="hot_water_outgoing" value="' . $current_hot_water . '">
                        </div>';
        }
        return view('services/before_calculate')->with(['input_form' => $answer]);
    }

    public function info()
    {
        // TODO: Implement info() method.
    }

    public function create_user_info_view()
    {
        $services = Service::where('service_name','=',self::HOT_WATER_ALIAS)->get();
        $hot_water_vendors = '<option value="0">Нет горячей воды/Не высчитываю ее на данном сайте</option>';
        foreach($services as $service)
        {
            $hot_water_vendors .= '<option value="' . $service->vendor_id . '">' . Vendor::find($service->vendor_id)->vendor_name . '</option>';
        }
        $answer = '<div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" tabindex="0" name="counter">
            <label>У меня дома есть счетчик.</label>
            </div>
            </div>
            <div class="inline field">
            <label>Поставщик моей горячей воды:</label>
            <select class="ui sropdawn" name="hot_water_vendor">' .
            $hot_water_vendors .
            '</select>
            </div>
            <div class="two fields">
            <div class="ui input">
            <label>Скидка</label>
             <input type="text" placeholder="Размер скидки в %" name="relief">
             </div>
            <div class="ui input">
            <label>Объем воды по скидке</label>
            <input type="text" placeholder="Объем льготной воды в куб.м" name="num_of_relief_water">
            </div>
            </div>';
        return view('services/create_service')->with(['layout'=> $answer]);
    }

    public function create_user_info_view_with_info()
    {
        $services = Service::where('service_name','=',self::HOT_WATER_ALIAS)->get();
        $hot_water_vendors = '<option value="0" ' . ($this->user_service_info->hot_water_vendor == 0 ? 'selected' : '') . '>Нет горячей воды/Не высчитываю ее на данном сайте</option>';
        foreach($services as $service)
        {
            $hot_water_vendors .= '<option value="' . $service->vendor_id . '" ' . ($this->user_service_info->hot_water_vendor == $service->vendor_id ? 'selected' : '') . '>' . Vendor::find($service->vendor_id)->vendor_name . '</option>';
        }
        $answer = '<div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" tabindex="0" name="counter" ' . ($this->user_service_info->counter ? 'checked' : '') . '>
            <label>У меня дома есть счетчик.</label>
            </div>
            </div>
            <div class="inline field">
            <label>Поставщик моей горячей воды:</label>
            <select class="ui sropdawn" name="hot_water_vendor">' .
            $hot_water_vendors .
            '</select>
            </div>
            <div class="two fields">
            <div class="ui input">
            <label>Скидка</label>
             <input type="text" placeholder="Размер скидки в %" name="relief" value="' . $this->user_service_info->relief . '">
             </div>
            <div class="ui input">
            <label>Объем воды по скидке</label>
            <input type="text" placeholder="Объем льготной воды в куб.м" name="num_of_relief_water" value="' . $this->user_service_info->num_of_relief_water . '>
            </div>
            </div>';
        return view('services/create_service')->with(['layout'=> $answer]);
    }

    public function safe($request)
    {
        $this->validate_info_request($request);
        if(!$this->guest)
        {

            if(null === $this->user_service_info)
            {
                $this->user_service_info = new HotWaterKiyvEnergoUserInfo((boolean)$request['counter'], (integer)$request['hot_water_vendor'], (float)$request['relief'], (float)$request['num_of_relief_water']);
                $user_service = new UserService();
                $user_service->user_id = $this->user_info->id;
                $user_service->service_id = $this->service_id;
                $user_service->user_info = serialize($this->user_service_info);
                $user_service->save();
            }
            else
            {
                $this->user_service_info = new HotWaterKiyvEnergoUserInfo((boolean)$request['counter'], (integer)$request['hot_water_vendor'], (float)$request['relief'], (float)$request['num_of_relief_water']);
                $user_service = UserService::find($this->user_service_id);
                $user_service->user_info = serialize($this->user_service_info);
                $user_service->save();
            }
        }
        else
        {
            if(null === $this->user_service_info)
            {
                $this->user_service_info = new HotWaterKiyvEnergoUserInfo((boolean)$request['counter'], 0, (float)$request['relief'], (float)$request['num_of_relief_water']);
            }
        }
    }

    public function safe_history($request)
    {
        // TODO: Implement safe_history() method.
    }


    /**
     * @param $info_array
     * the first element is counter value of previous month
     * the second value is counter value of current month
     * the third parameter is amount of hot water consumed this month
     * @return int
     */
    public function calculate($info_array)
    {
        $this->validate_calculate_request($info_array);
        if($this->user_service_info->counter)
        {
            $diff = $info_array['current_counter'] - $info_array['previous_counter'];
        }
        else
        {
            $diff = $info_array['water_volume'];
        }
        $diff_with_relief = $diff - ($this->user_info->num_of_relief_water < $diff ? $this->user_info->num_of_relief_water : $diff) * $this->user_info->relief;
        $relief = 0.0;
        $num_of_relief_hot_water = 0.0;
        if($this->user_service_info->hot_water_vendor != 0)
        {
            $hot_water_services = Service::whereRaw('vendor_id = ' . $this->user_service_info->hot_water_vendor . ' and service_alias = ' . self::SERVICE_ALIAS)->get();
            if (null == $hot_water_services) {
                throw new ServiceException('Cold water KiyvVodoKanal, unknown hot water service');
            }
            $hot_water_service_id = $hot_water_services[0]->id;
            $hot_water_user_services = UserService::whereRaw('user_id = ' . $this->user_info->id . ' and service_id = ' . $hot_water_service_id)->get();
            if (count($hot_water_user_services) == 0) {
                //create layout with info, that current user doesn't have hot water service
                // with choosen vendor
                throw new ServiceException('Cold water KiyvVodoKanal, unknown hot water user_service');
            }
            $hot_water_user_info = unserialize($hot_water_user_services[0]->user_info);
            $relief = $hot_water_user_info->relief;
            $num_of_relief_hot_water = $hot_water_user_info->num_of_relief_hot_water;
        }
        $hot_water_outgoing_with_relief = $info_array['hot_water_outgoing'] - ($num_of_relief_hot_water > $info_array['hot_water_outgoing'] ? $info_array['hot_water_outgoing'] : $num_of_relief_hot_water) * $relief;
        $cost = $diff_with_relief * self::COST_WATER_WITH_OUTGOING + $hot_water_outgoing_with_relief * self::COST_OUTGOING;
        return $this->successful_calculate_layout([$info_array['current_counter'], $diff, $info_array['hot_water_outgoing'], $cost]);
    }

    public function successful_calculate_layout($calculate_values)
    {
        return 1;
    }

    private function validate_info_request($request)
    {
        if(array_key_exists('counter', $request))
        {
            $request['counter'] = true;
        }
        else
        {
            $request['counter'] = false;
        }
        if(array_key_exists('hot_water_vendor', $request))
        {
            $request['hot_water_vendor'] = (integer)($request['hot_water_vendor']);
        }
        else
        {
            $request['hot_water_vendor'] = 0;
        }
        if(array_key_exists('relief', $request))
        {
            $request['relief'] = (float)$request['relief'];
        }
        else
        {
            $request['relief'] = 0;
        }
        if(array_key_exists('num_of_relief_water', $request))
        {
            $request['num_of_relief_water'] = (float)$request['num_of_relief_water'];
        }
        else
        {
            $request['num_of_relief_water'] = 0;
        }
    }

    private function validate_calculate_request($request)
    {
        if($this->user_service_info->counter)
        {
            if(array_key_exists('previous_counter', $request))
            {
                $request['previous_counter'] = (float)$request['previous_counter'];
            }
            else
            {
                $request['previous_counter'] = 0;
            }
            if(array_key_exists('current_counter', $request))
            {
                $request['current_counter'] = (float)$request['current_counter'];
            }
            else
            {
                $request['current_counter'] = 0;
            }
            if(array_key_exists('hot_water_outgoing', $request))
            {
                $request['hot_water_outgoing'] = (float)$request['hot_water_outgoing'];
            }
            else
            {
                $request['hot_water_outgoing'] = 0.0;
            }
        }
        else
        {
            if(array_key_exists('water_volume', $request))
            {
                $request['water_volume'] = (float)$request['water_volume'];
            }
            else
            {
                $request['water_volume'] = 0;
            }
            if(array_key_exists('hot_water_outgoing', $request))
            {
                $request['hot_water_outgoing'] = (float)$request['hot_water_outgoing'];
            }
            else
            {
                $request['hot_water_outgoing'] = 0.0;
            }
        }
    }



}