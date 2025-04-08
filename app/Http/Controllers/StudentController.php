<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Institute;
use App\Models\Student;
use App\Helpers\Helper;
use Auth;
use Session;
use Hash;
use DB;

class StudentController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Student',
            'controller'        => 'StudentController',
            'controller_route'  => 'student',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'student.list';
            $data['rows']                   = DB::table('students')
                                                ->join('institutes', 'students.id', '=', 'institutes.id')
                                                ->select('students.*', 'institutes.name as inst_name')
                                                ->where('students.status', '!=', 3)
                                                ->orderBy('students.id', 'DESC')
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
                    'institute_id'      => 'required',
                    'first_name'        => 'required',
                    'last_name'         => 'required',
                    'work_email'        => 'required',
                    'phone'             => 'required',
                    'status'            => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'institute_id'              => strip_tags($postData['institute_id']),
                        'first_name'                => strip_tags($postData['first_name']),
                        'last_name'                 => strip_tags($postData['last_name']),
                        'work_email'                => strip_tags($postData['work_email']),
                        'phone'                     => strip_tags($postData['phone']),
                        'status'                    => strip_tags($postData['status']),
                    ];
                    Student::insert($fields);
                    return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'student.add-edit';
            $data['row']                    = [];
            $data['institutes']             = Institute::select('id', 'name')->where('status', '=', 1)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'student.add-edit';
            $data['row']                    = Student::where($this->data['primary_key'], '=', $id)->first();
            $data['institutes']             = Institute::select('id', 'name')->where('status', '=', 1)->get();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'institute_id'      => 'required',
                    'first_name'        => 'required',
                    'last_name'         => 'required',
                    'work_email'        => 'required',
                    'phone'             => 'required',
                    'status'            => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'institute_id'              => strip_tags($postData['institute_id']),
                        'first_name'                => strip_tags($postData['first_name']),
                        'last_name'                 => strip_tags($postData['last_name']),
                        'work_email'                => strip_tags($postData['work_email']),
                        'phone'                     => strip_tags($postData['phone']),
                        'status'                    => strip_tags($postData['status']),
                    ];
                    Student::where($this->data['primary_key'], '=', $id)->update($fields);
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
            Student::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Student::find($id);
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
