<?php

use App\Http\Controllers\api\backoffice\VerifyUserRoleController;
use App\Http\Controllers\api\frontoffice\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'homePosts']);

//Route pour le composant du frontoffice

Route::get('/{slug}', [HomeController::class, 'category']);

Route::get('/auteurs/{slug}', [HomeController::class, 'authors']);

Route::get('/tags/{slug}', [HomeController::class, 'tags']);

//Routes pour les composants backoffice

Route::view('/admin/dashboard', 'includes.backoffice.backoffice');

Route::view('/admin/categories', 'includes.backoffice.backoffice');

Route::view('/publicateur/dashboard', 'includes.backoffice.backoffice');




