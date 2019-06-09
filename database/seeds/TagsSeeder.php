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
            'Php',
            'JavaScript',
            'Laravel',
            'React',
            'Vue',
        ];

        $data = [];

        foreach ($tags as $tag) {
            $data[] = ['tag'=> $tag, 'created_at' => sqlFormatDate(), 'updated_at' => sqlFormatDate(), ];
        }

        App\Tag::insert($data);
    }
}
