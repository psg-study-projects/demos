<?php

use Illuminate\Database\Seeder;

class ActivitymessagesSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('activitymessages')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- Faker ---

        factory(App\Models\Activitymessage::class, 100)->create()->each(function ($o) {
            $o->save();
        });
    }
}
