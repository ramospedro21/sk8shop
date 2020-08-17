<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    /**
     * @property int $id
     * @property int $option_id
     * @property string $title
     * @property string $color
     */

    protected $fillable = [ 
        'id',
        'option_id',
        'title',
        'color',
    ];

    public function option()
    {
        return $this->belongsTo('App\Models\Option', 'option_id', 'id');
    }
}
