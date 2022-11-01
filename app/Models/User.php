<?php

namespace App\Models;

use App\Mail\EmailAdmin;
use App\Mail\EmailInvitation;
use App\Mail\EmailVerification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\OtpVerification;
use App\Http\Resources\Customer as CustomerResource;
use Auth;
use ImageOptimizer;
use Spatie\ImageOptimizer\OptimizerChainFactory;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone','password', 'device_type', 'device_token', 'status', 'profile_image','is_verified', 'verified_at','language', 'city_id','country_id', 'lat','lng', 'notify_mute', 'notify_type','share_num',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function createUser($data)
    {
        return User::create([
            'name' => $data['name']??null,
            'email' => $data['email']??null,
            'phone' => $data['phone'],
            'profile_image' => isset($data['profile_image'])?$data['profile_image']:null,
            'device_type' => isset($data['device_type'])?$data['device_type']:2,
            'device_token' => isset($data['device_token'])?$data['device_token']:null,
            'lat' => isset($data['lat'])?$data['lat']:null,
            'lng' => isset($data['lng'])?$data['lng']:null,
            'notify_mute' => isset($data['notify_mute'])?$data['notify_mute']:0,
            'notify_type' => isset($data['notify_type'])?$data['notify_type']:'all',
            'language' => isset($data['language'])?$data['language']:'en',
            'status' => (isset($data['status']) && $data['status'] == 'true')?true:false,
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateUser($data,$user)
    {
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['language'])) {
            $user->language = $data['language'];
        }
        if (isset($data['profile_image'])) {
            $user->profile_image = $data['profile_image'];
        }
        if (isset($data['phone'])) {
            $user->phone = $data['phone'];
        }
        if (isset($data['city_id'])) {
                $user->city_id = $data['city_id'];
        }
        if (isset($data['country_id'])) {
                $user->country_id = $data['country_id'];
        }
        if (isset($data['lat'])) {
                $user->lat = $data['lat'];
        }
        if (isset($data['lng'])) {
                $user->lng = $data['lng'];
        }
        if (isset($data['status'])) {
            $user->status = $data['status']=='true'?1:0;
        }
        if (isset($data['notify_mute'])) {
            $user->notify_mute = $data['notify_mute']?$data['notify_mute']:0;
        }
         if (isset($data['notify_type'])) {
            $user->notify_type = $data['notify_type']?$data['notify_type']:'all';
         }
         if (isset($data['device_token'])) {
            $user->device_token = $data['device_token'];
         }
//
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        return $user;
    }

    public function uploadFile($file, $is_profile = false)
    {
        $optimizerChain = OptimizerChainFactory::create();
        $full_name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $name = explode('.'.$extension, $full_name);
        $name = isset($name[0])?$name[0]:'image';
        $name = str_replace(" ","_",$name);
        $name = $name.mt_rand().time().'.'.$extension;
        $destinationPath = ($is_profile)?public_path('/profile_images'):public_path('/documents');
        try {
            $optimizerChain->optimize($file->path(), $destinationPath.'/'.$name);
        } catch (\Exception $e) {
            // dd($e);
            $file->move($destinationPath, $name);
        }
        $publicUrl = ($is_profile)?('/profile_images/'):('/documents/');
        return $publicUrl.$name;
    }

    public function getUserDetailAccType($user)
    {
        $res = new CustomerResource($user);
        return $res;
    }
    public function sendVerificationCode($data, $email=false)
    {
        $res['status'] = false;
        $res['message'] = __('api.credentials_do_not_match')."!";
        $user = User::where('phone', $data['phone'])->first();
        if ($user) {
            if ($email && $user->verified_at != null) {
                $res['message'] = __('api.already_verified')."!";
            }else{
                $verifyObj = new OtpVerification;
                $createOtp = $verifyObj->createOtp($user->phone);
                $message = "Your mobile verification code for ".\Config::get('app.name')." is : ".$createOtp->otp;
                $sendOtp = sendSms($user->phone,$message);
                if ($email) {
                    $verifyObj->sendEmailVerification($createOtp->phone,$createOtp->otp,$user->email);
                }
                if ($sendOtp['status']) {
                    $res['status'] = true;
                    // testing
                    $res['message'] = __('api.send_code')." : ".$createOtp->otp."!";
                    $res['otp'] = $createOtp->otp;
                }else{
                    $res['message'] = __('api.unable_send_code')."!";
                }
            }
        }
        return $res;
    }

    public function sendSmsVerificationCode($data)
    {
        $res['status'] = false;
        $res['message'] = __('api.unable_send_code')."!";
        $user = User::where('phone', $data['phone'])->first();
        if (isset($data['phone'])) {
            $verifyObj = new OtpVerification;
            $createOtp = $verifyObj->createOtp($data['phone']);
            $message = "Your mobile verification code for ".\Config::get('app.name')." is : ".$createOtp->otp;
            $sendOtp = sendSms($data['phone'],$message);
            if ($sendOtp['status']) {
                $res['status'] = true;
                // testing
                $res['message'] = __('api.send_code')." : ".$createOtp->otp."!";
                $res['otp'] = $createOtp->otp;
            }
        }
        return $res;
    }

    public function sendNotification($title, $message, $user = null,$type = null)
    {
        $notification = null;
        if (!$user) {
            $user = Auth::user();
        }
        if ($user && $user->device_token && $user->device_token != "") {
            $notification = sendPushNotification($user->device_token, $title, $message, "");
        }
        $saveNoti = Notification::createNotification($user->id,$title,$message,$type);
        return $notification;
    }
    public function sendEmailRegistration(string $phone, string $recipient)
    {
        $res = true;
        $data['phone'] = $phone;
        try {
            \Mail::to($recipient)->send(new EmailVerification($data));
        } catch (\Exception $e) {
            //dd($e);
            $res = false;
        }
        return $res;
    }
    public function sendEmailInvitation(string $phone, string $recipient)
    {
        $res = true;
        $data['phone'] = $phone;
        try {
            \Mail::to($recipient)->send(new EmailInvitation($data));
        } catch (\Exception $e) {
            //dd($e);
            $res = false;
        }
        return $res;
    }
    public function sendEmailAdmin(string $phone, string $recipient)
    {
        $res = true;
        $data['phone'] = $phone;
        try {
            \Mail::to($recipient)->send(new EmailAdmin($data));
        } catch (\Exception $e) {
            //dd($e);
            $res = false;
        }
        return $res;
    }

    public function checkProfile()
    {
        $is_complete=true;
        if (is_null($this->name) && is_null($this->email) && is_null($this->profile_image)){
            $is_complete=false;
        }
        return $is_complete;
    }
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function keywords()
    {
        return $this->hasMany('App\Models\UserKeyword');
    }

    public function favourites()
    {
        return $this->hasMany('App\Models\Favourite');

    }
    public function invitations()
    {
        return $this->hasMany('App\Models\Invitation');

    }

    public function contacts()
    {
        return $this->hasMany('App\Models\ContactHistory','user_id');
    }
}
