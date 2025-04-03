<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Package;
use App\Models\Faq;
use App\Models\FaqCategory;

class CommonController extends BaseApiController
{
    /**
     * Return all state list @JSON.
    */
    public function getStates()
    {
            $states = State::select('id', 'name')->get();
            return $this->sendResponse($states, 'UAE State list');
    }

    /**
     * Return all active packages list @JSON.
    */
    public function getPackages($status = 1)
    {
            $packages = Package::where('status', $status)->get();
            return $this->sendResponse($packages, 'All active packages/subscriptions list');
    }

    /**
     * Return all active FAQ & FAQ category list @JSON.
    */
    public function getFaqs()
    {
        // $faqs = Faq::where('status', 1)->get();
        $faqs = FaqCategory::where('status', 1)->with('faqs')->get();
        return $this->sendResponse([
            'faq_categories'=> $faqs,
            // 'faq_categorys'=> $faq_categorys,
        ], 'All active FAQ & FAQ category list');
    }

    /**
     * Return all active FAQ & FAQ category list @JSON.
    */
    public function getNotifications()
    {
        // $faqs = Faq::where('status', 1)->get();
        $faqs = FaqCategory::where('status', 1)->with('faqs')->get();
        return $this->sendResponse([
            'faq_categories'=> $faqs,
            // 'faq_categorys'=> $faq_categorys,
        ], 'All active FAQ & FAQ category list');
    }

}
