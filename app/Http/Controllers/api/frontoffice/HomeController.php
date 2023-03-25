<?php

namespace App\Http\Controllers\api\frontoffice;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Message;
use App\Models\NewsLetter;
use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends BaseController
{
    public function home(){

        $categories = Category::where('visible', 1)->get();

        return view('welcome', ['categories' => $categories]);

    }

    public function tagsFooter()
    {

        $tags =  Tags::orderby('id', 'desc')->take(21)->get();

        return $this->sendResponse(['tags' => $tags , 'status' => 200], 'Informations sur le footer');

    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function category($slug)
    {
        $categories = Category::where('visible', 1)->get();

        if ($slug == 'about') {

            return view('frontoffice.about', ['categories' => $categories]);
           
        } elseif ($slug == 'publicites') {

            return view('frontoffice.publicites', ['categories' => $categories]);

        }elseif ($slug == 'forum') {

            return view('frontoffice.forum', ['categories' => $categories]);

        }elseif ($slug == 'contact') {

            return view('frontoffice.contact', ['categories' => $categories]);

        }elseif ($slug == 'infos-pratiques') {

            return view('frontoffice.infosPratiques', ['categories' => $categories]);

        }elseif ($slug == 'login') {

            return view('includes.backoffice.backoffice');

        }else{

             $author = Category::where('slug', $slug)->where('visible', 1)->first();

        $article = Article::where('slug', $slug)->where('visible', 1)->where("status", 1)->first();

        if (($author == null) && ( $article == null)) {

            return view('layouts.404');
       
        } elseif(($author !== null) && ( $article == null)) {

            

            $articles = Article::select(array("articles.id", "articles.title", "articles.slug"  , "articles.date_publish", "categories.name as categoryName",  "categories.slug as categorySlug" ,"articles.authorName", "articles.authorSlug","articles.ogImage"))
            ->where("articles.visible", 1)
            ->where("articles.status", 1)
            ->where("categories.id", $author->id)
            ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
            ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
            ->orderBy('articles.date_publish', 'desc')
            ->paginate(9);

            return view('frontoffice.category', ['articles' => $articles, 'category' => $author, 'categories' => $categories]);
           
    
        }else{

            $categories = Category::where('visible', 1)->get();

            $categoriesH = Article::select(array("categories.name", "categories.slug"))
            ->where("articles.visible", 1)
            ->where("articles.id", $article->id)
            ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
            ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
            ->get();

            $tags = Article::select(array("tags.name", "tags.id", "tags.slug"))
            ->where("articles.visible", 1)
            ->where("articles.id", $article->id)
            ->leftJoin("article_tags", "article_tags.article_id", "=", "articles.id")
            ->leftJoin("tags", "tags.id", "=", "article_tags.tag_id")
            ->get();

            $tagsCount = Article::select(array("tags.name" ,"tags.id", "tags.slug"))
            ->where("articles.visible", 1)
            ->where("articles.id", $article->id)
            ->leftJoin("article_tags", "article_tags.article_id", "=", "articles.id")
            ->leftJoin("tags", "tags.id", "=", "article_tags.tag_id")
            ->count();

            $files = Article::select(array("fichiers.picture_link"))
            ->where("articles.visible", 1)
            ->where("articles.id", $article->id)
            ->leftJoin("article_fichiers", "article_fichiers.article_id", "=", "articles.id")
            ->leftJoin("fichiers", "fichiers.id", "=", "article_fichiers.fichier_id")
            ->get();

            $previous = Article::select('title', 'slug')->where('id', $article->id -1)->where('visible', 1)->where("status", 1)->first();
        
            $next = Article::select('title', 'slug')->where('id', $article->id +1)->where('visible', 1)->where("status", 1)->first();

            if($previous && $next){

                $category = Article::select(array("categories.id"))
                ->where("articles.visible", 1)
                ->where("articles.id", $article->id)
                ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->first();
                
                $similars = Article::select(array("articles.id", "articles.title", "articles.slug"  , "articles.date_publish", "categories.name as categoryName",  "categories.slug as categorySlug" ,"articles.authorName", "articles.authorSlug","articles.ogImage"))
                ->where("articles.visible", 1)
                ->where("articles.status", 1)
                ->where("categories.id", $category->id)
                ->where("articles.id", "!=" ,$article->id)
                ->where("articles.id", "!=" ,$previous->id)
                ->where("articles.id", "!=" ,$next->id)
                ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->orderBy('articles.date_publish', 'desc')
                ->take(9)->get();

            }else{

                $category = Article::select(array("categories.id"))
                ->where("articles.visible", 1)
                ->where("articles.id", $article->id)
                ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->first();
                
                $similars = Article::select(array("articles.id", "articles.title", "articles.slug"  , "articles.date_publish", "categories.name as categoryName",  "categories.slug as categorySlug" ,"articles.authorName", "articles.authorSlug","articles.ogImage"))
                ->where("articles.visible", 1)
                ->where("articles.status", 1)
                ->where("categories.id", $category->id)
                ->where("articles.id", "!=" ,$article->id)
                ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->orderBy('articles.date_publish', 'desc')
                ->take(9)->get();

               

            }

            return view('frontoffice.article',[
                'article' => $article, 
                'categories' => $categories, 
                'files' => $files, 
                'tags' => $tags, 
                'tagsCount' => $tagsCount, 
                'previous' => $previous, 
                'next' => $next,
                'similars' => $similars,
                'categoriesH' => $categoriesH
                ]);


        }

        }
        
    }

    public function tags($slug)
    {
       

        $author = Tags::where('slug', $slug)->where('visible', 1)->first();

        if ($author == null) {

            return view('layouts.frontoffice.404');
       
        } else {

           
            $articles = Article::select(array("articles.id", "articles.title", "articles.slug", "articles.date_publish", "categories.name as categoryName" ,"articles.authorName", "articles.authorSlug","articles.ogImage"))
            ->where("articles.visible", 1)
            ->where("articles.status", 1)
            ->where("tags.id", $author->id)
            ->leftJoin("article_tags", "article_tags.article_id", "=", "articles.id")
            ->leftJoin("tags", "tags.id", "=", "article_tags.tag_id")
            ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
            ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
            ->orderBy('articles.date_publish', 'desc')
            ->paginate(9);

            return view('frontoffice.tags', ['articles' => $articles, 'category' => $author]);
           
    
        }

    }

    public function homePosts()
    {

        $articlesAlaUne = Article::select(array("articles.id", "articles.title", "articles.content" ,"articles.categoryName", "articles.categorySlug" ,"articles.slug", "articles.commentsCount", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->orderBy('articles.date_publish', 'desc')
        ->take(13)
        ->get();

        $politique = Article::select(array("articles.id", "articles.title", "articles.content", "articles.categoryName", "articles.categorySlug" ,"articles.slug", "articles.commentsCount", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("articles.categorySlug", 'politique')
        ->orderBy('articles.date_publish', 'desc')
        ->take(14)
        ->get();

        $international = Article::select(array("articles.id", "articles.title", "articles.content" ,"articles.categoryName", "articles.categorySlug" ,"articles.slug", "articles.commentsCount", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("articles.categorySlug", 'international')
        ->orWhere("articles.categorySlug", 'monde')
        ->orWhere("articles.categorySlug", 'afrique')
        ->orderBy('articles.date_publish', 'desc')
        ->take(10)
        ->get();

        $aNePasManquer = Article::select(array("articles.id", "articles.title", "articles.content" ,"articles.categoryName", "articles.categorySlug" ,"articles.slug", "articles.commentsCount", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("articles.categorySlug", 'a-ne-pas-rater-togo')
        ->orderBy('articles.date_publish', 'desc')
        ->take(6)
        ->get();

        $categories = Category::where('visible', 1)->get();
 
        return view('welcome', ['alaUne'=> $articlesAlaUne,'categories' => $categories,  'politique' => $politique, 'international' => $international, 'aNePasManquer' => $aNePasManquer]);

    }

    public function all()
    {

        $all = Article::select(array("articles.id", "articles.title", "articles.slug", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->take(14)
        ->get();

        return $this->sendResponse([
            'all' =>  $all, 
            'status' => 200
        ], 'Liste des articles publiés');


    }


    public function importants(){

        $important = Article::select(array("articles.id", "articles.title", "articles.slug", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("categories.id", 18)
        ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
        ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
        ->orderBy('articles.date_publish', 'desc')
        ->take(8)
        ->get();

        return $this->sendResponse([
            'important' =>  $important, 
            'status' => 200
        ], 'Liste des articles publiés');
    }

    public function aNePasManquer(){

        $aNePasManquerTogo = Article::select(array("articles.id", "articles.title", "articles.slug", "articles.commentsCount", "categories.slug as categorySlug"  , "articles.date_publish", "categories.name as categoryName" ,"articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("categories.id", 1)
        ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
        ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
        ->orderBy('articles.date_publish', 'desc')
        ->take(3)
        ->get();

        return $this->sendResponse([
            'aNePasManquerTogo' =>  $aNePasManquerTogo, 
            'status' => 200
        ], 'Liste des articles publiés');
    }

    public function sports()
    {

        $sport = Article::select(array("articles.id", "articles.title", "articles.slug", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("categories.id", 31)
        ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
        ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
        ->orderBy('articles.date_publish', 'desc')
        ->take(8)
        ->get();

        return $this->sendResponse([
            'sports' =>  $sport, 
            'status' => 200
        ], 'Liste des articles publiés');


    }

    public function societe()
    {

        $societe = Article::select(array("articles.id", "articles.title", "articles.slug", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("categories.id", 29)
        ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
        ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
        ->orderBy('articles.date_publish', 'desc')
        ->take(8)
        ->get();

        return $this->sendResponse([
            'societe' =>  $societe, 
            'status' => 200
        ], 'Liste des articles publiés');


    }

    public function opinion()
    {

        $opinion = Article::select(array("articles.id", "articles.title", "articles.slug", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("categories.id", 25)
        ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
        ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
        ->orderBy('articles.date_publish', 'desc')
        ->take(8)
        ->get();

        return $this->sendResponse([
            'opinion' =>  $opinion, 
            'status' => 200
        ], 'Liste des articles publiés');


    }

    public function faitsdivers()
    {

        $faitsDivers = Article::select(array("articles.id", "articles.title", "articles.slug", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("categories.id", 14)
        ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
        ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
        ->orderBy('articles.date_publish', 'desc')
        ->take(8)
        ->get();

        return $this->sendResponse([
            'faitsDivers' =>  $faitsDivers, 
            'status' => 200
        ], 'Liste des articles publiés');


    }

    public function fenetre()
    {

        $fenetres = Article::select(array("articles.id", "articles.title", "articles.slug", "articles.date_publish","articles.authorName","articles.authorSlug","articles.ogImage"))
        ->where("articles.visible", 1)
        ->where("articles.status", 1)
        ->where("categories.id", 15)
        ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
        ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
        ->orderBy('articles.date_publish', 'desc')
        ->take(8)
        ->get();

        return $this->sendResponse([
            'fenetres' =>  $fenetres, 
            'status' => 200
        ], 'Liste des articles publiés');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function authors($slug)
    {
       

        $author = Author::where('slug', $slug)->where('visible', 1)->first();

        if ($author == null) {

            return view('layouts.frontoffice.404');
       
        } else {

            

               
                $articles = Article::select(array("articles.id", "articles.title", "articles.slug"  , "articles.date_publish", "categories.name as categoryName",  "categories.slug as categorySlug" ,"articles.authorName", "articles.authorSlug","articles.ogImage"))
                ->where("articles.visible", 1)
                ->where("articles.status", 1)
                ->where("author_id", $author->id)
                ->leftJoin("article_categories", "article_categories.article_id", "=", "articles.id")
                ->leftJoin("categories", "categories.id", "=", "article_categories.category_id")
                ->orderBy('articles.date_publish', 'desc')
                ->paginate(9);

                return view('frontoffice.authors', ['articles' => $articles, 'category' => $author]);

           
    
        }

    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeNewsLetter(Request $request)
    {
        $datas = $request->all();

        $validator = Validator::make($datas, [
            'email' => ['required','string', 'email', 'max:255', 'unique:news_letters'],
        ],[
            'required' => 'L\' :attribute est obligatoire.',
        ], [
            'email' => 'email',
        ]);

       if ($validator->fails()) {

            return $this->sendResponse(['errors'=> $validator->errors(), 'status' => 401],'Erreur de validation');

        }
        if (isset($datas['email']) && !empty($datas['email'])) {

            $datas['slug'] = str_replace(' ', '-', strtolower($datas['email']));

        }

        $utlisateur = NewsLetter::create([
            'email' => $datas['email'],
            'slug' =>  $datas['slug'],
        ]);

        return $this->sendResponse(['utlisateur', $utlisateur, 'status' => 200], 'Vous êtes maintenant abonné à ce site".');
    }
   
    public function storeSms(Request $request )
    {

        $datas = $request->all();

        $validator = Validator::make($datas, [
            'email' => ['required','string', 'email', 'max:255', 'unique:users'],
            'telephone' => ['required','integer', ],
            'siteweb' => [],
            'nomComplet' => ['required', 'string', 'max:255'],
            'sujet' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:3'],
        ],[
            'required' => 'Votre :attribute est obligatoire.',
            'password.required' => 'Votre :attribute est obligatoire.',
            'nomComplet.required' => 'Vos :attribute est obligatoire.'
        ], [
            'email' => 'email',
            'telephone' => 'Télephone',
            'nomComplet' => 'nom et prénoms',
            'siteweb' => 'Site Web',
            'sujet' => 'Sujet',
            'content' => 'Contenu',
        ]);

        if ($validator->fails()) {

            return $this->sendResponse(['errors'=> $validator->errors(), 'status' => 401],'Erreur de validation');

        }


        $message = Message::create($datas);


        return $this->sendResponse(['message' => $message, 'status' => 200], 'Message envoyé avec succès');

    }

}
