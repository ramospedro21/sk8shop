<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    /**
     * @property int $id
     * @property string $title
     * @property string $type
     */

    protected $fillable = [ 
        'id',
        'title',
        'type',
    ];
}
