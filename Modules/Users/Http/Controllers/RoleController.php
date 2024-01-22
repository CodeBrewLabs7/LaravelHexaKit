<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Modules\Users\Http\Requests\CreateRoleRequest;
use DB;
use Exception;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $roles = Role::orderBy('id','DESC')->get();
        return view('users::roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        try {
            $permission = Permission::get();
            $returnHTML = view('users::roles.create', compact('permission'))->render();
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
    public function store(CreateRoleRequest $request)
    {
        try {
            DB::beginTransaction();
                $role = Role::create(['name' => $request->input('name')]);
                $role->syncPermissions($request->input('permission'));
            DB::commit();
            return response()->json(['status' =>true, 'message' => __('New Role has been registered successfully')]);
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
        return view('users::roles.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $role = Role::find($id);
            $permission = Permission::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all();
            $returnHTML = view('users::roles.edit')->with(['role' => $role,'permission' => $permission, 'rolePermissions' => $rolePermissions])->render();
            return response()->json(array('status' => true, 'html' => $returnHTML));
        } catch(Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()],422);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
                $role = Role::find($id);
                $role->name = $request->input('name');
                $role->save();
                $role->syncPermissions($request->input('permission'));
            DB::commit();
            return response()->json(['status' =>true, 'message' => __('New Role has been registered successfully')]);
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
            Role::where('id', $id)->delete();
            return response()->json(['status'=> true,'message'=> __('Role Information has been deleted successfully')]);
        } catch (Exception $e) {
            return response()->json(['status'=> false,'message'=> $e->getMessage()],422);
        }
    }
}
