<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Models\User;
use App\Models\Lessonplan;
use App\Models\LessonplanStudent;

class DashboardController extends BaseApiController
{
    private $sme_role_id;

    public function __construct()
    {
        $this->sme_role_id = env('SME_ROLE_ID');
    }
    public function index(Request $request){
        $user_id_array = [auth()->user()->id];
        if(auth()->user()->role_id != $this->sme_role_id){
            $user_id_array = array();
            $user_id_array = User::where('parent_id', auth()->user()->id)->get()->pluck('id')->toArray();
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
        ], 'Dashboard details.');
    }

}
