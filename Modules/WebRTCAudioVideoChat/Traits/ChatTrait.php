<?php

namespace Modules\WebRTCAudioVideoChat\Traits;

use App\Models\{Order,OrderVendor,UserDevice,ClientPreference, Product};
use Auth;
use GuzzleHttp\Client as GCLIENT;
use Log;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

trait ChatTrait{


     /**
     * start Chat.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public $eCommerceArray = ['vendor_id'=>0,'order_number'=>0,'vendor_order_id'=>0,'order_id'=>0,'isRaiseIssue'=>0,'product_id'=>0,'agent_db'=>'','agent_id'=>0,'p2p_id'=>0,'vendor_name'=>'CbBaseVendor','product_name'=>'CbBaseProduct','product_price'=>0,'vendorImage'=>''];
    // It will save data in json string 
    public $optionalItem =[]; 
    public $server_name = '';
    public $socket_url = '';
    // Categories of Chat 
    public $c_type = null;
    public $user_id = 0;
    public $user_image = '';
    public $user_name = '';  
    public $read_message = '';  


    public function startChat(Request $request){

        echo "<pre>";
        print_r($this->eCommerceArray);
        die;
        //try {
            $data = $request->all();
            
            $vendor_id = $data['vendor_id'];
            $order_number = $data['order_number'] ?? null;
            $vendor_order_id = $data['order_vendor_id'] ?? '';
           
            $order_id = $data['order_id'] ?? '';
            $isRaiseIssue = $data['isRaiseIssue'] ?? 0 ;
            $server_name = $_SERVER['SERVER_NAME'];
            $product_id = $data['product_id'] ?? null;
            $agent_db = '';
            $agent_id = '';
            $socket_url = $this->client_data->socket_url;
            $c_type = $data['type'] ?? null;
            $p2p_id = null;
            $vendor_name = null;
            $product_name = null;
            $product_price = null;
            $user_id = Auth::id();

            $product = [];
            $vendorImage = [];
           
            // dd(is_null($order_id));
            // check order_vendor_id and order_id is empty then it is called for p2p chat
            if( $c_type == 'user_to_user' ) {

           
              
                $room_name = $room_id = ($order_number != null) ? 'p2p-productId-'.$product_id.'-orderNumber-'.$order_number :'p2p-productId-'.$product_id."-userId-".$user_id;
                $orderby_user_id = Auth::id();
                $p2p_id = $vendor_id;
                $vendor = Vendor::where('id', $vendor_id)->first();
                if(@$vendor){
                    $user_vendor = UserVendor::where('vendor_id', $vendor_id)->first();
                    if(@$user_vendor->user_id){
                        $vendorImage = User::where('id', $user_vendor->user_id)->first();
                    }
                }
                $vendor_name = $vendor->name;
                $product = Product::with('variant')->where('id', $product_id)->first();
                $product_name = $product->title??'';
                $product_price = $product->variant[0]->price??0;
            }
            else {

                $order = $this->OrderVendorDetail($request);
                if(@$order){
                    $room_id = $order->order_number;
                    if(isset($data['agent_id'])) {
                        $room_name = 'OrderNo-'.$order->order_number.'-orderId-'.$order->id.'-oderVendor-'.$vendor_id.'-agentId-'.$data['agent_id'];
                        $agent_db = $data['agent_db'];
                        $agent_id = $data['agent_id'];
                    } else {

                        $room_name = 'OrderNo-'.$order->order_number.'-orderId-'.$order->id.'-oderVendor-'.$vendor_id;
                        $agent_db = '';
                        $agent_id = '';
                    }
                    $orderby_user_id = $order->user_id;

                } else {

                    return response()->json(['status' => false, 'message' => __('Something went wrong!!!')]);
                }
            }
 

            $url = $socket_url.'/api/room/createRoom';
            $params =  [
                'room_id' => $room_id,
                'room_name' => $room_name,
                'order_vendor_id'=> $vendor_order_id,
                'order_id'=>$order_number,
                'vendor_id'=>$vendor_id,
                'sub_domain' =>$server_name,
                'vendor_user_id' =>$data['user_id'],
                'order_user_id' =>$orderby_user_id,
                'type'=>$data['type'],
                'db_name'=>$this->client_data->database_name,
                'client_id'=>$this->client_data->id,
                'p2p_id'=>$p2p_id,
                'product_id'=>$product_id,
                'vendor_name' => $vendor_name,
                'product_name' => $product_name,
                'product_price' => $product_price,
                'agent_id'=>$agent_id,
                'agent_db'=>$agent_db,
                'isRaiseIssue'=> $isRaiseIssue
            ];
          

            $response =   Http::post($url,$params);


            $statusCode = $response->getStatusCode();

            if($statusCode == 200) {
                $roomData = $response['roomData'];
                return response()->json(['status' => true, 'roomData' => $roomData ,'product' => $product,'vendorImage' => $vendorImage, 'message' => __('Room created successfully !!!')]);
            } else {
              
                return response()->json(['status' => false, 'message' => __('Something went wrong!!!')]);
            }
        // } catch (\Throwable $th) {
         
        //     return response()->json(['status' => false, 'message' => __('Something went wrong!!!')]);
        // }
    }





     # get prefereance if last mile on or off and all details updated in config
     public function getDispatchDomain()
     {
         $preference = ClientPreference::first();
         return $preference;
        
     }
    
    /**
     * OrderVendorDetail
     *
     * @param  mixed $request
     * @return void
     */
    public function OrderVendorDetail($request)
    {
        $data = $request->all();
        $order_vendor_id = $data['order_vendor_id'];
        $order_id = $data['order_id'];
        $order = Order::with(array(
            'vendors' => function ($query) use ($order_vendor_id) {
                $query->where('id', $order_vendor_id);
            },'vendors.vendor'
        ))->findOrFail($order_id);
        return  $order;
    }

    /**
     * OrderVendorDetail
     *
     * @param  mixed $request
     * @return void
     */
    public function ProductDetail($request)
    {
        $data = $request->all();
        $pid  = $data['product_id'];
        $product = Product::with([
        'category.categoryDetail', 'variant.media.pimage.image', 'vendor', 'media.image', 'related', 'upSell', 'crossSell']);
   
        $product = $product->select('id', 'title', 'sku', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'is_new', 'is_featured', 'is_physical', 'has_inventory', 'has_variant', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating','minimum_order_count','batch_count','minimum_duration','minimum_duration_min','additional_increments','additional_increments_min','buffer_time_duration','buffer_time_duration_min',  'is_long_term_service','service_duration','latitude','longitude','address');
   
        $product = $product->where('id', $pid)
        ->first();
        return  $product;
    }
    
    /**
     * sendNotification
     *
     * @param  mixed $request
     * @param  mixed $from
     * @return void
     */
    public function sendNotification($request,$from='')
    {
        $data = $request->all();
        if($from=='from_dispatcher'){
            $username =  $data['username'];
            $removeAuth = array_values(array_column($request->all()['user_ids'], 'auth_user_id'));
        } else{
            $username =  Auth::user()->name;
            $auid =  Auth::user()->id;
            $result = array_values(array_column($request->all()['user_ids'], 'auth_user_id'));
            $removeAuth = $result;
            if(@$data['auth_id']==$auid){
                $removeAuth = array_values(array_diff($result, array($auid)));
            }
             /**dispacth noti */
             if($data['order_vendor_id']!=''){
                $this->getDispacthUrl($data['order_vendor_id'],$data['order_id'],$data['vendor_id'],$data);
             }
            
            /**end */
        }
       
        $client_preferences = ClientPreference::select('fcm_server_key','favicon')->first();
        $devices            = UserDevice::whereNotNull('device_token')->whereIn('user_id',$removeAuth)->pluck('device_token') ?? [];
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $data = [
                "registration_ids" => $devices,
                "notification" => [
                    "title" => $username,
                    "body"  => $request->text_message,
                    'sound' => "default",
                    "icon"  => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                    "android_channel_id" => "default-channel-id"
                ],
                "data" => [
                    "title" => $username,
                    "room_id"=>$request->roomId,
                    "room_id_text"=>$request->roomIdText,
                    "body"  => $request->text_message,
                    'data'  => 'chat_text',
                    'type'  => ""
                ],
                "priority" => "high"
            ];
                      
            $response = sendFcmCurlRequest($data);
            $result = json_decode($response); 
            return $result;
        }
    }
    
    /**
     * getDispacthUrl
     *
     * @param  mixed $order_vendor_id
     * @param  mixed $order_id
     * @param  mixed $vendor_id
     * @param  mixed $postdata
     * @return void
     */
    public function getDispacthUrl($order_vendor_id,$order_id,$vendor_id,$postdata)
    {
     $checkdeliveryFeeAdded = OrderVendor::with('LuxuryOption')->where(['order_id' => $order_id, 'vendor_id' => $vendor_id])->first();      
     
        $luxury_option_id = isset($checkdeliveryFeeAdded) ? @$checkdeliveryFeeAdded->LuxuryOption->luxury_option_id : 1;
        $dispatchDomain = $this->getDispatchDomain();
      
        /// luxury option 8 ( static ) for appointment you can check it on luxuryOptionSeeder
        if ($luxury_option_id == 8) { // only for appointment type 
                $dispatch_domain=[
                    'service_key'      => $dispatchDomain->appointment_service_key,
                    'service_key_code' => $dispatchDomain->appointment_service_key_code,
                    'service_key_url'  => $dispatchDomain->appointment_service_key_url,
                ];
            
        }elseif ($luxury_option_id == 6) { // only for on_demand type         
            if($dispatchDomain && $dispatchDomain != false){
               
                $dispatch_domain=[
                    'service_key'      => $dispatchDomain->dispacher_home_other_service_key,
                    'service_key_code' => $dispatchDomain->dispacher_home_other_service_key_code,
                    'service_key_url'  => $dispatchDomain->dispacher_home_other_service_key_url,
                 
                ];
            }
        } else{
            $dispatch_domain=[
                'service_key'      => $dispatchDomain->delivery_service_key,
                'service_key_code' => $dispatchDomain->delivery_service_key_code,
                'service_key_url'  => $dispatchDomain->delivery_service_key_url,
                
              
            ];
        }
       $this->hitDispacthHook($dispatch_domain,$postdata);
       
    }    
    /**
     * hitDispacthHook
     *
     * @param  mixed $dispatch_domain
     * @param  mixed $postdata
     * @return void
     */
    public function hitDispacthHook($dispatch_domain,$postdata){
      
        if ($dispatch_domain && $dispatch_domain != false) {
            
                $client = new GClient([
                    'headers' => [
                        'personaltoken' => $dispatch_domain['service_key'],
                        'shortcode' => $dispatch_domain['service_key_code'],
                        'content-type' => 'application/json'
                    ]
                ]);
                
                $url = $dispatch_domain['service_key_url'];
                //Log::info($url);
                //Log::info($postdata);
                $res = $client->post(
                    $url . '/api/chat/sendNotificationToAgent',
                    ['form_params' => ($postdata)]
                );
                $response = json_decode($res->getBody(), true);
                //Log::info($response);
                return $response;
        } else{
            return response()->json(['status' => false, 'notiFY' => [] , 'message' => __('No Data found!!!')]);
        }
    }

    public function signAws(Request $request)
    {
       

        $accessKeyId = \Config::get('app.AWS_ACCESS_KEY_ID_CHAT');
        $secretAccessKey = \Config::get('app.AWS_SECRET_ACCESS_KEY_CHAT');
        $region = \Config::get('app.AWS_DEFAULT_REGION_CHAT');
        $bucketName = \Config::get('app.AWS_BUCKET_CHAT');
     
        $fileName = $request->input('filename');

        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $accessKeyId,
                'secret' => $secretAccessKey,
            ],
        ]);

        try {
            
            $cmd = $s3Client->getCommand('PutObject', [
                'Bucket' => $bucketName,
                'Key' => $fileName,
                'ACL' => 'public-read',
            ]);
            
            $request = $s3Client->createPresignedRequest($cmd, '+1 hour');
            $signedUrl = (string) $request->getUri();

            return response()->json([
                'url' => $signedUrl,
                //'thumbnail_url' => $thumbnailUrl,
            ]);

            //return response()->json(['url' => $signedUrl]);
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
}
