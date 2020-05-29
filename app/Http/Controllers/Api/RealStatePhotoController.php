<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\RealStatePhoto;
use App\Api\ApiMessages;


class RealStatePhotoController extends Controller
{
    private $realStatePhoto;
    public function __construct(RealStatePhoto $realStatePhoto){
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId){

        try{
            $photo = $this->realStatePhoto
            ->where('real_state_id',$realStateId)
            ->where('is_thumb',true);

            if($photo->count()){
                $photo->first()->update(['is_thumb' => false]);
            }
            

            $photo = $this->realStatePhoto->find($photoId);
            $photo->update(['is_thumb' => true]);

            return response()->json([
                'data' => [
                    'msg' => 'Thumb atualizada com sucesso!'
                ]
            ],200);

        } catch(\Expection $e){
            
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(),401);
        }
    }

    public function remove($photoId){

        try{

            $photo = $this->realStatePhoto->find($photoId);

            if($photo->is_thumb){
                $message = new ApiMessages('Não é possível remover foto Thumb, selecione outra yhumb e remova a imagem');
                return response()->json([$message->getMessage()],401);
            }

            if($photo){
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imagem removida com sucesso!'
                ]
            ],200);

        } catch(\Expection $e){
            
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }

    }
}
