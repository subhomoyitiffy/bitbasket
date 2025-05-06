<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Validator;

use App\Models\Lessonplan;
use App\Models\LessonplanStudent;
use App\Models\Institute;

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
    public function destroy($id)
    {
        try{
            $data = Lessonplan::findOrFail($id);
            $data->status = 3;
            $data->delete();

            return $this->sendResponse([], 'Lesson plan has successfully deleted.');
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

}
