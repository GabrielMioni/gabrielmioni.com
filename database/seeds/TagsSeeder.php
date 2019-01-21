<?php

use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /*protected function formatDate() {
        return date('Y-m-d H:i:s', time());
    }*/

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'php',
            'javascript',
            'laravel',
            'react',
            'vue',
        ];

        $data = [];

        foreach ($tags as $tag) {
            $data[] = ['tag'=> $tag, 'created_at' => sqlFormatDate(), 'updated_at' => sqlFormatDate(), ];
        }

        App\Tags::insert($data);

    }
}