<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyUserTable extends Migration
{
    /*
        * Run the migrations.
        *
        * @return void
        */
    /*
       public function up()
       {
           Schema::create('study_users', function (Blueprint $table) {
               $table->increments('id');
               $table->integer('study_id')->nullable()->unsigned()->references('id')->on('studies')->onDelete('cascade');
               $table->integer('user_id')->nullable()->unsigned()->references('id')->on('token')->onDelete('cascade');
               $table->integer('permission_id')->nullable()->unsigned()->references('id')->on('token')->onDelete('cascade');
               $table->timestamps();

               $table->foreign('study_id')->references('id')->on('studies');
               $table->foreign('user_id')->references('id')->on('users');
               $table->foreign('permission_id')->references('id')->on('permissions');
           });
       }

       /**
        * Reverse the migrations.
        *
        * @return void
        */
    /*
    public function down()
    {
        Schema::dropIfExists('study_user');
    }*/
}
