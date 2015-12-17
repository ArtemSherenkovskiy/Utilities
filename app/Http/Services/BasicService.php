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
    public abstract function layout();
    public abstract function info();
    public abstract function safe($Request);
    public abstract function create_user_info_view();
    public abstract function create_user_info_view_with_info();
    public abstract function calculate($info_array);
}