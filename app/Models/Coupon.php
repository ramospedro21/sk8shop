<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * @property int $id
     * @property string $title
     * @property string $description
     * @property date $start_date
     * @property date $end_date
     * @property int $max_uses
     * @property int $type
     * @property int $limit_per_user
     * @property int $min_product_quantity
     * @property int $target
     * @property decimal $reduction_amount
     * @property decimal $min_value
     * @property boolean $first_buy_only
     * @property boolean $status
     */
    
    public const PER_PAGE = 16;
    
    public const TYPE = [
        'PERCENT' => 0,
        'MONEY' => 1
    ];

    public const TARGET = [
        'PRICE' => 0,
        'FREIGHT' => 1
    ];

    public const STATUS = [
        'INACTIVE' => 0,
        'ACTIVE' => 1
    ];

    public const FIRST_BUY_ONLY = [
        'FALSE' => 0,
        'TRUE' => 1
    ];

    protected $fillable = [
        'id',
        'title',
        'description',
        'start_date',
        'end_date',
        'max_uses',
        'type',
        'limit_per_user',
        'min_product_quantity',
        'target',
        'reduction_amount',
        'min_value',
        'first_buy_only',
        'status',
    ];
    
    public function products()
    {
        return $this->hasMany('App\Models\ProductCoupons', 'coupon_id', 'id');
    }
    
}
