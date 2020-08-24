<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
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

    public const CPF = 0;
    public const CNPJ = 1;
    public const PER_PAGE = 16;

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
        return $this->hasMany('App\Models\Purchase', 'provider_id', 'id');
    }
}
