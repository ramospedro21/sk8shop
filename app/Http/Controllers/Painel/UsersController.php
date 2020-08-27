<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\ErrorsLog;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserDetail;

class UsersController extends Controller
{   
    public function view(){
        return view('users.index');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $users = User::with(['user_type', 'address'])->paginate(User::PER_PAGE);

            return response()->json($users, 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Não foi possivel listar os usuários.',
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

            $user = User::create([
                'user_type_id' => $request->input('user.user_type_id'),
                'name' => $request->input('user.name'),
                'email' => $request->input('user.email'),
                'password' => Hash::make($request->input('user.password')),
            ]);

            if($user->save()){
                $userAddress = UserAddress::create([
                    'user_id' => $user->id,
                    'street' => $request->input('user.address.street'),
                    'district' => $request->input('user.address.district'),
                    'street_number' => $request->input('user.address.street_number'),
                    'complement' => $request->input('user.address.complement'),
                    'zipcode' => $request->input('user.address.zipcode'),
                    'city' => $request->input('user.address.city'),
                    'state' => $request->input('user.address.state'),
                    'country' => 'BRA',
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
                'message' => 'Não foi possivel cadastrar o usuário.',
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
        try{

            $user = User::with(['details', 'address', 'user_type'])->find($id);

            if($user->details) $user->details->phone = $user->details->phone_area_code . $user->details->phone_number;

            return view('users.show', ['user' => $user]);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Não foi possivel buscar o usuário.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine()
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
        try{

            DB::beginTransaction();

                $user = User::where('id', $id)->first()->update([
                    "user_type_id" => $request->input('user.user_type_id'),
                    "name" => $request->input('user.name'),
                    "email" => $request->input('user.email'),
                ]);

                UserAddress::where('user_id', $id)->delete();

                foreach($request->input('user.address') as $address){

                    $address = (object) $address;

                    $userAddress = UserAddress::create([
                        "user_id" => $id,
                        "street" => $address->street,
                        "district" => $address->district,
                        "street_number" => $address->street_number,
                        "complement" => $address->complement,
                        "zipcode" => $address->zipcode,
                        "city" => $address->city,
                        "state" => $address->state,
                        "country" => 'BRA',
                    ]);
                }
                
                $phone = str_replace('(', '', $request->input('user.details.phone'));
                $phone = str_replace(')', '', $phone);
                $phone = str_replace(' ', '', $phone);
                $phone = str_replace('-', '', $phone);
                $phone_area_code = substr($phone, 0, 2);
                $phone_number = substr($phone, 2);

                $userDetails = UserDetail::firstOrCreate(
                    [
                        'id' => $id,
                    ],
                    [
                        'user_id' => $id,
                        "birthdate" => date('Y-m-d', strtotime($request->input('user.details.birthdate'))),
                        "tax_document_type" => 'CPF',
                        "tax_document_number" => $request->input('user.details.tax_document_number'),
                        "identity_document_number" => $request->input('user.details.identity_document_number'),
                        "identity_document_type" => 'RG',
                        "phone_country_code" => '+55',
                        "phone_area_code" => $phone_area_code,
                        "phone_number" => $phone_number,
                        "gender" => $request->input('user.details.gender'),
                    ]
                );

                DB::commit();

            return response()->json([
                'success' => true
            ], 200);


        }catch(\Exception $e){
            
            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Não foi possivel editar o usuário.',
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
    public function destroy($id)
    {
        try{

            DB::beginTransaction();

                $user = User::where('id', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true
            ], 200);

        }catch(\Exception $e){

        }
    }
}
