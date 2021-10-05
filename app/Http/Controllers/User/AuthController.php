<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestRegister;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class AuthController extends Controller
{
    use GeneralTrait;


    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUser(RequestRegister $request)
    {

        try {
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;
             User::create([
                'name' => $name
                , 'email' => $email
                , 'password' => $password
            ]);
            $credentials = $request->only(['email', 'password']);
            $token = Auth::guard('user-api')->attempt($credentials);
            $user = Auth::guard('user-api')->user();
            $user ->token = $token;
            return $this->returnDatas('user',$user);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return $this->returnError('', 'some thing went wrongs');
        }
    }

    public function logout(Request $request)
    {
        $token = $request->header('auth-token');
        if ($token) {
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return $this->returnError($e->getCode(), $e->getMessage());
            }
            return $this->returnSuccessMessage('s000', 'Logged out successfully');
        } else {
            $this->returnError('E200', 'some thing went wrongs');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token =Auth::guard('user-api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'user' => Auth::guard('user-api')->user(),
            'token' => $token,

        ]);
    }

}
