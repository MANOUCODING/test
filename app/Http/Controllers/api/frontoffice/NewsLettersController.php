<?php

namespace App\Http\Controllers\api\frontoffice;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsLettersController extends BaseController
{
    
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
}
