<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesKeyframeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_keyframe', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->nullable()->unsigned()->references('id')->on('files')->onDelete('cascade');
            $table->integer('keyframe_id')->nullable()->unsigned()->references('id')->on('keyframe')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('file_id')->references('id')->on('files_interview');
            $table->foreign('keyframe_id')->references('id')->on('keyframes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files_keyframe');
    }
}
