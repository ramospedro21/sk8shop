<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    /**
     * @property int $id
     * @property int $product_id
     * @property int $coupon_id
    */

    protected $fillable = [ 
        'id',
        'product_id',
        'coupon_id',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon', 'coupon_id', 'id');
    }
}
