<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitymessagesTable extends Migration
{
    public function up()
    {
        Schema::create('activitymessages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid')->unique();
            $table->string('slug')->unique();

            $table->unsignedInteger('sender_id')->comment("FKID to [users] table identifying sender");
            $table->unsignedInteger('receiver_id')->comment("FKID to [users] table identifying receiver");
            $table->longtext('amcontent')->nullable()->comment("Message content");

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activitymessages');
    }
}
