<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use App\Models\Institute;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use App\Models\lessonplanStudentCodeExplanation as CodeExplanation;
use App\Models\Student;
use App\Models\Lessonplan;
use App\Models\lessonplanConversions;
use App\Models\LessonplanStudent;
use App\Models\Subject;

class StudentLessonplanController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $lessonplan_id)
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
                    ->where('lessonplan_student_code_explanations.lessonplan_id', $lessonplan_id)
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
    /* public function enable_edit(Request $request, $id)
    {
        $data = CodeExplanation::findOrFail($id);
        $data->edit_plan = true;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Edit enable for student.');
    } */

    /**
     * Change the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    /* public function code_published(Request $request, $id)
    {
        $data = CodeExplanation::findOrFail($id);
        $data->edit_plan = false;
        $data->published = true;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Code saved for student.');
    } */

    public function get_student_lessonplan(Request $request, $token){
        if(empty($token)){
            return $this->sendError('Authentication Error', 'Token is missing.', 201);
        }

        $student = Student::select('id', 'first_name', 'last_name', 'work_email', 'phone', 'token')
                            ->where('token', $token)->first();
        if(!$student){
            return $this->sendError('Authentication Error', 'Unable to identify student.', 201);
        }
        $mapped_lessonplan = LessonplanStudent::where('student_id', $student->id)->get()->pluck('lessonplan_id')->toArray();
        $lesson_plan = Lessonplan::select('id', 'name', 'description')
                                    ->whereIn('id', $mapped_lessonplan)
                                    ->where('status', 1)
                                    ->latest()->get();

        $student->lesson_plan = $lesson_plan;
        $student->institute = Institute::select('id', 'name')
                                        ->where('id', $student->institute_id)
                                        ->first();
        return $this->sendResponse([
            $student
        ], 'Student lessonplan list.');
    }

    /**
     * {
        "lessonplan_id": 3,
        "channel": "group",
        "messages": [
            {
            "teacher": "Hello, class.\n\nToday we will learn to write basic Rust."
            },
            {
            "student_lara": "hi",
            },
            {
            "student_lara2: "Hello"
            }
        ]
    */

    public function post_sme_chat_conversion_old(Request $request){
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
                                'student' => [],
                                'message'=> $request->message,
                                'sender'=> $request->sme,
                                'receiver'=> $receiver,
                                'timestamp'=> date('Y-m-d H:i:s')
                            ];

            $has_conversion = lessonplanConversions::where($data_array)->first();
            if($has_conversion){
                $conversion = json_decode($has_conversion->message, true);
                array_push($conversion, $conversion_node);
                // $array_reverse = array_reverse($conversion);
                lessonplanConversions::where('id', $has_conversion->id)->update([
                    'message' => json_encode($conversion)
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

    public function post_student_chat_conversion_old(Request $request){
        $validator = Validator::make($request->all(), [
            'lessonplan' => 'required|integer',
            'channel' => 'required|string|in:group,individual', //['group', 'individual']
            'student' => 'required|string', // student token registered by institute
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
            $student_details =Student::find($request->student);
            $conversion_node = [
                                'sme'=> false,
                                'student' => ['id'=> $request->student, 'name'=> $student_details->first_name.' '.$student_details->last_name],
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
                // $array_reverse = array_reverse($conversion);
                lessonplanConversions::where('id', $has_conversion->id)->update([
                    'message' => json_encode($conversion)
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

    public function get_chat_conversion(Request $request, $lesson_id){
        if($request->is_group == 0 && empty($request->student_id)){
            return $this->sendError('Payload error', 'Student ID is required for individual chat request', 201);
        }
        $sql = lessonplanConversions::select('message')->where('lessonplan_id', $lesson_id);
        if(!empty($request->student_id)){
            $sql->where('student_id', $request->student_id);
        }
        if(!empty($request->is_group)){
            $sql->where('is_group', ($request->is_group == 1 ? true : false));
        }
        if(!empty($request->sme_id)){
            $sql->where('user_id', $request->sme_id);
        }

        $data = $sql->first();

        return $this->sendResponse(
            $data ? json_decode($data->message, true) : [],
            'Conversion list.');
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
                $channel = 'group';
            }else{
                $data_array['student_id'] = $request->student;
                $data_array['is_group'] = false;
                $channel = 'individual';
            }
            $lessonplan = Lessonplan::select('id', 'name', 'description', 'subject_id')->where('id', $request->lessonplan)->first();
            $sme_name = strtolower(str_replace(" ", "_", auth()->user()->name)).'_'.auth()->user()->id;

            $has_conversion = lessonplanConversions::where($data_array)->first();
            if($has_conversion){
                $message = [$sme_name => $request->message];
                $conversion = json_decode($has_conversion->message, true);
                array_push($conversion['message'], $message);
                lessonplanConversions::where('id', $has_conversion->id)->update([
                    'message' => json_encode($conversion)
                ]);
            }else{
                $conversion_node = [
                                'lessonplan_id'=> $request->lessonplan,
                                'lessonplan' => $lessonplan,
                                'subject'=> Subject::select('name')->where('id', $lessonplan->subject_id)->first(),
                                'channel'=> $channel,
                                'message'=> [
                                    [$sme_name => $request->message]
                                ]
                            ];
                $data_array['message'] = json_encode($conversion_node);
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
            'channel' => 'required|string|in:group,individual', //['group', 'individual']
            'student' => 'required|string', // student token registered by institute
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
        $student_details = Student::where('token', $request->student)->first();
        if($request->channel == 'individual'){
            $has_student_relation = LessonplanStudent::where('lessonplan_id', $request->lessonplan)
                                                    ->where('student_id', $student_details->id)
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
                $channel = 'group';
            }else{
                $data_array['student_id'] = $student_details->id;
                $data_array['is_group'] = false;
                $channel = 'individual';
            }
            $name = $student_details->first_name.'_'.$student_details->last_name;
            $student_name = strtolower(str_replace(" ", "_", $name)).'_'.$student_details->id;

            $has_conversion = lessonplanConversions::where($data_array)->first();
            $conversion = [];
            if($has_conversion){
                $message = [$student_name => $request->message];
                $conversion = json_decode($has_conversion->message, true);
                array_push($conversion['message'], $message);
                lessonplanConversions::where('id', $has_conversion->id)->update([
                    'message' => json_encode($conversion)
                ]);
            }else{
                $conversion_node = [
                                'lessonplan_id'=> $request->lessonplan,
                                'lessonplan' => $has_lessonplan[0],
                                'subject'=> Subject::select('name')->where('id', $has_lessonplan[0]->subject_id)->first(),
                                'channel'=> $channel,
                                'message'=> [
                                    [$student_name => $request->message]
                                ]
                            ];
                $data_array['message'] = json_encode($conversion_node);
                lessonplanConversions::create($data_array);
            }
            return $this->sendResponse([], 'Student conversion saved successfully.');
        }catch(\Exception $cus_ex){
            // Error through. Some error occurred
            return $this->sendError('Unable to save conversion.', $cus_ex->getMessage(), 500);
        }
    }

}
