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

    public function store(Request $request, $uid) {
    // Definisikan aturan validasi
    $rules = [
        'email' => 'required',
        'name' => 'required',
    ];

    // Lakukan validasi
    $validator = Validator::make($request->all(), $rules);

    // Jika validasi gagal, kembalikan pesan error
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    try {
        // Persiapkan data untuk dokumen baru dengan UID sebagai ID
        $data = [
            'email' => $request->input('email'),
            'nama' => $request->input('name'),
            'imageUrl' => "https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2Favatar.webp?alt=media&token=8fab0c44-9448-4858-a5fc-cd9ce0775526",
            'role' => "User"
        ];

        // Tambahkan dokumen baru ke koleksi 'users' di Firestore dengan UID sebagai ID
        $firestore = Firebase::firestore();
        $newUserRef = $firestore->database()->collection('users')->document($uid);
        $newUserRef->set($data);

        $newUserId = $newUserRef->id();

        $newUserData = $newUserRef->snapshot()->data();

        // Persiapan respons JSON dengan pesan dan data
        $response = [
            'message' => 'User created successfully',
            'user_id' => $newUserId,
            'user_data' => $newUserData
        ];

        return response()->json($response, 201);
        } catch (FirebaseException $e) {
            // Tangani kesalahan autentikasi Firebase
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
