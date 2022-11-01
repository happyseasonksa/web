<?php

use App\Models\Order;
use App\Models\User;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Page;
use App\Models\ProductImage;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\DriverTask as DriverTaskResource;
use App\Http\Resources\NoticeBoard as NoticeBoardResource;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Twilio\Rest\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleClient;

function getCountryCodes()
{
    try {
        $countryCodes = File::get(storage_path('app/country_codes.json'));
    } catch (\Exception $e) {
        $arr = [];
        $countryCodes = json_encode($arr);
    }
    return json_decode($countryCodes);
}

function csvToArray($filename = '', $delimiter = ',')
{
    if (!file_exists($filename) || !is_readable($filename))
        return false;

    $header = null;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== false)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
        {
            if (!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }

    return $data;
}

function getTimeLeft($assigned_at, $min = 2)
{
    $datetime1 = strtotime(date('Y-m-d H:i:s'));
    $datetime2 = strtotime('+'.$min.' minutes',strtotime($assigned_at));
    $diff = ($datetime2>$datetime1)?($datetime2-$datetime1):0;
    return $diff;
}

function validateDate($date, $format = 'Y-m-d', $transform = 'Y-m-d')
{
	$d = \DateTime::createFromFormat($format, $date);
	return [
	    'valid' => $d && $d->format($format) === $date,
	    'date' => ($d)?$d->format($transform):false
	];
}

function getLocalAttribute()
{
    $local = (request()->hasHeader('Accept-Language')) ? request()->header('Accept-Language') : 'en';
    if (!in_array($local, \Config::get('app.supported_languages'))) {
        $local = 'en';
    }
    return $local;
}

function getResInputAsLocal($input,$obj,$local='en')
{
    $res = isset($obj->$input)?$obj->$input:'';
    if ($local !== 'en') {
        $input = $local.'_'.$input;
        if (isset($obj->$input) && !empty($obj->$input)) {
            $res = $obj->$input;
        }
    }
    return $res;
}

function deletePrevImage($path)
{
    if(File::exists($path)) {
        File::delete($path);
    }
}


function dateDiff($fdate, $tdate)
{
    $valid = false;
    if (strtotime($fdate) > strtotime($tdate)) {
        $datetime1 = new \DateTime($fdate);
        $datetime2 = new \DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        if ($days > 0) {
            $valid = true;
        }
    }
    return [
        'valid' => $valid,
        'days' => (isset($days))?(int)$days:0,
    ];
}

function createNotificationSession($admin,$request)
{
    $res = false;
    if ($admin instanceof Admin) {
        // \Session::flush();
        // \Session::put('last_notification_id', 0);
        // \Session::save();
        $sessionLast = \Session::has('last_notification_id')?\Session::get('last_notification_id'):0;
        $lastNoti = $admin->notifications()->latest()->first()->id??0;
        if ($request->updated == "false" || $sessionLast !== $lastNoti) {
            \Session::put('last_notification_id', $lastNoti);
            \Session::save();
            $res = true;
        }
    }
    return $res;
}

function sendPushNotification($fcm_token, $title, $message, $id="", $driver = false) {
    $push_notification_key = \Config::get('constant.fcm_key');

    // dd($push_notification_key);
    $url = "https://fcm.googleapis.com/fcm/send";
    $header = array("authorization: key=" . $push_notification_key . "",
        "content-type: application/json"
    );

    $postdata = '{
        "to" : "' . $fcm_token . '",
            "notification" : {
                "sound" : "default",
                "title":"' . $title . '",
                "text" : "' . $message . '",
                "body" : "' . $message . '"
            },
        "data" : {
            "id" : "'.$id.'",
            "sound" : "default",
            "title":"' . $title . '",
            "description" : "' . $message . '",
            "text" : "' . $message . '",
            "is_read": 0
          }
    }';

    $ch = curl_init();
    $timeout = 120;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // Get URL content
    $result = curl_exec($ch);
    // close handle to release resources
    curl_close($ch);

    return $result;
}

function distanceBtwLatLong($lat1, $lon1, $lat2, $lon2, $unit='K') {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

function paymentOptions()
{
    return ['Cash','Card'];
}
function getAllDays()
{
    return ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
}

function getCountries()
{
    return \App\Models\Country::all();
}

function getCategories()
{
    return \App\Models\Category::active()->get();
}

function getSubCategories($id)
{
    return \App\Models\SubCategory::where('category_id',$id)->active()->get();
}

function getImageCategories()
{
    return \App\Models\ImageCategory::active()->get();
}

function getCities()
{
    return \App\Models\City::all();
}

function typeOfAdmins()
{
    return [
        1 => 'admin',
        2 => 'Host',
        3 => 'Back Office',
        4 => 'IT',
        5 => 'Government',
    ];
}

function typeOfAdminsEntity()
{
    return [
        1 => 'access',
        2 => 'customer',
        3 => 'category',
        4 => 'item',
        5 => 'ads',
        6 => 'city',
        7 => 'page',
        8 => 'report',
        9 => 'review',
        10 => 'setting',
    ];
}

function getAllRestAsAdmin($admin) {
    $items = collect();
    if ($admin->type === 0) {
        $items = new Restaurant;
    }else{
        if ($admin->assignItems && count($admin->assignItems)) {
            $ids = $admin->assignItems()->pluck('item_id');
            $items = Restaurant::whereIn('id',$ids);
        }
    }
    return $items;
}

function checkValidRestId($admin, $item_id)
{
    $valid = false;
    $item=null;
    if ($admin->type === 0) {
        $valid = true;
    }else{
        if ($admin->assignItems && count($admin->assignItems)) {
            if ($item_id){
                $item = $admin->assignItems()->where('id',$item_id)->first();
            }
            if ($item) {
                $valid = true;
            }
        }
    }
    return $valid;
}

function generate_string($strength = 6) {
    $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }

    return $random_string;
}

function sendSms($to,$message)
{
    $message_enable=\App\Models\Setting::where('name_id',\App\Models\Setting::MESSAGE_SETTING)->first()->value;
    if ($message_enable && $message_enable == 'false'){
        $res = [
            'status' => true,
            'message' => __('api.successful'),
        ];
        return $res;
    }
    $data=[
          "userName"=> config('constant.msegat_user_name'),
          "numbers"=> $to,
          "userSender"=> config('constant.msegat_user_sender'),
          "apiKey"=> config('constant.msegat_api_key'),
          "msg"=> $message
    ];
        // dd(getTokenInfo($token));
        $res = [
            'status' => false,
            'message' => __('api.something_went_wrong'),
        ];
        $client = new GuzzleClient();
        $url = 'https://www.msegat.com/gw/sendsms.php';
        $credentials = config('constant.paytabs_server_key');

        try {
            $request = $client->post($url, [
                'headers' => [
                    'content-type' => 'application/json',
                ],
                'json' => $data
            ]);
            if ($request->getStatusCode() == 200) {
                $res = [
                    'status' => true,
                    'message' => __('api.successful'),
                    'data' => json_decode($request->getBody()->getContents()),
                ];
            }
        } catch (\Exception $e) {
            $res['message'] = $e->getMessage();
        }
        return $res;

}

function sendSmsCode(){

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://www.msegat.com/gw/sendsms.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);

    curl_setopt($ch, CURLOPT_POST, TRUE);

            $fields = <<<EOT
        {
          "userName": "xxxxxx",
          "numbers": "966xxxxxx",
          "userSender": "xxxxxx",
          "apiKey": "xxxxxx",
          "msg": "xxxxxx"
        }
EOT;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
            ));

            $response = curl_exec($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

            var_dump($info["http_code"]);
            var_dump($response);
}


function reIndexArray($arr)
{
    try {
        $arr = array_filter($arr);
        sort($arr);
        return $arr;
    } catch (\Exception $e) {

    }
    return $arr;
}
function convertFileToBase64($file)
{
    $file = public_path($file);
    if (file_exists($file)) {
        $filetype = pathinfo($file,PATHINFO_EXTENSION);
        return 'data:image/'.$filetype.';base64,'.base64_encode(file_get_contents($file));
    }
    return null;
}

function get_friendly_time_ago($distant_timestamp, $max_units = 3, $single = true) {
    $i = 0;
    $time = time() - $distant_timestamp; // to get the time since that moment
    $tokens = [
        31536000 => ($single)?'y':'year',
        2592000 => ($single)?'m':'month',
        604800 => ($single)?'w':'week',
        86400 => ($single)?'d':'day',
        3600 => ($single)?'h':'hour',
        60 => ($single)?'min':'minute',
        1 => ($single)?'s':'second'
    ];

    $responses = [];
    while ($i < $max_units && $time > 0) {
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) {
                continue;
            }
            $i++;
            $numberOfUnits = floor($time / $unit);

            $responses[] = $numberOfUnits . '' . $text . (($numberOfUnits > 1 && $unit !== 1) ? 's' : '');
            $time -= ($unit * $numberOfUnits);
            break;
        }
    }

    if (!empty($responses)) {
        return $responses[0];
    }

    return 'Just now';
}

function optimizeImage($path)
{
    try {
        if(file_exists(public_path().$path)){
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize(public_path().$path);
        }
    } catch (\Exception $e) {
        // dd($e);
    }
}

function getResizeImage($imagePath,$width=400,$height=400)
{
    try {
        $fileName = getFileName($imagePath);
        $imagePre = 'rsz-'.$width.'-'.$height.'-';
        if (file_exists(public_path('/thumbnail/'.$imagePre.$fileName))) {
            return '/thumbnail/'.$imagePre.$fileName;
        }else{
            $destinationPath = public_path('/thumbnail');
            $img = \Image::make(public_path($imagePath));
            $imageName = $imagePre.$fileName;
            $img->resize($width, $height)->save($destinationPath.'/'.$imageName);
            return '/thumbnail/'.$imageName;
        }
    } catch (\Exception $e) {
        // dd($e->getMessage());
    }
    return $imagePath;
}

function getFileName($imagePath)
{
    $res = '';
    if (!empty($imagePath)) {
        $arr = explode('/', $imagePath);
        if (count($arr) > 0) {
            $index = count($arr) - 1;
            $res = $arr[$index];
        }
    }
    return $res;
}

function getPage($name)
{
    $page = Page::where('name', $name)->first();
    return $page;
}

function testPushNotification($fcm_token, $title, $message, $click="") {
    $push_notification_key = \Config::get('constant.fcm_key');
    // dd($push_notification_key);
    $url = "https://fcm.googleapis.com/fcm/send";
    $header = array("authorization: key=" . $push_notification_key . "",
        "content-type: application/json"
    );

    $postdata = '{
        "to" : "' . $fcm_token . '",
        "notification" : {
            "title":"' . $title . '",
            "body":"' . $message . '",
            "click_action":"' . url('/').$click. '",
            "icon" : "' . asset('dist/img/rsz_login_logo@3x.png') . '"
        }
    }';

    $ch = curl_init();
    $timeout = 120;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // Get URL content
    $result = curl_exec($ch);
    // close handle to release resources
    curl_close($ch);

    return $result;
}
function mine_encrypt($simple_string){
    // Store the cipher method
    $ciphering = "AES-128-CTR";

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

    // Non-NULL Initialization Vector for encryption
    $encryption_iv = '1234567891011121';

    // Store the encryption key
    $encryption_key =config('constant.ENCRYPTION_KEY') ;

    // Use openssl_encrypt() function to encrypt the data
    $encryption = openssl_encrypt($simple_string, $ciphering,
        $encryption_key, $options, $encryption_iv);

    return $encryption;
}
function mine_decrypt($encryption){
    $ciphering = "AES-128-CTR";

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

    // Non-NULL Initialization Vector for encryption
    $encryption_iv = '1234567891011121';

    // Store the encryption key
    $encryption_key = config('constant.ENCRYPTION_KEY');

    // Use openssl_decrypt() function to decrypt the data
    $decryption=openssl_decrypt ($encryption, $ciphering,
        $encryption_key, $options, $encryption_iv);
    return $decryption;
}

function tableText($text, $length=30)
{
    return strlen($text) > $length ? substr($text,0,$length)."..." : $text;
}
