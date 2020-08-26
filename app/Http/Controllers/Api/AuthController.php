<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        $credentials=request(['email','password']);
        if(!Auth::attempt($credentials)){
            return response()->json([
                'message'=>'Invaled email or password'
            ],401);
        }
        $user=$request->user();
        $token=$user->createToken('Access token');
        $user->access_token=$token->accessToken;
        return response()->json([
            "user"=>$user
        ],200);
        
        
    }
    public function signup(Request $request){
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string|confirmed'
        ]);

            $user=new User([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password)
            ]);

            $user->save();
            return response()->json([
                "message"=>"User registered succesfull"
            ],201);

    }
    public function logout(Request $request){
        $request->user()->token()->revoke();

        return response()->json([
            "message"=>"User logged out succesfully"
        ],200);
         
    }
    public function index(){
        echo "hello world";
    }
}
