<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'min:10', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'gender' => ['required', 'string', 'max:255'],
            'id_proof_1' => ['required','image','mimes:jpeg,png,jpg','max:10000'],
            'id_proof_2' => ['required','image','mimes:jpeg,png,jpg','max:10000'],
            'address' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return false;
        // $userObj = new User;
        // $user = $userObj->createUser($data, 1);
        // $data['user_id'] = $user->id;
        // if(isset($data['id_proof_1'])){
        //     $data['id_proof_1'] = $userObj->uploadFile($data['id_proof_1']);
        // }
        // if(isset($data['id_proof_2'])){
        //     $data['id_proof_2'] = $userObj->uploadFile($data['id_proof_2']);
        // }
        // $cusObj = new Customer;
        // $customer = $cusObj->createCustomer($data);
        // return $user;
    }

    protected function showRegistrationForm()
    {
        return redirect('/login');
    }
}
