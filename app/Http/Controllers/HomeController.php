<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Module;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->user_type_id == 2){

            return redirect('/');

        } else {

            return view('home');

        }
    }
}
