<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use App\Models\lessonplanStudentCodeExplanation as CodeExplanation;
use App\Models\Student;

class StudentLessonplanController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $has_active_student = Student::where('token', $request->token)->first();
        if(!$has_active_student){
            return $this->sendError('Authentication Error', 'Unable to identify student.', 201);
        }

        $sql = CodeExplanation::select('lessonplan_student_code_explanations.*', 'lessonplans.name AS lessonplan_name', 'lessonplans.description', 'students.id AS student_id', 'students.first_name', 'students.last_name', 'institutes.name AS institute_name')
                    ->join('lessonplans', 'lessonplans.id', '=', 'lessonplan_student_code_explanations.lessonplan_id')
                    ->join('students', 'students.id', '=', 'lessonplan_student_code_explanations.student_id')
                    ->join('institutes', 'institutes.id', '=', 'students.institute_id')
                    ->where('students.token', $request->token);
        if(!empty($request->limit)){
            $sql->take($request->limit);
        }
        $list = $sql->orderBy('lessonplan_student_code_explanations.id', 'desc')
                    ->get();
                    // ->with('user_subjects');

        return $this->sendResponse([
            $list
        ], 'Student submission list.');
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
            'token' => 'required|string',
            'lessonplan' => 'required|integer',
            'code' => 'required|string',
            'explanation' => 'required|string'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $has_active_student = Student::where('token', $request->token)->first();
            if(!$has_active_student){
                return $this->sendError('Authentication Error', 'Unable to identify student.', 201);
            }
            $has_data = CodeExplanation::where('student_id', $has_active_student->id)
                                        ->where('lessonplan_id', $request->lessonplan)
                                        ->where('status', 1)
                                        ->first();
            if($has_data){
                CodeExplanation::where('id', $has_data->id)->update([
                    'status'=> 4,
                    'deleted_at'=> date('Y-m-d H:i:s')
                ]);
            }
            $submission_id = CodeExplanation::insertGetId([
                'student_id' => $has_active_student->id,
                'lessonplan_id'=> $request->lessonplan,
                'code'=> $request->code,
                'explanation'=> $request->explanation,
                'status'=> 1
            ]);

            if($submission_id){
                return $this->sendResponse([], 'Code explanation submission successfully done.');
            }else{
                return $this->sendError('Error', 'Sorry!! Unable to submit explanation.');
            }
        }catch(\Exception $cus_ex){
            // Error through. Some error occurred
            return $this->sendError('Code explanation submission Error', $cus_ex->getMessage(), 500);
        }
    }

    /**
     * Change the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function enable_edit(Request $request, $id)
    {
        $data = CodeExplanation::findOrFail($id);
        $data->edit_plan = true;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Edit enable for student.');
    }

    /**
     * Change the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function code_published(Request $request, $id)
    {
        $data = CodeExplanation::findOrFail($id);
        $data->edit_plan = false;
        $data->published = true;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Code saved for student.');
    }


}
