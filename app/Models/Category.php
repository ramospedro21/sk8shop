<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public const PER_PAGE = 16;

    protected $table = 'categories';

    /**
     * @property int $id
     * @property string $title
     * @property string $description
     * @property int $parent_id
     * @property string $slug
    */

    protected $fillable = [
        'id',
        'title',
        'description',
        'parent_id',
        'slug',
    ];
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id', 'id');
    } 

    public function products()
    {
        return $this->hasMany('App\Models\ProductCategories', 'category_id', 'id');
    }
}
