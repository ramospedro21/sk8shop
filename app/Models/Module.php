<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /**
     * @property int $id
     * @property string $name
     * @property string $icon
     * @property string $link
     */

    protected $fillable = [
        'id',
        'name',
        'icon',
        'link',
    ];
}
