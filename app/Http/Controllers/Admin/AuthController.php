<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GeneralTrait;


    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerAdmin(Request $request)
    {


        $rules = [
            'name' => 'unique:admins|required|max:100',
            'email' => 'unique:admins|email|required|max:100',
            'password' => 'required|string|min:6|max:100',
        ];

        $input = $request->only('name', 'email', 'password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        try {
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;
            $user = Admin::create([
                'name' => $name
                , 'email' => $email
                , 'password' => $password
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return $this->returnError('', 'some thing went wrongs');
        }
        $token = auth()->login($user);
        return response()->json(compact('user', 'token'), 201);
    }

    public function logout(Request $request)
    {
        $token = $request->header('auth-token');
        if ($token) {
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return $this->returnError('', 'some thing went wrongs');
            }
            return $this->returnSuccessMessage('', 'Logged out successfully');
        } else {
            $this->returnError('', 'some thing went wrongs');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email ',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = Auth::guard('admin-api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'user' => Auth::guard('admin-api')->user(),
            'token' => $token,

        ]);
    }

}
