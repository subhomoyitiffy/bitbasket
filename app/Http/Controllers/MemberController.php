<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Package;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserSubscription;
use App\Helpers\Helper;
use Auth;
use Session;
use Hash;
use DB;

class MemberController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Member',
            'controller'        => 'MemberController',
            'controller_route'  => 'member',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'member.list';
            $data['rows']                   = DB::table('users')
                                                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                                                ->leftjoin('states', 'user_details.city_id', '=', 'states.id')
                                                ->leftjoin('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
                                                ->leftjoin('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                ->select('users.name', 'users.email as user_email', 'users.country_code as user_country_code', 'users.phone as user_phone', 'users.profile_image', 'users.status as user_status', 'user_details.*', 'states.name as state_name', 'user_subscriptions.subscription_start', 'user_subscriptions.subscription_end', 'packages.name as package_name')
                                                ->where('users.status', '!=', 3)
                                                ->where('users.role_id', '=', 2)
                                                ->orderBy('users.id', 'DESC')
                                                ->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'description'           => 'required',
                    'duration'              => 'required',
                    'price'                 => 'required',
                    'no_of_users'           => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'name'                  => strip_tags($postData['name']),
                        'description'           => strip_tags($postData['description']),
                        'duration'              => strip_tags($postData['duration']),
                        'price'                 => strip_tags($postData['price']),
                        'no_of_users'           => strip_tags($postData['no_of_users']),
                    ];
                    User::insert($fields);
                    return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'member.add-edit';
            $data['row']                    = [];
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'member.add-edit';
            $data['row']                    = User::where($this->data['primary_key'], '=', $id)->first();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'description'           => 'required',
                    'duration'              => 'required',
                    'price'                 => 'required',
                    'no_of_users'           => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'name'                  => strip_tags($postData['name']),
                        'description'           => strip_tags($postData['description']),
                        'duration'              => strip_tags($postData['duration']),
                        'price'                 => strip_tags($postData['price']),
                        'no_of_users'           => strip_tags($postData['no_of_users']),
                    ];
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
    /* membership history */
        public function membershipHistory(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $data['member']                 = DB::table('users')->where('id', '=', $id)->first();
            $data['rows']                   = DB::table('user_subscriptions')
                                                ->join('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                ->select('user_subscriptions.*', 'packages.name as package_name')
                                                ->where('user_subscriptions.is_active', '=', 1)
                                                ->where('user_subscriptions.user_id', '=', $id)
                                                ->orderBy('user_subscriptions.id', 'DESC')
                                                ->get();
            $title                          = 'Membership History : ' . (($data['member'])?$data['member']->name:'');
            $page_name                      = 'member.membership-history';
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function allMembershipHistory(Request $request){
            $data['module']                 = $this->data;
            $data['rows']                   = DB::table('user_subscriptions')
                                                ->join('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                ->join('users', 'user_subscriptions.user_id', '=', 'users.id')
                                                ->select('user_subscriptions.*', 'packages.name as package_name', 'users.name as user_name', 'users.email as user_email')
                                                ->where('user_subscriptions.is_active', '=', 1)
                                                ->orderBy('user_subscriptions.id', 'DESC')
                                                ->get();
            $title                          = 'All Membership History';
            $page_name                      = 'member.all-membership-history';
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* membership history */
    /* membership plan */
        public function allMemberMembershipPlan(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Membership List';
            $page_name                      = 'member.all-member-membership-plan';
            $data['rows']                   = DB::table('users')
                                                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                                                ->leftjoin('states', 'user_details.city_id', '=', 'states.id')
                                                ->leftjoin('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
                                                ->leftjoin('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                ->select('users.name', 'users.email as user_email', 'users.country_code as user_country_code', 'users.phone as user_phone', 'users.profile_image', 'users.status as user_status', 'user_details.*', 'states.name as state_name', 'user_subscriptions.subscription_start', 'user_subscriptions.subscription_end', 'packages.name as package_name')
                                                ->where('users.status', '!=', 3)
                                                ->where('users.role_id', '=', 2)
                                                ->orderBy('users.id', 'DESC')
                                                ->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* membership plan */
}
