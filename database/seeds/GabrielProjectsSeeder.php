<?php

use Illuminate\Database\Seeder;

class GabrielProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\GabrielProjects::class, 6)->create();
    }
}

