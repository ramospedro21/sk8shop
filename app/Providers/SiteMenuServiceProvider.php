<?php

namespace App\Providers;

use App\Helpers\CategoryHelper;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SiteMenuServiceProvider extends ServiceProvider
{

    private $categories;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        View::composer(['layouts.app', 'layouts.checkout'], function ($view) {
            try {

                if (!$this->categories) {

                    $categories = CategoryHelper::getCategories(null, true);

                    foreach($categories as $key=>$category){

                        if($category->category_count > 0 || $category->products_count > 0){

                            $category->children = CategoryHelper::getCategories($category->id);

                            if($category->children->count() > 0){

                                foreach($category->children as $key=>$child){

                                    if($child->category_count > 0 || $child->products_count > 0){

                                        $child->children = CategoryHelper::getCategories($child->id);

                                        foreach($child->children as $key=>$children){

                                            if($children->products_count == 0){
                                                unset($child->children[$key]);
                                            }

                                        }

                                    }else{
                                        unset($category->children[$key]);
                                    }

                                }

                            }
                        }else{
                            unset($categories[$key]);
                        }

                    }

                    $this->categories = $categories;
                }

                View::share('categories', $this->categories);

            } catch (\Exception $e) {
                dd($e);
                View::share('categories', 'erro');

            }
        });

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
