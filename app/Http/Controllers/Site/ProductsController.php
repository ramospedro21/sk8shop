<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Option;

use Session;

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
                                      'images',
                                      'variants',
                                      'variants.values',
                                      'variants.images',
                                      'variants.stocks' => function ($query){
                                          $query->where(function($query) {
                                              $query->where('price', '>', 0);
                                              $query->orWhere('promote_price', '>', 0);
                                          });
                                      }])
                              ->whereHas('variants.stocks', function ($query){
                                  $query->where('price', '>', 0);
                                  $query->orWhere('promote_price', '>', 0);
                              })
                              ->where('slug', $slug)
                              ->first();

            // Make a product breadcrumb by category
            $product->breadcrumb = $this->makeBreadcrumb($product);

            $similars = $this->getSimilars($product->categories, $product->id);

            $data = $this->makeOptions($product);

            $options = $data[0];
            $variants = $data[1];

            return view('product', [
                'product' => $product,
                'similars' => $similars,
                'options' => $options,
                'variants' => $variants,
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

    private function makeBreadcrumb($product){

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

        return $product->breadcrumb->reverse()->flatten();
    }

    private function getSimilars($categories, $product_id){

        $categoriesIds = $categories->pluck('category_id');

        $products = Variant::with(['images',
                                   'product' => function($query){
                                       $query->where('enabled', 1);
                                   },
                                   'stocks' => function ($query){
                                        $query->where(function($query) {
                                            $query->where('price', '>', 0);
                                            $query->orWhere('promote_price', '>', 0);
                                        });
                                    }])
                           ->whereHas('stocks', function($query){
                               $query->where(function($query){
                                   $query->where('price', '>', 0);
                                   $query->orWhere('promote_price', '>', 0);
                               });
                           })
                           ->whereHas('product.categories', function ($query) use ($categoriesIds) {
                               $query->whereIn('category_id', $categoriesIds);
                           })
                           ->limit(4)
                           ->where('product_id', '!=', $product_id)
                           ->orderBy('created_at', 'desc')
                           ->whereHas('stocks', function ($query){
                               $query->where('price', '>', 0);
                               $query->orWhere('promote_price', '>', 0);
                           })->get();

        return $products;
    }

    private function makeOptions($product){

        $options = collect();
        $optionsValues = $product->variants->pluck('values')->flatten();
        $product->options = $optionsValues->pluck('option_id')->flatten()->unique();

        foreach($product->options as $optionId){

            $option = Option::select(['id', 'title'])->find($optionId);
            $option->values = $optionsValues->where('option_id',$optionId)->flatten()->unique('option_value_id');
            $options->push($option);

        }

        $variants = collect();

        foreach($product->variants as $variant){

            $quantity = 0;

            // quantidade
            $quantity = $variant->stocks->sum("quantity");

            // estoque valido
            $variantStocks = $variant->stocks->filter(function($variantStock) {
                return $variantStock->price > 0 || $variantStock->saleprice > 0;
            })->flatten();

            $values = collect();

            foreach($variant->values as $value){

                $values->push([
                    "option" => $value->option_id,
                    "value" => $value->pivot->option_value_id,
                ]);
            }

            if($variant->stocks->sum("quantity") > 0){

                if(isset($variantStocks[0])){
                    $variants->push([
                        "id" => $variant->id,
                        "price" => $variantStocks[0]->price,
                        "promote_price" => $variantStocks[0]->promote_price,
                        "weight" => $variant->weight,
                        "quantity" => $quantity,
                        "options" => $values,
                        "images" => $variant->images,
                    ]);
                }

            }
        }

        unset($product['options']);
        unset($product['variants']);

        return [$options, $variants];
    }
}
