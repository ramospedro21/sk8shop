<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    /**
     * @property int $id
     * @property int $stock_id
     * @property int $provider_id
     * @property date $date
     * @property decimal $amount
     * @property int $status
    */

    protected $fillable = [
        'id',
        'stock_id',
        'provider_id',
        'date',
        'amount',
        'status',
    ];

    public function stock()
    {
        return $this->belongsTo('App\Models\Stock', 'stock_id', 'id');
    }
    
    public function purchases()
    {
        return $this->hasMany('App\Models\ProductPurchase', 'purchase_id', 'id');
    }
}
