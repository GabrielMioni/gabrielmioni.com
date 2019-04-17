<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('profile')) {
            Schema::create('profile', function (Blueprint $table) {
                $table->increments('id');
                $table->string('about_me')->nullable()->default(null);
                $table->string('avatar')->nullable()->default(null);
                $table->string('contact_email')->nullable()->default(null);
                $table->string('email')->nullable()->default(null);
                $table->string('github')->nullable()->default(null);
                $table->string('linkedin')->nullable()->default(null);
                $table->string('tag_line')->nullable()->default(null);
                $table->timestamps();
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
        Schema::dropIfExists('profile');
    }
}
