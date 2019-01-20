<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGabrielProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gabriel_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);;
            $table->string('github')->nullable()->default(null);
            $table->string('wordpress')->nullable()->default(null);
            $table->string('documentation')->nullable()->default(null);
            $table->string('image_main')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gabriel_projects');
    }
}
