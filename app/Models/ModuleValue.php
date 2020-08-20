<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleValue extends Model
{
    /**
     * @property int $id
     * @property int $module_id
     * @property string $icon
     * @property string $link
     */

    protected $fillable = [
        'id',
        'module_id',
        'icon',
        'link',
    ];

    public function module()
    {
        return $this->belongsTo('App\Models\Module', 'module_id', 'id');
    }

    public function userType()
    {
        return $this->hasMany('App\Models\UserModule', 'module_value_id', 'id');
    }

}
