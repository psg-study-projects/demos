<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Activitymessage;

class ActivitymessagesSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('activitymessages')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $conversations = Conversation::get();

        foreach ($conversations as $c) {
            for ( $i = 0 ; $i < rand(1,40) ; $i++ ) { // number of messages per conversation
                //$users = $c->users; // should be 2
                if ( rand(0,1) ) {
                    $sender = $c->users[0];
                    $receiver = $c->users[1];
                } else {
                    $sender = $c->users[1];
                    $receiver = $c->users[0];
                }
                $am = Activitymessage::create([
                    'conversation_id' => $c->id,
                    'sender_id'       => $sender->id,
                    'receiver_id'     => $receiver->id,
                    'amcontent'       => $faker->paragraph(1,true),
                ]);
            }
        }
    }
}
