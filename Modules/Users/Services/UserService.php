<?php

namespace Modules\Users\Services;

use App\Models\User;
use Exception;
use Spatie\Permission\Models\Role;
use DB;

class UserService {

    public function list() {
        return User::where('role_id', '!=', '1')->orderBy("id","desc")->get();
    }

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

    public function store(array $userData)
    {
        try {
            DB::beginTransaction();
                $insertData = $userData;
                $insertData['password'] = bcrypt($userData['password']);
                $userDetail = User::create($insertData);
                $userDetail->assignRole($userData['role_id']);
            DB::commit();
            return response()->json(['status' =>true, 'message' => __('New User has been registered successfully')]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status'=> false,'message'=> $e->getMessage()], 422);
        }
    }

    public function edit($id) {
        try {
            $roles = Role::pluck('name','id')->all();
            $userDetail = User::where('id', $id)->firstOrFail();
            $returnHTML = view('users::edit')->with(['userDetail' => $userDetail,'roles' => $roles])->render();
            return response()->json(array('status' => true, 'html' => $returnHTML));
        } catch(Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'],422);
        }
    }

    public function update($id, $updateData) {
        try {
            unset($updateData['password']);
            DB::beginTransaction();
                if(isset($updateData['password']) && $updateData['password'] != '') {
                    $updateData['password'] = bcrypt($updateData['password']);
                }
                $userDetail = User::find($id);
                $userDetail->update($updateData);

                DB::table('model_has_roles')->where('model_id',$id)->delete();
                $userDetail->assignRole($updateData['role_id']);
            DB::commit();
            return response()->json(['status' =>true, 'message' => __('User information has been updated successfully')]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status'=> false,'message'=> $e->getMessage()], 422);
        }
    }

    public function destroy($id) {
        try {
            User::where('id', $id)->delete();
            return response()->json(['status'=> true,'message'=> __('User Information has been deleted successfully')]);
        } catch (Exception $e) {
            return response()->json(['status'=> false,'message'=> $e->getMessage()],422);
        }
    }
}
