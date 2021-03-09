<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Variant;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{

            $products = Variant::with(['images',
                                       'stocks' => function($query){
                                            $query->where(function($query){
                                                $query->where('price', '>', 0);
                                                $query->orWhere('promote_price', '>', 0);
                                            });
                                       },
                                       'product' => function($query) use ($request){
                                           $query->where('title', 'like', '%' . $request->search . '%');
                                           $query->where('enabled', 1);
                                       }
                                     ])
                               ->whereHas('product', function($query) use ($request){
                                   $query->where('title', 'like', '%' . $request->search . '%');
                                   $query->where('enabled', 1);
                               })
                               ->get();

            if($products->count() == 0) {

                return view('components.404');

            } else {

                return view('search', ['products' => $products, 'search' => $request->search]);

            }


        }catch(\Exception $e){
            return view('components.404');
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
}
