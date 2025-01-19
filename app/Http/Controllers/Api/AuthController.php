<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use JWTAuth;
use App\Models\User;

class AuthController extends BaseApiController
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     * @resuest string $email, string $password
    */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credentials = request(['email', 'password']);
        $credentials['role_id'] = $this->member_role_id;
        try{
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->sendError('Unauthorized', 'Email or Password not matched.', Response::HTTP_UNAUTHORIZED);
            }
            if(auth()->user()->status == 0){
                return $this->sendError('Unauthorized', 'Your account is not verified yet. Please verify your account to login.', Response::HTTP_UNAUTHORIZED);
            }
            if(auth()->user()->status == 2){
                return $this->sendError('Unauthorized', 'Your account is declined by the admin. Please contact admin.', Response::HTTP_UNAUTHORIZED);
            }

            return $this->sendResponse([
                                        'token_type' => 'bearer',
                                        'token' => $token,
                                        'user' => User::where('id', auth()->user()->id)->with('user_details')->get(),
                                        'expires_in' => 60 * 60,
                                    ], 'Login has successfully done.');
        } catch (JWTException $e) {
            return $this->sendError('Error', 'Login has failed.',  Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            return $this->sendResponse('', 'Logged out successfully.');
        } catch (JWTException $exception) {
            return $this->sendError('Error', 'Sorry, the user cannot be logged out.',  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display auth details.
     */
    public function getUser()
    {
        try {
            return $this->sendResponse(User::where('id', auth()->user()->id)->with('user_details')->get());
        } catch (JWTException $exception) {
            return $this->sendError('Error', 'Sorry, the user cannot be logged out.',  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
