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
        $credentialPath = base_path('resources/credentials/firebase_credentials.json');
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
        // $uid = $request['user_id'] = Auth::id(); // Menggunakan Auth dari Laravel untuk mendapatkan UID

        // Persiapkan data untuk dokumen baru
        $data = [
            'title' => $request->input('title'),
            'user_id' => $request->input('user_id'),
            'description' => $request->input('description'),
            'timestamp' => Carbon::now()->toDateTimeString(), // Timestamp saat ini
            'image_url' => $request->input('image_url'),
            'ingredients' => $request->input('ingredients'),
            'steps' => $request->input('steps'),
            'type' => $request->input('type'),
            'level' => $request->input('level'),
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


public function update(Request $request, $id)
{
    // Validasi request
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        // Sesuaikan dengan atribut lain yang ingin Anda perbarui
    ]);

    // Dapatkan UID pengguna yang sedang login
    $uid = Auth::id();

    // Konfigurasi Firebase menggunakan credentials
    $serviceAccount = base_path('resources/credentials/firebase_credentials.json');
    $factory = (new Factory)->withServiceAccount($serviceAccount);
    $this->firestore = $factory->createFirestore();


    // Dapatkan dokumen resep dari Firestore berdasarkan ID
    $firestore = Firebase::firestore();
    $recipeRef = $firestore->database()->collection('recipes')->document($id);
    $recipeSnapshot = $recipeRef->snapshot();

    // Periksa apakah dokumen ditemukan
    if (!$recipeSnapshot->exists()) {
        return response()->json(['error' => 'Recipe not found'], 404);
    }

    // Dapatkan data resep
    $recipeData = $recipeSnapshot->data();

    // dd($recipeData);
    // // Periksa apakah pengguna yang sedang login memiliki akses untuk memperbarui resep
    // if ($recipeData['user_id'] !== $uid) {
    //     $message = "Unauthorized access: User $uid attempted to update recipe with ID $id owned by user {$recipeData['user_id']}.";
    //     \Illuminate\Support\Facades\Log::info($message);
    //     return response()->json([
    //         'error' => 'Unauthorized',
    //         'message' => 'User is not authorized to update this recipe.',
    //         'user_id_attempted' => $uid,
    //         'user_id_recipe_owner' => $recipeData['user_id']
    //     ], 401);
    // }

    // Perbarui data resep dengan data yang diterima dari request
    $updatedData = array_merge($recipeData, $validatedData);

    // Simpan data yang diperbarui kembali ke Firestore
    $recipeRef->set($updatedData);

    // Respon sukses
    return response()->json(['message' => 'Recipe updated successfully']);
}

    public function destroy(Request $request, $id)
    {
        // Dapatkan UID pengguna yang sedang login
        // $uid = Auth::id();

        // Dapatkan dokumen resep dari Firestore berdasarkan ID
        $firestore = Firebase::firestore();
        $recipeRef = $firestore->database()->collection('recipes')->document($id);
        $recipeSnapshot = $recipeRef->snapshot();

        // Periksa apakah dokumen ditemukan
        if (!$recipeSnapshot->exists()) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        // Dapatkan data resep
        $recipeData = $recipeSnapshot->data();

        // Periksa apakah pengguna yang sedang login memiliki akses untuk menghapus resep
        // if ($recipeData['user_id'] !== $uid) {
        //     $message = "Unauthorized access: User $uid attempted to delete recipe with ID $id owned by user {$recipeData['user_id']}.";
        //     \Illuminate\Support\Facades\Log::info($message);
        //     return response()->json([
        //         'error' => 'Unauthorized',
        //         'message' => 'User is not authorized to delete this recipe.',
        //         'user_id_attempted' => $uid,
        //         'user_id_recipe_owner' => $recipeData['user_id']
        //     ], 401);
        // }

        // Hapus dokumen resep dari Firestore
        $recipeRef->delete();

        // Respon sukses
        return response()->json(['message' => 'Recipe deleted successfully']);
    }


    public function edit($id)
    {
        // Inisialisasi Firestore
        $db = new FirestoreClient();

        // Ambil data resep berdasarkan ID dari Firestore
        $recipeRef = $db->collection('recipes')->document($id);
        $recipe = $recipeRef->snapshot()->data();

        // Pastikan pengguna memiliki izin untuk mengedit resep
        if ($recipe['user_id'] != auth()->id()) {
            return abort(403); // Forbidden
        }

        // Kirimkan data resep ke view edit.blade.php
        return view('recipes.edit', compact('recipe'));
    }

}
