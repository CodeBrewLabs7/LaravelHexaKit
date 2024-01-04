<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Entities\Users;
use Modules\Auth\Traits\AuthUser;

class AuthController extends Controller
{
    use AuthUser, ValidatesRequests;

    public function showRegistrationForm(Request $request) {
        return view('auth::register');
    }

    public function createUser(Request $request) {

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:60|unique:users',
            'phone' => 'nullable|string|max:24|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        switch($request->type) {
            case 'vendor':
                $type = Users::TYPE_VENDOR;
                break;
            case 'user':
                $type = Users::TYPE_USER;
                break;
        }

        // Call the registerUser method to handle user registration
        $this->registerUser($request, $type);
        $user = Auth::user();
        
        switch($user->type) {
            case Users::TYPE_VENDOR:
                $redirectPath = 'vendor/dashbaord';
                $message = 'Vendor Registered Successfully!!';
                break;
            case Users::TYPE_USER:
                $redirectPath = 'user/dashboard';
                $message = 'User Registered Successfully!!';
                break;
        }

        return redirect()->intended($redirectPath)->with('success', $message);
    }

    public function showVendorRegistration(Request $request) {
        return view('auth::vendor_register');
    }

    public function showLoginForm(Request $request) {
        return view('auth::login');
    }

    public function loginUser(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return $this->userLogin($request);
    }

    public function changePassword(Request $request) {
        return view('auth::change_password');
    }

    public function setPassword(Request $request) {
        
    }

    public function logoutUser(Request $request) {
        $this->userLogout();

        return redirect()->intended(route('logout'))->with('success','Logout Successfully');
    }

    public function userDashboard(Request $request) {
        return 3;
    }

    public function vendorDashboard(Request $request) {
        return 4;
    }
}
