<?php

namespace Database\Seeders;

use App\Models\Tags;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 10; $i++){

            //Exporter les Tags 01
            $tags = Http::get('https://www.togoactualite.com/wp-json/wp/v2/tags?per_page=100&page='.$i)->json();

            foreach ($tags as  $value) {

                $post =  Tags::create([
                    'name' => $value["name"],
                    'count' => $value["count"],
                    'slug' => $value["slug"],
                    'tag_id'=> $value["id"],
                ]);

            }

        }
    }
}
