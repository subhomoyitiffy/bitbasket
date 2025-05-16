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
use App\Models\Subject;
use App\Helpers\Helper;
use Auth;
use Session;
use Hash;
use DB;

class MemberSubjectController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Member Subjects',
            'controller'        => 'MemberSubjectController',
            'controller_route'  => 'member-subject',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list($member_id = ''){
            $data['module']                 = $this->data;
            $page_name                      = 'member-subject.list';
            if($member_id == 'all'){
                $title                          = $this->data['title'].' List : All';
                $data['rows']                   = DB::table('subjects')
                                                ->join('users', 'subjects.member_id', '=', 'users.id')
                                                ->select('users.name as user_name', 'subjects.*')
                                                ->where('subjects.status', '!=', 3)
                                                ->orderBy('subjects.id', 'DESC')
                                                ->get();
            } else {
                $member_id                      = Helper::decoded($member_id);
                $getParentUser                  = User::select('name')->where('id', '=', $member_id)->first();
                $title                          = $this->data['title'].' List : ' . (($getParentUser)?$getParentUser->name:'');
                $data['rows']                   = DB::table('subjects')
                                                ->join('users', 'subjects.member_id', '=', 'users.id')
                                                ->select('users.name as user_name', 'subjects.*')
                                                ->where('subjects.status', '!=', 3)
                                                ->where('subjects.member_id', '=', $member_id)
                                                ->orderBy('subjects.id', 'DESC')
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
                    'member_id'                     => 'required',
                    'name'                          => 'required',
                    'status'                        => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'member_id'         => $postData['member_id'],
                        'name'              => strip_tags($postData['name']),
                        'status'            => strip_tags($postData['status']),
                    ];
                    Subject::insert($fields);
                    return redirect($this->data['controller_route'] . "/list/" . Helper::encoded($postData['member_id']))->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'member-subject.add-edit';
            $data['row']                    = [];
            $data['parentUsers']            = User::select('id', 'name')->where('status', '=', 1)->where('role_id', '=', 2)->where('parent_id', '=', 0)->orderBy('name', 'ASC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'member-subject.add-edit';
            $data['row']                    = DB::table('subjects')->where($this->data['primary_key'], '=', $id)->first();
            $data['parentUsers']            = User::select('id', 'name')->where('status', '=', 1)->where('role_id', '=', 2)->where('parent_id', '=', 0)->orderBy('name', 'ASC')->get();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'member_id'                     => 'required',
                    'name'                          => 'required',
                    'status'                        => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'member_id'         => $postData['member_id'],
                        'name'              => strip_tags($postData['name']),
                        'status'            => strip_tags($postData['status']),
                    ];
                    DB::table('subjects')->where('id', '=', $id)->update($fields);
                    return redirect($this->data['controller_route'] . "/list/" . Helper::encoded($postData['member_id']))->with('success_message', $this->data['title'].' Updated Successfully !!!');
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
            $model                          = DB::table('subjects')->where('id', '=', $id)->first();
            $fields = [
                'status'             => 3,
                'deleted_at'         => date('Y-m-d H:i:s'),
            ];
            Subject::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect($this->data['controller_route'] . "/list/" . Helper::encoded($model->member_id))->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = DB::table('subjects')->where('id', '=', $id)->first();
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
            DB::table('subjects')->where('id', '=', $id)->update($fields);
            return redirect($this->data['controller_route'] . "/list/" . Helper::encoded($model->member_id))->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
}
