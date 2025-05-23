<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Package;
use App\Models\State;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserSubscription;
use App\Helpers\Helper;
use Auth;
use Session;
use Hash;
use DB;
use stripe;

class MemberController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Member',
            'controller'        => 'MemberController',
            'controller_route'  => 'member',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'member.list';
            // $data['rows']                   = DB::table('users')
            //                                     ->leftjoin('user_details', 'users.id', '=', 'user_details.user_id')
            //                                     ->leftjoin('states', 'user_details.city_id', '=', 'states.id')
            //                                     ->leftjoin('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
            //                                     ->leftjoin('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
            //                                     ->select('users.name', 'users.email as user_email', 'users.country_code as user_country_code', 'users.phone as user_phone', 'users.profile_image', 'users.status as user_status', 'user_details.*', 'states.name as state_name', 'user_subscriptions.subscription_start', 'user_subscriptions.subscription_end', 'packages.name as package_name')
            //                                     ->where('users.status', '!=', 3)
            //                                     ->where('users.role_id', '=', 2)
            //                                     ->orderBy('users.id', 'DESC')
            //                                     // ->orderBy('user_subscriptions.id', 'DESC')
            //                                     ->get();
            $data['rows']                   = DB::table('users')
                                                ->leftjoin('user_details', 'users.id', '=', 'user_details.user_id')
                                                ->leftjoin('states', 'user_details.city_id', '=', 'states.id')
                                                ->select('users.name', 'users.email as user_email', 'users.country_code as user_country_code', 'users.phone as user_phone', 'users.profile_image', 'users.status as user_status', 'user_details.*', 'states.name as state_name')
                                                ->where('users.status', '!=', 3)
                                                ->where('users.role_id', '=', 2)
                                                ->orderBy('users.id', 'DESC')
                                                // ->orderBy('user_subscriptions.id', 'DESC')
                                                ->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'first_name'                    => 'required',
                    'last_name'                     => 'required',
                    'email'                         => 'required',
                    'phone'                         => 'required',
                    'status'                        => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fullname = $postData['first_name'].' '.$postData['last_name'];
                    /* profile image */
                        $imageFile      = $request->file('profile_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('profile_image', $imageName, 'user', 'image');
                            if($uploadedFile['status']){
                                $profile_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $profile_image = '';
                        }
                    /* profile image */
                    $fields = [
                        'parent_id'         => 0,
                        'role_id'           => 2,
                        'name'              => strip_tags($fullname),
                        'email'             => strip_tags($postData['email']),
                        'country_code'      => strip_tags($postData['country_code']),
                        'phone'             => strip_tags($postData['phone']),
                        'password'          => Hash::make(strip_tags($postData['password'])),
                        'profile_image'     => $profile_image,
                        'status'            => strip_tags($postData['status']),
                    ];
                    // Helper::pr($fields);
                    $user_id = User::insertGetId($fields);

                    $fields2 = [
                        'user_id'                               => $user_id,
                        'country'                               => strip_tags($postData['country']),
                        'first_name'                            => strip_tags($postData['first_name']),
                        'last_name'                             => strip_tags($postData['last_name']),
                        'email'                                 => strip_tags($postData['email']),
                        'country_code'                          => strip_tags($postData['country_code']),
                        'phone'                                 => strip_tags($postData['phone']),
                        'city_id'                               => strip_tags($postData['city_id']),
                        'emarati'                               => strip_tags($postData['emarati']),
                        'business_license'                      => strip_tags($postData['business_license']),
                        'tax_registration_number'               => strip_tags($postData['tax_registration_number']),
                        'company_type'                          => strip_tags($postData['company_type']),
                        'employer_identification_no'            => strip_tags($postData['employer_identification_no']),
                        'created_at'                            => date('Y-m-d H:i:s'),
                        'updated_at'                            => date('Y-m-d H:i:s'),
                    ];
                    // Helper::pr($fields2);
                    UserDetails::insert($fields2);
                    return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'member.add-edit';
            $data['row']                    = [];
            $data['row2']                   = [];
            $data['states']                 = State::select('id', 'name')->where('country_id', '=', 229)->orderBy('name', 'ASC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'member.add-edit';
            $data['row']                    = DB::table('users')->where($this->data['primary_key'], '=', $id)->first();
            $data['row2']                   = DB::table('user_details')->where('user_id', '=', $id)->first();
            $data['states']                 = State::select('id', 'name')->where('country_id', '=', 229)->orderBy('name', 'ASC')->get();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'first_name'                    => 'required',
                    'last_name'                     => 'required',
                    'email'                         => 'required',
                    'phone'                         => 'required',
                    'status'                        => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fullname = $postData['first_name'].' '.$postData['last_name'];
                    /* profile image */
                        $imageFile      = $request->file('profile_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('profile_image', $imageName, 'user', 'image');
                            if($uploadedFile['status']){
                                $profile_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $profile_image = $data['row']->profile_image;
                        }
                    /* profile image */
                    if($postData['password'] != ''){
                        $fields = [
                            'parent_id'         => 0,
                            'name'              => strip_tags($fullname),
                            'email'             => strip_tags($postData['email']),
                            'country_code'      => strip_tags($postData['country_code']),
                            'phone'             => strip_tags($postData['phone']),
                            'password'          => Hash::make(strip_tags($postData['password'])),
                            'profile_image'     => $profile_image,
                            'status'            => strip_tags($postData['status']),
                        ];
                    } else {
                        $fields = [
                            'parent_id'         => 0,
                            'name'              => strip_tags($fullname),
                            'email'             => strip_tags($postData['email']),
                            'country_code'      => strip_tags($postData['country_code']),
                            'phone'             => strip_tags($postData['phone']),
                            'profile_image'     => $profile_image,
                            'status'            => strip_tags($postData['status']),
                        ];
                    }
                    DB::table('users')->where('id', '=', $id)->update($fields);
                    $user_id = $id;

                    $fields2 = [
                        'user_id'                               => $user_id,
                        'country'                               => strip_tags($postData['country']),
                        'first_name'                            => strip_tags($postData['first_name']),
                        'last_name'                             => strip_tags($postData['last_name']),
                        'email'                                 => strip_tags($postData['email']),
                        'country_code'                          => strip_tags($postData['country_code']),
                        'phone'                                 => strip_tags($postData['phone']),
                        'city_id'                               => strip_tags($postData['city_id']),
                        'emarati'                               => strip_tags($postData['emarati']),
                        'business_license'                      => strip_tags($postData['business_license']),
                        'tax_registration_number'               => strip_tags($postData['tax_registration_number']),
                        'company_type'                          => strip_tags($postData['company_type']),
                        'employer_identification_no'            => strip_tags($postData['employer_identification_no']),
                        'updated_at'                            => date('Y-m-d H:i:s'),
                    ];
                    DB::table('user_details')->where('user_id', '=', $id)->update($fields2);
                    return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Updated Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* edit */
    /* delete */
        public function delete(Request $request, $id){
            $id                             = Helper::decoded($id);
            $fields = [
                'status'             => 3,
                'deleted_at'         => date('Y-m-d H:i:s'),
            ];
            User::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = DB::table('users')->where('id', '=', $id)->first();
            if ($model->status)
            {
                $status  = 0;
                $msg     = 'Deactivated';
            } else {
                $status  = 1;
                $msg     = 'Activated';
            }
            $fields = [
                'status'             => $status
            ];
            DB::table('users')->where('id', '=', $id)->update($fields);
            return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
    /* membership history */
        public function membershipHistory(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $data['member']                 = DB::table('users')->where('id', '=', $id)->first();
            $data['rows']                   = DB::table('user_subscriptions')
                                                ->join('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                ->select('user_subscriptions.*', 'packages.name as package_name')
                                                ->where('user_subscriptions.is_active', '=', 1)
                                                ->where('user_subscriptions.user_id', '=', $id)
                                                ->orderBy('user_subscriptions.id', 'DESC')
                                                ->get();
            $title                          = 'Membership History : ' . (($data['member'])?$data['member']->name:'');
            $page_name                      = 'member.membership-history';
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function allMembershipHistory(Request $request){
            $data['module']                 = $this->data;
            $data['rows']                   = DB::table('user_subscriptions')
                                                ->join('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                ->join('users', 'user_subscriptions.user_id', '=', 'users.id')
                                                ->select('user_subscriptions.*', 'packages.name as package_name', 'users.name as user_name', 'users.email as user_email')
                                                ->where('user_subscriptions.is_active', '=', 1)
                                                ->orderBy('user_subscriptions.id', 'DESC')
                                                ->get();
            $title                          = 'All Membership History';
            $page_name                      = 'member.all-membership-history';
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* membership history */
    /* membership plan */
        public function allMemberMembershipPlan(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Membership List';
            $page_name                      = 'member.all-member-membership-plan';
            $data['rows']                   = DB::table('users')
                                                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                                                ->leftjoin('states', 'user_details.city_id', '=', 'states.id')
                                                ->leftjoin('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
                                                ->leftjoin('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                ->select('users.name', 'users.email as user_email', 'users.country_code as user_country_code', 'users.phone as user_phone', 'users.profile_image', 'users.status as user_status', 'user_details.*', 'states.name as state_name', 'user_subscriptions.subscription_start', 'user_subscriptions.subscription_end', 'packages.name as package_name')
                                                ->where('users.status', '!=', 3)
                                                ->where('users.role_id', '=', 2)
                                                ->orderBy('users.id', 'DESC')
                                                ->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* membership plan */
    /* membership renew */
        public function membershipSelectPackage(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $data['member_id']              = $id;
            $data['member']                 = DB::table('users')->where('id', '=', $id)->first();
            $data['current_package']        = DB::table('user_subscriptions')
                                                ->join('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                ->select('user_subscriptions.*', 'packages.name as package_name')
                                                ->where('user_subscriptions.is_active', '=', 1)
                                                ->where('user_subscriptions.user_id', '=', $id)
                                                ->orderBy('user_subscriptions.id', 'DESC')
                                                ->first();
            $data['packages']               = Package::where('status', '=', 1)->orderBy('id', 'ASC')->get();
            $title                          = 'Membership Renew : ' . (($data['member'])?$data['member']->name:'');
            $page_name                      = 'member.membership-select-package';
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function subscriptionCheckout(Request $request, $package_id, $member_id){
            $data['module']                 = $this->data;
            $package_id                     = Helper::decoded($package_id);
            $member_id                      = Helper::decoded($member_id);
            $data['package_id']             = $package_id;
            $data['member_id']              = $package_id;
            $data['member']                 = DB::table('users')->where('id', '=', $member_id)->first();
            $data['package']                = Package::where('id', '=', $package_id)->first();
            $data['current_package']        = DB::table('user_subscriptions')
                                                ->join('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                ->select('user_subscriptions.*', 'packages.name as package_name')
                                                ->where('user_subscriptions.is_active', '=', 1)
                                                ->where('user_subscriptions.user_id', '=', $member_id)
                                                ->orderBy('user_subscriptions.id', 'DESC')
                                                ->first();
            $title                          = 'Membership Subscription : ' . (($data['member'])?$data['member']->name:'') . ' ' . (($data['package'])?$data['package']->name:'');
            $page_name                      = 'member.subscription-checkout';
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function subscriptionPayment(Request $request){
            $apiStatus                      = TRUE;
            $apiMessage                     = '';
            $apiResponse                    = [];
            $package_id                     = $request->package_id;
            $uId                            = $request->user_id;
            $user                           = User::where('id', '=', $uId)->first();
            if($request->package_id == ''){
                $apiStatus = FALSE;
                $apiMessage = 'Package Required !!!';
            } elseif($request->cardNo == ''){
                $apiStatus = FALSE;
                $apiMessage = 'Card No Required !!!';
            } elseif($request->cardHolderName == ''){
                $apiStatus = FALSE;
                $apiMessage = 'Card Holder Name Required !!!';
            } elseif($request->cardExpiryMM == ''){
                $apiStatus = FALSE;
                $apiMessage = 'Card Expiry Month Required !!!';
            } elseif($request->cardExpiryYY == ''){
                $apiStatus = FALSE;
                $apiMessage = 'Card Expiry Year Required !!!';
            } elseif($request->cardCvv == ''){
                $apiStatus = FALSE;
                $apiMessage = 'Card Cvv Required !!!';
            } else {
                $renewalPackage                 = Package::where('id', '=', $package_id)->first();
                $price                          = (($renewalPackage)?(int)$renewalPackage->price:0);
                $postData['cardNo']             = $request->cardNo;
                $postData['cardHolderName']     = $request->cardHolderName;
                $postData['cardExpiryMM']       = $request->cardExpiryMM;
                $postData['cardExpiryYY']       = $request->cardExpiryYY;
                $postData['cardCvv']            = $request->cardCvv;                
                // Helper::pr($postData);
                $stripeData         = $this->commonStripePayment($user, $postData, $price, 'Payment for '.$renewalPackage->name.' Subscription Package on ' . date('Y-m-d H:i:s'));
                // Helper::pr($stripeData);
                if($stripeData['status']){
                    UserSubscription::where('user_id','=',$user['id'])->update(['is_active' => 0]);
                    
                    $service_from   = date('Y-m-d');
                    $service_to     = date('Y-m-d', strtotime("+".$renewalPackage->duration." months"));
                    $userSubscriptionData = [
                        'subscription_id'               => $package_id,
                        'user_id'                       => $user['id'],
                        'coupon_id'                     => 0,
                        'coupon_discount'               => 0,
                        'coupon_code'                   => '',
                        'payable_amount'                => (($renewalPackage)?(int)$renewalPackage->price:0.00),
                        'stripe_subscription_id'        => $stripeData['transaction_id'],
                        'subscription_start'            => $service_from,
                        'subscription_end'              => $service_to,
                        'is_active'                     => 1,
                        'created_at'                    => date('Y-m-d H:i:s'),
                        'updated_at'                    => date('Y-m-d H:i:s'),
                    ];
                    // Helper::pr($userSubscriptionData);
                    $ref_id = UserSubscription::insertGetId($userSubscriptionData);
                    $apiStatus = TRUE;
                    $apiMessage = 'Subscription Payment Successfully Completed !!!';
                } else {
                    $apiStatus = FALSE;
                    $apiMessage = 'Payment Failed !!! Please Try Again Later !!!';
                }
            }
            
            $data                       = array("status" => $apiStatus, "message" => $apiMessage, "response" => $apiResponse);
            header("Content-Type: application/json");
            echo json_encode($data);
        }
        /* Common Stripe Payment */
            private function commonStripePayment($user, $postData, $price, $msg = ''){
                $stripe_payment_type = Helper::getSettingValue('stripe_payment_type');
                $stripe_sandbox_sk = Helper::getSettingValue('stripe_sandbox_sk');
                $stripe_sandbox_pk = Helper::getSettingValue('stripe_sandbox_pk');
                $stripe_live_sk = Helper::getSettingValue('stripe_live_sk');
                $stripe_live_pk = Helper::getSettingValue('stripe_live_pk');
                $stripeSecret   = ($stripe_payment_type) ? $stripe_sandbox_sk : $stripe_live_sk;
                // echo $stripeSecret;die;
                $stripe         = new \Stripe\StripeClient($stripeSecret);
                try {
                    $stripeToken    = $stripe->tokens->create([
                        'card' => [
                            'number'    => $postData['cardNo'],
                            'exp_month' => $postData['cardExpiryMM'],
                            'exp_year'  => $postData['cardExpiryYY'],
                            'cvc'       => $postData['cardCvv'],
                            'name'      => $postData['cardHolderName'],
                        ],
                    ]);
                } catch (\Stripe\Exception\OAuth\OAuthErrorException $e) {
                    //exit('Error: ' . $e->getMessage());
                    $return = array(
                        'status'    => FALSE,
                        'message'   => 'Something Went Wrong !!! Please Try Again !!!',
                    );
                }
                try {
                    $customer       = $stripe->customers->create([
                        'email'     => $user->email,
                        'name'      => (!empty($user->name)) ? $user->name : 'New User',
                        'address'   => [
                            'line1'         => 'Demo Address',
                            'postal_code'   => '2000',
                            'city'          => 'Sydney',
                            'state'         => 'NSW',
                            'country'       => 'AU',
                        ],
                        'source'    => $stripeToken
                    ]);
                } catch (\Stripe\Exception\OAuth\OAuthErrorException $e) {
                    $return = array(
                        'status'    => FALSE,
                        'message'   => 'Something Went Wrong !!! Please Try Again !!!',
                    );
                }
                try {
                    $charge = $stripe->charges->create([
                        'amount'        => $price,
                        'currency'      => 'usd',
                        'description'   => $msg,
                        'customer'      => $customer->id,
                        //'metadata'      => $msg
                    ]);
                } catch (\Stripe\Exception\OAuth\OAuthErrorException $e) {
                    $return = array(
                        'status'    => FALSE,
                        'message'   => 'Something Went Wrong !!! Please Try Again !!!',
                    );
                }
                if (isset($charge->status)) :                    
                    if ($charge->status == 'succeeded') :
                        $return = array(
                            'status'                => TRUE,
                            'payment_gateway_id'    => $charge->id,
                            'transaction_id'        => $charge->balance_transaction,
                            'customer_id'           => $charge->customer,
                            'customer_card_id'      => $charge->payment_method,
                            'currency'              => $charge->currency,
                            'particulars'           => $charge->description,
                            'card_last_4_digits'    => $charge->payment_method_details->card->last4,
                            'expiry_month'          => $charge->payment_method_details->card->exp_month,
                            'expiry_year'           => $charge->payment_method_details->card->exp_year,
                        );
                        // Helper::pr($return);
                    else :
                        $return = array(
                            'status'    => FALSE,
                            'message'   => 'Payment Failed !!! Please Try Again !!!',
                        );
                    endif;
                else :
                    $return = array(
                        'status'    => FALSE,
                        'message'   => 'Something Went Wrong !!! Please Try Again !!!',
                    );
                endif;
                return $return;
            }
        /* Common Stripe Payment */
    /* membership renew */
}
