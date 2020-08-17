<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    /**
     * @property int $id
     * @property int $product_id
     * @property string $url
     */
    
    protected $fillable = [
        'id',
        'product_id',
        'url',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
