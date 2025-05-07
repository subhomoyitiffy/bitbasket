<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Validator;
use Hash;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserSubject;
use App\Mail\RegistrationSuccess;
/**-------------------------- SME -------------------------------- */
class MemberUserController extends BaseApiController
{
    private $role_id;

    public function __construct()
    {
        $this->role_id = env('SME_ROLE_ID');
        if(auth()->user()->role_id != $this->member_role_id){
            return $this->sendError('Error', 'Unauthorize request.');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $sql = User::where('users.role_id', $this->role_id)
                    ->where('users.parent_id', auth()->user()->id)
                    ->with('user_subjects');
        if(!empty($request->search)){
            $sql->where('first_name', 'like', '%'.$request->search.'%');
            $sql->orWhere('last_name', 'like', '%'.$request->search.'%');
            $sql->orWhere('email', 'like', '%'.$request->search.'%');
            $sql->orWhere('email', 'like', '%'.$request->search.'%');
        }
        $list = $sql->latest()->get();

        return $this->sendResponse([
            'list'=> $list,
            'subscription_details'=> auth()->user()->user_subscriptions[0] ?? []
        ], 'Member User list.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $number_of_team_members = auth()->user()->user_subscriptions ? auth()->user()->user_subscriptions[0]->no_of_users : 0 ;
        $total_enrolled_members = User::where('parent_id', auth()->user()->id)->where('role_id', $this->role_id)->get();
        if($total_enrolled_members->count() >= $number_of_team_members){
            return $this->sendError('Error', 'Sorry!! you have already enrolled available number of SME.');
        }
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $request->id,
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'integer|exists:subjects,id',
            // 'country_code' => 'required|string|max:5',
            // 'phone' => 'required|digits_between:10,15|unique:users,phone,' . $request->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            $my_str = "543ZAbcdabXRLcd123PTas@t9876GTDX#EChFIHBnWqY";
            $my_str = str_shuffle($my_str);
            $pwd = substr($my_str, 0, 6);
            $user_id = User::insertGetId([
                'parent_id' => auth()->user()->id,
                'role_id'=> $this->role_id,
                'name'=> $request->first_name.' '.$request->last_name,
                'email'=> $request->email,
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
                    'city_id'=> NULL,
                    'emarati'=> NULL,
                    'business_license'=> NULL,
                    'tax_registration_number'=> NULL,
                    'company_type' => NULL,
                    'employer_identification_no' => NULL
                ]);

                if(!empty($request->subjects)){
                    foreach($request->subjects as $subject){
                        if(!empty($subject)){
                            UserSubject::create([
                                'user_id'=> $user_id,
                                'subject_id'=> $subject
                            ]);
                        }
                    }
                }
                $full_name = $request->first_name.' '.$request->last_name;
                $message = 'Your account registration has successfully completed. Now you can login using your registered email & password';
                Mail::to($request->email)->send(new RegistrationSuccess($request->email, $full_name, $message, $pwd));

                return $this->sendResponse([], 'Member account registration has successfully completed.');
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
        $list = User::where('id', $id)->with('user_subjects')->first();

        return $this->sendResponse($list, 'SME details.');
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $id,
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'integer|exists:subjects,id',
            'status' => 'required|boolean',  // Assuming status is a boolean (1 or 0)
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $data = User::findOrFail($id);
            $data->name = $request->first_name.' '.$request->last_name;
            $data->email = $request->email;
            $data->status = $request->status;
            if(!empty($request->password)){
                $data->password = Hash::make($request->password);
            }
            if (request()->hasFile('image')) {
                $file = request()->file('image');
                $fileName = md5($file->getClientOriginalName() .'_'. time()) . "." . $file->getClientOriginalExtension();
                Storage::disk('public')->put('uploads/user/'.$fileName, file_get_contents($file));
                $data->profile_image = 'storage/uploads/user/'.$fileName;
            }
            $data->save();

            $userDetails = UserDetails::where('user_id', $id)->first();
            $userDetails->first_name = $request->first_name;
            $userDetails->last_name = $request->last_name;
            $userDetails->email = $request->email;
            $userDetails->country_code = $request->country_code;
            $userDetails->phone = $request->phone;
            $userDetails->save();
            if(!empty($request->subjects)){
                UserSubject::where([
                                    'user_id'=> $id
                                ])->delete();
                foreach($request->subjects as $subject){
                    if(!empty($subject)){
                        UserSubject::create([
                            'user_id'=> $id,
                            'subject_id'=> $subject
                        ]);
                    }
                }
            }

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
