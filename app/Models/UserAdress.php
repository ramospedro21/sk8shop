<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAdress extends Model
{
    /**
     * @property int $user_id
     * @property string $street
     * @property string $district
     * @property string $street_number
     * @property string $complement
     * @property string $zipcode
     * @property string $city
     * @property string $state
     * @property string $country
     * @property string $longitude
     * @property string $latitude
     */

    protected $fillable = [
        'user_id',
        'street',
        'district',
        'street_number',
        'complement',
        'zipcode',
        'city',
        'state',
        'country',
        'longitude',
        'latitude',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
