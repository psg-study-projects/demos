<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationUserJoinTable extends Migration
{
    public function up()
    {
        Schema::create('conversation_user', function (Blueprint $table) {

            $table->unsignedInteger('conversation_id')->unsigned();
            $table->unsignedInteger('user_id')->unsigned();

            $table->foreign('conversation_id')->references('id')->on('conversations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['conversation_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversation_user');
    }
}
