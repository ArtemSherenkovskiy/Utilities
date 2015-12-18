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



    public function __construct()
    {
        $this->relief = 0.0;
        $this->numOfReliefHotWater = 0.0;
    }
    public function __construct1($relief, $numOfReliefHotWater)
    {
        $this->relief = $relief;
        $this->numOfReliefHotWater = $numOfReliefHotWater;
    }
}

class ColdWaterKiyvVodoKanalMonthInfo
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

class ColdWaterKiyvVodoKanalService extends BasicService
{
    const SERVICE_ALIAS = "ColdWater";
    const SERVICE_NAME = "Холодное водоснабжение и водоотвод";
    const VENDOR_ALIAS = "KiyvVodoKanal";
    const VENDOR_NAME = "КиевВодоКанал";

    const COST_OUTGOING = 4.092;
    const COST_WATER = 4.596;
    const COST_WATER_WITH_OUTGOING = self::COST_OUTGOING + self::COST_WATER;


    private $user_service;
    private $user_info;


    public function __construct()
    {

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
            <div class="ui slider checkbox">
            <input type="checkbox" tabindex="0" name="counter">
            <label>У меня дома есть счетчик.</label>
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


    /**
     * @param $info_array
     * the first element is counter value of previous month
     * the second value is counter value of current month
     * the third parameter is amount of hot water consumed this month
     * @return int
     */
    public function calculate($info_array)
    {
        if($info_array[0] && $info_array[1] && $info_array[2] && $info_array[0] = (int)$info_array[0] && $info_array[1] = (int)$info_array[1])
        {
            $diff = $info_array[1] - $info_array[0] - $this->user_info->numOfReliefHotWater * $this->user_info->relief;
            $cost = 0.0;
            if($this->user_info->dryer)
            {
                $cost = $diff * self::COST_WATER_WITH_OUTGOING;
            }
            else
            {
                $cost = $diff * self::COST_WATER_WITH_OUTGOING;
            }
            return $cost + (int)$info_array[2] * self::COST_OUTGOING;
        }
        else
        {
            return -1;
        }
    }


    private function hot_water_outgoing()
    {
        $time = strtotime(date('Y-m-01 00:00:00'));
        $user_id = Auth::user()->id;
        //$hot_water_id = DB::select("SELECT user_service.id FROM user_service LEFT JOIN services ON user_service.service_id = services.id WHERE services.service_alias = 'HotWater' AND user_service.user_id = $user_id");
        //$history_item = History::find([$this->user_service->id, $time]);
    }

}