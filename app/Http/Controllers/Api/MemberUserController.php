<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use Validator;
use Hash;
use App\Models\User;
use App\Models\UserDetails;
use App\Mail\RegistrationSuccess;

class MemberUserController extends BaseApiController
{
    private $role_id;

    public function __construct()
    {
        $this->role_id = env('MEMBER_USER_ROLE_ID');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $sql = User::where('users.role_id', $this->role_id)
                    ->where('users.parent_id', auth()->user()->id);
        if(!empty($request->search)){
            $sql->where('first_name', 'like', '%'.$request->search.'%');
            $sql->orWhere('last_name', 'like', '%'.$request->search.'%');
            $sql->orWhere('email', 'like', '%'.$request->search.'%');
            $sql->orWhere('phone', 'like', '%'.$request->search.'%');
        }
        $list = $sql->latest()
                ->paginate(env('LIST_PAGINATION_COUNT'));

        return response()->json([
            'success' => true,
            'message' => 'Member Team list',
            'data' => $list
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'country_code' => 'required',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $image_path = "";
            if (request()->hasFile('image')) {
                $file = request()->file('image');
                $fileName = md5($file->getClientOriginalName() .'_'. time()) . "." . $file->getClientOriginalExtension();
                if ($file->move('public/uploads/user/', $fileName)) {
                    $image_path = 'public/uploads/user/'.$fileName;
                }
            }

            $user_id = User::insertGetId([
                'parent_id' => auth()->user()->id,
                'role_id'=> $this->role_id,
                'name'=> $request->first_name.' '.$request->last_name,
                'email'=> $request->email,
                'country_code' => $request->country_code,
                'phone'=> $request->phone,
                'password'=> Hash::make($request->password),
                'profile_image'     => $image_path,
                'status'=> 1
            ]);

            if($user_id){
                UserDetails::create([
                    'user_id'=> $user_id,
                    'country'=> NULL,
                    'first_name'=> $request->first_name,
                    'last_name'=> $request->last_name,
                    'email'=> $request->email,
                    'country_code' => $request->country_code,
                    'phone'=> $request->phone,
                    'city_id'=> NULL,
                    'emarati'=> NULL,
                    'business_license'=> NULL,
                    'tax_registration_number'=> NULL,
                    'company_type' => NULL,
                    'employer_identification_no' => NULL
                ]);
                $full_name = $request->first_name.' '.$request->last_name;
                $message = 'Your account registration has successfully completed. Now you can login using your registered email & password(phone).';
                Mail::to($request->email)->send(new RegistrationSuccess($request->email, $full_name, $message));

                return $this->sendResponse([], 'Your account registration has successfully completed.');
            }else{
                return $this->sendError('Error', 'Sorry!! Unable to register user.');
            }
        }catch(\Exception $cus_ex){
            // Error through. Some error occurred
            return $this->sendError('Registration Error', $cus_ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $list = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'User details',
            'data' => $list
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email'.$id,
            'country_code' => 'required',
            'phone' => 'required|unique:users,phone'.$id,
            'status' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $data = User::findOrFail($id);
            $data->name = $request->first_name.' '.$request->last_name;
            $data->country_code = $request->country_code;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->status = $request->status;
            if(!empty($request->password)){
                $data->password = Hash::make($request->password);
            }
            if (request()->hasFile('image')) {
                $file = request()->file('image');
                $fileName = md5($file->getClientOriginalName() .'_'. time()) . "." . $file->getClientOriginalExtension();
                if ($file->move('public/uploads/user/', $fileName)) {
                    $data->profile_image = 'public/uploads/user/'.$fileName;
                }
            }
            $data->save();

            $userDetails = UserDetails::where('user_id', $id)->first();
            $userDetails->first_name = $request->first_name;
            $userDetails->last_name = $request->last_name;
            $userDetails->email = $request->email;
            $userDetails->country_code = $request->country_code;
            $userDetails->phone = $request->phone;
            $userDetails->save();

            return $this->sendResponse([], 'Member data has successfully updated.');
        }catch(\Exception $cus_ex){
            // Error through. Some error occurred
            return $this->sendError('Error', $cus_ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $data = User::findOrFail($id);
            $data->status = 3;
            $data->delete();

            return $this->sendResponse([], 'Member data has successfully deleted.');
        }catch(\Exception $cus_ex){
            return $this->sendError('Error', $cus_ex->getMessage(), 500);
        }
    }

    /**
     * Change the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|int'
        ]);

        $data = User::findOrFail($id);
        $data->status = $request->status;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Member status has successfully changed.');
    }

}
