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
}
