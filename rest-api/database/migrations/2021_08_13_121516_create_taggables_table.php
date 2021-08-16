<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTaggablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('taggable_id'); //blog_id & page_id
            $table->string('taggable_type'); //blog_type & page_type
            $table->timestamps();

            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);

            $table->foreign('tag_id')->references('id')->on('tags')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::dropIfExists('taggables');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
