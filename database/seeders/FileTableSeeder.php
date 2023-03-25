<?php

namespace Database\Seeders;

use App\Models\Fichier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class FileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 94; $i++){

            $medias = Http::get("https://www.togoactualite.com/wp-json/wp/v2/media/?per_page=100&page=$i")->json();
        
            foreach( $medias as $media )
            {
                
                    $fichier =  Fichier::create([
                    'picture_link' => str_replace("https://www.togoactualite.com/", "", $media['guid']['rendered']),
                    'post_id' => $media['id'],
                    'picture_name' => $media['title']['rendered'],
                    'picture_wp' => $media['guid']['rendered'],
                    'date_publish' => $media["modified_gmt"]
                        
                    ]);

                
                
            }
        }
    }
}
