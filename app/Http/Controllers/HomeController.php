<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Module;

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
        $modules = Module::with(['values'])->get();

        return view('home', ['modules' => $modules]);
    }
}
