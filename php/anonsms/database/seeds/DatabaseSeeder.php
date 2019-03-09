<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            //UsstatesTableSeeder::class,
            //WeekperiodsTableSeeder::class,
            //MonthperiodsTableSeeder::class,

            RolesSeeder::class,

            TopicsSeeder::class,
            UsersSeeder::class,

        ]);
    }
}
