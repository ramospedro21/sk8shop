<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantImage extends Model
{   
    protected $fillable = [
        'variant_id',
        'product_id',
        'url'
    ];

    public function variant()
    {
        return $this->belongsTo('App\Models\Variant', 'variant_id', 'id');
    }
}
