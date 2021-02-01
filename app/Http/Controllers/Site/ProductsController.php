<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        
        try{

            $product = Product::with(['categories.category',
                                      'variants',
                                      'variants.images', 
                                      'variants.stocks'])
                                ->where('slug', $slug)
                                ->first();

            // Make a product breadcrumb by category
            $product->breadcrumb = collect();
            
            if ($product->categories->count() > 0) {
                
                $product->breadcrumb->push($product->categories[0]['category']);
                
                if ($product->categories[0]['category']->parent_id) {

                    $category = Category::select('slug', 'title', 'parent_id')->find($product->categories[0]['category']->parent_id);
                    $product->breadcrumb->push($category);

                    if ($category->parent_id) {
                        $category = Category::select('slug', 'title', 'parent_id')->find($category->parent_id);
                        $product->breadcrumb->push($category);
                    }

                }

            }
            
            $categoriesIds = $product->categories->pluck('category_id');

            $product->breadcrumb = $product->breadcrumb->reverse()->flatten();
            dd($product['variants']);
            return view('product', [
                'product' => $product,
            ]);

        }catch(\Exception $error){
            dd($error);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
