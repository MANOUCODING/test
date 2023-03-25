<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            ['nomComplet' => 'Info du pays', 'authorName' => 'MawuwonamTg', 'slug' => 'info-du-pays', 'wp_author_id' => 1  ], // 1
            ['nomComplet' => 'dutogoactu', 'authorName' => 'delomepub', 'slug' => 'dutogoactu', 'wp_author_id' => 2 ], // 0
        ];

        foreach ($datas as $data){
           Author::create($data);
        }
    }
}
