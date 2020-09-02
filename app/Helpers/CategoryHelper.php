<?php

namespace App\Helpers;
use Illuminate\Support\Facades\URL;
use Auth;

use App\Models\Category;

class CategoryHelper {

    public static function getCategories($parent_id)
    {
        try{

            $categories = Category::where('parent_id',$parent_id)
                                    ->get();

            return $categories;    


        }catch(\Exception $e){
            dd($e);
        }
    }

}
