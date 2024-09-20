<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolesStudiesUserTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('roles')) {
            // Create table for storing roles
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('user_roles')) {
            // Create table for storing roles
            Schema::create('user_roles', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->references('id')->on('users')->onDelete('cascade');
                $table->integer('role_id')->unsigned()->references('id')->on('roles')->onDelete('cascade');
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('studies')) {
            // Create table for storing teams
            Schema::create('studies', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('display_name', 255)->nullable();
                $table->integer('user_id')->unsigned()->references('id')->on('users')->onDelete('cascade');
                $table->text('author');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('user_studies')) {
            // Create table for user invited into it.
            Schema::create('user_studies', function (Blueprint $table) {
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('study_id')->nullable();
                $table->foreign('study_id')->references('id')->on('studies')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['user_id', 'study_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('studies');
    }
}
