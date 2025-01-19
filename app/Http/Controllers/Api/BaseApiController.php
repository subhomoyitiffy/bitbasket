<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends Controller
{
    protected $member_role_id;
    function __construct() {
        $this->member_role_id = env('MEMBER_ROLE_ID');
    }
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
    */
    public function sendResponse($result = [], $message = 'Request response is here.')
    {
    	$response = [
            'success' => true,
            'message' => $message,
            'data'    => $result
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
    	$response = [
            'success' => false,
            'error' => $errorMessages,
        ];

        /* if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        } */

        return response()->json($response, $code);
    }

}
