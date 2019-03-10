<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkActivitymessageToConversations extends Migration
{
    public function up()
    {
        Schema::table('activitymessages', function (Blueprint $table) {
            $table->unsignedInteger('conversation_id')->nullable()->after('slug')->comment('FKID to [conversations] table');
            $table->foreign('conversation_id')->nullable()->references('id')->on('conversations');
        });
    }

    public function down()
    {
        Schema::table('activitymessages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropColumn(['conversation_id']);
        });
    }
}
