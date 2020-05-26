<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Api\ApiMessages;
use App\User;

class UserController extends Controller
{
  
    protected $user;

    public function __construct(User $user){

        $this->user = $user;

    }

    public function index()
    {
        $users = $this->user->paginate(10);

        return response()->json($users, 200);
    }

  
    public function store(Request $request)
    {
        $data = $request->all();
        
        if(!$request->has('password') || !$request->get('password')){
            $message = new ApiMessage('É necessário informar uma senha pro Usuário');
            return response()->json([$message->getMessage()],401);
        }

        Validator::make($data,[
            'mobile_phone' => 'required',
            'phone' => 'required'
        ])->validate();

        try{

            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data);
            $user->profile()->create(
                [
                    'phone' => $data['phone'],
                    'mobile_phone' => $data['mobile_phone']
                ]
            );

            return response()->json([
                'data' => [
                    'msg' => 'Usuario cadastrado com sucesso!'
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


            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);

            return response()->json([
                'data' => $user
                ],200);

        }catch(\Expection $e){
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();


        if($request->has('password') && $request->get('passoword')){
            $data['password'] = bcrypt($data['passowrd']);
        }else {
            unset($data['password']);
        }
        
        Validator::make($data,[
            'profile.mobile_phone' => 'required',
            'profile.phone' => 'required'
        ])->validate();

        try{

            $profile = $data['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);

            $user = $this->user->findOrFail($id);
            $user->profile->update($profile);
            $user->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'Usuario atualizado com sucesso!'
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

            $user = $this->user->findOrFail($id);
            $user->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Usuario removido com sucesso!'
                ]
            ],200);

        }catch(\Expection $e){
            
            $message = new ApiMessage($e->getMessage());
            return response()->json([$message->getMessage()],401);
        }
    }
}
