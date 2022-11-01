<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Mail\EmailVerification;

class OtpVerification extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone','otp','otp_valid_till'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function createOtp($phone)
    {
    	$data['phone'] = $phone;
    	//$data['otp'] = 1111;
        $data['otp'] = mt_rand(1000, 9999);
    	$data['otp_valid_till'] = date('Y-m-d H:i:s', strtotime("+3 minutes"));
    	$create = OtpVerification::create($data);
    	return $create;
    }

    public function matchOtp($phone, $otp)
    {
    	$time = date('Y-m-d H:i:s');
    	$check = OtpVerification::where('phone',$phone)->where('otp',$otp)->where('otp_valid_till','>=',$time)->first();
    	return $check;
    }

    public function sendEmailVerification(string $phone, string $otp, string $recipient)
    {
        $res = true;
        $data['phone'] = $phone;
        $data['code'] = $otp;
        try {
            \Mail::to($recipient)->send(new EmailVerification($data));
        } catch (\Exception $e) {
            //dd($e);
            $res = false;
        }
        return $res;
    }
}
