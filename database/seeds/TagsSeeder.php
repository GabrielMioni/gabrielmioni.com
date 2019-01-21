<?php

use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
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

        App\Tag::insert($data);
    }
}