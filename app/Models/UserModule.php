<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModule extends Model
{
    /**
     * @property int $id
     * @property int $user_type_id
     * @property int $module_id
    */

    protected $fillable = [
        'user_type_id',
        'module_value_id',
    ];

}   
