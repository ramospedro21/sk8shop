<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\UserAccessHelper;

use App\Models\ErrorsLog;
use App\Models\Box;

class BoxesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(){

        $access = UserAccessHelper::getAccess();

        if($access == true)
            return view('boxes.index');
        else
            abort('404');
    }


    public function index()
    {
        try{

            $boxes = Box::paginate(Box::PER_PAGE);

            return response()->json($boxes, 200);

        }catch(\Exception $e){
            
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar as embalagems',
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

                $box = Box::create([
                    'title' => $request->input('box.title'),
                    'quantity' => $request->input('box.quantity'),
                    'width' => $request->input('box.width'),
                    'depth' => $request->input('box.depth'),
                    'height' => $request->input('box.height'),
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
                'message' => 'Não foi possivel cadastrar a embalagem',
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

                $box = Box::where('id', $id)->first()->update([
                    'title' => $request->input('box.title'),
                    'quantity' => $request->input('box.quantity'),
                    'width' => $request->input('box.width'),
                    'depth' => $request->input('box.depth'),
                    'height' => $request->input('box.height'),
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
                'message' => 'Não foi possivel editar a embalagem',
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

                $box = Box::where('id', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true
            ], 200);

        }catch(\Exception $e){
            
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel excluir a embalagem',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }
}
