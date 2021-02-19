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
}
