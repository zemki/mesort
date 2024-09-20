<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('interview_id')->nullable()->unsigned()->references('id')->on('interviews')->onDelete('cascade');
            $table->integer('token_id')->nullable()->unsigned()->references('id')->on('token')->onDelete('cascade');
            $table->text('valutation');
            $table->integer('sorting_id')->nullable()->unsigned()->references('id')->on('token')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('interview_id')->references('id')->on('interviews');
            $table->foreign('token_id')->references('id')->on('tokens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_tokens');
    }
}
