<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    /**
     * @property int $id
     * @property int $order_id
     * @property string $gateway_id
     * @property string $gateway_brand
     * @property string $gateway_status
     * @property int $installments
     * @property string $barcode_url
     * @property date $barcode_expiration_date
     * @property decimal $shipping_price
     * @property decimal $amount
    */

    protected $fillable = [
        'id',
        'order_id',
        'gateway_id',
        'gateway_brand',
        'gateway_status',
        'installments',
        'barcode_url',
        'barcode_expiration_date',
        'shipping_price',
        'amount',
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }
}
