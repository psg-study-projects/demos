<?php
use Illuminate\Database\Seeder;

use App\Models\Topic;

class TopicsSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('topics')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $o = Topic::create([ 'tname' => 'Red', ]);
        $o = Topic::create([ 'tname' => 'Blue', ]);
        $o = Topic::create([ 'tname' => 'Yellow', ]);
        $o = Topic::create([ 'tname' => 'Black', ]);
        $o = Topic::create([ 'tname' => 'White', ]);
        $o = Topic::create([ 'tname' => 'Pink', ]);
        $o = Topic::create([ 'tname' => 'Gray', ]);
        $o = Topic::create([ 'tname' => 'Cyan', ]);
        $o = Topic::create([ 'tname' => 'Green', ]);
        $o = Topic::create([ 'tname' => 'Purple', ]);
        $o = Topic::create([ 'tname' => 'Orange', ]);

        // --- Faker ---

        /*
        factory(App\Models\Topic::class, 100)->create()->each(function ($f) {
            $f->save();
        });
         */
    }
}
