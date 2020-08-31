<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public const PER_PAGE = 16;

    /**
     * @property int $id
     * @property string $title
     * @property string $type
     */

    protected $fillable = [ 
        'id',
        'title',
        'type',
    ];

    public function values()
    {
        return $this->hasMany('App\Models\OptionValue', 'option_id', 'id');
    }
}
