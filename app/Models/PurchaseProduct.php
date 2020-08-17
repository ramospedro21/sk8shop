<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    /**
     * @property int $id
     * @property int $purchase_id
     * @property int $product_id
     * @property int $variant_id
     * @property decimal $price
     * @property decimal $amount
     * @property int $quantity
     * @property date $delivery_date
     * @property int $delivery_status
    */

    protected $fillable = [
        'id',
        'purchase_id',
        'product_id',
        'variant_id',
        'price',
        'amount',
        'quantity',
        'delivery_date',
        'delivery_status',
    ];

    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'purchase_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo('App\Models\Variant', 'variant_id', 'id');
    }
}
