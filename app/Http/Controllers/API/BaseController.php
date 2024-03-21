<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Models\User;
use Illuminate\Support\Facades\Mail as FacadesMail;
// use Mail;
use App\Jobs\SendBulkEmailJob;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($result, $message, $code = 404)
    {
        $response = [
            'success' => false,
            'data'    => $result,
            'message' => $message,
        ];


        /* if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }*/


        return response()->json($response, $code);
    }

    public function sendBulkEmail()
    {
        try {
            $emails = User::whereNotNull('email')->orderBy('id', 'ASC')->pluck('email')->toArray();
            // $emails=array_chunk($emails,5);
            // dd($emails);
            // foreach()
            // FacadesMail::send('emails.welcome', [], function($message) use ($emails)
            // {
            //     $message->to($emails)->subject('Welcome Bonkers');
            // });
            if (count($emails) > 0) {
                FacadesMail::send('emails.welcome', [], function ($message) use ($emails) {
                    $message->to($emails)->subject('Welcome Bonkers');
                });
                // foreach($emails as $value){
                //     dispatch(new SendBulkEmailJob($value))->onQueue('send_email_bulk');
                // }
            }
            return response()->json([
                'status' => 200,
                'message' => "email sent"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 422,
                'message' => $th->getMessage()
            ]);
        }
    }
}
