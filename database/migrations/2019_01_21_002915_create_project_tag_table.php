<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('project_tag')) {
            Schema::create('project_tag', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('project_id');
                $table->unsignedInteger('tag_id');
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('projects_tag');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
