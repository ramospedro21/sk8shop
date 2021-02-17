<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Helpers\UserAccessHelper;
use App\Helpers\CategoryHelper;

Use App\Models\Category;
Use App\Models\ErrorsLog;

class CategoriesController extends Controller
{

    public function view(){

        $access = UserAccessHelper::getAccess();

        if($access == true)
            return view('categories.index');
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

            $categories = CategoryHelper::getCategories(null);

            foreach($categories as $category){

                $category->children = CategoryHelper::getCategories($category->id);

                if($category->children->count() > 0){

                    foreach($category->children as $child){
                        $child->children = CategoryHelper::getCategories($child->id);
                    }

                }

            }
            return response()->json($categories, 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel listar as categorias',
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

            $slug = Str::slug($request->input('category.title'), '-');

            $hasSlug = Category::where('slug', $slug)->first();

            if($hasSlug) $slug = $slug . Str::random(3);


            // Se enviar categoria pai
            if($request->input('category.parent_id')){

                // valida se o id enviado pertence a empresa
                $parent = Category::where('id', $request->input('category.parent_id'))
                                    ->first();

                if($parent){
                    $parent = $parent->id;
                }
                else {
                    return response()->json([
                        'message' => "A categoria pai não existe."
                    ], 500);
                }

            } else {
                $parent = 0;
            }

            $category = Category::create([
                'title' => $request->input('category.title'),
                'description' => $request->input('category.description'),
                'parent_id' => $request->input('category.parent_id'),
                'slug' => $slug,
                'showing' => $request->input('category.showing'),
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
                'message' => 'Não foi possivel cadastrar a categoria.',
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

                $category = Category::where('id', $id)->update([
                    'title' => $request->input('category.title'),
                    'description' => $request->input('category.description'),
                    'parent_id' => $request->input('category.parent_id'),
                    'showing' => $request->input('category.showing'),
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
                'message' => 'Não foi possivel editar a categoria.',
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

                $category = Category::where('parent_id', $id)->orWhere('id', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true
            ], 200);

        }catch(\Exception $e){

            $errorLog = ErrorsLog::create([
                'description' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possivel apagar a categoria.',
                'errors' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]
            ], 500);

        }
    }
}
