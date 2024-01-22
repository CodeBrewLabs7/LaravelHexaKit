<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Modules\Users\Http\Requests\CreateUserRequest;
use Modules\Users\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use Exception;
use DB;
use Carbon\Carbon;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $users = User::where('role_id','!=', 1)->get();
        return view('users::index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        try {
            $roles = Role::pluck('name','id')->all();
            $returnHTML = view('users::create', compact('roles'))->render();
            return response()->json(['status' =>true, 'html' => $returnHTML]);
        } catch(Exception $e) {
            return response()->json(['status' => false, 'message' => 'Server Error!'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();
                $insertData = $request->except('_token','password');
                $insertData['password'] = bcrypt($request->password);
                //$insertData['role_id']  = User::USER_TYPE;
                $userDetail = User::create($insertData);
                $userDetail->assignRole($request->input('role_id'));
            DB::commit();
            return response()->json(['status' =>true, 'message' => __('New User has been registered successfully')]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status'=> false,'message'=> $e->getMessage()], 422);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('users::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $roles = Role::pluck('name','id')->all();
            $userDetail = User::where('id', $id)->firstOrFail();
            $returnHTML = view('users::edit')->with(['userDetail' => $userDetail,'roles' => $roles])->render();
            return response()->json(array('status' => true, 'html' => $returnHTML));
        } catch(Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'],422);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            DB::beginTransaction();
                $updateData = $request->except('_token','password');
                if($request->has('password')) {
                    $updateData['password'] = bcrypt($request->password);
                }
                $userDetail = User::find($id);
                $userDetail->update($updateData);

                DB::table('model_has_roles')->where('model_id',$id)->delete();
                $userDetail->assignRole($request->input('roles'));
            DB::commit();
            return response()->json(['status' =>true, 'message' => __('New User has been registered successfully')]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status'=> false,'message'=> $e->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            User::where('id', $id)->delete();
            return response()->json(['status'=> true,'message'=> __('User Information has been deleted successfully')]);
        } catch (Exception $e) {
            return response()->json(['status'=> false,'message'=> $e->getMessage()],422);
        }
    }
}
