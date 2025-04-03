<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stripe;
use Validator;
use App\Helpers\Helper;
use App\Models\UserSubscription;
use App\Models\Package;
use App\Models\User;
use App\Models\UserDetails;

class UserSubscriptionController extends BaseApiController
{
    private $stripe_secret = '';
    public function __construct()
    {
        $stripe_payment_type = Helper::getSettingValue('stripe_payment_type');
        $stripe_sandbox_sk = Helper::getSettingValue('stripe_sandbox_sk');
        $stripe_live_sk = Helper::getSettingValue('stripe_live_sk');
        $this->stripe_secret   = ($stripe_payment_type) ? $stripe_sandbox_sk : $stripe_live_sk;

        // $this->stripe_secret = env('STRIPE_SECRET');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sql = UserSubscription::where('user_id', auth()->user()->id)
                                // ->where('is_active', 1)
                                ->orderBy('id', 'DESC');
        if(!empty($request->limit)){
            $sql->take($request->limit);
        }

        return $this->sendResponse($sql->get(), 'User subscription history.');
    }

    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required',
            'stripe_token' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $subscription = Package::findOrFail($request->subscription_id);
        if($subscription){
            try{
                Stripe\Stripe::setApiKey($this->stripe_secret);
                $user = UserDetails::where('user_id', auth()->user()->id)->first();
                $stripe_cust_id = $user->stripe_cust_id;
                try{
                    if(empty($stripe_cust_id)){
                        $customer = Stripe\Customer::create([
                            'name' => auth()->user()->name,
                            'email' => auth()->user()->email,
                            'source' => $request->stripe_token,
                            'description' => $subscription->name. ' Subscription purchase',
                            'address' => [
                                'line1' => '123 Main Street', // Customer's address line 1
                                'city' => 'Mumbai', // Customer's city
                                'state' => 'Maharashtra', // Customer's state
                                'postal_code' => '400001', // Customer's postal code
                                'country' => 'IN', // Country code for India
                            ],
                        ]);
                        $user->stripe_cust_id = $customer->id;
                        $user->save();
                        $stripe_cust_id = $customer->id;
                    }else{
                        try{
                            $cus_status = Stripe\Customer::retrieve($stripe_cust_id, []);
                            if(!$cus_status){
                                $customer = Stripe\Customer::create([
                                    'name' => auth()->user()->name,
                                    'email' => auth()->user()->email,
                                    'source' => $request->stripe_token,
                                    'description' => $subscription->name. ' Subscription purchase',
                                    'address' => [
                                        'line1' => '123 Main Street', // Customer's address line 1
                                        'city' => 'Mumbai', // Customer's city
                                        'state' => 'Maharashtra', // Customer's state
                                        'postal_code' => '400001', // Customer's postal code
                                        'country' => 'IN', // Country code for India
                                    ],
                                ]);
                                $user->stripe_cust_id = $customer->id;
                                $user->save();
                                $stripe_cust_id = $customer->id;
                            }
                        }catch(\Exception $ex){
                            $customer = Stripe\Customer::create([
                                'name' => auth()->user()->name,
                                'email' => auth()->user()->email,
                                'source' => $request->stripe_token,
                                'description' => $subscription->name. ' Subscription purchase',
                                'address' => [
                                    'line1' => '123 Main Street', // Customer's address line 1
                                    'city' => 'Mumbai', // Customer's city
                                    'state' => 'Maharashtra', // Customer's state
                                    'postal_code' => '400001', // Customer's postal code
                                    'country' => 'IN', // Country code for India
                                ],
                            ]);
                            $user->stripe_cust_id = $customer->id;
                            $user->save();
                            $stripe_cust_id = $customer->id;
                        }
                    }
                }catch(\Exception $ex){
                    // Error through. Some error occurred
                    return $this->sendError('Stripe Error', $ex->getMessage(), 500);
                }

                //Create proce object for a subscription package
                $stripe_price_id = $subscription->stripe_price_id;
                if(empty($stripe_price_id)){
                    $price = Stripe\price::create([
                        'unit_amount' => round($subscription->price, 2) * 100,
                        'currency' => 'USD',
                        'recurring' => ['interval' => 'month', 'interval_count'=> 1],
                        'product_data' => ['name' => $subscription->name],
                    ]);
                    $stripe_price_id = $price->id;
                    Package::find($subscription->id)->update(['stripe_price_id'=> $stripe_price_id]);
                }

                /*--------- Check user/member has already active subscription, if found cancel it*/
                $user_has_subscription = UserSubscription::where('user_id', auth()->user()->id)->where('is_active', 1)->first();
                if($user_has_subscription && !empty($user_has_subscription->stripe_subscription_id)){
                    try{
                        $sub = Stripe\Subscription::retrieve($user_has_subscription->stripe_subscription_id);
                        $sub->cancel();
                    }catch(\Exception $ex){
                        // Error through. Some error occurred
                        //return $this->sendError('Stripe Error', $ex->getMessage(), 500);
                    }
                }

                $has_subscription = Stripe\subscription::create([
                    'customer' => $stripe_cust_id,
                    'items' => [
                        ['price' => $stripe_price_id],
                    ],
                ]);
                if($has_subscription){
                    //Deactive all existing subscription if have
                    UserSubscription::where('user_id', auth()->user()->id)->update(['is_active'=> 0]);
                    //Active new subscription from now till cancelled
                    $subscription_id = UserSubscription::insertGetId([
                                        'subscription_id'=> $subscription->id,
                                        'user_id'=> auth()->user()->id,
                                        'payable_amount'=> $subscription->price,
                                        'stripe_subscription_id'=> $has_subscription->id,
                                        'subscription_start'=> date('Y-m-d H:i:s'),
                                        'subscription_end'=> date('Y-m-d H:i:s', strtotime('+'.$subscription->duration.'months')),
                                        'comment'=> 'User has taken '.$subscription->name,
                                        'is_active'=> 1
                                    ]);

                    return $this->sendResponse(['subscription_id'=> $subscription_id], 'User subscription created successfully.');
                }else{
                    return $this->sendError('Stripe Error', 'Due to some error, unable to create subscription.', 500);
                }
            }catch(\Exception $cus_ex){
                // Error through. Some error occurred
                return $this->sendError('Stripe Error', $cus_ex->getMessage(), 500);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
    */
    public function destroy(int $id)
    {
        Stripe\Stripe::setApiKey($this->stripe_secret);
        try{
            /*--------- Check user/member has already active subscription, if found cancel it*/
            $user_has_subscription = UserSubscription::find($id);
            if($user_has_subscription && !empty($user_has_subscription->stripe_subscription_id)){
                try{
                    $sub = Stripe\Subscription::retrieve($user_has_subscription->stripe_subscription_id);
                    $sub->cancel();

                    $user_has_subscription->is_active = 0;
                    $user_has_subscription->subscription_end = date('Y-m-d H:i:s');
                    $user_has_subscription->comment = 'Subscription has cancelled';
                    $user_has_subscription->save();

                    return $this->sendResponse([], 'User subscription cancelled successfully.');
                }catch(\Exception $ex){
                    // Error through. Some error occurred
                    return $this->sendError('Stripe Error', 'Sorry!! Unable to cancel subscription due to '.$ex->getMessage(), 500);
                }
            }
        }catch(\Exception $cus_ex){
            // Error through. Some error occurred
            return $this->sendError('Stripe Error', $cus_ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
    */
    public function invoice_list()
    {
        Stripe\Stripe::setApiKey($this->stripe_secret);
        try{
            /*--------- Check user/member has already active subscription, if found cancel it*/
            $user_has_subscription = UserSubscription::where('user_id', auth()->user()->id)->where('is_active', 1)->first();
            $user_details = UserDetails::where('user_id', auth()->user()->id)->first();
            if($user_has_subscription && !empty($user_has_subscription->stripe_subscription_id)){
                try{
                    $list = Stripe\Invoice::all([
                        'subscription' => $user_has_subscription->stripe_subscription_id
                        // 'customer' => $user_details->stripe_cust_id,
                    ]);

                    return $this->sendResponse(['list'=> $list], 'User subscription invoice list.');
                }catch(\Exception $ex){
                    // Error through. Some error occurred
                    return $this->sendError('Stripe Error', 'Sorry!! Unable to cancel subscription due to '.$ex->getMessage(), 500);
                }
            }
        }catch(\Exception $cus_ex){
            // Error through. Some error occurred
            return $this->sendError('Stripe Error', $cus_ex->getMessage(), 500);
        }
    }

}
