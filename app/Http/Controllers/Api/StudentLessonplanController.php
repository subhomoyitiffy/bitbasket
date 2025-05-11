<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use App\Models\lessonplanStudentCodeExplanation as CodeExplanation;
use App\Models\Student;
use App\Models\Lessonplan;
use App\Models\lessonplanConversions;
use App\Models\LessonplanStudent;

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
                    ->where('students.token', $request->token)
                    ->orderBy('lessonplan_student_code_explanations.id', 'desc');
        if(!empty($request->limit)){
            $list = $sql->take($request->limit)->get();
        }else{
            $list = $sql->withTrashed()->get();
        }

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

    public function get_student_lessonplan(Request $request, $token){
        if(empty($token)){
            return $this->sendError('Authentication Error', 'Token is missing.', 201);
        }

        $student_with_lessonplan = Student::with('lessonplan')
                                        ->where('token', $token)->first();
        if(!$student_with_lessonplan){
            return $this->sendError('Authentication Error', 'Unable to identify student.', 201);
        }

        return $this->sendResponse([
            $student_with_lessonplan
        ], 'Student lessonplan list.');
    }

    public function post_sme_chat_conversion(Request $request){
        $validator = Validator::make($request->all(), [
            'lessonplan' => 'required|integer',
            'sme' => 'required|integer',
            'channel' => 'required|string|in:group,individual', //['group', 'individual']
            'student' => 'required_if:channel,individual',
            'message' => 'required|string'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

         $has_lessonplan = Lessonplan::where('id', $request->lessonplan)
                        ->where('user_id', $request->sme)
                        ->where('status', 1)
                        ->get();
        if($has_lessonplan->count() != 1){
            return $this->sendError('Unauthorize access error', 'Unauthorize access error', 201);
        }
        if($request->channel == 'individual'){
            $has_student_relation = LessonplanStudent::where('lessonplan_id', $request->lessonplan)
                                                    ->where('student_id', $request->student)
                                                    ->get();
            if($has_student_relation->count() != 1){
                return $this->sendError('Unauthorize access error', 'Student Lessonplan relation not found.', 201);
            }
        }
        try{
            $data_array = [
                'lessonplan_id'=> $request->lessonplan,
                'user_id'=> $request->sme
            ];
            if($request->channel == 'group'){
                $data_array['is_group'] = true;
                $receiver = 'group';
            }else{
                $data_array['student_id'] = $request->student;
                $data_array['is_group'] = false;
                $receiver = $request->student;
            }

            $conversion_node = [
                                'sme'=> true,
                                'student' => false,
                                'message'=> $request->message,
                                'sender'=> $request->sme,
                                'receiver'=> $receiver,
                                'timestamp'=> date('Y-m-d H:i:s')
                            ];

            $has_conversion = lessonplanConversions::where($data_array)->first();
            if($has_conversion){
                $conversion = json_decode($has_conversion->message, true);
                array_push($conversion, $conversion_node);
                $array_reverse = array_reverse($conversion);
                lessonplanConversions::where('id', $has_conversion->id)->update([
                    'message' => json_encode($array_reverse)
                ]);
            }else{
                $conversion[] = $conversion_node;
                $data_array['message'] = json_encode($conversion);
                lessonplanConversions::create($data_array);
            }
            return $this->sendResponse([], 'Conversion saved successfully.');
        }catch(\Exception $cus_ex){
            // Error through. Some error occurred
            return $this->sendError('Unable to save conversion.', $cus_ex->getMessage(), 500);
        }
    }

    public function post_student_chat_conversion(Request $request){
        $validator = Validator::make($request->all(), [
            'lessonplan' => 'required|integer',
            // 'user' => 'required|integer', //sme
            'channel' => 'required|string|in:group,individual', //['group', 'individual']
            'student' => 'required|integer',
            'message' => 'required|string'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

         $has_lessonplan = Lessonplan::where('id', $request->lessonplan)
                        ->where('status', 1)
                        ->get();
        if($has_lessonplan->count() != 1){
            return $this->sendError('Unauthorize access error', 'Unauthorize access error', 201);
        }
        if($request->channel == 'individual'){
            $has_student_relation = LessonplanStudent::where('lessonplan_id', $request->lessonplan)
                                                    ->where('student_id', $request->student)
                                                    ->get();
            if($has_student_relation->count() != 1){
                return $this->sendError('Unauthorize access error', 'Student Lessonplan relation not found.', 201);
            }
        }
        try{
            $sme_id = $has_lessonplan[0]->user_id;
            $data_array = [
                'lessonplan_id'=> $request->lessonplan,
                'user_id'=> $sme_id
            ];
            if($request->channel == 'group'){
                $data_array['is_group'] = true;
                $receiver = 'group';
            }else{
                $data_array['student_id'] = $request->student;
                $data_array['is_group'] = false;
                $receiver = $sme_id;
            }
            $conversion_node = [
                                'sme'=> false,
                                'student' => true,
                                'message'=> $request->message,
                                'sender'=> $request->student,
                                'receiver'=> $receiver,
                                'timestamp'=> now()
                            ];

            $has_conversion = lessonplanConversions::where($data_array)->first();
            $conversion = [];
            if($has_conversion){
                $conversion = json_decode($has_conversion->message, true);
                array_push($conversion, $conversion_node);
                $array_reverse = array_reverse($conversion);
                lessonplanConversions::where('id', $has_conversion->id)->update([
                    'message' => $array_reverse
                ]);
            }else{
                $conversion[] = $conversion_node;
                $data_array['message'] = $conversion_node;
                lessonplanConversions::create($data_array);
            }
            return $this->sendResponse([], 'Conversion saved successfully.');
        }catch(\Exception $cus_ex){
            // Error through. Some error occurred
            return $this->sendError('Unable to save conversion.', $cus_ex->getMessage(), 500);
        }
    }

    public function get_chat_conversion(Request $request, $lesson_id){
        $sql = lessonplanConversions::where('lessonplan_id', $lesson_id);
        if(!empty($request->student_id)){
            $sql->where('student_id', $request->student_id);
        }
        if(!empty($request->is_group)){
            $sql->where('is_group', ($request->is_group == 1 ? true : false));
        }
        if(!empty($request->sme_id)){
            $sql->where('user_id', $request->sme_id);
        }

        $data = $sql->get();

        return $this->sendResponse($data, 'Conversion list.');
    }

}
