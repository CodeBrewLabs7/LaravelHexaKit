<?php

namespace Modules\Users\Services;
use Exception;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\Permission\Models\Permission;

class RoleService {

    public function list() {
        return Role::get();
    }

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

    public function store(array $roleData)
    {
        try {
            DB::beginTransaction();
                $role = Role::create(['name' => $roleData['name']]);
                $role->syncPermissions($roleData['permission']);
            DB::commit();
            return response()->json(['status' =>true, 'message' => __('New Role has been registered successfully')]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status'=> false,'message'=> $e->getMessage()], 422);
        }
    }

    public function edit($id) {
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

    public function update($id, $updateData) {
        try {
            DB::beginTransaction();
                $role = Role::find($id);
                $role->name = $updateData['name'];
                $role->save();
                $role->syncPermissions($updateData['permission']);
            DB::commit();
            return response()->json(['status' =>true, 'message' => __('New Role has been registered successfully')]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status'=> false,'message'=> $e->getMessage()], 422);
        }
    }

    public function destroy($id) {
        try {
            Role::where('id', $id)->delete();
            return response()->json(['status'=> true,'message'=> __('Role Information has been deleted successfully')]);
        } catch (Exception $e) {
            return response()->json(['status'=> false,'message'=> $e->getMessage()],422);
        }
    }
}
