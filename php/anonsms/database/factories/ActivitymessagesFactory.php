<?php

use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Activitymessage;

$factory->define(App\Models\Activitymessage::class, function (Faker $faker) {

    $MAX = 20;
    $iter = 0;
    do {
        $sender = User::whereNotNull('topic_id')->inRandomOrder()->first();
        $receiver = User::where('topic_id',$sender->topic_id)->inRandomOrder()->first();
        if ($iter++ > $MAX) {
            throw new \Exception('Could not resolve');
        }
    } while ( $sender->id === $receiver->id );

    return [
        'sender_id'    => $sender->id,
        'receiver_id'  => $receiver->id,
        //'cslug'        => Activitymessage::makeCslug($sender,$receiver),
        'amcontent'    => $faker->paragraph(1,true),
    ];
});
