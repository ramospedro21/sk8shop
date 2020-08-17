<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    /**
     * @property int $id
     * @property int $variant_id
     * @property string $title
     * @property decimal $price
     * @property decimal $discount
     * @property decimal $amount
     * @property int $quantity
    */

    protected $fillable = [
        'id',
        'variant_id',
        'title',
        'price',
        'discount',
        'amount',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo('App\Models\Variants', 'variant_id', 'id');
    }
}
