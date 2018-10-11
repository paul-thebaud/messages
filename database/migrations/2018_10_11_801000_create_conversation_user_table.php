<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('conversation_user', function (Blueprint $table) {
            $table->uuid('conversation_id');
            $table->uuid('user_id');
            $table->primary(['conversation_id', 'user_id']);

            $table->string('nickname', 60)->nullable();
            $table->string('role', 10);

            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('conversation_user');
    }
}
