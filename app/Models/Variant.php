<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    /**
     * @property int $id
     * @property int $product_id
     * @property decimal $weight
     * @property string $sku
     */

    public const PER_PAGE = 16;

    protected $fillable = [
        'id',
        'product_id',
        'weight',
        'sku',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany('App\Models\ProductPurchase', 'variant_id', 'id');
    }
    
    public function values()
    {
        return $this->belongsToMany('App\Models\OptionValue', 'variant_values', 'variant_id', 'option_value_id');
    }

    public function stocks()
    {
        return $this->hasMany('App\Models\ProductStock', 'variant_id', 'id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\VariantImage', 'variant_id', 'id');
    }
}
