<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{   
    
    /**
     * @property int $id
     * @property string $TITLE
     */
    
     public const PER_PAGE = 16;
    
    protected $fillable = [ 
        'id',
        'title'
    ];

    public function modules()
    {
        return $this->belongsToMany('App\Models\ModuleValue', 'user_modules', 'user_type_id', 'module_value_id');
    }
}
