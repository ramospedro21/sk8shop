<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    /**
     * @property int $id
     * @property int $product_id
     * @property int $stock_id
    */

    protected $fillable = [
        'id',
        'product_id',
        'stock_id',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
    
    public function stock()
    {
        return $this->belongsTo('App\Models\Stock', 'stock_id', 'id');
    }
}
