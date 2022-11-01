<?php

namespace App\Http\Controllers\API;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Invite;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;

use App\Models\Driver;
use App\Models\Page;
use App\Models\OtpVerification;
use App\Models\ContactUs;
use App\Http\Resources\DriverShift as DriverShiftResource;
use App\Http\Resources\Driver as DriverResource;
use App\Http\Resources\Page as PageResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Validator;

class AuthController extends BaseController
{
    /**
     * Register Customer api
     *
     * @return \Illuminate\Http\Response
     */
    public function registerCustomer(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'language' => 'required|string|in:en,ar',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'phone' => 'required|string|min:7|max:15|unique:users',
            'password' => 'nullable|confirmed|min:6',
            'city_id' => 'nullable|integer|exists:cities,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }

        $data = $req->all();
        $userObj = new User;
        if (!isset($req->password)){
            $data['password']='123456';
        }
        $user = $userObj->createUser($data);
        $res = $this->authUserArray($req,$user,true);
        return $this->sendResponse($res, 'Successfully register.');
    }


    /**
     * OTP Verification api
     *
     * @return \Illuminate\Http\Response
     */
    public function otpVerification(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'phone'   => 'required|string|exists:users,phone',
            //'phone_code' => 'required|string',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        try {
            $userObj = New User;
            $verifyObj = new OtpVerification;
            $data = $req->all();
            // login with phone, code
            if (isset($req->verify_code)) {
                $msg = __('api.phone_not_registered')."!";
                $matchCode = $verifyObj->matchOtp($req->phone,$req->verify_code);
                if($matchCode){
                    $user=User::where('phone',$req->phone)->first();
                    if (is_null($user->verified_at) || $user->is_verified == 0) {
                        $user->verified_at = time();
                        $user->is_verified = 1;
                        $user->save();
                    }
                    Auth::login($user, true);
                    if(Auth::check()){
                        $res = $this->authUserArray($req);
                        return $this->sendResponse($res,__('api.login_success')."!");
                    }
                }
                if (!$matchCode) {
                    $msg = __('api.invalid_verification_code')."!";
                }
                return $this->sendError($msg, ['error' => $msg]);
            }
            // send verification code
            $sendCode = $userObj->sendSmsVerificationCode($data);
            if ($sendCode['status']) {
                return $this->sendResponse(['otp'=>$sendCode['otp']], $sendCode['message']);
            }
            return $this->sendError($sendCode['message'], ['error' => $sendCode['message']]);
        } catch (Exception $e) {
            return $this->sendError(trans('api.something_went_wrong'), ['error' => trans('api.something_went_wrong')."!"], 500);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function loginCustomer(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'phone'   => 'required|string',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        try {
            $userObj = New User;
            $verifyObj = new OtpVerification;
            $data = $req->all();
            // login with phone, code
            if (isset($req->verify_code)) {
                $msg = __('api.phone_not_registered')."!";
                $matchCode = $verifyObj->matchOtp($req->phone,$req->verify_code);
                if($matchCode){
                    $user = User::where('phone',$req->phone)->first();
                    if ($user) {
                        if ($user->status == 0) {
                            $msg = __('api.deactivated_account')."!";
                        }else{
                            Auth::login($user, true);
                            if(Auth::check()){
                                $res = $this->authUserArray($req);
                                return $this->sendResponse($res,__('api.login_success')."!");
                            }
                        }
                    }
                }
                if (!$matchCode) {
                    $msg = __('api.invalid_verification_code')."!";
                }
                return $this->sendError($msg, ['error' => $msg]);
            }
            // send verification code
            $user=User::where('phone',$req->phone)->first();
            if (empty($user)){
                $userObj = new User;
                if (!isset($req->password)){
                    $data['password']='123456';
                }
                $data['status']=true;
                $user = $userObj->createUser($data);
                $notiTitle = __('api.successfully_register');
                $notiMsg = __('api.successfully_register');
                $user->sendNotification($notiTitle,$notiMsg,$user,'register');
                // send email

            }
            $sendCode = $userObj->sendVerificationCode($data);
            if ($sendCode['status']) {
                return $this->sendResponse(['otp'=>$sendCode['otp']], $sendCode['message']);
            }
            return $this->sendError($sendCode['message'], ['error' => $sendCode['message']]);
        } catch (Exception $e) {
            return $this->sendError(trans('api.something_went_wrong'), ['error' => trans('api.something_went_wrong')."!"], 500);
        }
    }

  /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'phone'   => 'required|string',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        $user=User::where('phone',$req->phone)->first();
        if ($user) {
            $res = $this->authUserArray($req,$user,true);
            return $this->sendResponse($res,__('api.login_success') );
        }else{
            $data = $req->all();
            $userObj = new User;
            if (!isset($req->password)){
                $data['password']='123456';
            }
            $data['status']=1;
            $user = $userObj->createUser($data);
            $res = $this->authUserArray($req,$user,true);
            return $this->sendResponse($res, __('Successfully register'));
        }
    }


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function resendVerificationCode(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'phone'   => 'required|string|exists:users,phone',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }

        $user=User::where('phone',$req->phone)->first();
        if ($user){

            $verifyObj = new OtpVerification;
            // send verification code
            $sendCode = $verifyObj->createOtp($user->phone);
            if ($sendCode) {
                return $this->sendResponse(__('api.send_code'), __('api.send_code'));
            }else{
                return $this->sendError(__('api.unable_send_code'), __('api.unable_send_code'));
            }
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);

    }




    public function authUserArray($req, $user=null, $token = true)
    {
        $res = [];
        if (Auth::check() || $user) {
            if (Auth::check()) {
                $user = Auth::user();
            }
            $this->deletePrevTokens($user);
            if (isset($req->device_type) && is_integer($req->device_type))
                $user->device_type = $req->device_type;
            if (isset($req->device_token))
                $user->device_token = $req->device_token;
            $user->save();
            $res = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'country_code' => $user->country_code,
                'language' => $user->language,
                "city" => $user->city_id?$user->city->name:null,
                "city_id" => $user->city_id,
                "country" => $user->country_id?$user->country->name:null,
                "country_id" => $user->country_id,
                "notify_mute" => $user->notify_mute,
                "notify_type" => $user->notify_type,
                'profile_image' => ($user->profile_image)?asset($user->profile_image):"",
                'device_type' => $user->device_type,
                'device_token' => $user->device_token,
                'profile_completed'=>$user->checkProfile(),
                'is_verified' => $user->is_verified,
                'status' => $user->status,
            ];
            if ($token) {
                $tokenName = 'User';
                $res['token'] = $user->createToken($tokenName)->accessToken;
            }
        }
        return $res;
    }

    public function forgotPassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email'   => 'required|string|email|exists:users,email',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        $user=User::where('email',$req->email)->first();
        $data['phone_code']=1111;
        $user=$user->updateUser($data,$user);
        $verifyObj = new OtpVerification;
        // send verification code
        $sendCode = $verifyObj->sendEmailVerification($user->phone,'1111',$user->email);
        if($sendCode) {
            return $this->sendResponse(__('api.reset_code'),  __('api.reset_code'));
        }
        return $this->sendError(__('api.something_went_wrong'), __('api.something_went_wrong'));
    }

    public function confirmEmailCode(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|string|email',
            'code'=>'required|string'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        if (isset($req->code)) {
            $user = User::where('email',$req->email)->first();
            if(!empty($user)){
                if ($user->phone_code == $req->code) {
                    return $this->sendResponse(__('api.successfully_verified'), __('api.successfully_verified'));
                }
                return $this->sendError(__('api.invalid_verification_code'), __('api.invalid_verification_code'));
            }
            return $this->sendError(__('api.credentials_do_not_match'), __('api.credentials_do_not_match'));
        }

    }


    public function resetPassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email'   => 'required|string|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        $user=User::where('email',$req->email)->first();
        if (Hash::check($req->old_password, $user->password)) {
            $data=$req->all();
            $user=$user->updateUser($data,$user);
            return $this->sendResponse(__('api.password_reset_success'),  __('api.password_reset_success'));
        }
        return $this->sendError(__('api.something_went_wrong'), __('api.something_went_wrong'));
    }


    public function deletePrevTokens($user)
    {
        $tokens = $user->tokens;
        if ($tokens && count($tokens) > 0) {
            foreach($tokens as $token) {
                $token->revoke();
            }
        }
        return $user;
    }

    public function cmsPages()
    {
        $pages = Page::get();
        return $this->sendResponse(PageResource::collection($pages), 'Successful.');
    }

    public function contactUs(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|min:7|max:15',
            'phone_code' => 'nullable|string|max:15',
            'country_code' => 'nullable|string|max:15',
            'message' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        $data = $req->all();
        $contactUsObj = new ContactUs;
        $contact = $contactUsObj->createContactUs($data);
        return $this->sendResponse([], __('api.successful').".");
    }

    public function addByShare(Request $req,$slug)
    {
        if ($slug){
            $string=explode('HappySeason',$slug);
            if (count($string) != 2){
                return $this->sendError(trans('api.something_went_wrong'), ['error' => trans('api.something_went_wrong')."!"], 500);
            }
            $user=User::where('name',$string[0])->where('id',$string[1])->first();
            if ($user){
                $shareObj=new Invite();
//                $shareObj->create([
//                    'user_id',
//                ]);
                $user->share_num=$user->share_num + 1;
                $user->save();
                return $this->sendResponse(__('api.successfully_added'),__('api.successful'));
            }
        }
        return $this->sendError(__('api.customer.item_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);

    }

}
