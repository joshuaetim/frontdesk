<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendPasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends APIController
{
    public function sendMail(Request $request)
    {
        $validator = Validator::make($request->only('email'), [
            'email' => 'required|email'
        ]);

        if($validator->fails()) {
            return $this->sendError("Validation error", $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return $this->sendError("Email not found in our database", [], 404);
        }

        // new code to authenticate
        $user->remember_token = Str::random(6);
        $user->save();
        // $user->refresh();

        // send email to user
        Mail::to($request->email)
        ->queue(new SendPasswordReset($user));

        return $this->sendResponse([
            'token' => $user->remember_token,
        ], "Link sent to your email");
    }

    public function verifyToken(Request $request)
    {
        $user = User::where([
            ['email', $request->email],
            ['remember_token', $request->token]
        ])->first();

        if(!$user) {
            return $this->sendError("Verification failed", [], 401);
        }

        return $this->sendResponse([
            'email' => $request->email,
            'token' => $request->token,
        ], "Proceed to change password");
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'c_password' => 'required|same:password'
        ]);

        if($validator->fails()) {
            return $this->sendError("Validation error", $validator->errors(), 422);
        }

        $user = User::where([
            ['email', $request->email],
            ['remember_token', $request->token]
        ])->first();

        if(!$user) {
            return $this->sendError("Verification failed", [], 401);
        }

        $user->password = bcrypt($request->password);
        // remove token
        $user->remember_token = "";
        $user->save();

        return $this->sendResponse([], "Password changed successfully");
    }
}
