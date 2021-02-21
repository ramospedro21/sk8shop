<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * @property int $id
     * @property int $user_id
     * @property int $stock_id
     * @property string $name
     * @property string $tax_document_type
     * @property string $tax_document_number
     * @property string $phone
     * @property string $ip_address
     * @property string $street
     * @property string $street_number
     * @property string $complement
     * @property string $district
     * @property string $city
     * @property string $state
     * @property string $zipcode
     * @property string $gatway_id
     * @property string $delivery_code
     * @property string $delivery_service
     * @property int $status
    */

    public const PER_PAGE = 16;

    public const STATUS = [
        'WAITING_PAYMENT' => 1,
        'IN_ANALYSIS' => 2,
        'APROVED' => 3,
        'IN_ROUTE' => 4,
        'DELIVERED' => 5,
        'CANCELLED' => 6,
    ];

    protected $fillable = [
        'id',
        'user_id',
        'stock_id',
        'name',
        'tax_document_type',
        'tax_document_number',
        'phone',
        'ip_address',
        'street',
        'street_number',
        'complement',
        'district',
        'city',
        'state',
        'zipcode',
        'gatway_id',
        'delivery_code',
        'delivery_service',
        'status',
    ];

    public function payment()
    {
        return $this->hasOne('App\Models\OrderPayment', 'order_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
