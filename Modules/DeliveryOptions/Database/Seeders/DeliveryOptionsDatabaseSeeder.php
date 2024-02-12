<?php

namespace Modules\DeliveryOptions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\DeliveryOptions\Entities\DeliveryOption;

class DeliveryOptionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $option_count = DB::table('delivery_options')->count();

        $shipping_options = array(
                array('id' => '1', 'path' => '', 'code' => 'shiprocket',  'title' => 'ShipRocket', 'status' => '0','test_mode'=>'1'),
                array('id' => '2', 'path' => '', 'code' => 'lalamove', 'title' => 'Lalamove', 'status' => '0','test_mode'=>'1'),
                array('id' => '3', 'path' => '', 'code' => 'dunzo', 'title' => 'Dunzo', 'status' => '0','test_mode'=>'1'),
                array('id' => '4', 'path' => '', 'code' => 'ahoy', 'title' => 'Ahoy', 'status' => '0','test_mode'=>'1'),
                array('id' => '5', 'path' => '', 'code' => 'shippo', 'title' => 'Shippo', 'status' => '0','test_mode'=>'1'),
                array('id' => '6', 'path' => '', 'code' => 'kwikapi', 'title' => 'KwikApi', 'status' => '0','test_mode'=>'1'),
                array('id' => '7', 'path' => '', 'code' => 'roadie', 'title' => 'Roadie', 'status' => '0','test_mode' => '1'),
                array('id' => '8', 'path' => '', 'code' => 'shipengine', 'title' => 'ShipEngine', 'status' => '0','test_mode' => '1'),
                array('id' => '9', 'path' => '', 'code' => 'd4b_dunzo', 'title' => 'D4B Dunzo', 'status' => '0','test_mode'=>'1'),
                array('id' => '10', 'path' => '', 'code' => 'borzo', 'title' => 'Borzoe', 'status' => '0','test_mode'=>'1'),
                );

                if($option_count == 0)
                {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('delivery_options')->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');

                DB::table('delivery_options')->insert($shipping_options);
                }
            else{
                        foreach ($shipping_options as $option) 
                        {
                        $find = DeliveryOption::where('code',$option['code'])->first();
                        if(!$find){
                         DeliveryOption::Create([
                            'title' => $option['title'],
                            'code' => $option['code'],
                            'path' => $option['path'],
                            'status' => $option['status'],
                        ]);
                        }
                    }
                }
    }

}
