<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Http\Resources\Review as ReviewResource;
use App\Models\ContactHistory;
use App\Models\Customer;
use App\Models\Favourite;
use App\Http\Resources\Favourite as FavouriteResource;
use App\Models\Invitation;
use App\Models\InvitationUsers;
use App\Models\Invite;
use App\Models\Item;
use App\Models\Order;
use App\Models\OtpVerification;
use App\Models\Review;
use App\Models\User;
use App\Models\UserKeyword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\Notification as NotificationResource;
use App\Http\Resources\Keyword as KeywordResource;
use App\Http\Resources\Invitation as InvitationResource;
use App\Http\Resources\ContactHistory as ContactHistoryResource;
use App\Models\Notification;

class CustomerController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('IsCustomer');
    }

    /*
     Profile
    */
    public function updateProfile(Request $req)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
        }
        $rules=[
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
            'password' => 'nullable|confirmed|min:6',
            'city_id' => 'nullable|integer|exists:cities,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
            'notify_mute' => 'nullable|integer:in,0,1',
            'notify_type' => 'nullable|string|max:255',
            'promocode' => 'nullable|string|max:255',
        ];
        if (is_null($user->name) &&  is_null($user->email)){
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|string|email|max:255|unique:users,email,'.$user->id;
        }else{
            $rules['name'] = 'nullable|string|max:255';
            $rules['email'] = 'nullable|string|email|max:255|unique:users,email,'.$user->id;
        }
        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        $userObj=new User();
        $data=$req->all();

        //link sharing functionality
        if (isset($req->promocode)){
            $slug=$req->promocode;
            $string=explode('HappySeason',$slug);
            if (count($string) != 2){
                return $this->sendError(__('api.promocode_incorrect').".", ['error'=>__('api.promocode_incorrect')."."]);
            }
            $inviter=User::where('name',$string[0])->where('id',$string[1])->first();
            if (!$inviter){
                return $this->sendError(__('api.promocode_incorrect').".", ['error'=>__('api.promocode_incorrect')."."]);
            }
            if ($inviter){
                $inviter->share_num=$user->share_num + 1;
                $inviter->save();
            }
        }

        if($req->hasFile('profile_image')){
            $data['profile_image'] = $user->uploadFile($req->profile_image);
        }
        $user = $userObj->updateUser($data, $user);
        $res = $user->getUserDetailAccType($user);
        if (isset($data['email'])) {
            $userObj->sendEmailRegistration($data['email'],$data['email']);
        }
        return $this->sendResponse($res, __('api.profile_updated').".");

    }


    /*
    Profile
    */
    public function updatePhone(Request $req)
    {
        $user = Auth::user();
        $validator = Validator::make($req->all(), [
            'phone' => 'required|string|min:7|max:15|unique:users,phone',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        if ($user) {
            try {
                $userObj = New User;
                $verifyObj = new OtpVerification;
                $data = $req->all();
                // login with phone, code
                if (isset($req->verify_code)) {
                    $msg = __('api.phone_not_registered')."!";
                    $matchCode = $verifyObj->matchOtp($req->phone,$req->verify_code);
                    if($matchCode){
                        $user = $user->updateUser($data, $user);
                        $res = $user->getUserDetailAccType($user);
                        return $this->sendResponse($res, __('api.phone_updated').".");
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
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }



    public function listNotifications(Request $req)
    {
        $user=Auth::user();

        if ($user){
            $notifications=Notification::where('user_id',$user->id)->where('entity_type','user')->
            orderBy('id', 'desc')->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); // grouping by day
            });
            $res=[];
            if (!empty($notifications)){
                    foreach ($notifications as $key => $items){
                        $res[]=['date'=>$key ,'data'=> NotificationResource::collection($items)];
                    }
            }
            return $this->sendResponse($res,__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function listUnreadNotifications(Request $req)
    {
        $user=Auth::user();

        if ($user){
            $notifications=Notification::where('user_id',$user->id)->where('entity_type','user')->where('is_read',0)->orderBy('id', 'desc')->get();
            return $this->sendResponse(NotificationResource::collection($notifications),__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function readNotification(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'notification_id'=>'required|integer|exists:notifications,id',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();

        if ($user){
            $notification=Notification::find($req->notification_id);
            if (empty($notification) || $notification->user_id != $user->id || $notification->is_read != 0){
                return $this->sendError(__('api.notification.notification_not_found'),__('api.notification.notification_not_found'));
            }
            $notification->update(['is_read'=>1]);
            return $this->sendResponse(new NotificationResource($notification),__('api.successful'));

        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function keywordAdd(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'keyword'=>'required|string|max:255',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user) {
            $keywordObj= UserKeyword::create([
                'user_id'=>auth()->id(),
                'keyword'=>$req->keyword
            ]);
            return $this->sendResponse(__('api.successfully_added'),__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function keywordList()
    {
        $user=Auth::user();
        if ($user) {
            $keywords=$user->keywords;
            return $this->sendResponse(KeywordResource::collection($keywords),__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function keywordDelete()
    {
        $user=Auth::user();
        if ($user) {
            $keywords=$user->keywords;
            if (count($keywords) > 0){
                foreach ($keywords as $keyword){
                    $keyword->delete();
                }
            }
            return $this->sendResponse(__('api.successfully_deleted'),__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);

    }

    /*
     Review Item
    */

    public function itemReview(Request $req)
    {
        $user = Auth::user();
        $rules = [
            'item_id' => 'required|exists:items,id',
            'star' => 'required|integer:in:1,2,3,4,5',
            'comment' => 'nullable|string',
        ];
        $validator = Validator::make($req->all(), $rules);
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        if ($user) {
                $data = $req->all();
                $data['user_id'] = $user->id;
                $data['status'] = 1;
                $reviewObj=new Review();
                $reviewExist = Review::where('item_id',$data['item_id'])->where('user_id',$data['user_id'])->first();
                $review = ($reviewExist)?$reviewExist->update($data):$reviewObj->createReview($data);
                if ($reviewExist){
                    return $this->sendResponse(__('api.successfully_updated'),__('api.successful'));
                }
                return $this->sendResponse(__('api.successfully_added'),__('api.successful'));
        }
        return $this->sendError(__('api.customer.order_not_found').".", ['error'=>__('api.customer.order_not_found')."."]);
    }

    public function reviews(Request $req)
    {
        $user = Auth::user();
        if ($user){
            $reviews=$user->reviews;
            return $this->sendResponse(ReviewResource::collection($reviews),__('api.successful'));
        }
        return $this->sendError(__('api.customer.reviews_not_found').".", ['error'=>__('api.customer.reviews_not_found')."."]);
    }

    public function favAdd(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'item_id'=>'required|integer|exists:items,id',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user) {
            $fav=Favourite::where('item_id',$req->item_id)->where('user_id',$user->id)->first();
            if ($fav){
                return $this->sendResponse(__('api.customer.already_exist'),__('api.successful'));
            }
            $favObj= Favourite::create([
                'user_id'=>auth()->id(),
                'item_id'=>$req->item_id
            ]);
            return $this->sendResponse(__('api.successfully_added'),__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function favList()
    {
        $user=Auth::user();
        if ($user) {
            $favs=$user->favourites;
            return $this->sendResponse(FavouriteResource::collection($favs),__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function favDelete(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'item_id'=>'required|integer|exists:items,id',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user) {
            $fav=Favourite::where('item_id',$req->item_id)->where('user_id',$user->id)->first();
            if ($fav){
                $fav->delete();
                return $this->sendResponse(__('api.successfully_deleted'),__('api.successful'));
            }
            return $this->sendError(__('api.customer.item_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function invitationAdd(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'card_id'=>'required|integer|exists:cards,id',
            'message'=>'required|string',
            'item_name'=>'required|string',
            'address'=>'required|string',
            'lat'=>'nullable|string',
            'lng'=>'nullable|string',
            'invitation_date'=>'required|string|date_format:Y-m-d H:i|after:'.date('Y-m-d H:i',strtotime('-1 hour')),
            'signature'=>'required|string',
            'invitees' =>'required|array',
            'image' => 'required|string'
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user){
            $data=$req->all();
            $data['user_id']=auth()->id();
            $userObj=new User();
            $invitationObj=new Invitation();
            if ($req->hasFile('image')) {
                $data['image'] = $userObj->uploadFile($req->image);
            }
            $invitationObj=$invitationObj->createInvitation($data);
            if (count($req->invitees) > 0){
                foreach ($req->invitees as $invitee){
                    // send notification
                    //end send notificartion
                    $invitationUserObj=new InvitationUsers();
                    $user=User::where('phone',$invitee)->first();
                    if ($user){
                        $dataInvitation['invitee_id']=$user->id;
                        $notiTitle = __('api.invitation_title');
                        $notiMsg = __('api.invitation_added_successfully');
                        $user->sendNotification($notiTitle,$notiMsg,$user,'invitation');

//                        //send email invitation
//                        if ($user->email) {
//                            $userObj->sendEmailInvitation($user->phone, $user->email);
//                        }
                    }
                    $dataInvitation['inviter_id']=auth()->id();
                    $dataInvitation['invitation_id']=$invitationObj->id;
                    $dataInvitation['phone']=$invitee;
                    $invitationUserObj=$invitationUserObj->createItem($dataInvitation);

                }
            }
            return $this->sendResponse(__('api.successfully_added'),__('api.successful'));

        }
    }

    public function invitationList(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'type'=>'required|string:in,all,today,yesterday,week,month,year',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user) {
            $invitations=[];
            switch ($req->type) {
                case 'all':
                    $invitations = $user->invitations()->orderBy('created_at','desc')->get();
                    break;
                case 'today':
                    $invitations = $user->invitations()->whereBetween('created_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d 23:59:59', time())])->orderBy('created_at','desc')->get();
                    break;
                case 'yesterday':
                    $invitations = $user->invitations()->whereBetween('created_at', [date("Y-m-d 00:00:00", strtotime('-1 days')), date("Y-m-d 23:59:59", strtotime('-1 days'))])->orderBy('created_at','desc')->get();
                    break;
                case 'week':
                    $invitations = $user->invitations()->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('created_at','desc')->get();
                    break;
                case 'month':
                    $invitations = $user->invitations()->whereMonth('created_at', Carbon::now()->month)->orderBy('created_at','desc')->get();
                    break;
                case 'year':
                    $invitations = $user->invitations()->whereYear('created_at', date('Y'))->orderBy('created_at','desc')->get();
                    break;
                default:
                    $invitations = $user->invitations()->orderBy('created_at','desc')->get();
                    break;
            }
            return $this->sendResponse(InvitationResource::collection($invitations),__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);

    }

    public function invitationDelete(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'invitation_id'=>'required|integer|exists:invitations,id',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user) {
            $invitation=$user->invitations()->where('id',$req->invitation_id)->first();
            if ($invitation){
                $invitation->delete();
                return $this->sendResponse(__('api.successfully_deleted'),__('api.successful'));
            }
            return $this->sendError(__('api.customer.item_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function receivedInvitations(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'type'=>'required|string:in,all,today,yesterday,week,month,year',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user) {
            $invitations_ids=InvitationUsers::where('invitee_id',$user->id)->pluck('invitation_id');
            $invitations=[];
            if (!empty($invitations_ids)) {
                switch ($req->type) {
                    case 'all':
                        $invitations = Invitation::whereIn('id',$invitations_ids)->orderBy('created_at','desc')->get();
                        break;
                    case 'today':
                        $invitations = Invitation::whereIn('id', $invitations_ids)->whereBetween('created_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d 23:59:59', time())])->orderBy('created_at','desc')->get();
                        break;
                    case 'yesterday':
                        $invitations = Invitation::whereIn('id', $invitations_ids)->whereBetween('created_at', [date("Y-m-d 00:00:00", strtotime('-1 days')), date("Y-m-d 23:59:59", strtotime('-1 days'))])->orderBy('created_at','desc')->get();
                        break;
                    case 'week':
                        $invitations = Invitation::whereIn('id', $invitations_ids)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('created_at','desc')->get();
                        break;
                    case 'month':
                        $invitations = Invitation::whereIn('id', $invitations_ids)->whereMonth('created_at', Carbon::now()->month)->orderBy('created_at','desc')->get();
                        break;
                    case 'year':
                        $invitations = Invitation::whereIn('id', $invitations_ids)->whereYear('created_at', date('Y'))->orderBy('created_at','desc')->get();
                        break;
                    default:
                        $invitations = Invitation::whereIn('id',$invitations_ids)->orderBy('created_at','desc')->get();
                        break;
                }
            }
            return $this->sendResponse(InvitationResource::collection($invitations), __('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function invitationDetails(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'id'=>'required|integer|exists:invitations,id',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user) {
           $invitation=Invitation::find($req->id);
           if (!empty($invitation)) {
               return $this->sendResponse(new InvitationResource($invitation), __('api.successful'));
           }
            return $this->sendError(__('api.customer.item_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function contactHistoryAdd(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'item_id'=>'required|integer|exists:items,id',
            'type'=>'required|string|max:255'
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        $item=Item::find($req->item_id);
        if ($user) {
            $exist=ContactHistory::where('item_id',$req->item_id)->where('user_id',$user->id)->first();
            if ($exist){
                return $this->sendResponse(__('api.customer.already_exist'),__('api.successful'));
            }
            $contactObj= ContactHistory::create([
                'user_id'=>auth()->id(),
                'contact_type'=>$req->type,
                'item_id'=>$req->item_id,
                'admin_id'=>$item->id
            ]);
            return $this->sendResponse(__('api.successfully_added'),__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }


    public function contactHistotyList(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'type'=>'required|string:in,all,today,yesterday,week,month,year',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user) {
            $invitations=[];
            switch ($req->type) {
                case 'all':
                    $invitations = $user->contacts()->orderBy('created_at','desc')->get();
                    break;
                case 'today':
                    $invitations = $user->contacts()->whereBetween('created_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d 23:59:59', time())])->orderBy('created_at','desc')->get();
                    break;
                case 'yesterday':
                    $invitations = $user->contacts()->whereBetween('created_at', [date("Y-m-d 00:00:00", strtotime('-1 days')), date("Y-m-d 23:59:59", strtotime('-1 days'))])->orderBy('created_at','desc')->get();
                    break;
                case 'week':
                    $invitations = $user->contacts()->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('created_at','desc')->get();
                    break;
                case 'month':
                    $invitations = $user->contacts()->whereMonth('created_at', Carbon::now()->month)->orderBy('created_at','desc')->get();
                    break;
                case 'year':
                    $invitations = $user->contacts()->whereYear('created_at', date('Y'))->orderBy('created_at','desc')->get();
                    break;
                default:
                    $invitations = $user->contacts()->orderBy('created_at','desc')->get();
                    break;
            }
            return $this->sendResponse(ContactHistoryResource::collection($invitations),__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);

    }

    public function contactHistotyDelete(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'item_id'=>'required|integer|exists:items,id',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $user=Auth::user();
        if ($user) {
            $exist=ContactHistory::where('item_id',$req->item_id)->where('user_id',$user->id)->first();
            if ($exist){
                $exist->delete();
                return $this->sendResponse(__('api.successfully_deleted'),__('api.successful'));
            }
            return $this->sendError(__('api.customer.item_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);
    }

    public function shareLink()
    {
        $user=Auth::user();
        if ($user) {
            $slug=$user->name.'HappySeason'.$user->id;
            return $this->sendResponse(['slug'=>$slug],__('api.successful'));
        }
        return $this->sendError(__('api.customer.customer_not_found').".", ['error'=>__('api.customer.customer_not_found')."."]);

    }

}
