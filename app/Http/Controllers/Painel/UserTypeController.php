<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ErrorsLog;
use App\Models\Module;
use App\Models\UserType;
use App\Models\UserModule;

class UserTypeController extends Controller
{   
    public function view(){
        return view('user_type.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $userTypes = UserType::with(['modules'])->paginate(UserType::PER_PAGE);


            $userTypes->map(function($uT){

                $uT->modules->map(function($module){
                    unset($module->pivot);
                    return $module;
                });
                return $uT;

            });
            
            return response()->json($userTypes, 200);

        }catch(\Exception $e){
            
            #CADASTRO DE LOG DE ERRO (CASO ACONTEÇA)
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar os tipos de usuário.',
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


            $userType = UserType::create([
                'title' => $request->input('user_type.title'),
            ]);

            foreach($request->input('user_type.modules') as $m){

                $userModule = UserModule::create([
                    'user_type_id' => $userType->id,
                    'module_value_id' => $m['id']
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Tipo de usuário cadastrado com sucesso.'
            ], 200);

        }catch(\Exception $e){

            #CADASTRO DE LOG DE ERRO (CASO ACONTEÇA)
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel cadastrar o tipo de usuário.',
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

            $userType = UserType::where('id', $id)->update([
                'title' => $request->input('user_type.title'),
            ]);

            UserModule::where('user_type_id', $id)->delete();

            foreach($request->input('user_type.modules') as $m){
                $userModule = UserModule::create([
                    'user_type_id' => $id,
                    'module_value_id' => $m['id']
                ]);
            }

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
                'message' => 'Não foi possivel editar o tipo de usuário.',
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

            $userType = UserType::where('id', $id)->delete();
            
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
                'message' => 'Não foi possivel excluir o tipo de usuário.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    public function all(){
        
        try{

            $user_types = UserType::get();

            return response()->json($user_types, 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar todos os módulos.',
                'errors' => [ 
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    public function allModules(){
        try{

            $modules = Module::with(['values'])->get();

            return response()->json($modules, 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar todos os módulos.',
                'errors' => [ 
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }
}
