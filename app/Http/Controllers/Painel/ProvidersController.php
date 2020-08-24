<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Provider;
use App\Models\ErrorsLog;

class ProvidersController extends Controller
{   

    public function view(){
        return view('factories.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            
            $providers = Provider::with(['purchases'])->paginate(Provider::PER_PAGE);

            return response()->json($providers, 200);

        }catch(\Exception $e){

            #CADASTRO DE LOG DE ERRO (CASO ACONTEÇA)
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar os fornecedores.',
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

                $provider = Provider::create([
                    'tax_document_type' => $request->input('provider.tax_document_type'),
                    'tax_document_number' => $request->input('provider.tax_document_number'),
                    'name' => $request->input('provider.name'),
                    'phone' => $request->input('provider.phone'),
                    'site' => $request->input('provider.site'),
                    'city' => $request->input('provider.city'),
                    'district' => $request->input('provider.district'),
                    'street' => $request->input('provider.street'),
                    'street_number' => $request->input('provider.street_number'),
                    'complement' => $request->input('provider.complement'),
                    'zipcode' => $request->input('provider.zipcode'),
                    'state' => $request->input('provider.state'),
                    'country' => 'BRA',
                ]);

            DB::commit();
            
            return response()->json([
                'success' => true,
            ], 200);


        }catch(\Exeception $e){

            #CADASTRO DE LOG DE ERRO (CASO ACONTEÇA)
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel cadastrar o fornecedor.',
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

                $provider = Provider::where('id', $id)->update([
                    'tax_document_type' => $request->input('provider.tax_document_type'),
                    'tax_document_number' => $request->input('provider.tax_document_number'),
                    'name' => $request->input('provider.name'),
                    'phone' => $request->input('provider.phone'),
                    'site' => $request->input('provider.site'),
                    'city' => $request->input('provider.city'),
                    'district' => $request->input('provider.district'),
                    'street' => $request->input('provider.street'),
                    'street_number' => $request->input('provider.street_number'),
                    'complement' => $request->input('provider.complement'),
                    'zipcode' => $request->input('provider.zipcode'),
                    'state' => $request->input('provider.state'),
                ]);

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
                'message' => 'Não foi possivel editar o fornecedor.',
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

                $provider = Provider::where('id', $id)->delete();

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
                'message' => 'Não foi possivel excluir o fornecedor.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }
}
