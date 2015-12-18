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

    public function __construct()
    {
        $this->dryer = true;
        $this->relief = 0.0;
        $this->num_of_relief_hot_water = 0.0;
    }
    public function __construct1($counter, $dryer, $relief, $num_of_relief_hot_water)
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
     * value of money you paid that month
     */
    public $paid_value;


    public function __construct()
    {
        $this->counter_value = 0.0;
        $this->paid_value = 0.0;
    }

    /**
     * HotWaterKiyvEnergoMonthInfo constructor.
     * @param $counter_value
     * @param $paid_value
     */
    public function __construct1($counter_value, $paid_value)
    {
        $this->counter_value = $counter_value;
        $this->paid_value = $paid_value;
    }


}

class HotWaterKiyvEnergoService extends BasicService
{
    /**
     * id of service in DB
     */
    const SERVICE_ID = 2;

    const COST_WITH_DRYER = 40.92;
    const COST_WITHOUT_DRYER = 37.91;

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
        if(Auth::guest())
        {
            $this->guest = true;
            $this->user_service_info = null;
        }
        else
        {
            $this->guest = false;
            $this->user_info = Auth::user();
            $user_services = UserService::whereRaw('user_id = ? and service_id = ?', [$this->user_info->id, self::SERVICE_ID]);
            if(count($this->user_service_info) > 0)
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
        if($this->user_service_info->counter)
        {
            $previous_history_element =  History::where('user_service_id', '=', $this->user_service_id)->max('time_period');
            if(null === $previous_history_element)
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
        $answer = '<div class="inline field">
            <div class="ui slider checkbox">
            <input type="checkbox" name="counter">
            <label>У меня дома есть счетчик.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui slider checkbox">
            <input type="checkbox" name="dryer">
            <label>У меня дома есть сушилка для полотенец.</label>
            </div>
            </div>
            <div class="two fields">
            <div class="ui input">
             <input type="text" placeholder="Размер скидки в %" name="relief">
             </div>
            <div class="ui input">
            <input type="text" placeholder="Объем льготной воды в куб.м" name="num_of_relief_hot_water">
            </div>
            </div>';
        return view('services/create_service')->with(['layout'=> $answer]);

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
            throw new ServiceException("Error in HotWaterKiyvEnergo with null user_service variable");
        }
        $answer = '<div class="inline field">
            <div class="ui slider checkbox">
            <input type="checkbox" name="counter"' . ($this->user_service_info->counter ? "checked" : "") . '>
            <label>У меня дома есть счетчик.</label>
            </div>
            </div>
            <div class="inline field">
            <div class="ui slider checkbox">
            <input type="checkbox" name="dryer"' . ($this->user_service_info->dryer ? "checked" : "") . '>
            <label>У меня дома есть сушилка для полотенец.</label>
            </div>
            </div>
            <div class="two fields">
            <div class="ui input">
             <input type="text" placeholder="Размер скидки в %" name="relief" value="' . ($this->user_service_info->relief) . '">
             </div>
            <div class="ui input">
            <input type="text" placeholder="Объем льготной воды в куб.м" name="num_of_relief_hot_water" value="' . ($this->user_service_info->num_of_relief_hot_water) . '">
            </div>
            </div>';
        return view('services/create_service')->with(['layout'=> $answer]);
    }


    /**
     * @param $request
     * array, that contains information which come from answer for layout made with create_user_info_view()
     * or create_user_info_view_with_info() functions
     */
    public function safe($request)
    {
        $this->validate_info_request($request);
        if(!$this->guest)
        {
            if(null === $this->user_service_info)
            {
                $this->user_service_info = new HotWaterKiyvEnergoUserInfo((boolean)$request['counter'], (boolean)$request['dryer'], (integer)$request['relief'], (integer)$request['num_of_relief_hot_water']);
                $user_service = new UserService();
                $user_service->user_id = $this->user_info->id;
                $user_service->service_id = self::SERVICE_ID;
                $user_service->user_info = serialize($this->user_service_info);
                $user_service->save();
            }
            else
            {
                $this->user_service_info = new HotWaterKiyvEnergoUserInfo((boolean)$request['counter'], (boolean)$request['dryer'], (integer)$request['relief'], (integer)$request['num_of_relief_hot_water']);
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

    /**
     * @param $info_array
     * the first element is counter value of previous month
     * the second value is counter value of current month
     * \n if you don't have counter, the first element is volume of consumed water
     * @return int
     */
    public function calculate($info_array)
    {
        if($this->user_service_info->counter)
        {
            if($info_array[0] && $info_array[1] && $info_array[0] = (int)$info_array[0] && $info_array[1] = (int)$info_array[1])
            {
                $diff = $info_array[1] - $info_array[0];

            } else
            {
                return -1;
            }
        }
        else
        {
            if($info_array[0] && $info_array[0] = (int)$info_array[0])
            {
                $diff = $info_array[0];
            }
            else
            {
                return -1;
            }
        }
        $diff = $diff - ($this->user_info->numOfReliefHotWater < $diff ? $this->user_info->numOfReliefHotWater : $diff) * $this->user_info->relief;
        if($this->user_info->dryer)
        {
            $cost = $diff * self::COST_WITH_DRYER;
        } else
        {
            $cost = $diff * self::COST_WITHOUT_DRYER;
        }
        return $cost;
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
            $request['relief'] = (integer)$request['relief'];
        }
        else
        {
            $request['relief'] = 0;
        }


    }
}