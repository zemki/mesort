<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudySortingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_sortings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sorting_id')->nullable()->unsigned()->references('id')->on('sortings')->onDelete('cascade');
            $table->integer('study_id')->nullable()->unsigned()->references('id')->on('studies')->onDelete('cascade');
            // info about the sorting, like number of circles or valutations min and max
            $table->text('details')->nullable();

            $table->timestamps();

            $table->foreign('study_id')->references('id')->on('studies');
            $table->foreign('sorting_id')->references('id')->on('sortings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_sortings');
    }
}
