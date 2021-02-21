<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\UserAccessHelper;

use App\Models\Order;
use App\Models\ErrorsLog;

class OrdersController extends Controller
{
    public function view(){

        $access = UserAccessHelper::getAccess();

        if($access == true)

            return view('orders.index');

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

            $orders = Order::withCount('products')->with(['payment'])->paginate(Order::PER_PAGE);

            foreach($orders as $order){

                # DEFINICAO DE STATUS
                if($order->status == Order::STATUS['WAITING_PAYMENT']) $order->status = "Aguardando pagamento.";
                if($order->status == Order::STATUS['IN_ANALYSIS']) $order->status = "Pagamento em analise";
                if($order->status == Order::STATUS['APROVED']) $order->status = "Pagamento aprovado";
                if($order->status == Order::STATUS['IN_ROUTE']) $order->status = "Em rota";
                if($order->status == Order::STATUS['DELIVERED']) $order->status = "Entregue";

            }

            return response()->json($orders, 200);


        }catch(\Exception $error){

            ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar os pedidos.',
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
        $order = Order::with(['payment', 'products.variant.images', 'products.variant.product', 'products.variant.values.option', 'user'])->findOrFail($id);

        return view('orders.show', ['order' => $order]);
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
