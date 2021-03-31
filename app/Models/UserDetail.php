<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    /**
     * @property int $id
     * @property int $user_id
     * @property date $birthdate
     * @property string $tax_document_type
     * @property string $tax_document_number
     * @property string $identity_document_number
     * @property string $identity_document_type
     * @property int $phone_country_code
     * @property string $phone_area_code
     * @property string $phone_number
     * @property string $gateway_id
     */

    protected $fillable = [
        'user_id',
        'birthdate',
        'tax_document_type',
        'tax_document_number',
        'identity_document_number',
        'identity_document_type',
        'phone_country_code',
        'phone_area_code',
        'phone_number',
        'gateway_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
