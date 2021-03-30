<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Variant;

use Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cart');
    }

    public function details(Request $request){

        $cart = $request->session()->get('cart');

        try{

            return response()->json($cart);

        }catch(\Exception $error){

            return response()->json([
                'message' => 'Não foi possivel retornar os detalhes do carrinho',
                'errors' => [
                    'message' => $error->getMessage(),
                    'line' => $error->getLine()
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
        try{

            $product = $this->getVariant($request->item);

            if($request->input('item.qtd')){
                $product->quantity = $request->input('item.qtd');
            }else{
                $product->quantity = 1;
            }

            $products = collect($request->session()->get('cart.products'));

            $products = json_decode(json_encode($products), FALSE);

            if($request->input('item.discount')){
                $discount = $request->input('item.discount');
            }else{
                $discount = 0;
            }

            $cartAmount = $request->session()->get('cart.amount');

            $newCart = collect();
            $found = 0;
            $amount = $cartAmount != null ? $cartAmount : 0;

            // Passa por toda a lista existente
            foreach($products as $productCart){

                // Se o produto da vez for igual ao recém adicionado
                if($productCart->id == $product->id) {

                    // Soma a quantity desejada com a já existente
                    if($request->input('item.qtd'))
                    {
                        $productCart->quantity = $productCart->quantity + $request->input('item.qtd');

                    }else{

                        $productCart->quantity = $productCart->quantity + 1;

                    }

                    // Calcula o subtotal do produto
                    if($productCart->stocks[0]->promote_price !== '0.00'){

                        $productCart->subTotal = number_format( $productCart->stocks[0]->promote_price, 2, '.', '');

                    }else{

                        $productCart->subTotal = number_format( $productCart->stocks[0]->price, 2, '.', '');

                    }

                    $found = 1;

                    $amount += $productCart->subTotal;
                }

                // Adiciona o produto da vez no novo cart
                $newCart->push($productCart);
            }

            // Se o produto solicitado não estava na lista salva, adiciona na nova lista
            if($found == 0) {

                if($request->input('item.qtd'))
                {
                    $product->quantity = $request->input('item.qtd');
                }
                else{
                    $product->quantity = 1;
                }

                if($request->input('item.discount')){
                    $product->discount = $request->input('item.discount');
                }else{
                    $product->discount = 0;
                }

                $amount += $product['stocks'][0]['promote_price'] != '0.00' ? $product['stocks'][0]['promote_price'] : $product['stocks'][0]['price'];

                $newCart->push($product);
            }


            // Salva ou atualiza a lista de products
            $request->session()->put('cart.products', $newCart);
            $request->session()->put('cart.amount', $amount);
            $request->session()->put('cart.cartCount', $newCart->sum('quantity'));

            return response()->json([
                "cart" => Session::get('cart')
            ], 200);

        }catch(\Exception $error){

            return response()->json([
                'message' => 'Não foi possivel adicionar o produto ao carrinho',
                'errors' => [
                    'message' => $error->getMessage(),
                    'line' => $error->getLine(),
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
    public function update(Request $request)
    {

        $products = $request->input('cart');
        $cart = json_decode(json_encode($products), FALSE);

        $request->session()->put('cart', $request->input('cart'));
        return $this->details($request);
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

    private function getVariant($item){

        return $variant = Variant::with([
                                    'product.images',
                                    'values' => function($query) use ($item){
                                        $query->where('option_value_id', $item['optionsValues'][0]['value_id']);
                                    },
                                    'product' => function($query){
                                        $query->with('categories');
                                    },
                                    'stocks' => function($query){
                                        $query->where(function($query){
                                            $query->where('price', '>', 0);
                                            $query->orWhere('promote_price', '>', 0);
                                        });
                                    },
                                ])->whereHas('stocks', function($query){
                                    $query->where(function($query){
                                        $query->where('price', '>', 0);
                                        $query->orWhere('promote_price', '>', 0);
                                    });
                                })->find($item['variant']['id']);

    }
}
