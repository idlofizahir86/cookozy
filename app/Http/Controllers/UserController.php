<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Http\Resources\RecipeResource;
use App\Http\Resources\RecipeDetailResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Kreait\Firebase\Firestore;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        $credentialPath = base_path('resources/credentials/firebase_credentials.json');
        $factory = (new Factory)->withServiceAccount($credentialPath);
        $this->firestore = $factory->createFirestore();

        // Terapkan middleware auth pada semua metode kecuali index dan show
        // $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $firestore = Firebase::firestore();
        $usersRef = $firestore->database()->collection('users')->documents();
        $users = [];

        foreach ($usersRef as $userRef) {
            $user = $userRef->data();
            $user['id'] = $userRef->id();
            $users[] = new UserResource($user);
        }

        return UserResource::collection($users);
    }

    public function show($id)
    {
        $firestore = Firebase::firestore();
        $userRef = $firestore->database()->collection('users')->document($id);
        $user = $userRef->snapshot()->data();
        $user['id'] = $id;

        return new UserResource($user);
    }

    // Implement other CRUD methods as needed
}
