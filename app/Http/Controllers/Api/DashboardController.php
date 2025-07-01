<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Models\User;
use App\Models\Lessonplan;
use App\Models\LessonplanStudent;
use App\Models\Subject;

class DashboardController extends BaseApiController
{
    private $sme_role_id;

    public function __construct()
    {
        $this->sme_role_id = env('SME_ROLE_ID');
    }
    /**
     * SME Dashboard: Remove Wallet Balance, and Orders This Month. Instead have Total Lesson Plans, Completed Lesson Plans, Student    Involved in All Lesson Plans and Student Involved in Completed Lesson Plans.
     * Member Dashboard: Total SMEs, Total Subjects, Total Lesson Plans, Completed Lesson Plans, Student Involved in All Lesson Plans and Student Involved in Completed Lesson Plans. SME wise Lesson Plan Count, Subject wise Lesson Plan Count, SME wise Completed Lesson Plan Count, Subject wise Completed Lesson Plan Count,
    */
    public function index(Request $request){
        $user_id_array = [auth()->user()->id];

        $sme_wise_lessonplan = $subject_wise_lessonplan = [];
        if(auth()->user()->role_id != $this->sme_role_id){
            //Subscriber member login
            $user_id_array = array();
            $sme_users = User::select('id', 'first_name', 'last_name', 'email')->where('parent_id', auth()->user()->id)->get(); //->pluck('id')->toArray();

            if($sme_users->count() > 0){
                foreach($sme_users as $sme){
                    array_push($user_id_array, $sme->id);
                    $sme_wise_lessonplan[] = [
                                            'sme'=> $sme,
                                            'total_lesson_plan' => Lessonplan::where('user_id', $sme->id)
                                                                                ->count(),
                                            'total_completed_lesson_plan' =>Lessonplan::where('user_id', $sme->id)
                                                                                ->where('status', 4)
                                                                                ->count()
                                            ];
                }
            }

            $subjects = Subject::select('id', 'name', 'status')
                                ->where('member_id', auth()->user()->id)
                                ->get();
            if($subjects->count() > 0){
                foreach($subjects as $subject){
                    $subject_wise_lessonplan[] = [
                                            'subject'=> $subject,
                                            'total_lesson_plan' => Lessonplan::whereIn('user_id', $user_id_array)
                                                                                ->where('subject_id', $subject->id)
                                                                                ->count(),
                                            'total_completed_lesson_plan' =>Lessonplan::whereIn('user_id', $user_id_array)
                                                                                ->where('subject_id', $subject->id)
                                                                                ->where('status', 4)
                                                                                ->count()
                                            ];
                }
            }
        }
        $total_lessonplan_array = Lessonplan::whereIn('user_id',$user_id_array)
                                                ->get()->pluck('id')->toArray();
        $completed_lessonplan_array = Lessonplan::whereIn('user_id', $user_id_array)
                                                ->where('status', 4)
                                                ->get()->pluck('id')->toArray();

        $lessonplan_students = LessonplanStudent::whereIn('lessonplan_id', $total_lessonplan_array)->count();
        $completed_lessonplan_students = LessonplanStudent::whereIn('lessonplan_id', $completed_lessonplan_array)->count();

        return $this->sendResponse([
            'total_lessonplan_array' => count($total_lessonplan_array),
            'completed_lessonplan_array' => count($completed_lessonplan_array),
            'lessonplan_students' => $lessonplan_students,
            'completed_lessonplan_students' => $completed_lessonplan_students,
            'sme_wise_lessonplan'=> $sme_wise_lessonplan,
            'subject_wise_lessonplan'=> $subject_wise_lessonplan
        ], 'Dashboard details.');
    }

}
