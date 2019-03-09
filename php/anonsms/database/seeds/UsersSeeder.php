<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class UsersSeeder extends Seeder
{
    protected $_roles;

    public function run()
    {
        $this->_roles = [
            'general_user' => Role::where('name','=','general-user')->firstOrFail(),
        ];

        $now = Carbon::now();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_user')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create();

        // --- Real users ---

        $user = User::create([
                        'username' => 'peter',
                        'phone' => '(717) 555-5123',
                        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                        'remember_token' => str_random(10),
                ]);
        $user = User::find($user->id);
        $user->attachRole($this->_roles['general_user']);

        $user = User::create([
                        'username' => 'ryan',
                        'phone' => '(310) 555-9876',
                        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                        'remember_token' => str_random(10),
                ]);
        $user = User::find($user->id);
        $user->attachRole($this->_roles['general_user']);


        // --- Fake users ---

        // 50 users 
        for ( $i = 0 ; $i < 50 ; $i++ ) {
            $fn = $faker->firstName;
            $ln = $faker->lastName;
            $phone = $faker->randomNumber(3,true).'555'.$faker->randomNumber(4,true);
            $user = User::create([
                        'username' => strtolower($fn.'.'.$ln),
                        'phone' => $phone,
                        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                        'remember_token' => str_random(10),
            ]);
            $user->attachRole($this->_roles['general_user']);
        };

    } // run()


}
