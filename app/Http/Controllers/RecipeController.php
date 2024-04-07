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

class RecipeController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        $credentialPath = base_path('resources\credentials\firebase_credentials.json');
        $factory = (new Factory)->withServiceAccount($credentialPath);
        $this->firestore = $factory->createFirestore();

        // Terapkan middleware auth pada semua metode kecuali index dan show
        // $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $firestore = Firebase::firestore();
        $recipes = $firestore->database()->collection('recipes')->documents();
        $data = [];

        foreach ($recipes as $recipe) {
            $recipeData = $recipe->data();
            $recipeData['id'] = $recipe->id(); // Menambahkan ID dokumen ke dalam data resep
            $data[] = new RecipeResource($recipeData);
        }

        return RecipeResource::collection($data);
    }

    public function show($id) {
        $firestore = Firebase::firestore();
        $recipeRef = $firestore->database()->collection('recipes')->document($id);
        $recipeSnapshot = $recipeRef->snapshot();

        if ($recipeSnapshot->exists()) {
            $recipeData = $recipeSnapshot->data();
            $recipeData['id'] = $recipeSnapshot->id();

            return new RecipeDetailResource($recipeData);
        } else {
            return response()->json(['message' => 'Recipe not found.'], 404);
        }
    }

    public function store(Request $request) {
        // Definisikan aturan validasi
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'image_url' => 'required',
            'ingredients' => 'required',
            'steps' => 'required'
        ];

        // Lakukan validasi
        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembalikan pesan error
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Ambil UID pengguna yang login
        $uid = $request['user_id'] = Auth::id(); // Menggunakan Auth dari Laravel untuk mendapatkan UID

        // Persiapkan data untuk dokumen baru
        $data = [
            'title' => $request->input('title'),
            'user_id' => $uid,
            'description' => $request->input('description'),
            'timestamp' => Carbon::now()->toDateTimeString(), // Timestamp saat ini
            'image_url' => $request->input('image_url'),
            'ingredients' => $request->input('ingredients'),
            'steps' => $request->input('steps'),
            'verified' => false
        ];

        // Tambahkan dokumen baru ke koleksi 'recipes' di Firestore
        $firestore = Firebase::firestore();
        $newRecipeRef = $firestore->database()->collection('recipes')->add($data);

        // Ambil ID dokumen yang baru ditambahkan
        $newRecipeId = $newRecipeRef->id();

        // Ambil data dari dokumen yang baru ditambahkan
        $newRecipeData = $newRecipeRef->snapshot()->data();

        // Persiapan respons JSON dengan pesan dan data
        $response = [
            'message' => 'Recipe created successfully',
            'recipe_id' => $newRecipeId,
            'recipe_data' => $newRecipeData
        ];

        return response()->json($response, 201);
    }


}
