<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use App\Models\State;

class CommonController extends BaseApiController
{
    /**
     * Return all UAT state list @JSON.
    */
    public function getStates()
    {
            $status = State::select('id', 'name')->get();
            return $this->sendResponse($status, 'UAE State list');
    }
}
