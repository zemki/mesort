<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyInterviewPublicUrlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_interview_public_url', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->integer('study_id')->nullable()->unsigned()->references('id')->on('studies')->onDelete('cascade');
            $table->datetime('created_at');
            $table->datetime('first_opened_at')->nullable();
            $table->datetime('submitted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_interview_public_url');
    }
}
