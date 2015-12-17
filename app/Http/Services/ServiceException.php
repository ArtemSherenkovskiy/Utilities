<?php
/**
 * Created by IntelliJ IDEA.
 * User: Artem
 * Date: 17.12.2015
 * Time: 23:47
 */

namespace App\Http\Services;


class ServiceException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}