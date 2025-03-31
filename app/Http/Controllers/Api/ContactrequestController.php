<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use App\Models\Contactrequest;

class ContactrequestController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Contactrequest::latest()->paginate(env('LIST_PAGINATION_COUNT'));

        return $this->sendResponse(['list' => $list], 'Contact request list.');
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
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'required|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);
        try{
            if($validator->fails()){
                return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $data = new Contactrequest();
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->subject = $request->subject;
            $data->message = $request->message;
            $data->save();

            return $this->sendResponse([], 'Contact request added has successfully done.');
        }catch(\Exception $ex){
            return $this->sendError('Error', 'Sorry!! Unable to register Teacher.');
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
        $list = Contactrequest::findOrFail($id);

        return $this->sendResponse(['data' => $list], 'Contact request added has successfully done.');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Contactrequest::findOrFail($id);
        $data->delete();

        return $this->sendResponse([], 'Data deleted successfully done.');
    }

    /**
     * Change the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    /* public function change_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|int'
        ]);

        $data = Contactrequest::findOrFail($id);
        $data->is_active = $request->status;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        return $this->sendResponse([], 'Contactrequest '.($request->status == 0 ? 'Inactive' : 'Active').' successfully done.');
    } */

}
