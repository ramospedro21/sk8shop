<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\UserAccessHelper;

use App\Models\Option;
use App\Models\OptionValue;
use App\Models\ErrorsLog;

class OptionsController extends Controller
{

    public function view(){

        $access = UserAccessHelper::getAccess();

        if($access == true)
            return view('options.index');
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

            $options = Option::with(['values'])->paginate(Option::PER_PAGE);

            return response()->json($options, 200);

        }catch(\Exception $e){
            
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar as opções',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine()
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

                if($request->input('option.parent_id')){

                    $optionValue = OptionValue::create([
                        'option_id' => $request->input('option.parent_id'),
                        'title' => $request->input('option.title'),
                        'color' => $request->input('option.color'),
                    ]);

                }else{
                    
                    $optionValue = Option::create([
                        'title' => $request->input('option.title'),
                        'type' => 'droplist',
                    ]);
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
                'message' => 'Não foi possivel cadastrar a opção',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine()
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

                if($request->input('option.parent_id')){

                    $optionValue = OptionValue::where('id', $id)->update([
                        'title' => $request->input('option.title'),
                        'color' => $request->input('option.color'),
                    ]);

                }else{

                    $option = Option::where('id', $id)->update([
                        'title' => $request->input('option.title'),
                    ]);

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
                'message' => 'Não foi possivel editar a opção',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine()
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
    public function destroy($id, $type)
    {   
        try{
            DB::beginTransaction();

                if($type == 'option'){

                    $option = Option::where('id', $id)->delete();

                }else{
                    
                    $value = OptionValue::where('id', $id)->delete();

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
                'message' => 'Não foi possivel excluir a opção',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
}
