<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;

class ConversationsSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('activitymessages')->truncate();
        DB::table('conversation_user')->truncate();
        DB::table('conversations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        for ( $i = 0 ; $i < 50 ; $i++ ) {
            $sender = User::whereNotNull('topic_id')
                          ->inRandomOrder()->first();

            $receiver = User::where('topic_id',$sender->topic_id)
                            //->where('id','!=', $sender->id)
                            ->inRandomOrder()->first();

            if ( $sender->id === $receiver->id ) {
                continue; // skip
            }
            $conversation = Conversation::create([]);
            $conversation->users()->attach([$sender->id,$receiver->id]);
        };
    }

    /*
    private function getRandomUserPair()
    {
        $MAX = 20;
        $iter = 0;
        do {
            $user1 = User::whereNotNull('topic_id')
                         ->inRandomOrder()->first();

            $user2 = User::where('topic_id',$user1->topic_id
                         ->where('id','!=', $user1->id)
                         ->inRandomOrder()->first();

            if ($iter++ > $MAX) {
                throw new \Exception('Could not resolve');
            }
        } while ( $user1->id === $user2->id );

        return ($user1,$user2);
    }
     */
}
