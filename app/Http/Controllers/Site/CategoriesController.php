<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantValue;
use App\Models\OptionValue;
use App\Models\Option;

use StdClass;

class CategoriesController extends Controller
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
    public function show(Request $request, $slug)
    {
        try{

            // Procura a categoria solicitada
            $category = Category::query()->where('slug', $slug)->first();

            // cria um conjunto de id com o id inicial solicitado
            $categoriesIds = collect($category->id);

            // Procura as categorias filho
            $category->parents = Category::where('parent_id', $category->id)->get();

            // Junta os IDs dos filhos com o original
            $categoriesIds = $categoriesIds->merge($category->parents->pluck('id'));

            // Passa em cada filho procurando mais filhos
            foreach ($category->parents as $child) {

                // Procura os filhos
                $child = Category::where('parent_id', $child->id)->get();

                // Junta os IDs dos filhos com o original
                $categoriesIds = $categoriesIds->merge($child->pluck('id'));

            }

            // Remove os duplicados
            $categoriesIds = $categoriesIds->unique();

            // Procura os produtos que tenham os Ids das categorias
            $products = Variant::with(['images',
                                       'product',
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
                                });

            // dd($request->all());
            if ($request->query('ids', null)) {

                $ids = explode(',', $request->ids);
                $ids = str_replace('[', '', $ids);
                $ids = str_replace(']', '', $ids);
                $ids = str_replace('"', '', $ids);
                $ids = str_replace('"', '', $ids);

                $options = OptionValue::query()->whereIn('id', $ids)->get()->groupBy('option_id');

                // adiciona um where na consulta de produtos
                $products->where(function ($query) use ($options) {

                    // passa em cada opcao
                    foreach ($options as $option) {

                        // faz com que dentro desse where sempre considere AND
                        $query->where(function ($query) use ($option) {

                            foreach ($option as $key => $value) {

                                // se for o primeiro value usa where
                                if ($key == 0) {

                                    $query->whereHas('values', function ($query) use ($value) {
                                        $query->where('option_values.id', $value->id);
                                    });

                                    // se for depois da primeira usa OR WHERE
                                } else {

                                    $query->orWhereHas('values', function ($query) use ($value) {
                                        $query->where('option_values.id', $value->id);
                                    });

                                }

                            }

                        });

                    }

                });

            }

            $products = $products->orderBy('created_at', 'desc')->get();

            $variants = [];

            foreach ($products as $product) {
                foreach ($product->stocks as $stock) {
                    array_push($variants, $stock->variant_id);
                }
            }

            $variants_values = VariantValue::whereIn('variant_id', $variants)->get();

            $option_values = $variants_values->pluck('option_value_id')->unique()->toArray();
            $option_values = array_values($option_values);

            $options = OptionValue::with('option')->whereIn('id', $option_values)->get();

            $options_id = $options->pluck('option_id')->unique()->toArray();
            $options_id = array_values($options_id);

            $options_bd = Option::with('values')->whereIn('id', $options_id)->get();

            return view('categories', ['products' => $products, 'filters' => $options_bd, 'category' => $category]);

        }catch(\Exception $error){

            return view('components.404');

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
