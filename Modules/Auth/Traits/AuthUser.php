<?php

namespace Modules\Auth\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Entities\Users;
use Illuminate\Auth\Events\Registered;

trait AuthUser
{
    /**
     * Register a new user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Modules\Auth\Entities\Users
     */
    public function registerUser($request=null, $type=null)
    {
        $user = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'type' => $type,
        ]);

        event(new Registered($user));

        $this->guard()->login($user);

        return $user;
    }

    /**
     * Handle a user login.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function userLogin($request)
    {
        // check the user is valid
        if ($this->attemptLogin($request)) {
            // Authentication passed
            return $this->handleLoginResponse($request);
        }

        // Authentication failed
        return $this->handleFailedLoginResponse($request);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin($request)
    {
        $credentials = $request->only('email', 'password');

        // You can customize this to check for additional conditions if needed
        return Auth::attempt($credentials, $request->filled('remember'));
    }

    /**
     * Attempt to log out user.
     *
     */
    public function userLogout()
    {
        Auth::logout();
    }

    /**
     * Handle the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function handleLoginResponse($request)
    {
        $request->session()->regenerate();

        // this will be used for custom response
        // if ($response = $this->authenticated($request, Auth::user())) {
        //     return $response;
        // }

        return $request->expectsJson()
            ? response()->json(['message' => 'User authenticated successfully', 'user' => Auth::user()])
            : $this->webLoginResponse($request);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Handle the failed login response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function handleFailedLoginResponse($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        return $this->webFailedLoginResponse($request);
    }

    /**
     * Handle the web response after a successful login.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function webLoginResponse($request)
    {
        $user = Auth::user();

        switch($user->type) {
            case Users::TYPE_VENDOR:
                $redirectPath = 'vendor/dashbaord';
                break;
            case Users::TYPE_USER:
                $redirectPath = 'user/dashboard';
                break;
            case Users::TYPE_ADMIN:
                $redirectPath = 'adminpanel/dashboard';
                break;
        }
        // Customize the redirect path after a successful login
        return redirect()->intended($redirectPath);
    }


     /**
     * Change the user's password.
     *
     * @param string $currentPassword
     * @param string $newPassword
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changePassword($currentPassword, $newPassword)
    {
        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($currentPassword, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Incorrect current password.']);
        }

        // Update the user's password
        $user->update(['password' => bcrypt($newPassword)]);

        return response()->json(['message' => 'Password changed successfully', 'user' => $user]);
    }

    /**
     * Handle the web response after a failed login.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function webFailedLoginResponse($request)
    {
        // Customize the redirect path after a failed login
        return redirect()->route('login')->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Invalid email or password']);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated($request, $user)
    {
        // Perform additional logic if needed
        // ...

        // Return a custom response if needed
        // For example, return a JSON response
        return response()->json(['message' => 'User authenticated successfully', 'user' => $user]);
    }
}
