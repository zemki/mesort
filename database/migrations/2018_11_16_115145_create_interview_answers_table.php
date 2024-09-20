<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('interview_id')->nullable()->unsigned()->references('id')->on('interviews')->onDelete('cascade');
            $table->integer('answer_id')->nullable()->unsigned()->references('id')->on('answers')->onDelete('cascade');
            $table->integer('question_id')->nullable()->unsigned()->references('id')->on('questions')->onDelete('cascade');
            $table->text('result')->nullable();
            $table->timestamps();

            $table->foreign('interview_id')->references('id')->on('interviews');
            $table->foreign('answer_id')->references('id')->on('answers');
            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_answers');
    }
}
