<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;

class AuthController extends APIController
{
    // register
    public function register(Request $request)
    {
        $input = $request->only('first_name', 'last_name', 'email', 'password', 'c_password');

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $input['password'] = bcrypt($input['password']);
        Auth::login($user = User::create($input));

        $token = $user->createToken('user_token_'.$user->id)->plainTextToken;

        $success = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->sendResponse($success, 'user registered successfully', 201);
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');

        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $remember = $request->remember;

        if(Auth::attempt($input, $remember)){
            $user = Auth::user();
            $token = $user->createToken('user_token_'.$user->id)->plainTextToken;

            $success = ['user' => $user, 'token' => $token];

            return $this->sendResponse($success, 'Logged In');
        }
        else{
            return $this->sendError('Unauthorized', ['error' => "Invalid Login credentials"], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'Logged Out');
    }
}
