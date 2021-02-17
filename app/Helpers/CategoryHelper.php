<?php

namespace App\Helpers;
use Illuminate\Support\Facades\URL;
use Auth;

use App\Models\Category;

class CategoryHelper {

    public static function getCategories($parent_id, $showing = false)
    {
        try{

            $categories = Category::where('parent_id', $parent_id)
                                  ->withCount(['products', 'category']);

            if($showing == true){
                $categories = $categories->where('showing', 1);
            }

            return $categories->get();


        }catch(\Exception $e){
            dd($e);
        }
    }

}
