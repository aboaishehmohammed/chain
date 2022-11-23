<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

class EmployeeController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'user_type' => "employee",
                'status' => 'active',
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Employee Created Successfully',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * list employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $employees = User::employee()->with('contactDetails')->get();
        if (!isset($employees)) {
            return response()->json([], 204);
        }

        return response()->json([$employees], 200);

    }

    /**
     * deactivate employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {

        try {
            $validateUser = Validator::make($request->all(),
                [
                    'user_id' => ['required', 'exists:users,id',
                        function ($attribute, $value, $fail) use ($request) {

                            if (auth('sanctum')->user()->id==$value) {
                                $fail("You can't update your status");
                            }
                        }

                    ],
                    'status' => 'required:in:inactive,active'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            $user = User::where('id', $request->user_id)->employee()->update([
                'status' => $request->status,
            ]);
            if (!$user) {
                return response()->json([], 404);
            }
            return response()->json([], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }


    }
}
