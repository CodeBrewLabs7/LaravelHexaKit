<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use DB;

class PermissionTableSeederTableSeeder extends Seeder
{
     /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        Model::unguard();

        $permissions = array(
                //Dashboard Page
                array('id'=>'1','name' => 'role-list','controller'=>'RoleController'),
                array('id'=>'2','name' => 'role-create','controller'=>'RoleController'),
                array('id'=>'3','name' => 'role-edit','controller'=>'RoleController'),
                array('id'=>'4','name' => 'role-delete','controller'=>'RoleController'),
                array('id'=>'5','name' => 'user-list','controller'=>'UsersController'),
                array('id'=>'6','name' => 'user-create','controller'=>'UsersController'),
                array('id'=>'7','name' => 'user-edit','controller'=>'UsersController'),
                array('id'=>'8','name' => 'user-delete','controller'=>'UsersController')
        );

        foreach ($permissions as $key=> $permission) {
           $permissions_array[]=array(
            'id' => $permission['id'],
            'name' => $permission['name'],
            'controller' => $permission['controller'],
            'guard_name' => 'web',
           );
        }

        $option_count = DB::table('permissions')->count();
        if($option_count == 0)
        {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('permissions')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::table('permissions')->insert($permissions_array);
        }
        else{

            foreach ($permissions_array as $key=> $permission) {
                $payop = Permission::where('name', $permission['name'])->first();

                if ($payop !== null) {
                    $payop->update(['name' => $permission['name'],'controller'=>$permission['controller']]);
                } else {
                    $payop = Permission::create([
                        'id' => $permission['id'],
                        'name' => $permission['name'],
                        'controller' => $permission['controller'],
                        'guard_name' => 'web',
                    ]);
                }
            }

        }
    }
}
