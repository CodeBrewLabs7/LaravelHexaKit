<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $fillable = [
        'email', 'phone_number', 'country_code', 'status', 'code', 'expired_at'
    ];

    public function store($request)
    {
        $input[] = $request;
        $start = date('Y-m-d H:i:s');
        $input['expired_at'] = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($start)));

        $verification = $this->where([
            'email' => $input['email'],
            'status' => 'pending',
        ])->first();
        if ($verification) {
            $sms = $verification->update($input);
        } else {

            $this->fill($input);
            $sms = $this->save();
        }
        return response()->json($sms, 200);
    }

    public static function updateModel($request, $id)
    {
        $inputs['email'] = $request->email;
        $inputs['code'] = $request->code;
        $inputs['status'] = $request->status;
        Self::where('id', $id)->update($inputs);
        return true;
    }

    public function sendEmail($request)
    {
        $data['otp'] = $request->code;
        $data['to'] = $request->email;
        try {
            $data['email'] = $request->email;
            $mail = \Mail::send(
                'emailtemplate.emailotp',
                array('data' => $data, 'otp' => $data['otp']),
                function ($message) use ($data) {
                    $message->to($data['email'], 'Verification')->subject('OTP Verification!');
                    $message->from('sajstyled21@gmail.com', 'no-reply');
                }
            );
            return response(['status' => 'success', 'statuscode' => 200, 'message' => __('OTP sent to your Email ID!')], 200);
        } catch (Exception $e) {
            return response(['status' => 'error', 'statuscode' => 500, 'message' => $e->getMessage()], 500);
        }
    }
}
