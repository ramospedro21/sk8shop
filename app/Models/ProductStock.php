<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    /**
     * @property int $id
     * @property int $product_id
     * @property int $stock_id
    */

    protected $fillable = [
        'stock_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'promote_price',
        'factory_price',
        'reserved',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
    
    public function stock()
    {
        return $this->belongsTo('App\Models\Stock', 'stock_id', 'id');
    }
}
