<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;
use JWTAuth;
use App\Models\User;
use App\Models\UserDetails;

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
        // $credentials['role_id'] = $this->member_role_id;
        try{
            // if (! $token = JWTAuth::attempt($credentials)) {
            if (! $token = auth('api')->attempt($credentials)) {
                return $this->sendError('Unauthorized', 'Email or Password not matched.', Response::HTTP_UNAUTHORIZED);
            }
            // Set guard to "api" for the current request
            auth()->shouldUse('api');
            if(auth()->user()->status == 0){
                return $this->sendError('Unauthorized', 'Your account is not verified yet. Please verify your account to login.', Response::HTTP_UNAUTHORIZED);
            }
            if(auth()->user()->status == 2){
                return $this->sendError('Unauthorized', 'Your account is declined by the admin. Please contact admin.', Response::HTTP_UNAUTHORIZED);
            }
            $user = new User();
            return $this->sendResponse([
                                        'token_type' => 'bearer',
                                        'token' => $token,
                                        'user' => User::where('id', auth()->user()->id)
                                                        ->with('user_details')
                                                        ->with('user_subscriptions')
                                                        ->first(),
                                        'parent_subscription'=> auth()->user()->role_id != $this->member_role_id ? $user->user_parent_subscriptions() : []
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
            $user = new User();
            return $this->sendResponse([
                'user' => User::where('id', auth()->user()->id)
                                ->with('user_details')
                                ->with('user_subscriptions')
                                ->first(),
                'parent_subscription'=> auth()->user()->role_id != $this->member_role_id ? $user->user_parent_subscriptions() : []
            ]);
        } catch (JWTException $exception) {
            return $this->sendError('Error', 'Sorry, the user cannot be logged out.',  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Change Password.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'c_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            User::where('id', auth()->user()->id)
                    ->update([
                        'password' => Hash::make($request->password),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            return $this->sendResponse([], 'Password update successfully done.');
        }catch (\Exception $exception) {
            return $this->sendError('Error', 'Sorry!! Something went wrong. Unable to update password.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|email|max:100|unique:users,email,'.auth()->user()->id,
            'country_code' => 'required|max:5',
            'phone' => 'required|max:15|unique:users,phone,'.auth()->user()->id,

            'city' => 'required_if:country,uae',
            'emarati' => 'required_if:country,uae',
            'business_license' => 'required_if:country,uae',
            'tax_registration_number' => 'required_if:country,uae',

            'company_type' => 'required_if:country,usa',
            'employer_identification_no' => 'required_if:country,usa'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $image_path = "";
            if (request()->hasFile('image')) {
                $file = request()->file('image');
                $fileName = md5($file->getClientOriginalName() .'_'. time()) . "." . $file->getClientOriginalExtension();
                Storage::disk('public')->put('uploads/user/'.$fileName, file_get_contents($file));
                $image_path = 'storage/uploads/user/'.$fileName;
            }

            User::where('id', auth()->user()->id)->update([
                'name'=> $request->first_name.' '.$request->last_name,
                'email'=> $request->email,
                'country_code' => $request->country_code,
                'phone'=> $request->phone,
                'profile_image' => $image_path
            ]);

            UserDetails::where('user_id', auth()->user()->id)->update([
                'country'=> $request->country,
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'email'=> $request->email,
                'country_code' => $request->country_code,
                'phone'=> $request->phone,
                'city_id'=> $request->city,
                'emarati'=> $request->emarati,
                'business_license'=> $request->business_license,
                'tax_registration_number'=> $request->tax_registration_number,
                'company_type' => $request->company_type,
                'employer_identification_no' => $request->employer_identification_no
            ]);

            return $this->sendResponse([], 'Profile updated successfully.');

        } catch (JWTException $e) {
            return $this->sendError('Error', 'Sorry!! Unable to update profile.');
        }
    }

}
