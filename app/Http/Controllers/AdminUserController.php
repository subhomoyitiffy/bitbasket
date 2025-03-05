<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Role;
use App\Models\Module;
use App\Models\User;
use App\Helpers\Helper;
use Auth;
use Session;
use Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Admin User',
            'controller'        => 'AdminUserController',
            'controller_route'  => 'admin-users',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'admin-users.list';
            $data['rows']                   = User::where('status', '!=', 3)->where('role_id', '=', 1)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'role_id'               => 'required',
                    'name'                  => 'required',
                    'email'                 => 'required',
                    'country_code'          => 'required',
                    'phone'                 => 'required',
                    'password'              => 'required',
                    'status'                => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* profile image */
                        $imageFile      = $request->file('profile_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('profile_image', $imageName, '', 'image');
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
                        'role_id'           => strip_tags($postData['role_id']),
                        'name'              => strip_tags($postData['name']),
                        'email'             => strip_tags($postData['email']),
                        'country_code'      => strip_tags($postData['country_code']),
                        'phone'             => strip_tags($postData['phone']),
                        'password'          => Hash::make(strip_tags($postData['password'])),
                        'profile_image'     => $profile_image,
                        'status'            => strip_tags($postData['status']),
                    ];
                    User::insert($fields);
                    return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'admin-users.add-edit';
            $data['row']                    = [];
            $data['roles']                  = Role::select('id', 'role_name')->where('status', '=', 1)->where('id', '=', 1)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'admin-users.add-edit';
            $data['row']                    = User::where($this->data['primary_key'], '=', $id)->first();
            $data['roles']                  = Role::select('id', 'role_name')->where('status', '=', 1)->where('id', '=', 1)->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'role_id'               => 'required',
                    'name'                  => 'required',
                    'email'                 => 'required',
                    'country_code'          => 'required',
                    'phone'                 => 'required',
                    'status'                => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* profile image */
                        $imageFile      = $request->file('profile_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('profile_image', $imageName, '', 'image');
                            if($uploadedFile['status']){
                                $profile_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $profile_image = $row->profile_image;
                        }
                    /* profile image */
                    if($postData['password'] != ''){
                        $fields = [
                            'role_id'           => strip_tags($postData['role_id']),
                            'name'              => strip_tags($postData['name']),
                            'email'             => strip_tags($postData['email']),
                            'country_code'      => strip_tags($postData['country_code']),
                            'phone'             => strip_tags($postData['phone']),
                            'password'          => Hash::make(strip_tags($postData['password'])),
                            'profile_image'     => $profile_image,
                            'status'            => strip_tags($postData['status']),
                        ];
                    } else {
                        $fields = [
                            'role_id'           => strip_tags($postData['role_id']),
                            'name'              => strip_tags($postData['name']),
                            'email'             => strip_tags($postData['email']),
                            'country_code'      => strip_tags($postData['country_code']),
                            'phone'             => strip_tags($postData['phone']),
                            'profile_image'     => $profile_image,
                            'status'            => strip_tags($postData['status']),
                        ];
                    }
                    User::where($this->data['primary_key'], '=', $id)->update($fields);
                    return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Updated Successfully !!!');
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
            $fields = [
                'status'             => 3,
                'deleted_at'         => date('Y-m-d H:i:s'),
            ];
            User::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = User::find($id);
            if ($model->status == 1)
            {
                $model->status  = 0;
                $msg            = 'Deactivated';
            } else {
                $model->status  = 1;
                $msg            = 'Activated';
            }            
            $model->save();
            return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
}
