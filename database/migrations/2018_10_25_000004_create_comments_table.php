<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Could use polymorphic; since a comment is commentable (reply),
        // and a post is commentable as well
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->integer('post_id')->unsigned();
            $table->integer('comment_id')->unsigned()->nullable();
            $table->string('body', 255);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade');
            $table->foreign('comment_id')->references('id')->on('comments')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['post_id']);
            $table->dropForeign(['comment_id']);
        });

        Schema::dropIfExists('comments');
    }
}
