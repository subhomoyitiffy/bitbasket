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

class MemberTeacherController extends BaseApiController
{
    private $role_id;

    public function __construct()
    {
        $this->role_id = env('MEMBER_TEACHER_ROLE_ID');
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

            return $this->sendResponse([
                'list' => $list,
                'subscription_details'=> auth()->user()->user_subscriptions[0] ?? []
            ], 'Member Teacher list.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $number_of_team_members = auth()->user()->user_subscriptions ? auth()->user()->user_subscriptions[0]->no_of_teachers : 0 ;
        $total_enrolled_members = User::where('parent_id', auth()->user()->id)->where('role_id', $this->role_id)->get();
        if($total_enrolled_members->count() >= $number_of_team_members){
            return $this->sendError('Error', 'Sorry!! you have already enrolled available number of teachers.');
        }
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'country_code' => 'required',
            'phone' => 'required|unique:users',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $image_path = "";
            if (request()->hasFile('image')) {
                $file = request()->file('image');
                $fileName = md5($file->getClientOriginalName() .'_'. time()) . "." . $file->getClientOriginalExtension();
                if ($file->move('public/uploads/teacher/', $fileName)) {
                    $image_path = 'public/uploads/teacher/'.$fileName;
                }
            }
            $my_str = "543ZAbcdabXRLcd123PTas@t9876GTDX#EChFIHBnWqY";
            $my_str = str_shuffle($my_str);
            $pwd = substr($my_str, 0, 6);
            $user_id = User::insertGetId([
                'parent_id' => auth()->user()->id,
                'role_id'=> $this->role_id,
                'name'=> $request->first_name.' '.$request->last_name,
                'email'=> $request->email,
                'country_code' => $request->country_code,
                'phone'=> $request->phone,
                'password'=> Hash::make($pwd),
                'profile_image'     => $image_path,
                'status'=> 1
            ]);

            if($user_id){
                UserDetails::create([
                    'user_id'=> $user_id,
                    'country'=> "0",
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
                $message = 'Your account registration has successfully completed. Now you can login using your registered email & password('.$pwd.').';
                Mail::to($request->email)->send(new RegistrationSuccess($request->email, $full_name, $message));

                return $this->sendResponse([], 'Teacher account registration has successfully completed.');
            }else{
                return $this->sendError('Error', 'Sorry!! Unable to register Teacher.');
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

        return $this->sendResponse($list, 'User details.');
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
            'email' => 'required|email|unique:users,email,'.$id,
            'country_code' => 'required',
            'phone' => 'required|unique:users,phone,'.$id,
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
                if ($file->move('uploads/teacher/', $fileName)) {
                    $data->profile_image = 'public/uploads/teacher/'.$fileName;
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

            return $this->sendResponse([], 'Teacher data has successfully updated.');
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

            return $this->sendResponse([], 'Teacher data has successfully deleted.');
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

        return $this->sendResponse([], 'Teacher status has successfully changed.');
    }

}
