<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Validator;

use App\Models\Lessonplan;
use App\Models\User;
use App\Models\LessonplanStudent;
use App\Models\Institute;
use App\Models\Package;

class LessonplansController extends BaseApiController
{
    function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $sql = Lessonplan::where('user_id', auth()->user()->id)
                            ->where('status', '!=', 4) //[4-> Archived]
                            ->with('students')
                            ->with('subject');
        if(!empty($request->status)){
            $sql->where('status', $request->status);
        }
        $list = $sql->latest()
                ->paginate(env('LIST_PAGINATION_COUNT'))->toArray();
        $institute_id_array = [];
        if(!empty($list['data'])){
            foreach($list['data'] as $node){
                if(count($node['students']) > 0){
                    foreach($node['students'] as $student){
                        array_push($institute_id_array, $student['institute_id']);
                    }
                }
            }
        }

        return $this->sendResponse([
            'list' =>   $list,
            'institute'=>   count($institute_id_array) > 0 ?
                            Institute::select('id', 'name')
                                        ->where('status', 1)
                                        ->whereIn('id', $institute_id_array)
                                        ->orderBy('name', "ASC")->get() :
                            []
        ], 'List of Lesson plans.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Stop checking number of Member User/SME as per Feedback point
         * Membership Plan: Change No. Of Users to No. Of Lesson Plans.
        */
        /* $number_of_team_members = auth()->user()->user_subscriptions ? auth()->user()->user_subscriptions[0]->no_of_users : 0 ;
        $total_enrolled_members = User::where('parent_id', auth()->user()->id)->where('role_id', $this->role_id)->get();
        if($total_enrolled_members->count() >= $number_of_team_members){
            return $this->sendError('Error', 'Sorry!! you have already enrolled available number of SME.');
        } */
        $parent_subscriptions = Package::where('user_subscriptions.user_id', auth()->user()->parent_id)
                                        ->leftJoin('user_subscriptions', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                        ->where('user_subscriptions.is_active', '1')
                                        ->first();
        $number_of_lesson_plans = $parent_subscriptions ? $parent_subscriptions->no_of_lesson_plans : 0 ;
        $total_lessonplan = Lessonplan::where('user_id', auth()->user()->id)->get();
        if($total_lessonplan->count() >= $number_of_lesson_plans){
            return $this->sendError('Error', 'Sorry!! you have already completed your lesson plan quota. To add more upgrade your membership.');
        }

        $validator = Validator::make($request->all(), [
            'plan_name' => 'required|string|max:255',
            'description' => 'required',
            'subject' => 'required',
            'students' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            if(count($request->students) > 10){
                return $this->sendError('Error', 'Sorry!! You can add max 10 students.');
            }
            $Lesson_id = Lessonplan::insertGetId([
                'user_id' => auth()->user()->id,
                'subject_id' => $request->subject,
                'name'=> $request->plan_name,
                'description'=> $request->description,
                'status'=> 1
            ]);
            if($Lesson_id){
                foreach($request->students as $student){
                    LessonplanStudent::insertGetId([
                        'student_id' => $student,
                        'lessonplan_id' => $Lesson_id
                    ]);
                }
            }

            return $this->sendResponse([], 'Lesson plan added successfully done.');
        }catch(\Exception $cus_ex){
            // Error through. Some error occurred
            return $this->sendError('Error', $cus_ex->getMessage(), 500);
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
        $list = Lessonplan::where('id', $id)->with('students')->with('subject')->first();

        return $this->sendResponse($list, 'Lesson plan details.');
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
            'plan_name' => 'required|string|max:255',
            'description' => 'required',
            'subject' => 'required',
            'students' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            if(count($request->students) > 10){
                return $this->sendError('Error', 'Sorry!! You can add max 10 students.');
            }

            $data = Lessonplan::findOrFail($id);
            $data->subject_id = $request->subject;
            $data->name = $request->plan_name;
            $data->description = $request->description;
            $data->status = $request->status;
            $data->save();

            if(!empty($request->students)){
                LessonplanStudent::where([
                    'lessonplan_id' => $id
                ])->delete();
                foreach($request->students as $student){
                    LessonplanStudent::insertGetId([
                        'student_id' => $student,
                        'lessonplan_id' => $id
                    ]);
                }
            }

            return $this->sendResponse([], 'Lesson plan successfully updated.');
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
    public function archived($id)
    {
        try{
            $data = Lessonplan::findOrFail($id);
            $data->status = 4;
            $data->delete();

            return $this->sendResponse([], 'Lesson plan has successfully archived.');
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

        $data = Lessonplan::findOrFail($id);
        $data->status = $request->status;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Lesson plan status has successfully changed.');
    }

    public function lessonplan_edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'plan_name' => 'required|string|max:255',
            'description' => 'required',
            'explanation' => 'required|string'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $data = Lessonplan::findOrFail($id);
        $data->name = $request->plan_name;
        $data->description = $request->description;
        $data->explanation = $request->explanation;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Edit lessonplan saved successfully.');
    }

    /**
     * Change the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function code_published(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'explanation' => 'required|string'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $data = Lessonplan::findOrFail($id);
        $data->explanation = $request->explanation;
        $data->published = true;
        $data->status = 4; //4::Archived
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Code archived for student.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function get_archived(Request $request)
    {
        try{
            $sql = Lessonplan::where('user_id', auth()->user()->id)
                            ->where('status', 4); //[4-> Archived]
            if(empty($request->id)){
                $data = $sql->latest()
                        ->paginate(env('LIST_PAGINATION_COUNT'))->toArray();
            }else{
                $data = $sql->where('id', $request->id)->first();
            }

            return $this->sendResponse($data, 'Archived Lesson plan.');
        }catch(\Exception $cus_ex){
            return $this->sendError('Error', $cus_ex->getMessage(), 500);
        }
    }

    public function download_archived(Request $request, $id)
    {
        try{
            $sql = Lessonplan::where('id', $id)
                                ->where('user_id', auth()->user()->id)
                                ->where('status', 4); //[4-> Archived]
            $lessonplan = $sql->first();

            $content = $lessonplan->explanation;
            $fileName = auth()->user()->id.'_'.strtolower(str_replace(' ', '_', $lessonplan->name)).'.md';
            // Storage::disk('public')->put('uploads/user/'.$fileName, file_get_contents($file));
            Storage::disk('public')->put('uploads/user/lessonplan/'.$fileName, $content);
            $upload_path = 'storage/uploads/user/lessonplan/'.$fileName;

            // return Response::make($content, 200, [
            //     'Content-Type' => 'text/markdown',
            //     'Content-Disposition' => "attachment; filename={$fileName}",
            // ]);

            return $this->sendResponse(['file'=> $upload_path], 'Download file.');
        }catch(\Exception $cus_ex){
            return $this->sendError('Error', $cus_ex->getMessage(), 500);
        }
    }

}
