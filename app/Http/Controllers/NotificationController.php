<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Notification;
use App\Models\User;
use App\Helpers\Helper;
use Auth;
use Session;
use Hash;
use DB;
class NotificationController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Notification',
            'controller'        => 'NotificationController',
            'controller_route'  => 'notification',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'notification.list';
            $data['rows']                   = DB::table('notifications')
                                                ->join('users', 'notifications.to_user', '=', 'users.id')
                                                ->select('users.name as user_name', 'notifications.*')
                                                ->where('notifications.status', '!=', 3)
                                                ->orderBy('notifications.id', 'DESC')
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
                    'to_user'           => 'required',
                    'title'             => 'required',
                    'description'       => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'type'                  => 'announcement',
                        'to_user'               => strip_tags($postData['to_user']),
                        'title'                 => strip_tags($postData['title']),
                        'description'           => strip_tags($postData['description']),
                    ];
                    Notification::insert($fields);
                    return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'notification.add-edit';
            $data['row']                    = [];
            $data['users']                  = User::select('id', 'name')->where('status', '=', 1)->where('role_id', '!=', 0)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'notification.add-edit';
            $data['row']                    = Notification::where($this->data['primary_key'], '=', $id)->first();
            $data['users']                  = User::select('id', 'name')->where('status', '=', 1)->where('role_id', '!=', 0)->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'to_user'           => 'required',
                    'title'             => 'required',
                    'description'       => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'type'                  => 'announcement',
                        'to_user'               => strip_tags($postData['to_user']),
                        'title'                 => strip_tags($postData['title']),
                        'description'           => strip_tags($postData['description']),
                    ];
                    Notification::where($this->data['primary_key'], '=', $id)->update($fields);
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
            Notification::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Notification::find($id);
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
        public function change_status_send(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Notification::find($id);
            if ($model->is_send == 1)
            {
                $model->is_send  = 0;
                $msg            = 'Not Send Marked';
            } else {
                $model->is_send  = 1;
                $msg            = 'Send Marked';
            }            
            $model->save();
            return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
}
