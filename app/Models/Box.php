<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    public const PER_PAGE = 16;

    protected $fillable = [
        'title',
        'quantity',
        'width',
        'depth',
        'height',
    ];
}
