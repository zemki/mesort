<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesInterviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_interview', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 100);
            $table->text('path');
            $table->string('size', 100);
            $table->integer('interview_id')->nullable()->unsigned()->references('id')->on('interviews')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('interview_id')->references('id')->on('interviews');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files_interview');
    }
}
