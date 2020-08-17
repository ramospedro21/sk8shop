<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorsLog extends Model
{
    /**
     * @property int $id
     * @property string $description
     */

    protected $fillable = [
        'id',
        'description'
    ];
}
