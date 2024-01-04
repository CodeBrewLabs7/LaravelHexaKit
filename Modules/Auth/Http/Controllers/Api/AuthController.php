<?php

namespace Modules\Auth\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Entities\Users;
use Modules\Auth\Traits\ApiResponser;
use Modules\Auth\Traits\AuthUser;

class AuthController extends Controller
{
    use AuthUser, ValidatesRequests,ApiResponser;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('auth::index');
    }


    public function signup(Request $request)
    {
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
          $message = "Registration Successfully" ;
        return $this->successResponse($user, $message);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('auth::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('auth::show');
    }

    public function logoutUser(Request $request) {
        $this->userLogout();
        $message = "User Logout Successfully" ;
        return $this->successResponse( $message);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('auth::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
