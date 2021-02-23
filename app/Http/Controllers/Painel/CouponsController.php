<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\UserAccessHelper;

Use App\Models\Coupon;
Use App\Models\ProductCoupon;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function view(){

        $access = UserAccessHelper::getAccess();

        if($access == true)
            return view('coupons.index');
        else
            abort('404');
    }

    public function index()
    {
        try{

            $coupons = Coupon::paginate(Coupon::PER_PAGE);

            foreach($coupons as $coupon){

                if($coupon->type == Coupon::TYPE['MONEY']) $coupon->translated_type = 'Dinheiro';
                if($coupon->type == Coupon::TYPE['PERCENT']) $coupon->translated_type = 'Porcentagem';

                if($coupon->target == Coupon::TARGET['PRICE']) $coupon->translated_target = 'Preço';
                if($coupon->target == Coupon::TARGET['FREIGHT']) $coupon->translated_target = 'Frete';

                if($coupon->status == Coupon::STATUS['INACTIVE']) $coupon->translated_status = 'Desativado';
                if($coupon->status == Coupon::STATUS['ACTIVE']) $coupon->translated_status = 'Ativo';

                if($coupon->first_buy_only == Coupon::FIRST_BUY_ONLY['FALSE']) $coupon->translated_first_buy_only = 'Não';
                if($coupon->first_buy_only == Coupon::FIRST_BUY_ONLY['TRUE']) $coupon->translated_first_buy_only = 'Sim';

            }

            return response()->json($coupons, 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel cadastrar o cupom.',
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

        try{

            DB::beginTransaction();

                $coupon = Coupon::create([
                    'title' => $request->input('coupon.title'),
                    'description' => $request->input('coupon.description'),
                    'start_date' => $request->input('coupon.start_date'),
                    'end_date' => $request->input('coupon.end_date'),
                    'max_uses' => $request->input('coupon.max_uses'),
                    'type' => $request->input('coupon.type'),
                    'limit_per_user' => $request->input('coupon.limit_per_user'),
                    'min_product_quantity' => $request->input('coupon.min_product_quantity'),
                    'target' => $request->input('coupon.target'),
                    'reduction_amount' => $request->input('coupon.reduction_amount'),
                    'min_value' => $request->input('coupon.min_value'),
                    'first_buy_only' => $request->input('coupon.first_buy_only'),
                    'status' => $request->input('coupon.status'),
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel cadastrar o cupom.',
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
        try{

            Coupon::findOrFail($id)->update([
                'title' => $request->input('coupon.title'),
                'description' => $request->input('coupon.description'),
                'start_date' => $request->input('coupon.start_date'),
                'end_date' => $request->input('coupon.end_date'),
                'max_uses' => $request->input('coupon.max_uses'),
                'type' => $request->input('coupon.type'),
                'limit_per_user' => $request->input('coupon.limit_per_user'),
                'min_product_quantity' => $request->input('coupon.min_product_quantity'),
                'target' => $request->input('coupon.target'),
                'reduction_amount' => $request->input('coupon.reduction_amount'),
                'min_value' => $request->input('coupon.min_value'),
                'first_buy_only' => $request->input('coupon.first_buy_only'),
                'status' => $request->input('coupon.status'),
            ]);

            return response()->json([
                'success' => true
            ], 200);

        }catch(\Exception $error){

            ErrorsLog::create([
                'description' => $error->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel editar o cupom.',
                'errors' => [
                    'message' => $error->getMessage(),
                    'line' => $error->getLine(),
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
    public function destroy($id)
    {
        try{

            ProductCoupon::where('coupon_id', $id)->delete();

            Coupon::find($id)->delete();

            return response()->json([
                'success' => true
            ], 200);

        }catch(\Exception $error){

            ErrorsLog::create([
                'description' => $error->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel excluir o cupom.',
                'errors' => [
                    'message' => $error->getMessage(),
                    'line' => $error->getLine(),
                ]
            ], 500);
        }
    }

    public function all(){

        try{

            $coupons = Coupon::all();

            return response()->json($coupons);

        }catch(\Exception $error){

            ErrorsLog::create([
                'description' => $error->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi buscar os cupons.',
                'errors' => [
                    'message' => $error->getMessage(),
                    'line' => $error->getLine(),
                ]
            ], 500);

        }

    }

    public function addToProduct(Request $request, $id){

        try{

            ProductCoupon::where('product_id', $id)->delete();

            $coupons = collect();

            foreach($request->coupons as $coupon){

                $newCoupon = ProductCoupon::create([
                    'product_id' => $id,
                    'coupon_id' => $coupon['id']
                ]);

                $newCoupon->title = $coupon['title'];

                $coupons->push($newCoupon);
            }

            return response()->json($coupons);

        }catch(\Exception $error){

            ErrorsLog::create([
                'description' => $error->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi cadastrar os cupons no protudo.',
                'errors' => [
                    'message' => $error->getMessage(),
                    'line' => $error->getLine(),
                ]
            ], 500);
        }

    }
}
