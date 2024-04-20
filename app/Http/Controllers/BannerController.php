<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Http\Resources\BannerResource;
use Kreait\Firebase\Firestore;
use Illuminate\Support\Facades\Http;

class BannerController extends Controller
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
        $banners = $firestore->database()->collection('banners')->documents();
        $data = [];

        foreach ($banners as $banner) {
            $bannerData = $banner->data();
            $bannerData['id'] = $banner->id(); // Menambahkan ID dokumen ke dalam data banner
            $data[] = new BannerResource($bannerData);
        }

        return BannerResource::collection($data);
    }

}
