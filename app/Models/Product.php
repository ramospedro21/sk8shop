<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @property string $title
     * @property string $slug
     * @property string $short_description
     * @property string $description
     * @property int $installments
     * @property decimal $width
     * @property decimal $depth
     * @property decimal $heigth
     * @property boolean $enabled
     */

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'installments',
        'width',
        'depth',
        'heigth',
        'enabled',
    ];

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage', 'product_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Category', 'product_id', 'id');
    }
    
    public function coupons()
    {
        return $this->hasMany('App\Models\ProductCoupon', 'product_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany('App\Models\ProductPurchase', 'product_id', 'id');
    }
}
