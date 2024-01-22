<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;

class RoleTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $maps = array(
            array(
                'id' => 1,
                'name' => 'Super Admin',
                'guard_name'=>'web'
            ),
            array(
                'id' => 2,
                'name' => 'Admin',
                'guard_name'=>'web'

            ),
            array(
                'id' => 3,
                'name' => 'User',
                'guard_name'=>'web'
            ),
            array(
                'id' => 4,
                'name' => 'Vendor',
                'guard_name'=>'web'

            )
        );

        $option_count = DB::table('roles')->count();
        if($option_count == 0)
        {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('roles')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::table('roles')->insert($maps);

        }else{

            foreach ($maps as $key=> $permission) {
                $payop = Role::where('id', $permission['id'])->first();

                if ($payop !== null) {
                    $payop->update(['name' => $permission['name']]);
                } else {
                    $payop = Role::create([
                        'id' => $permission['id'],
                        'name' => $permission['name'],
                    ]);
                }
            }
        }
    }
}
