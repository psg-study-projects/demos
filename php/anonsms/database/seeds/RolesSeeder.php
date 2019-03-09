<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Permission;

class RolesSeeder extends Seeder
{
    
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $obj = new Role();
        $obj->name         = 'general-user';
        $obj->display_name = 'General User'; 
        $obj->description  = 'General User';
        $obj->save();
        unset($obj);

        
    }
}
