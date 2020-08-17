<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * @property int $id
     * @property string $title
     * @property string $description
     * @property string $city
     * @property string $district
     * @property string $street
     * @property string $street_number
     * @property string $complement
     * @property string $zipcode
     * @property string $state
     * @property string $country
     * @property boolean $status
     */

    protected $fillable = [
        'id',
        'title',
        'description',
        'city',
        'district',
        'street',
        'street_number',
        'complement',
        'zipcode',
        'state',
        'country',
        'status',
    ];

    public function products()
    {
        return $this->hasMany('App\Models\ProductStock', 'stock_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\StockLog', 'stock_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Orders', 'stock_id', 'id');
    }
}
