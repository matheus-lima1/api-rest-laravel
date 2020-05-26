<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Category;
use App\Api\ApiMessages;

class CategoryController extends Controller
{
    
    protected $category;

    public function __construct(Category $category){
        $this->category = $category;
    }

    public function index()
    {
        $category = $this->category->paginate(10);

        return response()->json($category, 200);
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->all();
        
        try{

            $category = $this->category->create($data);

            return response()->json([
                'data' => [
                    'msg' => 'Categoria cadastrada com sucesso!'
                ]
            ],200);

        }catch(\Expection $e){
            
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }
    }

    public function show($id)
    {
        try{

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data' => $category
                ],200);

        }catch(\Expection $e){
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }
    }


    public function update(CategoryRequest $request, $id)
    {
        $data = $request->all();

        try{

            $category = $this->category->findOrFail($id);
            $category->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'Categoria atualizada com sucesso!'
                ]
            ],200);

        }catch(\Expection $e){
            
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }
    }

    public function destroy($id)
    {
        try{

            $category = $this->category->findOrFail($id);
            $category->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Categoria removida com sucesso!'
                ]
            ],200);

        }catch(\Expection $e){
            
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }
    }

    public function realState($id){

        try{

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data' => $category->realStates
            ],200);

        }catch(\Expection $e){
            
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }

    }
}
