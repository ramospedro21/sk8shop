<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    /**
     * @property int $id
     * @property int $variant_id
     * @property int $option_value_id
    */

    protected $fillable = [
        'variant_id',
        'option_value_id',
    ];

    public function variant()
    {
        return $this->belongsTo('App\Models\Variant', 'variant_id', 'id');
    }
 
    public function option_value()
    {
        return $this->belongsTo('App\Models\OptionValue', 'option_value_id', 'id');
    }
}
