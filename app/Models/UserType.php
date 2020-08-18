<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    /**
     * @property int $id
     * @property string $name
    */

    protected $fillable = [ 
        'id',
        'name'
    ];

    public function modulos()
    {
        return $this->belongsToMany('App\Models\Modules', 'user_module', 'user_id', 'module_id');
    }
}
