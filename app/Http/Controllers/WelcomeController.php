<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class WelcomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($phone,$code)
    {
        $res['status'] = false;
        $res['message'] = 'Invalid verification code!';
        $check = OtpVerification::where('phone',$phone)->where('otp',$code)->first();
        if ($check) {
            $user = User::where('phone',$phone)->first();
            if ($user) {
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();
                $res['status'] = true;
                $res['message'] = 'Email verified successfully!';
            }else{
                $res['message'] = 'Invalid user!';
            }
        }
        return view('frontend.customer_message',compact('res'));
    }

    public function registerProvider(Request $req){

        $vaidator=Validator::make($req->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'phone' => 'required|string|min:7|max:15|unique:admins',
        ]);

        Session::flush();
        if ($vaidator->fails()){
            $errors=$vaidator->errors();
            return redirect()->back()->with(['errors'=>$errors]);
        }

        $data = $req->all();
        $data['type']=1;
        $data['status']=0;
        $data['password']='123456';
        $adminObj = new Admin;
        $admin = $adminObj->createAdmin($data);
        return redirect()->route('landing.page')->with('status', __('Successfully Sent'));
    }
}
