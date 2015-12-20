<?php

/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 27.10.2015
 * Time: 18:30
 */
namespace App\Http\Services;


abstract class BasicService
{
    public abstract function before_calculate_layout();
    public abstract function info();
    public abstract function safe($request);
    public abstract function safe_history($request);
    public abstract function create_user_info_view();
    public abstract function create_user_info_view_with_info();
    public abstract function calculate($info_array);
    public abstract function successful_calculate_layout($calculate_values);

}