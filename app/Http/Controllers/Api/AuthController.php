<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();

        $validate = Validator::make($registrationData,[
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
        ]);
        if($validate->fails())
            return response(['message'=> $validate->errors()], 400);
        
            $registrationData['password'] = bcrypt($request->password);

            $user = User::create($registrationData);

            return response([
                'message' => 'Registration Success',
                'user' => $user
            ], 200);
    }

    public function login(Request $request){
        $loginData = $request->all();

        $validate = Validator::make($loginData,[
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);
        if($validate->fails())
            return response(['message'=> $validate->errors()], 400);
        if(!Auth::attempt($loginData))
            return response(['message'=> 'Invalid Credential'], 401);

            $user = Auth::user();
            $token = $user->createToken('Authentication Token')->accessToken;

            return response([
                'message' => 'Authenticated',
                'user' => $user,
                'token_type' => 'Bearer',
                'access_token' => $token
            ]);
    }
    public function logout(Request $request){
        $user = Auth::user()->token();

        $dataUser = Auth::user();
        
        $user->revoke();
            return response([
                'message' => 'Logout Succes',
                'user' => $dataUser
            ]);
    }
}
