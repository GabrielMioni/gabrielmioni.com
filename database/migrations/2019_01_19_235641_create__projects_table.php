<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_column');
            $table->string('title')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);;
            $table->string('github')->nullable()->default(null);
            $table->string('wordpress')->nullable()->default(null);
            $table->string('documentation')->nullable()->default(null);
            $table->string('image_main')->nullable()->default(null);
            $table->string('image_main_ext')->nullable()->default(null);
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('projects');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
