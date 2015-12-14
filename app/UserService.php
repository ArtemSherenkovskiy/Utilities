<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_service';

    protected $primaryKey = ['user_id', 'service_id'];

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'service_id', 'user_info'];

}
