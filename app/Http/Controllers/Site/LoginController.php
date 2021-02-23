<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;

class LoginController extends Controller
{
    public function login(Request $request){


        $user = User::whereEmail($request->email)->first();

        if (!empty($user)) {

            if (Auth::attempt(['id' => $user->id, 'password' => $request->input('password')])) {

                return redirect()->back();

            }

        }
    }
}
