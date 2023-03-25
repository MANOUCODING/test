<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Http::get('https://www.togoactualite.com/wp-json/wp/v2/categories/?per_page=50&page=1')->json();
        
        foreach( $categories as $result )
        {
            
            $categories =  Category::create([
                'name' =>  $result['name'],
                'slug' => $result['slug'],
                'count' => $result['count'],
                'parent' => intval($result['parent']),
                'date_publish' =>  now(),
                'wp_category_id' => intval($result['id']),
            ]);      

        }
    }
}
