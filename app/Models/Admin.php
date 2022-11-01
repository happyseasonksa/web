<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use App\Models\AdminAccess;
use Hash;
use Auth;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','type','status','chat_name','web_fcm_token','phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function createAdmin($data)
    {
        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'type' => $data['type'],
            'phone' => $data['phone']??null,
            'password' => Hash::make($data['password']),
            'status' => (isset($data['status']) && $data['status'] == 'true')?true:false,
        ]);
//        $chatName = generateUniqueChatName($admin->name,$admin->id);
//        $admin->update(['chat_name'=>$chatName]);
//        createChatUser($chatName);
        return $admin;
    }

    public function updateAdmin($data,$admin)
    {
        if (isset($data['name'])) {
            $admin->name = $data['name'];
        }
        if (isset($data['email'])) {
            $admin->email = $data['email'];
        }
        if (isset($data['type'])) {
            $admin->type = $data['type'];
        }
        if (isset($data['phone'])) {
            $admin->phone = $data['phone'];
        }
        if (isset($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }
        if (isset($data['status'])) {
            $admin->status = ($data['status'] == 'true')?true:false;
        }
        $admin->save();
        return $admin;
    }

    public function defineAccess($admin,$input)
    {

        switch ($admin->type) {
            case 1:
                $allowed = [1,2,3,4,5,6,7,8,9,10];
                break;
            case 2:
                $allowed = [3,8,9];
                break;
        }
        if (isset($allowed)) {
            $entitys = typeOfAdminsEntity();
            $result = array_flip(array_filter(array_flip($entitys), function ($key) use ($allowed)
            {
                return in_array($key, $allowed);
            }));
            if (count($result) > 0) {
                foreach ($result as $value) {
                    $view = (isset($input[$value]['view']) && $input[$value]['view'] == "1")?1:0;
                    $add = (isset($input[$value]['add']) && $input[$value]['add'] == "1")?1:0;
                    $update = (isset($input[$value]['update']) && $input[$value]['update'] == "1")?1:0;
                    $delete = (isset($input[$value]['delete']) && $input[$value]['delete'] == "1")?1:0;
                    if ($update === 1 || $delete ===1) {
                        $view = 1;
                    }
                    AdminAccess::createAccess($admin->id,$value,$view,$add,$update,$delete);
                }
            }
        }
        return $admin;
    }

    public function checkAdminAccess($entity, $admin=null, $type=null)
    {
        if (!isset($admin)) {
            $admin = Auth::user();
        }
        $access = ($admin->type !== 0)?false:true;
        if ($admin->type !== 0) {
            $check = $admin->adminAccesses()->where('entity_name', $entity)->first();
            if ($check) {
                // execption of host to see all restaurant orders
                if ($admin->type == 2 && $check->entity_name == 'order' && $type == 'view') {
                    $access = true;
                }
                // execption of host to see all restaurant orders ENDS
                else{
                    if ($type) {
                        if ($type && $check[$type]) {
                            $access = $check;
                        }
                    }else{
                        if ($check->view || $check->add || $check->update || $check->delete) {
                            $access = $check;
                        }
                    }
                }
            }
        }
        return $access;
    }

    public function systemAdmin()
    {
        $res = false;
        if (Auth::guard('admin')->check() && Auth::user()->type === 0) {
            $res = true;
        }
        return $res;
    }

    public function assignItems()
    {
        return $this->hasMany('App\Models\Item');
    }

    public function adminAccesses()
    {
        return $this->hasMany('App\Models\AdminAccess');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\AdminNotification');
    }

    /**
     * Sends the password reset notification.
     *
     * @param  string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPassword($token));
    }
}

class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action('Reset Password', route('admin.password.reset.token', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]))
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }
}
