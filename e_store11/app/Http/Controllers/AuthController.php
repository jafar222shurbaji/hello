<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\UserResource;
use App\Mail\VerificationCodeEmail;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


use function Symfony\Component\Clock\now;

class AuthController extends Controller
{


    public function register(RegisterUserRequest $request)
    {
        $ValidatedData = $request->validated();
        $ValidatedData['OTP'] = rand(100000, 999999);
        $user = User::create($ValidatedData);
        // return response()->json($user, 201);

        Mail::to($request->email)->send(new VerificationCodeEmail($ValidatedData['OTP']));
        return response()->json(["message" => "The verification code has sent to you, please check your email "], 201);
    }


    public function checkCode(Request $request)
    {
        // Getting the email and the code form the user and make validation on them
        $request->validate([
            "email" => "required|email",
            "code" => "required|string|size:6",
        ]);
        // Saving the code and the email in varibles
        $email = $request->email;
        $code = $request->code;
        // Searching for the user
        $user = User::whereEmail($email)->first();

        // Comparing the code in DB and the code given by the user
        if ($user->OTP == $code) {
            $user->update(["email_verified_at" => now()]);
            // Sending a welcome mail to the user
            Mail::to($email)->send(new WelcomeMail($user));

            return response()->json([
                "Message" => "Succesfully",
                "data" => new UserResource($user),
                "Token" => $this->createToken($user),
            ], 200);
        } else {
            return response()->json(["message" => "Invalid input"], 401);
        }
    }



    // public function login(Request $request)
    // {
    //     $request->validate([
    //         "email" => "required|string|email",
    //         "password" => "required|string",
    //     ]);
    //     if (!Auth::guard('api')->attempt($request->only("email", "password"))) {
    //         return response()->json([
    //             "message" => "invalid email or password"
    //         ], 401);
    //     }
    //     $user = User::where("email", $request->email)->firstOrFail();
    //     $token = $user->createToken("auth_Token")->plainTextToken;
    //     return ApiResponse::successWithData( new UserResource($user),"Loging seccesfully",200);
    // }


    public function login(Request $request)
    {
        // dd('test');
        $request->validate([
            "email" => "required|string|email",
            "password" => "required|string",
        ]);

        $user = User::where("email", $request->email)->first();
        if (!$user) {
            return response()->json(["message" => "Not foun in our database"], 401);
        }
        if (is_null($user->email_verified_at)) {
            return response()->json(["message" => "The email is not confirmation"], 200);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(["invalid input"], 401);
        }
        $data = [
            'user' => new UserResource($user),
            'token' => $this->createToken($user),
        ];
        return ApiResponse::successWithData($data, "Loging seccesfully", 200);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "logout succesfully"
        ]);
    }

    public function createToken($user)
    {
        $token = $user->createToken("auth_Token")->plainTextToken;
        return $token;
    }

    public function checkEmail($user)
    {
        $email = User::find($user);

        if (!$email) {
            return response()->json(["message" => "Email not found in database"], 404);
        }
        return response()->json(null, 204);
    }

    public function sendEmail($email)
    {
        $user = Auth::user();
        $user->OTP = rand(100000, 999999);
        Mail::to($email)->send(new WelcomeMail($user->OTP));
    }

    // public function resetcodecheck(Request $request)
    // {
    //     $code = $request->validate([
    //         "code" => "required|string|size:6",
    //     ]);
    //     $user = Auth::user();
    //     if ($user->OTP == $code) {
    //         $user->update(["email_verified_at" => now()]);
    //         // Sending a welcome mail to the user
    //         Mail::to($email)->send(new WelcomeMail($user));
    //     }
    // }
}
