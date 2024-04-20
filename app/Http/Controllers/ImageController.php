<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Google\Cloud\Firestore\FirestoreClient;
use Session;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $expiresAt = new \DateTime('tomorrow');
        $imageReference = app('firebase.storage')->getBucket()->object("Images/defT5uT7SDu9K5RFtIdl.png");

        if ($imageReference->exists()) {
          $image = $imageReference->signedUrl($expiresAt);
        } else {
          $image = null;
        }

        return view('img',compact('image'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5000',
    ]);

    $image = $request->file('image');

    // Simpan gambar sementara di folder lokal Laravel
    $localFolder = public_path('firebase-temp-uploads') . '/';
    $extension = $image->getClientOriginalExtension();
    $fileName = time() . '.' . $extension;
    $image->move($localFolder, $fileName);

    // Unggah gambar ke Firebase Storage
    $firebaseStoragePath = 'recipes/';
    $uploadedFile = fopen($localFolder . $fileName, 'r');
    app('firebase.storage')->getBucket()->upload($uploadedFile, ['name' => $firebaseStoragePath . $fileName]);

    // Dapatkan URL gambar dari Firebase Storage
    $firebaseStorageUrl = "https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/recipes%2F" . $fileName;

    // Lakukan GET untuk mendapatkan downloadTokens
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $firebaseStorageUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        // Jika ada kesalahan saat melakukan permintaan GET
        return response()->json(['error' => 'Failed to retrieve downloadTokens'], 500);
    } else {
        // Ubah respons menjadi array
        $responseData = json_decode($response, true);

        // Jika respons berisi downloadTokens
        if (isset($responseData['downloadTokens'])) {
            $downloadToken = $responseData['downloadTokens'];

            // Gabungkan token unduhan ke dalam URL gambar
            $imageUrlToken = $firebaseStorageUrl . "?alt=media&token=" . $downloadToken;

            // Hapus file gambar sementara dari folder lokal Laravel
            unlink($localFolder . $fileName);

            // Berikan respons JSON dengan URL gambar yang telah diperbarui
            return response()->json(['image_url' => $imageUrlToken], 200);
        } else {
            // Jika respons tidak berisi downloadTokens
            return response()->json(['error' => 'Failed to retrieve downloadTokens'], 500);
        }
    }
}

    public function deleteImage(Request $request)
    {
        $request->validate([
            'image_url' => 'required|url',
        ]);

        $imageUrl = $request->input('image_url');

        // Mendapatkan nama file dari URL gambar
        $fileName = basename($imageUrl);

        // Hapus file dari Firebase Storage
        Storage::disk('firebase')->delete('recipes/' . $fileName);

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeImgStatic(Request $request)
    {
        //
        $request->validate([
          'image' => 'required',
        ]);
        $input = $request->all();
        $image = $request->file('image'); //image file from frontend

        $student   = app('firebase.firestore')->database()->collection('Images')->document('defT5uT7SDu9K5RFtIdl');
        $firebase_storage_path = 'Images/';
        $name     = $student->id();
        $localfolder = public_path('firebase-temp-uploads') .'/';
        $extension = $image->getClientOriginalExtension();
        $file      = $name. '.' . $extension;
        if ($image->move($localfolder, $file)) {
          $uploadedfile = fopen($localfolder.$file, 'r');
          app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
          //will remove from local laravel folder
          unlink($localfolder . $file);
          Session::flash('message', 'Succesfully Uploaded');
        }
        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $imageDeleted = app('firebase.storage')->getBucket()->object("Images/defT5uT7SDu9K5RFtIdl.png")->delete();
        Session::flash('message', 'Image Deleted');
        return back()->withInput();
    }
}
