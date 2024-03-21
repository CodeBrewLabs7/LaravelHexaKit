<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Verification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    public function checkUserStatus(Request $request)
    {
        $rules = [
            'phone_or_email' => 'required',
        ];
        $customMessages = [];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return $this->sendError([], $validator->getMessageBag()->first());
        }

        $userExists = false;

        if (filter_var($request->phone_or_email, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $request->phone_or_email)->first();
            if ($user && $user->role != User::ROLE_ADMIN) {
                $userExists = true;
            }
        } else if (is_numeric($request->phone_or_email)) {
            $user = User::where('phone_number', $request->phone_or_email)->first();
            if ($user && $user->role != User::ROLE_ADMIN) {
                $userExists = true;
            }
        } else {
            $userExists = false;
        }

        $data['user_exists'] = $userExists;
        if($userExists == true){
            return $this->sendResponse($data, trans('User status.', ['attribute' => 'User Status']));
        }else{
            return $this->sendError($data, trans('User status.', ['attribute' => 'User Status']));
        }
    }

    public function login(Request $request)
    {

        $rules = [
            'phone_or_email' => 'required',
            'password' => 'required',
            "device_type" => ['required', Rule::in(\App\Models\DeviceToken::$deviceTypes)],
            // "device_token" => ['required', 'string'],

        ];

        $customMessages = [];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        $data = [];
        if ($validator->fails()) {
            return $this->sendError((object) $data, $validator->getMessageBag()->first());
        }

        $credentials = ['password' => $request->password];

        $field = "phone_number";
        if (filter_var($request->phone_or_email, FILTER_VALIDATE_EMAIL)) {
            $field = "email";
        } else if (is_numeric($request->phone_or_email)) {
            $field = "phone_number";
        } else {
            $field = "phone_number";
        }

        switch ($field) {
            case 'email':
                $credentials['email'] = $request->phone_or_email;
                $checkUser = User::where('email', $request->phone_or_email)->first();
                break;

            default:
                $credentials['phone_number'] = $request->phone_or_email;
                $checkUser = User::where('phone_number', $request->phone_or_email)->first();
                break;
        }


        if ($checkUser) {
            $checkUser->device_type = $request->get('device_type');
            $checkUser->save();
            if (Auth::guard('web')->attempt($credentials)) {
                $user = Auth::user()->load(['deviceTokens']);

                if ($user->role == User::ROLE_USER) {
                    $device_token = '12345';
                    $user->updateDeviceToken($request->get('device_type'), $device_token);
                    $user->revokeTokens();
                    $token = $user->createToken('wealth_builder')->accessToken;
                    $user->token = $token;

                    return $this->sendResponse($user, trans('Login successfully.', ['attribute' => 'Login']));
                } else {
                    return $this->sendError((object) $data, trans('We are sorry, this user is not registered with us.'));
                }
            } else {
                return $this->sendError((object) $data, trans('Invalid credentials'));
            }
        } else {
            return $this->sendError((object) $data, trans('We are sorry, this user is not registered with us.'));
        }
    }

    public function register(Request $request)
    {
        // \Log::info($request->all());

        $input = $request->all();
        $rules = [
            'name' => ['required', 'string'],
            'country_flag' => ['required', 'string'],
            'country_code' => ['required', 'required_with:phone_number'],
            'phone_number' => ['required', 'required_with:country_code', Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('phone_number', $request->phone_number)
                    ->where('country_code', $request->country_code);
            }), 'integer'],
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('email', $request->email);
            }), 'string'],
            'password' => ['required'],
        ];


        $customMessages = [
            'country_code.required_with' => "Please enter country code with phone number.",
            'phone_number.required_with' => "Please enter phone number with country code.",
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        $data = [];
        if ($validator->fails()) {
            return $this->sendError((object) $data, $validator->getMessageBag()->first());
        }

        $email_verified_at = null;

        $user = DB::transaction(function () use ($input, $request) {
            $user = User::create([
                'name' => $request->get('name'),
                'country_flag' => $request->get('country_flag'),
                'country_code' => $request->get('country_code'),
                'phone_number' => $request->get('phone_number'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role' => User::ROLE_USER
            ]);

            return $user;
        });

        // $currentDatetime = now();
        // // Add 60 minutes to the current datetime
        // $newExpireAt = $currentDatetime->addMinutes(60);

        // $verification = Verification::create([
        //     'phone_number' => $request->get('phone_number'),
        //     'country_code' => $request->get('country_code'),
        //     'email' => $request->get('email'),
        //     'code' => 1234,
        //     'expired_at' => $newExpireAt
        // ]);

        // if ($verification) {
        //     $user->is_otp_sent = User::SENT;
        //     $user->save();
        // }

        //Update Tokens
        if ($request->get('device_token')) {
            $user->updateDeviceToken($request->get('device_type'), $request->get('device_token'));
        }

        //Auto login user
        Auth::login($user);

        $updateUser = User::where('id', $user->id)->first();

        $token = $updateUser->createToken('wealth_builder')->accessToken;
        $updateUser->token = $token;

        return $this->sendResponse(
            $updateUser,
            'User registered successfully.'
        );
    }

    public function sendOtp(Request $request)
    {

        $rules = [
            'country_code' => ['required', 'required_with:phone_number'],
            'phone_number' => ['required', 'required_with:country_code'],
        ];

        $customMessages = [
            'country_code.required_with' => "Please enter country code with phone number.",
            'phone_number.required_with' => "Please enter phone number with country code.",
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        $data = [];
        if ($validator->fails()) {
            return $this->sendError((object) $data, $validator->getMessageBag()->first());
        }

        if($request->get('register_otp') == 1){
            $phoneNumberExists = User::where('phone_number', $request->get('phone_number'))->exists();
            $emailExists = User::where('email', $request->get('email'))->exists();

            if ($phoneNumberExists) {
                return $this->sendError((object) $data, "Phone number already exists.");
            }

            if ($emailExists) {
                return $this->sendError((object) $data, "Email already exists.");
            }
        }

        try {
            $input['code'] = '123456';
            // $input['code'] = generateRandomCode();
            $input['status'] = User::OTP_PENDING;
            $input['phone_number'] = $request->get('phone_number');
            $input['country_code'] = $request->get('country_code');

            // Send OTP
            $number_phone = "+" . $input['country_code'] . $input['phone_number'];
            $account_sid = Config('services.twillio.TWILIO_SID');
            $auth_token = Config('services.twillio.TWILIO_KEY');
            $twilio_number = Config('services.twillio.TWILIO_NUMBER');
        } catch (Exception $e) {
            $message = $e->getMessage();
            return $this->sendError($number_phone, $message);
        }

        //Store OTP
        self::store($input);

        $data['phone_number'] = $request->get('country_code') . ' ' . $request->get('phone_number');
        $data['otp'] = $input['code'];
        return $this->sendResponse(
            $data,
            trans('OTP sent successfully.', ['attribute' => 'Register'])
        );

        return $this->sendError(
            (object) $data,
            trans('Invalid Phone Number.')
        );
    }

    public function verifyOtp(Request $request)
    {

        $input = $request->all();

        $rules = [
            'country_code' => ['required', 'required_with:phone_number'],
            'phone_number' => ['required'],
            'otp' => ['required']
        ];

        $customMessages = [
            'country_code.required_with' => "Please enter country code with phone number.",
            'phone_number.required_with' => "Please enter phone number with country code.",
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        $data = [];
        if ($validator->fails()) {
            return $this->sendError((object) $data, $validator->getMessageBag()->first());
        }

        $user = Verification::where([
            'phone_number' => $input['phone_number'],
            'country_code' => $input['country_code'],
        ])->latest()->first();

        if (!$user) {
            return $this->sendError(
                (object) $data,
                trans('User does not exists.')
            );
        }

        //Check if OTP is already verified
        $verified = \App\Models\Verification::where([
            'phone_number' => $input['phone_number'],
            'country_code' => $input['country_code'],
            'code' => $input['otp'],
        ])->latest()->first();

        if($verified && $verified->status == User::OTP_VERIFIED){
            return $this->sendResponse(
                (object) [],
                trans('OTP already verified.', ['attribute' => 'OTP verified'])
            );
        }

        //Check if OTP is there in the DB
        $verify = \App\Models\Verification::where([
            'phone_number' => $input['phone_number'],
            'country_code' => $input['country_code'],
            'code' => $input['otp'],
            'status' => User::OTP_PENDING,
        ])->latest()->first();

        $email_verified_at = null;

        if ($verify) {
            $verify->status = User::OTP_VERIFIED;
            $data['status'] = $verify->status;
            $verify->save();

            return $this->sendResponse(
                $verify,
                trans('OTP verified successfully.', ['attribute' => 'Verify OTP'])
            );
        } else {
            return $this->sendError(
                (object) $verify,
                trans('Invalid OTP.')
            );
        }
        return $this->sendError(
            (object) $verify,
            trans('Please try again!')
        );
    }


    private static function store($request)
    {
        $input = $request;
        $start = date('Y-m-d H:i:s');
        $input['expired_at'] = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($start)));

        $verification = Verification::where([
            'phone_number' => $input['phone_number'],
            'country_code' => $input['country_code'],
            'status' => User::OTP_PENDING,
        ])->first();
        if ($verification) {
            $otpSaved = $verification->update($input);
        } else {
            $otpSaved = Verification::create($input);
        }
        return response()->json($otpSaved, 200);
    }

    public function forgotPassword(Request $request)
    {
        $rules = [
            'email' => ['required', 'string', 'exists:users,email'],
        ];
        $customMessages = [];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        $data = [];

        if ($validator->fails()) {
            return $this->sendError([], $validator->getMessageBag()->first());
        }

        $check_user = User::where('email', $request->email)->first();

        if (!empty($check_user)) {
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
            ]);

            Mail::send('emails.forgotPassword', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });
            return $this->sendResponse($data, trans('We have mailed reset link!', ['attribute' => 'Mail Sent']));
        } else {
            return $this->sendError($data, trans('You are not a registred member!', ['attribute' => 'Mail not sent']));
        }
    }
}
