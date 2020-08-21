<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Stock;
use App\Models\ErrorsLog;

class StocksController extends Controller
{   

    public function view(){
        return view('stocks.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $stocks = Stock::with(['products'])->paginate(Stock::PER_PAGE);

            foreach($stocks as $stock){

                # DEFINICAO DE STATUS
                if($stock->status == Stock::ACTIVE)  $stock->status = "Ativo";
                if($stock->status == Stock::NOT_ACTIVE)  $stock->status = "Desativado";

            }

            return response()->json($stocks, 200);

        }catch(\Exception $e){

            #CADASTRO DE LOG DE ERRO (CASO ACONTEÇA)
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar os estoques.',
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

                $stock = Stock::create([
                    "status" => $request->input('stock.status'),
                    "zipcode" => $request->input('stock.zipcode'),
                    "street" => $request->input('stock.street'),
                    "district" => $request->input('stock.district'),
                    "street_number" => $request->input('stock.street_number'),
                    "complement" => $request->input('stock.complement'),
                    "city" => $request->input('stock.city'),
                    "state" => $request->input('stock.state'),
                    "title" => $request->input('stock.title'),
                    "description" => $request->input('stock.description'),
                    'country' => 'BRA',
                ]);

            DB::commit();

            return response()->json([
                'success' => true
            ], 200);

        }catch(\Exception $e){

            #CADASTRO DE LOG DE ERRO (CASO ACONTEÇA)
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel casdastrar o estoque.',
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
            DB::beginTransaction();

                $stock = Stock::where('id', $id)->update([
                    "status" => $request->input('stock.status'),
                    "zipcode" => $request->input('stock.zipcode'),
                    "street" => $request->input('stock.street'),
                    "district" => $request->input('stock.district'),
                    "street_number" => $request->input('stock.street_number'),
                    "complement" => $request->input('stock.complement'),
                    "city" => $request->input('stock.city'),
                    "state" => $request->input('stock.state'),
                    "title" => $request->input('stock.title'),
                    "description" => $request->input('stock.description'),
                ]);

            DB::commit();

            return response()->json([
                'success' => true
            ], 200);

        }catch(\Exception $e){

            #CADASTRO DE LOG DE ERRO (CASO ACONTEÇA)
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel editar o estoque.',
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
    public function destroy($id)
    {
        try{

            DB::beginTransaction();

            $stock = Stock::where('id', $id)->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
            ], 200);


        }catch(\Exception $e){

            #CADASTRO DE LOG DE ERRO (CASO ACONTEÇA)
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel excluir o estoque.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }
}
