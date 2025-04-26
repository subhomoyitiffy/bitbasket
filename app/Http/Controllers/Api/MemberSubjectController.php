<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Validator;

use App\Models\User;
use App\Models\Subject;

class MemberSubjectController extends BaseApiController
{
    private $role_id;
    public function __construct()
    {
        $this->role_id = env('SME_ROLE_ID');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        echo 'SME role_id:'.$this->role_id.' | Member role_id: '.$this->member_role_id.' | login:'.auth()->user()->role_id;
        $member_id = "";
        if(auth()->user()->role_id == $this->role_id){
            $member_id = auth()->user()->parent_id;
        }else if(auth()->user()->role_id == $this->member_role_id){
            $member_id = auth()->user()->id;
        }
        echo ' | member_id:'.$member_id;
        $sql = Subject::select('id', 'name', 'status')
                        ->where('member_id', $member_id);
        if(!empty($request->status)){
            $sql->where('status', $request->status);
        }
        $list = $sql->latest()->get();
                //->paginate(env('LIST_PAGINATION_COUNT'));

            return $this->sendResponse([
                'list' => $list,
            ], 'Subject List.');
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
            'subject_name' => 'required|string|max:20'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $has_subject_count = Subject::where('member_id', auth()->user()->id)
                                        ->where('name', ucfirst($request->subject_name))
                                        ->where('status', '!=', 3)
                                        ->count();
            if($has_subject_count > 0){
                return $this->sendError('Error', 'Same subject is already in your list.', 201);
            }
            Subject::insertGetId([
                'member_id' => auth()->user()->id,
                'name'=> ucfirst($request->subject_name),
                'status'=> 1
            ]);
            return $this->sendResponse([], 'Subject added successfully done.');
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
        $list = Subject::findOrFail($id);

        return $this->sendResponse($list, 'Subject details.');
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
            'subject_name' => 'required|string|max:20',
            'status' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $has_subject_count = Subject::where('member_id', auth()->user()->id)
                                        ->where('id', '!=', $id)
                                        ->where('name', ucfirst($request->subject_name))
                                        ->where('status', '!=', 3)
                                        ->count();
            if($has_subject_count > 0){
                return $this->sendError('Error', 'Same subject is already in your list.', 201);
            }
            $data = Subject::findOrFail($id);
            $data->name = ucfirst($request->subject_name);
            $data->status = $request->status;
            $data->save();

            return $this->sendResponse([], 'Subject successfully updated.');
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
            $data = Subject::findOrFail($id);
            $data->status = 3;
            $data->delete();

            return $this->sendResponse([], 'Subject has successfully deleted.');
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

        $data = User::findOrFail($id);
        $data->status = $request->status;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Subject status has successfully changed.');
    }

}
