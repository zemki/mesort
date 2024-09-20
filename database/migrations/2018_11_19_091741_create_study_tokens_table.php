<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('study_id')->nullable()->unsigned()->references('id')->on('studies')->onDelete('cascade');
            $table->integer('token_id')->nullable()->unsigned()->references('id')->on('token')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('study_id')->references('id')->on('studies');
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
        Schema::dropIfExists('study_tokens');
    }
}
