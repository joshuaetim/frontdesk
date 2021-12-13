<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;

class UserController extends APIController
{
    public function getAllUsers()
    {
        return $this->sendResponse(User::all(), "Users retrieved successfully");
    }

    public function getUser($user)
    {
        return $this->sendResponse(User::find($user), "User retrieved");
    }

    public function updateUser(Request $request, $user)
    {
        $input = $request->only('first_name', 'last_name', 'email');

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            // 'password' => 'required|min:8',
            // 'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $user = User::find($user);

        if(!$user) {
            return $this->resourceNotFoundResponse('user');
        }

        $user->update($input);

        $user->refresh();

        return $this->sendResponse($user, "Update successful");
    }

    public function changeUserStatus(Request $request, int $user)
    {
        $user = User::find($user);

        if(!$user) {
            return $this->resourceNotFoundResponse('user');
        }

        $user->status = $request->status;

        $user->save();

        return $this->sendResponse($user, "Status update successful");
    }

    public function deleteUser($user)
    {
        $user = User::find($user);
        
        $user->delete();

        return $this->sendResponse([], "Delete successful");
    }
}
