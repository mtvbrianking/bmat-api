<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tag', function (Blueprint $table) {
            $table->integer('post_id')->unsigned();
            $table->string('tag_name', 20);

            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade');
            $table->foreign('tag_name')->references('name')->on('tags')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_tag', function (Blueprint $table) {
            $table->dropForeign(['post_id',]);
            $table->dropForeign(['tag_name',]);
        });

        Schema::dropIfExists('post_tag');
    }
}
