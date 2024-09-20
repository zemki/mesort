<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewsletterToUserProfiles extends Migration
{
    public function up()
    {
        Schema::table('users_profiles', function (Blueprint $table) {
            $table->tinyInteger('newsletter')->default(0)->nullable();
        });
    }

    public function down()
    {
        Schema::table('users_profiles', function (Blueprint $table) {
        });
    }
}
