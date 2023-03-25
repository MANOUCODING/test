<?php

namespace App\Http\Controllers\api\backoffice;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request){

        $data = $request->all();

        //Validator check

        $validate = Validator::make($data, [
            'email' => 'required|string|unique:users',
            'telephone' =>'required|unique:users',
            'password' => 'required|string|min:5',
        ],
        [
        'required' => 'Le :attribute ou email est obligatoire.',
        'password.required' => 'Le :attribute est obligatoire.'
        ],
        [
        'username' => 'numéro de téléphone',
        'password' => 'mot de passe'
        ]);

        if($validate->fails()){
            return response()->json([
                'message'=> 'Erreur',
                'errors' => $validate->errors()
            ], 402);
        }



        $user = new User([
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password)
        ]);

        $user->save();

        //User Subgroup manager


        return response()->json(['message' => 'Utilisateur enregistré.'], 200);


    }

    public function login(Request $request)
    {
        if (filter_var($request->input('username'), FILTER_VALIDATE_EMAIL)) {
            $login_type = 'username';
        } else {
            $login_type = 'phone';
        }
        if ($login_type === 'username') {
            $validator = Validator::make($request->all(), [
                'username' => 'required|email',
                'password' => 'required|string|min:5',
            ], [
                'username.required' => ':attribute ou le numero de téléphone est obligatoire.',
                'password.required' => 'Le :attribute est obligatoire.'
            ],[
                'username' => 'L\'email',
                'password' => 'mot de passe'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors'=> $validator->errors(), 'status' => 401], 200);
            }

            $username = $request->input('username');
            $user = User::where('email', $username)->where('status', true)->first();;

            if (is_null($user)) {
                return response()->json(['error' => true,
                    'message' => 'Aucun utilisateur trouvé avec cet email.', 'status' => 422], 200);
            }

            if ($user->status === 0) {
                return response()->json(['error' => true,
                    'activate' => false,
                    'message' => 'Veuillez activer votre compte.', 'status' => 422], 200);
            }

            $credentials = array_merge(['email' => $request->input('username')], $request->only('password'));
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json([
                    'error' => true,
                    'errorType' => 'credentialsError',
                    'message' => 'Les identifiants fournis sont invalides(pas autoris(é)e).','status' => 422], 200);
            }
        }
        //Phone / Password Login
        if ($login_type === 'phone') {
            $validator = Validator::make($request->all(), [
                //'telephone_1' => 'required|regex:/(228)[0-9]{8}/',
                'username' => 'required|string|min:5',
                'password' => 'required|string|min:5',
            ], [
                'required' => 'Le :attribute ou l\'email est obligatoire.',
                'password.required' => 'Le :attribute est obligatoire.'
            ], [
                'username' => 'nom d\'utilisateur',
                'password' => 'mot de passe'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors'=> $validator->errors(), 'status' => 401], 200);
            }

            $user = User::where('username', $request->username)->first();

            if (is_null($user)) {
                return response()->json(['error' => true, 'message' => 'Aucun utilisateur trouvé avec ce nom.','status' => 422], 200);
            }

            if ($user->status === 0) {
                return response()->json(['error' => true,
                    'errorType' => 'activationError',
                    'message' => 'Veuillez activer votre compte.','status' => 422], 200);
            }

            if ($user->status === 2) {
                return response()->json(['error' => true,
                    'errorType' => 'suspended',
                    'message' => 'Votre compte est suspendu.','status' => 422], 200);
            }

            $credentials = array_merge(['username' => $request->input('username')], $request->only('password'));
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json(['error' => true,
                    'errorType' => 'credentialsError',
                    'message' => 'Les identifiants fournis sont invalides.','status' => 422], 200);
            }
        }

       
        return response()->json(['token' => $this->createNewToken($token), 'user' => $user, 'status' => 200], 200);
    }

    public function me()
    {
        $user = User::where('id', auth()->user()->id)
            ->firstOrFail();
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function getRole()
    {
        return response()->json([
            'status' => 200,
            'role' => auth()->user()->role_name
        ], 200);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }


    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Déconnexion reussie.']);
    }

    protected function createNewToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'status' => 200,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
        ])->header('Authorization', $token);
    }
}
