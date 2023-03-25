<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleFichier;
use App\Models\ArticleTags;
use App\Models\Author;
use App\Models\Category;
use App\Models\Fichier;
use App\Models\Tags;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 44; $i++){

            $posts = Http::get("https://www.togoactualite.com/wp-json/wp/v2/posts/?per_page=100&page=$i")->json();

            foreach ($posts as  $value) {
            
                $authors = Author::where('visible', 1)->get();
    
                $categories = Category::where('visible', 1)->get();
    
                $files = Fichier::where('visible', 1)->get();
    
                $tags = Tags::where('visible', 1)->get();

               
                    foreach ($authors as $author) {

                        if ($author->wp_author_id === $value["author"]) {
    
                            $cat = Category::where('wp_category_id', $value["categories"][0])->where('visible', 1)->first();
                            
                            $post =  Article::create([
                                'title' => $value["title"]['rendered'],
                                'author_id' => $author->id,
                                'ogImage' => isset($value['yoast_head_json']["og_image"][0]['url']) ? str_replace("https://www.togoactualite.com/", "", $value['yoast_head_json']["og_image"][0]['url']) : "null",
                                'authorName' => $author->nomComplet,
                                'categoryName' => $cat->name,
                                'categorySlug' => $cat->slug,
                                'authorSlug' => $author->slug,
                                'content' => $value["content"]["rendered"],
                                'slug' => $value["slug"],
                                'status' => $value["status"] == 'publish' ? 1 : 0,
                                'commentStatus' => $value["comment_status"] == 'open' ? 1 : 0,
                                'viewsCount' => 100,
                                'commentsCount' => 0,
                                'date_publish' => $value["modified_gmt"],
                                'post_id' => $value["id"]
                            ]);
    
                            if($author->count === 0){
    
                                $author->count = 1;
    
                                $author->update();
    
                            }else{
    
                                $author->count++;
    
                                $author->update();
    
                            }
        
                            
        
                            foreach ($value["categories"] as  $result) {
    
                                foreach ($categories as  $category) {
    
                                    if ($category->wp_category_id === $result) {
    
                                        ArticleCategory::create([
                                            'article_id' => $post->id,
                                            'category_id' => $category->id
                                        ]);
    
                                        if($category->count === 0){
    
                                            $category->count = 1;
    
                                            $category->update();
    
                                        }else{
    
                                            $category->count++;
    
                                            $category->update();
    
                                        }
    
                                    }
    
                                }
                            }
        
                            
        
                            
        
                            foreach ($value['tags'] as  $result) {
    
                                foreach ($tags as  $tag) {
        
                                    if ($tag->tag_id === $result) {
        
                                        ArticleTags::create([
                                            'article_id' => $post->id,
                                            'tag_id' => $tag->id
                                        ]);
        
                                    }
        
                                }
                            }
        
                            
        
        
                            if(isset($value['yoast_head_json']["og_image"])){
        
                                foreach ($value['yoast_head_json']["og_image"] as  $filewp) {
        
                                    foreach ($files as  $file) {
            
                                        if ($file->picture_wp === $filewp['url']) {
            
                                            ArticleFichier::create([
                                                'article_id' => $post->id,
                                                'post_id' => $post->post_id,
                                                'fichier_id' => $file->id
                                            ]);
            
                                        }
            
                                    }
            
                                }
        
                            }
                        }
                    }

                
                
    
            }
    

        }
    }
}
