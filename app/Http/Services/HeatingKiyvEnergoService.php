<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 11.12.2015
 * Time: 22:39
 */

namespace App\Http\Services;

class HeatingKiyvEnergoUserInfo
{

    /**
     * @var
     * true if you have counter
     * false otherwise
     */
    public $counter_present;
    /**
     * @var
     * float between 0.0 and 1.0,
     * this value describe discount for this service
     */
    public $relief;

    /**
     * @var
     * integer contains number of heat in MegaCalories or the discount square you have with discount
     * which contains in relief variable
     */
    public $numOfReliefHeat;

    /**
     * HeatingKiyvEnergoUserInfo constructor.
     * @param $counter_present
     * @param $relief
     * @param $numOfReliefHeat
     */
    public function __construct1($counter_present, $relief, $numOfReliefHeat)
    {
        $this->counter_present = $counter_present;
        $this->relief = $relief;
        $this->numOfReliefHeat = $numOfReliefHeat;
    }

    public function __construct()
    {
        $this->counter_present = false;
        $this->relief = 0.0;
        $this->numOfReliefHeat = 0;
    }


}

class HeatingKiyvEnergoMonthInfo
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

class HeatingKiyvEnergoService extends BasicService
{

    const SERVICE_ALIAS = "Electricity";
    const SERVICE_NAME = "Электроэнергия";
    const VENDOR_ALIAS = "KiyvEnergo";
    const VENDOR_NAME = "КиевЭнерго";


    const COST_SQUARE = 16.14;
    const COST_GIGA_CALORIES = 657.24;

    private $user_info;

    public function __construct()
    {
        $this->user_info = new HeatingKiyvEnergoUserInfo();
    }

    /**
     * HeatingKievEnergoService constructor.
     * @param $user_info
     */
    public function __construct1(HeatingKiyvEnergoUserInfo $user_info)
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
            <div class="ui slider checkbox">
            <input type="checkbox" name="counter">
            <label>У меня дома есть счетчик теплоэнергии.</label>
            </div>
            </div>
            <div class="two fields">
            <div class="ui input">
             <input type="text" placeholder="Размер скидки в %">
             </div>
            <div class="ui input">
            <input type="text" placeholder="Льготная площадь или льготные ГигаКалории   ">
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
     * if you have counter
     * the first element of $info_array is amount of last month counter value
     * the second element of $info_array is amount of current month counter value
     * \n if you don't have counter
     * the $info_array is empty
     * @return int
     * return amount of costs you need to pay
     * if there is error return -1
     */
    public function calculate($info_array)
    {
        $user = Auth::user();
        $cost = -1;
        if($this->user_info->counter_present && $info_array[0] && $info_array[1])
        {
            $diff = $info_array[1] - $info_array[0];
            $cost = ($diff - $this->user_info->numOfReliefHeat * $this->user_info->relief) * self::COST_GIGA_CALORIES;
        }
        else if(!$this->user_info->counter_present)
        {
            $cost = ($user['flat_square'] - $this->user_info->numOfReliefHeat * $this->user_info->relief) * self::COST_SQUARE;
        }
        return $cost;
    }


}