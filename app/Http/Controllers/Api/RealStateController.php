<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\RealState;
use App\Http\Requests\RealStateRequest;
use App\Api\ApiMessages;

class RealStateController extends Controller
{

    private $realState;

    public function __construct(RealState $realState){
        $this->realState = $realState;
    }

   
    public function index()
    {
        $realState = $this->realState->paginate(10);

        return response()->json($realState, 200); // retorna JSON e codigo 200
    }

   
    public function store(RealStateRequest $request)
    {

        $data = $request->all();
        $images = ($request->file('images'));
        
        try{

            $realState = $this->realState->create($data);

            if(isset($data['categories']) && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            if($images){

                $imagesUploaded = [];

                foreach($images as $image){
                    $path = $image->store('images','public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }

                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imovel cadastrado com sucesso!'
                ]
            ],200);

        }catch(\Expection $e){
            
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }

        //return response()->json($request->all(), 200);
    }

    
    public function show($id)
    {
        try{

            $realState = $this->realState->findOrFail($id);

            return response()->json([
                'data' => $realState
                ],200);

        }catch(\Expection $e){
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }
    }

    
    public function update(RealStateRequest $request, $id)
    {

        $data = $request->all();
        $images = $request->file('images');

        try{

            $realState = $this->realState->findOrFail($id);
            $realState->update($data);

            if(isset($data['categories']) && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            if($images){

                $imagesUploaded = [];

                foreach($images as $image){
                    $path = $image->store('images','public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }

                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imovel atualizado com sucesso!'
                ]
            ],200);

        }catch(\Expection $e){
            
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
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

            $realState = $this->realState->findOrFail($id);
            $realState->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Imovel removido com sucesso!'
                ]
            ],200);

        }catch(\Expection $e){
            
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }
    }
}
