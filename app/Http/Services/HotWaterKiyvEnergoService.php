<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 07.12.2015
 * Time: 11:55
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

class HotWaterKiyvEnergoUserInfo
{
    /**
     * @var
     * true if you have counter
     * false otherwise
     */
    public $counter;

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
    public $num_of_relief_hot_water;

//    public function __construct()
//    {
//        $this->counter = false;
//        $this->dryer = true;
//        $this->relief = 0.0;
//        $this->num_of_relief_hot_water = 0.0;
//    }
    public function __construct($counter, $dryer, $relief, $num_of_relief_hot_water)
    {
        $this->counter = $counter;
        $this->dryer = $dryer;
        $this->relief = $relief;
        $this->num_of_relief_hot_water = $num_of_relief_hot_water;
    }

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
     * value of consumed water
     */
    public $consumed_value;

    /**
     * @var
     * value of money you paid that month
     */
    public $paid_value;

    /**
     * HotWaterKiyvEnergoMonthInfo constructor.
     * @param $counter_value
     * @param $consumed_value
     * @param $paid_value
     */
    public function __construct($counter_value, $consumed_value, $paid_value)
    {
        $this->counter_value = $counter_value;
        $this->consumed_value = $consumed_value;
        $this->paid_value = $paid_value;
    }


//    public function __construct()
//    {
//        $this->counter_value = 0.0;
//        $this->paid_value = 0.0;
//    }




}

class HotWaterKiyvEnergoService extends BasicService
{
    /**
     * id of service in DB
     */
    const SERVICE_ALIAS = 'HotWater';
    const SERVICE_NAME = 'Горячая Вода';
    const VENDOR_ALIAS = 'KiyvEnergo';

    const COST_WITH_DRYER = 40.92;
    const COST_WITHOUT_DRYER = 37.91;

    private $service_id;


    /**
     * @var
     * contains global information about user
     * which is contains in 'user_service' table
     * in 'user_info' field
     */
    private $user_service_info;

    private $user_service_id;

    /**
     * @var
     * contains global information about user
     * which is contains in 'users' table
     */
    private $user_info;

    /**
     * @var bool
     * true for guests
     */
    private $guest;


    /**
     * HotWaterKiyvEnergoService constructor.
     * construct try to take all possible information about current user from DB
     * for guest all information is empty
     * \n initialize $guest variable to true if current user is unauthorized
     * , to false otherwise
     */

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
            $user_service = UserService::whereRaw('user_id = ' . $this->user_info->id . ' and service_id = ' . $this->service_id)->first()  ;
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



    public function before_calculate_layout()
    {
        if($this->user_service_info === null)
        {
            throw new ServiceException("Hot water KiyvEnergo, before_calculate_layout is null");
        }
        var_dump($this->user_service_info);
        if($this->user_service_info->counter)
        {
            $previous_time_period =  History::where('user_service_id', '=', $this->user_service_id)->max('time_period');
            if($previous_time_period == strtotime(date('Y-m-01 00:00:00')))
            {
                // return message with warning that we have already calculate in this month
            }
            if(null == $previous_time_period)
            {
                $previous_counter = 0;
            }
            else {
                $previous_history_element = History::whereRaw('user_service_id = ' . $this->user_service_id . ' and time_period = ' . $previous_time_period)->first();
                if (null != $previous_history_element)
                {
                    $previous_counter = unserialize($previous_history_element->history_item)->counter;
                }
            }
            $answer = ' <div class="header">' . self::SERVICE_NAME . '</div>
                        <div class="field">
                            <label>Показания счетчика в прошлом месяце</label>
                            <input type="text" name="previous_counter" value="' . $previous_counter . '">
                        </div>
                        <div class="field">
                            <label>Текущие показания счетчика</label>
                            <input type="text" name="current_counter" value="' . $previous_counter . '">
                        </div>';
        }
        else
        {
            $answer = ' <div class="field">
                            <label>Количество использованной воды</label>
                            <input type="text" name="water_volume" value="' . 0 . '">
                        </div>';
        }
        return view('services/before_calculate')->with(['input_form' => $answer]);
    }

    public function info()
    {

        // TODO: Implement info() method.
    }

    /**
     * @return mixed
     * create from for filling with information about user, which is needed
     * for calculating in this class
     */

    public function create_user_info_view()
    {
        $answer = '<div class="header">' . self::SERVICE_NAME . '</div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="counter">
            <label>У меня дома есть счетчик.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="dryer">
            <label>У меня дома есть сушилка для полотенец.</label>
            </div>
            </div>
            <div class="two fields">
            <div class="field">
            <label>Скидка</label>
             <input type="text" placeholder="Размер скидки в %" name="relief">
             </div>
            <div class="field">
            <label>Объем воды по скидке</label>
            <input type="text" placeholder="Объем льготной воды в куб.м" name="num_of_relief_hot_water">
            </div>
            </div>';
        return view('services/create_service')->with(['layout'=> $answer, 'id' => $this->service_id]);

    }

    /**
     * @return mixed
     * function, alternative to create_user_info_view(), but add initial values
     * from $this->user_service variable
     */

    public function create_user_info_view_with_info()
    {
        if(null === $this->user_service_info)
        {
            throw new ServiceException("Error in HotWaterKiyvEnergo with null user_service_info variable");
        }
        $answer = '<div class="header">' . self::SERVICE_NAME . '</div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="counter"' . ($this->user_service_info->counter ? "checked" : "") . '>
            <label>У меня дома есть счетчик.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui checkbox">
            <input type="checkbox" name="dryer"' . ($this->user_service_info->dryer ? "checked" : "") . '>
            <label>У меня дома есть сушилка для полотенец.</label>
            </div>
            </div>
            <div class="two fields">
            <div class="field">
             <label>Скидка</label>
             <input type="text" placeholder="Размер скидки в %" name="relief" value="' . ($this->user_service_info->relief * 100) . '">
             </div>
            <div class="field">
            <label>Объкмы льготной воды</label>
            <input type="text" placeholder="Объем льготной воды в куб.м" name="num_of_relief_hot_water" value="' . ($this->user_service_info->num_of_relief_hot_water) . '">
            </div>
            </div>';
        return view('services/create_service')->with(['layout'=> $answer, 'id' => $this->service_id]);
    }


    /**
     * @param $request
     * array, that contains information which come from answer for layout made with create_user_info_view()
     * or create_user_info_view_with_info() functions
     */
    public function safe($request)
    {
        $request = $this->validate_info_request($request);
        if(!$this->guest)
        {
            if(null === $this->user_service_info)
            {
                $this->user_service_info = new HotWaterKiyvEnergoUserInfo($request['counter'], $request['dryer'], $request['relief']  / 100.0, $request['num_of_relief_hot_water']);
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
                $this->user_service_info = new HotWaterKiyvEnergoUserInfo($request['counter'], $request['dryer'], $request['relief'] / 100.0, $request['num_of_relief_hot_water']);
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
        var_dump($this->user_service_info);

    }

    public function safe_history($request)
    {
        $history_item = new HotWaterKiyvEnergoMonthInfo($request['counter_value'], $request['consumed_value'], $request['paid_value']);
        $time = strtotime(date('Y-m-01 00:00:00'));
        if(History::whereRaw("user_service_id = $this->user_service_id and time_period = $time")->first())
        {
            throw new ServiceException("Current month has been calculated");
        }
        $history = new History();
        $history->time_period = $time;
        $history->history_item = serialize($history_item);
        $history->user_service_id = $this->user_service_id;
        $history->save();
    }

    /**
     * @param $info_array
     * request of before_calculate view
     * @return
     */
    public function calculate($info_array)
    {
        var_dump($this->user_service_info);
        $info_array = $this->validate_calculate_request($info_array);
        if($this->user_service_info->counter)
        {
            $current_counter = $info_array['current_counter'];
            $diff = $info_array['current_counter'] - $info_array['previous_counter'];
        }
        else
        {
            $diff = $info_array['water_volume'];
        }
        $diff_with_relief = $diff - ($this->user_service_info->num_of_relief_hot_water < $diff ? $this->user_service_info->num_of_relief_hot_water : $diff) * $this->user_service_info->relief;
        if($this->user_service_info->dryer)
        {
            $cost = $diff_with_relief * self::COST_WITH_DRYER;
        } else
        {
            $cost = $diff_with_relief * self::COST_WITHOUT_DRYER;
        }
        var_dump($info_array);
        if($this->user_service_info->counter)
        {
            return $this->successful_calculate_layout(array($diff, $cost, $current_counter));
        }
        else
        {
            return $this->successful_calculate_layout(array($diff, $cost));
        }
    }

    public function successful_calculate_layout($calculate_values)
    {
        var_dump($calculate_values[0]);
        $answer = '<div class="header">' . self::SERVICE_NAME . '</div>';
        if($this->user_service_info->counter)
        {
                    $answer = $answer .
                    '<div class="inline field">
                        <div class="field">
                            <label>Текущее значение счетчика</label>
                            <input type="text" placeholder="Сумма" name="counter_value" value="' . $calculate_values[2] . '">
                        </div>
                   </div>';
        }
                   $answer = $answer . '<div class="inline field">
                        <div class="field">
                            <label>Объемы использованной воды</label>
                            <input type="text" placeholder="Сумма" name="consumed_value" value="' . $calculate_values[0] . '">
                        </div>
                   </div>
                   <div class="inline field">
                        <div class="field">
                            <label>Сумма счета</label>
                            <input type="text" placeholder="Сумма" name="paid_value" value="' . $calculate_values[1] . '">
                        </div>
                   </div>';
       return view('services/successful_calculate')->with(['result_form' => $answer]);
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
        }
        return $request;
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
        if(array_key_exists('dryer', $request))
        {
            $request['dryer'] = true;
        }
        else
        {
            $request['dryer'] = false;
        }
        if(array_key_exists('relief', $request))
        {
            $request['relief'] = (float)$request['relief'];
        }
        else
        {
            $request['relief'] = 0;
        }
        if(array_key_exists('num_of_relief_hot_water', $request))
        {
            $request['num_of_relief_hot_water'] = (float)$request['num_of_relief_hot_water'];
        }
        else
        {
            $request['num_of_relief_hot_water'] = 0;
        }
        return $request;

    }
}