<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\UserAccessHelper;
use App\Helpers\UsersHelper;

use App\Models\User;
use App\Models\ErrorsLog;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(){

        $access = UserAccessHelper::getAccess();

        if($access == true)
            return view('clients.index');
        else
            abort('404');

    }

    public function index()
    {
        try{

            $clients = User::query()
                           ->where('user_type_id', 2)
                           ->withCount('orders')
                           ->paginate(User::PER_PAGE);

            return response()->json($clients, 200);

        }catch(\Exception $e){
            
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar os clientes.',
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
        try{

            $client = User::query()
                          ->with(['details', 'address', 'orders'])
                          ->find($id);
            
            if($client->details){
                $client->details->cellphone = $this->makeCellphone($client->details);
            }
            
            return view('clients.show', ['client' => $client]);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel buscar o cliente.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);

        }
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

    private function makeCellphone($details){
        return $details->phone_area_code . $details->phone_number;
    }
}
