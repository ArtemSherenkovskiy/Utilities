<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 10.12.2015
 * Time: 23:44
 */

namespace App\Http\Services;

class ColdWaterKiyvVodoKanalUserInfo
{
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


    private $user_info;

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

}