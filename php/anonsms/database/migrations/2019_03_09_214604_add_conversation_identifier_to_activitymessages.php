<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConversationIdentifierToActivitymessages extends Migration
{
    public function up()
    {
        Schema::table('activitymessages', function (Blueprint $table) {
            $table->string('cslug')->after('slug')->comment('Slug to identify conversation, non-unique');
        });
    }

    public function down()
    {
        Schema::table('activitymessages', function (Blueprint $table) {
            $table->dropColumn(['cslug']);
        });
    }
}
