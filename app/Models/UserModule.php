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
        'id',
        'user_type_id',
        'module_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'foreign_key', 'other_key');
    }

    public function module()
    {
        return $this->belongsTo('App\Models\Modules', 'module_id', 'id');
    }

}   
