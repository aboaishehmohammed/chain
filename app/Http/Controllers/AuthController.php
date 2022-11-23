<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Login The User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'password' => 'required',
                    'email' => ['required', 'email', 'exists:users,email',
                        function ($attribute, $value, $fail) use ($request) {
                            $valid = User::where(['email' => $value, 'status' => 'active'])->exists();
                            if (!$valid) {
                                $fail('Your Account is deactivate');
                            }
                        },],
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 400);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Logout
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('sanctum')->user()?->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => 'you are logout',
        ], 200);
    }
}

