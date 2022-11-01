<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Ads;
use App\Models\Card;
use App\Models\Category;
use App\Models\City;
use App\Models\GroupNotification;
use App\Models\Item;
use App\Models\Review;
use App\Models\SubCategory;
use App\Models\User;
use CobraProjects\Arabic\Arabic;
use Illuminate\Http\Request;


use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use Johntaa\Arabic\I18N_Arabic;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\ChatGrant;
use Auth;
use DataTables;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('checkStatusAdmin');
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();
            return $next($request);
        });
    }
    /**
     * show dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = User::count();
        $admins=Admin::where('type','<>',0)->count();
        $cities=City::count();
        $cities_names=City::all()->pluck('name')->toArray();
        $items_count_by_city=Item::select(\DB::raw('count(id) AS item_count'))->groupBy('city_id')->orderBy('city_id','ASC')->pluck('item_count')->toArray();
        $categories=Category::count();
        $subcategories=SubCategory::count();
        $items=Item::count();
        $ads=Ads::active()->count();
        $reviews=[];
        if (!Auth::user()->systemAdmin()){
            $items = Item::where('admin_id',$this->authUser->id)->pluck('id');
            $reviews = Review::whereIn('item_id', $items)->count();
            $items=Item::where('admin_id',auth()->id())->count();
            $ads=Ads::where('admin_id',auth()->id())->count();
            $items_count_by_city=Item::where('admin_id',auth()->id())->select(\DB::raw('count(id) AS item_count'))->groupBy('city_id')->orderBy('city_id','ASC')->pluck('item_count')->toArray();
        }
        return view('admin.home',get_defined_vars());
    }

    public function accountSetting(Request $req)
    {
        $user = Auth::user();
        return view('admin.accountSettings',compact('user'));
    }

    public function updateAccountSetting(Request $req)
    {
        $user = Auth::user();
        $data = $req->all();
        $user = $user->updateAdmin($data,$user);
        return redirect()->route('admin.account.settings')->with('toast-success', __('Successfully added').'!');
    }

    public function getNotificationList(Request $req)
    {
        if ($req->ajax()) {
            $data = $this->authUser->notifications()->latest()->get();
            return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('recieved_at', function($row){
                            return date('d-m-Y h:i a',strtotime($row->created_at));
                        })
                        ->addColumn('action', function($row){
                            $link = ($row->icon === "fas fa-exclamation-circle")?route('admin.order.index'):'';
                            return "<a class='btn btn-info btn-sm align-items-center h-100 mr-2' title='SHOW' href='javascript:void(0)' onclick='getNotificationDetail(".$row->id.",this,`".$link."`)'><i class='fa fa-info-circle mr-2'></i> View </a>";
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('admin.notification.index');
    }

    public function getNotificationData(Request $req)
    {
        $res = [
            'status' => false,
            'message' => 'Something went wrong!',
        ];
        $notis = [];
        try {
            // dd($this->authUser->notifications);
            $notis = (createNotificationSession($this->authUser,$req))?$this->authUser->notifications()->latest()->get()->take(10):[];
            if (count($notis) > 0) {
                $notis->map(function ($noti) {
                    $noti['title'] = tableText($noti['title'],120);
                    $noti['recieved_at'] = get_friendly_time_ago(strtotime($noti['created_at']));
                    return $noti;
                });
            }
            $totalUnread = $this->authUser->notifications()->unread()->count();
            $res = [
                'status' => true,
                'data' => ['notifications'=>$notis,'total_unread'=>$totalUnread],
            ];
        } catch (\Exception $e) {
            $res['message'] = $e->getMessage();
        }
        return response()->json($res);
    }

    public function getNotificationDetail(Request $req)
    {
        $res = [
            'status' => false,
            'message' => 'Something went wrong!',
        ];
        try {
            // dd($this->authUser->notifications);
            $noti = $this->authUser->notifications()->find($req->notification_id);
            if ($noti) {
                $noti->update(['is_read'=>1]);
                $res = [
                    'status' => true,
                    'data' => $noti,
                ];
            }else{
                $res['message'] = "Invalid notification selected";
            }
        } catch (\Exception $e) {
            $res['message'] = $e->getMessage();
        }
        return response()->json($res);
    }

    public function saveWebFcmToken(Request $req)
    {
        $res = [
            'status' => false,
            'message' => 'Something went wrong!',
        ];
        try {
            $admin = Auth::user();
            $admin->web_fcm_token = $req->token;
            $admin->save();
            $res = [
                'status' => true,
                'message' => 'Success!',
            ];
        } catch (\Exception $e) {
            $res['message'] = $e->getMessage();
        }
        return response()->json($res);
    }

    public function chatTokenGenerate(Request $request, AccessToken $accessToken, ChatGrant $chatGrant)
    {
        $appName = "TwilioChat";
        $identity = $request->input("identity");

        $TWILIO_CHAT_SERVICE_SID = config('services.twilio')['chatServiceSid'];

        $accessToken->setIdentity($identity);

        $chatGrant->setServiceSid($TWILIO_CHAT_SERVICE_SID);

        $accessToken->addGrant($chatGrant);

        $response = array(
            'identity' => $identity,
            'token' => $accessToken->toJWT()
        );

        return response()->json($response);
    }

    public function ListGroupNotifications()
    {
        if (!Auth::user()->systemAdmin()){
            return redirect('/admin')->with('toast-error', __('Not Authorised'));
        }
        $notifys=GroupNotification::all();
        return view('admin.group-notify.index',compact('notifys'));
    }
    public function CreateGroupNotification()
    {
        if (!Auth::user()->systemAdmin()){
            return redirect('/admin')->with('toast-error', __('Not Authorised'));
        }
        return view('admin.group-notify.create');
    }
    public function StoreGroupNotification(Request $req)
    {
        if (!Auth::user()->systemAdmin()){
            return redirect('/admin')->with('toast-error', __('Not Authorised'));
        }
        $rules = [
            'body'   => 'required|string|max:255',
            ];
        $this->validate($req, $rules);

        $userObj=new User();
        $data=$req->all();
        $data['admin_id']=auth()->user()->id;
        $groupObj=new GroupNotification();
        $group=$groupObj->createNotify($data);
        if ($group){
            $users=User::where('status',1)->get();
            $notiTitle = __('api.notification.adminGroupNotification');
            $notiMsg = $data['body'];
            if (count($users) > 0) {
                foreach ($users as $user) {
                    $user->sendNotification($notiTitle, $notiMsg, $user,'admin');
                    if ($user->email) {
                        $userObj->sendEmailAdmin($user->phone, $user->email);
                    }
                }
            }
        }
        return redirect()->route('admin.group-notification.index')->with('toast-success', __('Successfully added').'!');
    }

    public function destroyNotification(GroupNotification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.group-notification.index')->with('toast-success', __('successfully deleted').' ! ');
    }

    public function test()
    {
// Remove unnecessary words from the search term and return them as an array
        function filterSearchKeys($query){
            $query = trim(preg_replace("/(\s+)+/", " ", $query));
            $words = array();
            // expand this list with your words.
            $list = array("in","it","a","the","of","or","I","you","he","me","us","they","she","to","but","that","this","those","then");
            $c = 0;
            foreach(explode(" ", $query) as $key){
                if (in_array($key, $list)){
                    continue;
                }
                $words[] = $key;
                if ($c >= 15){
                    break;
                }
                $c++;
            }
            return $words;
        }

// limit words number of characters
        function limitChars($query, $limit = 200){
            return substr($query, 0,$limit);
        }

        function search($query){

            $query = trim($query);
            if (mb_strlen($query)===0){
                // no need for empty search right?
                return false;
            }
            $query = limitChars($query);

            // Weighing scores
            $scoreFullTitle = 6;
            $scoreTitleKeyword = 5;
            $scoreFullSummary = 5;
            $scoreSummaryKeyword = 4;
            $scoreFullDocument = 4;
            $scoreDocumentKeyword = 3;
            $scoreCategoryKeyword = 2;
            $scoreUrlKeyword = 1;

            $keywords = filterSearchKeys($query);
            $escQuery = DB::escape($query); // see note above to get db object
            $titleSQL = array();
            $sumSQL = array();
            $docSQL = array();
            $categorySQL = array();
            $urlSQL = array();

            /** Matching full occurences **/
            if (count($keywords) > 1){
                $titleSQL[] = "if (p_title LIKE '%".$escQuery."%',{$scoreFullTitle},0)";
                $sumSQL[] = "if (p_summary LIKE '%".$escQuery."%',{$scoreFullSummary},0)";
                $docSQL[] = "if (p_content LIKE '%".$escQuery."%',{$scoreFullDocument},0)";
            }

            /** Matching Keywords **/
            foreach($keywords as $key){
                $titleSQL[] = "if (p_title LIKE '%".DB::escape($key)."%',{$scoreTitleKeyword},0)";
                $sumSQL[] = "if (p_summary LIKE '%".DB::escape($key)."%',{$scoreSummaryKeyword},0)";
                $docSQL[] = "if (p_content LIKE '%".DB::escape($key)."%',{$scoreDocumentKeyword},0)";
                $urlSQL[] = "if (p_url LIKE '%".DB::escape($key)."%',{$scoreUrlKeyword},0)";
                $categorySQL[] = "if ((
                    SELECT count(category.tag_id)
                    FROM category
                    JOIN post_category ON post_category.tag_id = category.tag_id
                    WHERE post_category.post_id = p.post_id
                    AND category.name = '".DB::escape($key)."'
                                ) > 0,{$scoreCategoryKeyword},0)";
            }

            // Just incase it's empty, add 0
            if (empty($titleSQL)){
                $titleSQL[] = 0;
            }
            if (empty($sumSQL)){
                $sumSQL[] = 0;
            }
            if (empty($docSQL)){
                $docSQL[] = 0;
            }
            if (empty($urlSQL)){
                $urlSQL[] = 0;
            }
            if (empty($tagSQL)){
                $tagSQL[] = 0;
            }

            $sql = "SELECT p.p_id,p.p_title,p.p_date_published,p.p_url,
            p.p_summary,p.p_content,p.thumbnail,
            (
                (-- Title score
                ".implode(" + ", $titleSQL)."
                )+
                (-- Summary
                ".implode(" + ", $sumSQL)."
                )+
                (-- document
                ".implode(" + ", $docSQL)."
                )+
                (-- tag/category
                ".implode(" + ", $categorySQL)."
                )+
                (-- url
                ".implode(" + ", $urlSQL)."
                )
            ) as relevance
            FROM post p
            WHERE p.status = 'published'
            HAVING relevance > 0
            ORDER BY relevance DESC,p.page_views DESC
            LIMIT 25";
            $results = DB::query($sql);
            if (!$results){
                return false;
            }
            return $results;
        }
        //Now your search.php file can look like this:

        $term = isset($_GET['query'])?$_GET['query']: '';
        $search_results = search($term);

        if (!$search_results) {
            echo 'No results';
            exit;
        }

// Print page with results here.
    }
    public function makeImage(Request $req)
    {
        $x_axis=$req->x_axis??100;
        $y_axis=$req->y_axis??120;
        $text=$req->text??'This is a example';

        $Arabic = new Arabic('Glyphs');

        $text ="نتشرف بدعوتكم لحفل زفاف , والعاقبة عندكم";
        $text = $Arabic->utf8Glyphs($text);
        $img = Image::make(public_path('documents/blank-img.jpg'));
        $img->text($text, $x_axis, $y_axis, function ($font) use($req){
            $size=$req->size??28;
            $color=$req->color?'#'.$req->color:'#e1e1e1';
            $font->file(public_path('css/fonts/Avenir/Neo Sans Arabic Regular.ttf'));
            $font->size($size);
            $font->color($color);
            $font->align('center');
            $font->valign('bottom');
            //$font->angle(90);
        });
        $img->save(public_path('documents/blank-img1.jpg'));
        return response()->download(public_path().'/documents/blank-img1.jpg');
    }
}
