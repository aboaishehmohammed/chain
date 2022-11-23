<?php

namespace App\Http\Controllers;

use App\Models\ContactDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactDetailController extends Controller
{
    /**
     * Create Contact Details
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validateContact = Validator::make($request->all(),
                [
                    'full_name' => 'required',
                    'phone' => 'required|unique:contact_details,phone',
                    'birthdate' => 'date',
                    'address' => 'required',
                    'job_title' => 'required',
                    'user_id' => ['required', 'unique:contact_details,user_id', 'exists:users,id',
                        function ($attribute, $value, $fail) use ($request) {
                            $valid = User::where(['id' => $value, 'status' => 'active', 'user_type' => 'employee'])->exists();
                            if (!$valid) {
                                $fail('wrong user id');
                            }
                        }
                    ],
                ]);

            if ($validateContact->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateContact->errors()
                ], 400);
            }

            ContactDetail::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'contact Created Successfully',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * update Contact Details
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $validateContact = Validator::make($request->all(),
                [
                    'phone' => 'required|unique:contact_details,phone',
                    'birthdate' => 'date',
                    'address' => 'required',
                    'full_name' => 'required'
                ]);

            if ($validateContact->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateContact->errors()
                ], 400);
            }
            if (!auth('sanctum')->user()?->contactDetails()->exists())
                return response()->json([
                    'status' => false,
                    'message' => 'You dont have contact details',
                ], 400);

            auth('sanctum')->user()?->contactDetails()->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'contact update Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
