<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssociateUserWithATopic extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('topic_id')->nullable()->after('username')->comment('FKID to [topics] table');
            $table->foreign('topic_id')->nullable()->references('id')->on('topics');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn(['topic_id']);
        });
    }
}
