<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Package;
use App\Models\State;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserSubscription;
use App\Helpers\Helper;
use Auth;
use Session;
use Hash;
use DB;

class MemberUserController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Member User',
            'controller'        => 'MemberUserController',
            'controller_route'  => 'member-user',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list($member_id = ''){
            $data['module']                 = $this->data;
            $page_name                      = 'member-user.list';
            if($member_id == 'all'){
                $title                          = $this->data['title'].' List : All';
                $data['rows']                   = DB::table('users')
                                                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                                                ->leftjoin('states', 'user_details.city_id', '=', 'states.id')
                                                ->select('users.name', 'users.email as user_email', 'users.country_code as user_country_code', 'users.phone as user_phone', 'users.profile_image', 'users.status as user_status', 'user_details.*', 'states.name as state_name', 'users.parent_id')
                                                ->where('users.status', '!=', 3)
                                                ->where('users.role_id', '=', 3)
                                                ->orderBy('users.id', 'DESC')
                                                ->get();
            } else {
                $member_id                      = Helper::decoded($member_id);
                $getParentUser                  = User::select('name')->where('id', '=', $member_id)->first();
                $title                          = $this->data['title'].' List : ' . (($getParentUser)?$getParentUser->name:'');
                $data['rows']                   = DB::table('users')
                                                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                                                ->leftjoin('states', 'user_details.city_id', '=', 'states.id')
                                                ->select('users.name', 'users.email as user_email', 'users.country_code as user_country_code', 'users.phone as user_phone', 'users.profile_image', 'users.status as user_status', 'user_details.*', 'states.name as state_name', 'users.parent_id')
                                                ->where('users.status', '!=', 3)
                                                ->where('users.role_id', '=', 3)
                                                ->where('users.parent_id', '=', $member_id)
                                                ->orderBy('users.id', 'DESC')
                                                ->get();
            }
            
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'parent_id'                     => 'required',
                    'first_name'                    => 'required',
                    'last_name'                     => 'required',
                    'email'                         => 'required',
                    'phone'                         => 'required',
                    'status'                        => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fullname = $postData['first_name'].' '.$postData['last_name'];
                    /* profile image */
                        $imageFile      = $request->file('profile_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('profile_image', $imageName, 'user', 'image');
                            if($uploadedFile['status']){
                                $profile_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $profile_image = '';
                        }
                    /* profile image */
                    $fields = [
                        'parent_id'         => $postData['parent_id'],
                        'role_id'           => 3,
                        'name'              => strip_tags($fullname),
                        'email'             => strip_tags($postData['email']),
                        'country_code'      => strip_tags($postData['country_code']),
                        'phone'             => strip_tags($postData['phone']),
                        'password'          => Hash::make(strip_tags($postData['password'])),
                        'profile_image'     => $profile_image,
                        'status'            => strip_tags($postData['status']),
                    ];
                    // Helper::pr($fields);
                    $user_id = User::insertGetId($fields);

                    $fields2 = [
                        'user_id'                               => $user_id,
                        'country'                               => strip_tags($postData['country']),
                        'first_name'                            => strip_tags($postData['first_name']),
                        'last_name'                             => strip_tags($postData['last_name']),
                        'email'                                 => strip_tags($postData['email']),
                        'country_code'                          => strip_tags($postData['country_code']),
                        'phone'                                 => strip_tags($postData['phone']),
                        'city_id'                               => strip_tags($postData['city_id']),
                        'emarati'                               => strip_tags($postData['emarati']),
                        'business_license'                      => strip_tags($postData['business_license']),
                        'tax_registration_number'               => strip_tags($postData['tax_registration_number']),
                        'company_type'                          => strip_tags($postData['company_type']),
                        'employer_identification_no'            => strip_tags($postData['employer_identification_no']),
                        'created_at'                            => date('Y-m-d H:i:s'),
                        'updated_at'                            => date('Y-m-d H:i:s'),
                    ];
                    // Helper::pr($fields2);
                    UserDetails::insert($fields2);
                    return redirect($this->data['controller_route'] . "/list/" . Helper::encoded($postData['parent_id']))->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'member-user.add-edit';
            $data['row']                    = [];
            $data['row2']                   = [];
            $data['states']                 = State::select('id', 'name')->where('country_id', '=', 229)->orderBy('name', 'ASC')->get();
            $data['parentUsers']            = User::select('id', 'name')->where('status', '=', 1)->where('role_id', '=', 2)->orderBy('name', 'ASC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'member-user.add-edit';
            $data['row']                    = DB::table('users')->where($this->data['primary_key'], '=', $id)->first();
            $data['row2']                   = DB::table('user_details')->where('user_id', '=', $id)->first();
            $data['states']                 = State::select('id', 'name')->where('country_id', '=', 229)->orderBy('name', 'ASC')->get();
            $data['parentUsers']            = User::select('id', 'name')->where('status', '=', 1)->where('role_id', '=', 2)->orderBy('name', 'ASC')->get();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'parent_id'                     => 'required',
                    'first_name'                    => 'required',
                    'last_name'                     => 'required',
                    'email'                         => 'required',
                    'phone'                         => 'required',
                    'status'                        => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fullname = $postData['first_name'].' '.$postData['last_name'];
                    /* profile image */
                        $imageFile      = $request->file('profile_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('profile_image', $imageName, 'user', 'image');
                            if($uploadedFile['status']){
                                $profile_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $profile_image = $data['row']->profile_image;
                        }
                    /* profile image */
                    if($postData['password'] != ''){
                        $fields = [
                            'parent_id'         => $postData['parent_id'],
                            'name'              => strip_tags($fullname),
                            'email'             => strip_tags($postData['email']),
                            'country_code'      => strip_tags($postData['country_code']),
                            'phone'             => strip_tags($postData['phone']),
                            'password'          => Hash::make(strip_tags($postData['password'])),
                            'profile_image'     => $profile_image,
                            'status'            => strip_tags($postData['status']),
                        ];
                    } else {
                        $fields = [
                            'parent_id'         => $postData['parent_id'],
                            'name'              => strip_tags($fullname),
                            'email'             => strip_tags($postData['email']),
                            'country_code'      => strip_tags($postData['country_code']),
                            'phone'             => strip_tags($postData['phone']),
                            'profile_image'     => $profile_image,
                            'status'            => strip_tags($postData['status']),
                        ];
                    }
                    DB::table('users')->where('id', '=', $id)->update($fields);
                    $user_id = $id;

                    $fields2 = [
                        'user_id'                               => $user_id,
                        'country'                               => strip_tags($postData['country']),
                        'first_name'                            => strip_tags($postData['first_name']),
                        'last_name'                             => strip_tags($postData['last_name']),
                        'email'                                 => strip_tags($postData['email']),
                        'country_code'                          => strip_tags($postData['country_code']),
                        'phone'                                 => strip_tags($postData['phone']),
                        'city_id'                               => strip_tags($postData['city_id']),
                        'emarati'                               => strip_tags($postData['emarati']),
                        'business_license'                      => strip_tags($postData['business_license']),
                        'tax_registration_number'               => strip_tags($postData['tax_registration_number']),
                        'company_type'                          => strip_tags($postData['company_type']),
                        'employer_identification_no'            => strip_tags($postData['employer_identification_no']),
                        'updated_at'                            => date('Y-m-d H:i:s'),
                    ];
                    DB::table('user_details')->where('user_id', '=', $id)->update($fields2);
                    return redirect($this->data['controller_route'] . "/list/" . Helper::encoded($postData['parent_id']))->with('success_message', $this->data['title'].' Updated Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* edit */
    /* delete */
        public function delete(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = DB::table('users')->where('id', '=', $id)->first();
            $fields = [
                'status'             => 3,
                'deleted_at'         => date('Y-m-d H:i:s'),
            ];
            User::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect($this->data['controller_route'] . "/list/" . Helper::encoded($model->parent_id))->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = DB::table('users')->where('id', '=', $id)->first();
            if ($model->status)
            {
                $status  = 0;
                $msg     = 'Deactivated';
            } else {
                $status  = 1;
                $msg     = 'Activated';
            }
            $fields = [
                'status'             => $status
            ];
            DB::table('users')->where('id', '=', $id)->update($fields);
            return redirect($this->data['controller_route'] . "/list/" . Helper::encoded($model->parent_id))->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
}
