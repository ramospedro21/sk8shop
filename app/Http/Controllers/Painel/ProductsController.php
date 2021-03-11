<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Helpers\UserAccessHelper;

use App\Models\ErrorsLog;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductCategory;
use App\Models\ProductCoupon;
use App\Models\Variant;
use App\Models\VariantValue;
use App\Models\VariantImage;

use File;

use Intervention\Image\Facades\Image as Image;

class ProductsController extends Controller
{

    public function view(){

        $access = UserAccessHelper::getAccess();

        if($access == true)

            return view('products.index');

        else

            abort('404');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $products = Product::with(['stocks.stock' => function($query){
                $query->where('title', 'like' . '%' . 'Ecommerce' . '%');
            }])->paginate(Product::PER_PAGE);


            foreach($products as $product){

                $product->quantity = 0;

                foreach($product->stocks as $stock){
                    $product->quantity += $stock->quantity;
                }

                # DEFINICAO DE STATUS
                if($product->enabled == Product::ENABLED) $product->translatedEnabled = "Sim";
                if($product->enabled == Product::NOT_ENABLED) $product->translatedEnabled = "Não";

            }

            return response()->json($products, 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar os produtos.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{

            return view('products.create');

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel abrir a pagina de criação de produtos.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

            DB::beginTransaction();

                $slug = Str::slug($request->input('product.title'), '-');

                $hasSlug = Product::where('slug', $slug)->first();

                if($hasSlug) $slug = $slug . Str::random(3);

                $product = Product::create([
                    'title' => $request->input('product.title'),
                    'slug' => $slug,
                    'short_description' => $request->input('product.short_description'),
                    'description' => $request->input('product.description'),
                    'installments' => $request->input('product.installments'),
                    'width' => $request->input('product.width'),
                    'depth' => $request->input('product.depth'),
                    'heigth' => $request->input('product.heigth'),
                    'enabled' => $request->input('product.enabled'),
                    'self_delivery' => $request->input('product.self_delivery'),
                ]);

                if($request->input('product.variants')){

                    foreach($request->input('product.variants') as $variant){

                        $v = Variant::create([
                            'product_id' => $product->id,
                            "sku" => $variant['sku'],
                            "weight" => $variant['weight'],
                        ]);

                        foreach($variant['values'] as $variantValue){

                            $value = VariantValue::create([
                                'variant_id' => $v->id,
                                'option_value_id' => $variantValue['value_id']
                            ]);

                        }

                        foreach($variant['stocks'] as $stock){

                            if($stock['quantity'] > 0){

                                $variantStock = ProductStock::create([
                                    'stock_id' => $stock['id'],
                                    'product_id' => $product->id,
                                    'variant_id' => $v->id,
                                    'quantity' => $stock['quantity'],
                                    'price' => $stock['price'],
                                    'promote_price' => isset($stock['promote_price']) ? $stock['promote_price'] : 0 ,
                                    'factory_price' => isset($stock['factory_price']) ? $stock['factory_price'] : 0 ,
                                    'reserved' => 0,
                                ]);

                            }
                        }

                        if($variant['images']){

                            foreach($variant['images'] as $image){
                                $randNumber = rand();

                                $name = $slug.$randNumber;

                                $path = public_path('images/variants/'.$v->id.'/');

                                if(!File::exists($path)) {
                                    File::makeDirectory($path, $mode = 0777, true, true);
                                }

                                Image::make($image['url'])->save($path.$name);

                                $variantImage = VariantImage::create([
                                    'variant_id' => $v->id,
                                    'product_id' => $product->id,
                                    'url' => url('images/variants/'.$v->id.'/'.$name),
                                ]);
                            }
                        }
                    }
                }

                if($request->input('product.categories')){

                    foreach($request->input('product.categories') as $category){

                        $category = ProductCategory::create([
                            'product_id' => $product->id,
                            'category_id' => $category['id']
                        ]);

                    }
                }

                if(count($request->input('product.coupons')) == 0){

                    ProductCoupon::where('product_id', $product->id)->delete();

                } else {

                    foreach($request->input('product.coupons') as $coupon){

                        ProductCoupon::create([
                            'product_id' => $product->id,
                            'coupon_id' => $coupon['id']
                        ]);

                    }

                }

            DB::commit();

            return response()->json([
                'success' => true
            ], 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel cadastrar o produto.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{

            $product = Product::with(['categories.category',
                                      'coupons',
                                      'variants.values.option',
                                      'variants.stocks.stock',
                                      'images',
                                      'coupons.coupon'
                                    ])->find($id);

            $product->variants->map(function($variant){

                $variant->stocks->map(function($stock){
                    $stock->title = $stock->stock->title;
                    return $stock;
                });

                return $variant;
            });

            $product->coupons->map(function($coupon){

                $coupon->title = $coupon->coupon->title;

                return $coupon;
            });

            return view('products.show', ['product' => $product]);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel buscar o produto.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
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
        try{

            DB::beginTransaction();

                $product = Product::find($id)->update([
                    'title' => $request->input('product.title'),
                    'short_description' => $request->input('product.short_description'),
                    'description' => $request->input('product.description'),
                    'installments' => $request->input('product.installments'),
                    'width' => $request->input('product.width'),
                    'depth' => $request->input('product.depth'),
                    'heigth' => $request->input('product.heigth'),
                    'enabled' => $request->input('product.enabled'),
                    'self_delivery' => $request->input('product.self_delivery'),
                ]);

                Variant::where('product_id', $id)->delete();

                if($request->input('product.variants')){

                    foreach($request->input('product.variants') as $variant){

                        $v = new Variant;
                        $v->product_id = $id;
                        $v->sku = $variant['sku'];
                        $v->weight = $variant['weight'];
                        $v->save();


                        foreach($variant['values'] as $variantValue){

                            $value = VariantValue::create([
                                'variant_id' => $v->id,
                                'option_value_id' => isset($variantValue['value_id']) ? $variantValue['value_id'] : $variantValue['pivot']['option_value_id'],
                            ]);

                        }

                        foreach($variant['stocks'] as $stock){

                            if(isset($stock['created_at'])){

                                ProductStock::where('product_id', $id)->where('variant_id', $stock['variant_id'])->delete();

                            }

                            if($stock['quantity'] > 0){


                                $variantStock = ProductStock::create([
                                    'stock_id' => isset($stock['stock_id']) ? $stock['stock_id'] : $stock['id'],
                                    'product_id' => $id,
                                    'variant_id' => $v->id,
                                    'quantity' => $stock['quantity'],
                                    'price' => $stock['price'],
                                    'promote_price' => isset($stock['promote_price']) ? $stock['promote_price'] : 0 ,
                                    'factory_price' => isset($stock['factory_price']) ? $stock['factory_price'] : 0 ,
                                    'reserved' => 0,
                                ]);

                            }
                        }
                    }
                }

                ProductCategory::where('product_id', $id)->delete();

                if($request->input('product.categories')){

                    foreach($request->input('product.categories') as $category){

                        $category = ProductCategory::create([
                            'product_id' => $id,
                            'category_id' => isset($category['category_id']) ? $category['category_id'] : $category['id'],
                        ]);

                    }
                }

                $productImages = VariantImage::where('product_id', $id)->get();

                if($request->input('product.images')){

                    $imagesId = collect();

                    foreach($request->input('product.images') as $data){
                        $imagesId->push($data['id']);
                    }

                    $deleteImage = VariantImage::where('product_id', $id)->whereNotIn('id', $imagesId)->delete();

                    foreach($request->input('product.images') as $image){

                        if (isset($image['product_id'])) {

                            $image_id = $image['id'];

                            $prouctIndex = $productImages->search(function ($productImage) use ($image_id) {
                                return $productImage->id === $image_id;
                            });

                            if ($prouctIndex !== true) $productImages->splice($prouctIndex, 1);

                            continue;
                        }

                        $randNumber = rand();

                        $name = $request->input('product.slug') . $randNumber;

                        $path = public_path('images/products/'.$id.'/');

                        if(!File::exists($path)) File::makeDirectory($path, $mode = 0777, true, true);

                        $img = Image::make($image['url'])->save($path.$name);

                        $variantImage = VariantImage::create([
                            'product_id' => $id,
                            'url' => url('images/products/'.$id.'/'.$name),
                        ]);
                    }
                }


                if(count($request->input('product.coupons')) == 0){
                    ProductCoupon::where('product_id', $id)->delete();
                }


            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel editar o produto.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{

            if($request->productsToDelete){
                foreach($request->productsToDelete as $productToDelete){

                    Product::find($productToDelete)->delete();

                }
            } else {

                Product::find($request->id)->delete();

            }

            return response()->json([
                'success' => true,
            ], 200);

        }catch(\Exception $error){

            return response()->json([
                'message' => 'Não foi possivel apagar o produto.',
                'errors' => [
                    'message' => $error->getMessage(),
                    'line' => $error->getLine(),
                ],
            ], 500);

        }
    }
}
