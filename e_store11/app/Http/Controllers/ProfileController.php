<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Mail\UpdateProfileMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
    //

    public function show()
    {
        $user = Auth::user();
        // change the date format with the user resource
        $data = [
            "date" => new UserResource($user),
        ];
        return response()->json($data, 200);
    }

    public function Update(UpdateUserRequest $request)
    {
        $validatedData = $request->validated();
        // git the id from the authenticated user
        $user_id = Auth::user()->id;
        // save the user in the user variable
        $user = User::findOrFail($user_id);
        // update the data with the new data
        $user->update($validatedData);

        Mail::to($user->email)->send(new UpdateProfileMail());
        return response()->json(["message" => "Updated"], 200);

    }

    public function changePass(Request $request)
    {
        // validate data
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);

        // check that the current password is the same as the password in the database
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json(["message" => "your current password does not match"], 401);
        }

        // update the password
        $user->update([
            'password' => $request->new_password
        ]);
        return response()->json([
            'message' => " The password has been changed"
        ]);
    }

    public function destroy()
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $user->delete();
        return response()->json(null, 204);
    }
}
