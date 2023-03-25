<?php

namespace App\Http\Controllers\api\backoffice;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Category;
use App\Models\Fichier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryCount = Category::where("visible", 1)->count();

        if ($categoryCount == 0) {

            return $this->sendResponse(['categoryCount'=>$categoryCount, 'status' => 401], 'Aucune catégorie n\'est enregistrée.');

        } else {

            $categories = Category::query();

            if(request('search')){

                $categories = DB::table("categories") ->select(array("categories.id", "categories.name", "categories.slug" , "categories.updated_at" , "categories.count"))
                ->where('categories.visible', '=', 1)
                ->where('categories.name', 'like', '%'. request('search') . '%')
                ->orWhere('categories.slug', 'like', '%'. request('search') . '%')
                ->orWhere('categories.updated_at', 'like', '%'. request('search') . '%')
                ->orWhere('categories.count', 'like', '%'. request('search') . '%')
                ->orderBy('categories.count', 'desc')
                ->paginate(10);

            }else{

                $categories = DB::table("categories") ->select(array("categories.id", "categories.name", "categories.slug" , "categories.updated_at" , "categories.count"))
                ->where('categories.visible', '=', 1)
                ->orderBy('categories.count', 'desc')
                ->paginate(10);

            }

            return $this->sendResponse(['categories'=>$categories, 'categoryCount'=>$categoryCount, 'status' => 200], 'Liste de toutes les categories.');
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datas = $request->all();

        $validator = Validator::make($datas, [
            'name' => ['required', 'string', 'unique:categories' ,'max:255'],
        ],[
            'name.required' => 'La :attribute est obligatoire.'
        ], [
            'name' => 'catégorie'
        ]);

        if ($validator->fails()) {

            return $this->sendResponse(['errors'=> $validator->errors(), 'status' => 401],'Erreur de validation');

        }

        if (isset($datas['name']) && !empty($datas['name'])) {

            $datas['slug'] = str_replace(' ', '-', strtolower($datas['name']));

        }

        $category = Category::create([
            'name' => $datas['name'],
            'slug' =>  $datas['slug'],
        ]);

        return $this->sendResponse(['category' => $category, 'status' => 200 ], 'Votre catégorie a ete créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {


            $category = Category::where('slug', $slug)->where("visible", 1)->first();

            if($category == null){

                return $this->sendResponse( ['category' => $category, 'status' => 401 ], 'Aucune categorie n\'est trouvée.');

            }else{

                return $this->sendResponse( ['category' => $category, 'status' => 200 ], 'Votre catégorie a été selectionnée.');

            }

           

        } catch (ModelNotFoundException $modelNotFoundException){

            return $this->sendError('Aucune catégrie trouvée.');

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $datas = $request->all();

        $validator = Validator::make($datas, [
            'name' => ['required', 'string', 'max:255'],
        ],[
            'name.required' => 'La :attribute est obligatoire.'
        ], [
            'name' => 'catégorie'
        ]);

        if ($validator->fails()) {

            return $this->sendResponse(['errors'=> $validator->errors(), 'status' => 401],'Cette categorie existe déjà');

        }

        if (isset($datas['name']) && !empty($datas['name'])) {

            $datas['slug'] = str_replace(' ', '-', strtolower($datas['name']));

        }

        $category = Category::where('slug', $slug)->where("visible", 1)->first();

        $category->update($datas);

        return $this->sendResponse(['category' => $category, 'status' => 200 ], 'Votre catégorie a ete modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = Category::where('slug', $slug)->where("visible", 1)->first();

        $articles = ArticleCategory::where('category_id', '=', $category->id)->where("visible", 1)->get();

        foreach ($articles as $article) {

            $medias = Fichier::where('article_id', '=', $article->id)->where("visible", 1);

            $medias->visible = 0;

            $medias->update();

            $article->visible = 0;

            $info = Article::where('id', '=', $article->article_id)->where("visible", 1);

            $info->visible = 0;

            $info->update();

            $article->update();
        }


        $category->visible = 0;

        $category->update();

        return $this->sendResponse(['category' => $category, 'status' => 200 ], 'Votre catégorie a ete supprimée avec succès.');
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($slug)
    {
        $categoryCount = Category::where('visible', 1)->count();

        $author = Category::where('slug', $slug)->where('visible', 1)->first();

        if ($author == null) {

            return $this->sendResponse([ 'slug' => $slug, 'author' => 0, 'articleCount' => 0, 'articlePublishCount' => 0, 'articleNotPublishCount' => 0, 'status' => 404], 'Cette catégorie n\'existe pas');
       
        } else {

            if ($categoryCount == 0) {

                return $this->sendResponse([ 'slug' => $slug,'author' => $author->name,'articleCount' => 0, 'articlePublishCount' => 0, 'articleNotPublishCount' => 0, 'status' => 401], 'Aucune catégorie n\'est enregistrée.');
    
            } else {
    
                $articleCount = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                ->where("articles.visible", 1)
                ->where('article_categories.category_id', $author->id)
                ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->count();
    
                $articlePublishCount = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                ->where("articles.visible", 1)
                ->where("articles.status", 1)
                ->where('article_categories.category_id', $author->id)
                ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->count();
    
                $articleNotPublishCount = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                ->where("articles.visible", 1)
                ->where("articles.status", 0)
                ->where('article_categories.category_id', $author->id)
                ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->count();
    
                if ($articleCount == 0) {
        
                    return $this->sendResponse(['slug' => $slug,'author' => $author->name,'articleCount' => $articleCount, 'articlePublishCount' => $articlePublishCount, 'articleNotPublishCount' => $articleNotPublishCount , 'status' => 401], 'Aucun article de cette catégorie n\'est enregistré.');
        
                }else{
                   
                    if ($articleCount == 0) {
            
                        return $this->sendResponse(['slug' => $slug,'author' => $author->name,'articleCount' => $articleCount, 'articlePublishCount' => $articlePublishCount, 'articleNotPublishCount' => $articleNotPublishCount, 'status' => 401], 'Aucun article de cette catégorie n\'est enregistré.');
            
                    }else{

                        if(request('search')){

                            $articlePublish = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                            ->where("articles.visible", 1)
                            ->where('articles.title', 'like', '%'. request('search') . '%')
                            ->orWhere('articles.slug', 'like', '%'. request('search') . '%')
                            ->orWhere('articles.date_publish', 'like', '%'. request('search') . '%')
                            ->orWhere('categories.name', 'like', '%'. request('search') . '%')
                            ->where('article_categories.category_id', $author->id)
                            ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                            ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                            ->orderBy('articles.date_publish', 'desc')
                            ->paginate(10);
            
                        }else{
            
                            $articlePublish = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                            ->where("articles.visible", 1)
                            ->where('article_categories.category_id', $author->id)
                            ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                            ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                            ->orderBy('articles.date_publish', 'desc')
                            ->paginate(10);
            
                        }
    
                       
    
                        return $this->sendResponse(['slug' => $slug, 'author' => $author->name ,'articleCount' => $articleCount, 'articlePublishCount' => $articlePublishCount, 'articleNotPublishCount' => $articleNotPublishCount, 'articlePublish' => $articlePublish, 'status' => 200], 'Liste des articles publiés');
                    }
                }
            }
        }
                
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function publish($slug)
    {
        $categoryCount = Category::where('visible', 1)->count();

        $author = Category::where('slug', $slug)->where('visible', 1)->first();

        if ($author == null) {

            return $this->sendResponse([ 'slug' => $slug, 'author' => 0, 'articleCount' => 0, 'articlePublishCount' => 0, 'articleNotPublishCount' => 0, 'status' => 404], 'Cette catégorie n\'existe pas');
       
        } else {

            if ($categoryCount == 0) {

                return $this->sendResponse([ 'slug' => $slug,'author' => $author->name,'articleCount' => 0, 'articlePublishCount' => 0, 'articleNotPublishCount' => 0, 'status' => 401], 'Aucune catégorie n\'est enregistrée.');
    
            } else {
    
                $articleCount = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                ->where("articles.visible", 1)
                ->where('article_categories.category_id', $author->id)
                ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->count();
    
                $articlePublishCount = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                ->where("articles.visible", 1)
                ->where("articles.status", 1)
                ->where('article_categories.category_id', $author->id)
                ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->count();
    
                $articleNotPublishCount = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                ->where("articles.visible", 1)
                ->where("articles.status", 0)
                ->where('article_categories.category_id', $author->id)
                ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->count();
    
                if ($articleCount == 0) {
        
                    return $this->sendResponse(['slug' => $slug,'author' => $author->name,'articleCount' => $articleCount, 'articlePublishCount' => $articlePublishCount, 'articleNotPublishCount' => $articleNotPublishCount, 'status' => 401 ], 'Aucun article de cette catégorie n\'est enregistré.');
        
                }else{
                   
                    if ($articlePublishCount == 0) {
            
                        return $this->sendResponse(['slug' => $slug,'author' => $author->name,'articleCount' => $articleCount, 'articlePublishCount' => $articlePublishCount, 'articleNotPublishCount' => $articleNotPublishCount, 'status' => 401], 'Aucun article publié de cette catégorie n\'est enregistré.');
            
                    }else{
    
                        if(request('search')){

                            $articlePublish = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                            ->where("articles.visible", 1)
                            ->where("articles.status", 1)
                            ->where('articles.title', 'like', '%'. request('search') . '%')
                            ->orWhere('articles.slug', 'like', '%'. request('search') . '%')
                            ->orWhere('articles.date_publish', 'like', '%'. request('search') . '%')
                            ->orWhere('categories.name', 'like', '%'. request('search') . '%')
                            ->where('article_categories.category_id', $author->id)
                            ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                            ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                            ->orderBy('articles.date_publish', 'desc')
                            ->paginate(10);
            
                        }else{
            
                            $articlePublish = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                            ->where("articles.visible", 1)
                            ->where("articles.status", 1)
                            ->where('article_categories.category_id', $author->id)
                            ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                            ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                            ->orderBy('articles.date_publish', 'desc')
                            ->paginate(10);
            
                        }

                        
    
                        return $this->sendResponse(['slug' => $slug, 'author' => $author->name ,'articleCount' => $articleCount, 'articlePublishCount' => $articlePublishCount, 'articleNotPublishCount' => $articleNotPublishCount, 'articlePublish' => $articlePublish, 'status' => 200], 'Liste des articles publiés');
                    }
                }
            }
        }
                
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notPublish($slug)
    {
        $categoryCount = Category::where('visible', 1)->count();

        $author = Category::where('slug', $slug)->where('visible', 1)->first();

        if ($author == null) {

            return $this->sendResponse([ 'slug' => $slug, 'author' => 0, 'articleCount' => 0, 'articlePublishCount' => 0, 'articleNotPublishCount' => 0,'status' => 404], 'Cette catégorie n\'existe pas');
       
        } else {

            if ($categoryCount == 0) {

                return $this->sendResponse([ 'slug' => $slug,'author' => $author->name,'articleCount' => 0, 'articlePublishCount' => 0, 'articleNotPublishCount' => 0, 'status' => 401], 'Aucune catégorie n\'est enregistrée.');
    
            } else {
    
                $articleCount = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                ->where("articles.visible", 1)
                ->where('article_categories.category_id', $author->id)
                ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->count();
    
                $articlePublishCount = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                ->where("articles.visible", 1)
                ->where("articles.status", 1)
                ->where('article_categories.category_id', $author->id)
                ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->count();
    
                $articleNotPublishCount = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                ->where("articles.visible", 1)
                ->where("articles.status", 0)
                ->where('article_categories.category_id', $author->id)
                ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->count();
    
                if ($articleCount == 0) {
        
                    return $this->sendResponse(['slug' => $slug,'author' => $author->name,'articleCount' => $articleCount, 'articlePublishCount' => $articlePublishCount, 'articleNotPublishCount' => $articleNotPublishCount,'status' => 401 ], 'Aucun article de cette catégorie n\'est enregistré.');
        
                }else{
                   
                    if ($articleNotPublishCount == 0) {
            
                        return $this->sendResponse(['slug' => $slug,'author' => $author->name,'articleCount' => $articleCount, 'articlePublishCount' => $articlePublishCount, 'articleNotPublishCount' => $articleNotPublishCount, 'status' => 401], 'Aucun brouillon de cette catégorie n\'est enregistré.');
            
                    }else{
    
                        if(request('search')){

                            $articlePublish = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                            ->where("articles.visible", 1)
                            ->where("articles.status", 0)
                            ->where('articles.title', 'like', '%'. request('search') . '%')
                            ->orWhere('articles.slug', 'like', '%'. request('search') . '%')
                            ->orWhere('articles.date_publish', 'like', '%'. request('search') . '%')
                            ->orWhere('categories.name', 'like', '%'. request('search') . '%')
                            ->where('article_categories.category_id', $author->id)
                            ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                            ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                            ->orderBy('articles.date_publish', 'desc')
                            ->paginate(10);
            
                        }else{
            
                            $articlePublish = DB::table("articles") ->select(array("articles.id", "articles.title", "articles.slug" , "articles.visible" ,"articles.status"  , "articles.date_publish", "article_categories.category_id", "categories.name as categoryName" ))
                            ->where("articles.visible", 1)
                            ->where("articles.status", 0)
                            ->where('article_categories.category_id', $author->id)
                            ->leftJoin("article_categories", "article_categories.id", "=", "articles.id")
                            ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                            ->orderBy('articles.date_publish', 'desc')
                            ->paginate(10);
            
                        }
    
                        return $this->sendResponse(['slug' => $slug, 'author' => $author->name ,'articleCount' => $articleCount, 'articlePublishCount' => $articlePublishCount, 'articleNotPublishCount' => $articleNotPublishCount, 'articlePublish' => $articlePublish, 'status' => 200], 'Liste des articles publiés');
                    }
                }
            }
        }
                
    }
}