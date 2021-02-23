<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Coupon;
use App\Models\ProductCoupon;
use App\Models\Order;
use App\Models\UserCoupon;

use Auth;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

		$coupon = Coupon::with('products')->where('title', $request->input('coupon.title'))->first();

        //SE NAO EXISTIR O CUPOM ELE RETORNA ERRO.
        if(!$coupon){
            return response()->json([
                "mesangem" => "Cupom não encontrado!"
            ], 404);
        }

        $hoje = date("Y-m-d");

        //VERIFICAR SE O CUPOM ESTA ATIVO
        if($coupon->status == Coupon::STATUS['INACTIVE']){
            return response()->json([
                "mesangem" => "Cupom inativo!"
            ], 403);
        }

        //VERIFICAR SE O CUPOM ESTA NA DATA VALIDA OU SE JA ACABOU
        if($coupon->start_date > $hoje || $coupon->end_date < $hoje){
            return response()->json([
                "mesangem" => "Esse cupom foi expirado!"
            ], 403);
        }

        //VERIFICACOES QUE SAO NECESSARIAS PARA VALIDAR O CUPOM.

        //USO MAXIMO DO CUPOM
        $userCouponMax = UserCoupon::where('coupon_id', $coupon->id)
        ->count();

        if($userCouponMax > $coupon->max_uses){
            return response()->json([
                "mesangem" => "Limite do cupom atingido!"
            ], 403);
        }


        //VERIFICAR O LIMITE POR USUARIO
        $userCouponlimitperuser = UserCoupon::where('coupon_id', $coupon->id)
        ->where('user_id', Auth::user()->id)
        ->count();

        if($userCouponlimitperuser > $coupon->limit_per_user){
            return response()->json([
                "mesangem" => "Limite por usuário atingido!"
            ], 403);
        }

        //VERIFICAR A QUANTIDADE MINIMA DE PRODUTOS
        if($request->input('cart.cartCount') < $coupon->min_product_quantity){
            return response()->json([
                "mesangem" => "Quantidade mínima de produtos para utilização do cupom não atingida!"
            ], 403);
        }

        //VERIFICAR SE O CUPOM E UTILIZADO NA PRIMEIRA COMPRA. SE SIM, VERIFICAR SE USUARIO JA COMPROU ALGUMA VEZ NO SITE.
        if($coupon->first_buy_only == Coupon::FIRST_BUY_ONLY['TRUE']){
            $myOrders = Order::where('user_id', $request->user_id)->count();

            if($myOrders > 0){
                return response()->json([
                    "mesangem" => "Esse cupom só é válido para sua primeira compra!"
                ], 403);
            }
        }

        $product_coupon = ProductCoupon::where('coupon_id', $coupon->id)->get();

        $valido = "nao";

        foreach($request->input('cart.products') as $product){

            $search = $product_coupon->where('product_id', $product['product_id']);

            if($search->count() > 0){
                $valido = "sim";
            }

        }

        if($valido == "nao"){

            return response()->json([
                "message" => 'Este cupom não é valido para esse produto',
            ], 403);

        } else {

            return response()->json([
                "coupon" => $coupon
            ], 200);

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
        //
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
