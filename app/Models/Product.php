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

    public const PER_PAGE = 16;

    public const NOT_ENABLED = 0;
    public const ENABLED = 1;

    protected $fillable = [ 'title', 'slug', 'short_description', 'description', 'installments', 'width', 'depth',
                            'heigth', 'enabled', 'self_delivery'];

    public function categories()
    {
        return $this->hasMany('App\Models\ProductCategory', 'product_id', 'id');
    }

    public function coupons()
    {
        return $this->hasMany('App\Models\ProductCoupon', 'product_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany('App\Models\ProductPurchase', 'product_id', 'id');
    }

    public function stocks()
    {
        return $this->hasMany('App\Models\ProductStock', 'product_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany('App\Models\Variant', 'product_id', 'id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\VariantImage', 'product_id', 'id');
    }
}
