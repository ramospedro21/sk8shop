<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Providers extends Model
{
    /**
     * @property int $id
     * @property string $tax_document_type
     * @property string $tax_document_number
     * @property string $name
     * @property string $phone
     * @property string $site
     * @property string $city
     * @property string $district
     * @property string $street
     * @property string $street_number
     * @property string $complement
     * @property string $zipcode
     * @property string $state
     * @property string $country
    */

    protected $fillable = [
        'id',
        'tax_document_type',
        'tax_document_number',
        'name',
        'phone',
        'site',
        'city',
        'district',
        'street',
        'street_number',
        'complement',
        'zipcode',
        'state',
        'country',
    ];

    public function purchases()
    {
        return $this->hasMany('App\Models\Purchases', 'provider_id', 'id');
    }
}
