<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Helpers\UserAccessHelper;
use App\Models\ErrorsLog;
use App\Models\Product;

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

            $products = Product::with(['stocks'])->paginate(Product::PER_PAGE);

            foreach($products as $product){

                # DEFINICAO DE STATUS
                if($product->enabled == Product::ENABLED) $product->enabled = "Sim";
                if($product->enabled == Product::NOT_ENABLED) $product->enabled = "Não";

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
                    'enabled' => $request->input('product.enabled'),
                ]);

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
        //
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
