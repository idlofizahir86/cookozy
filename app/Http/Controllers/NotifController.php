<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Http\Resources\NotifResource;
use Kreait\Firebase\Firestore;
use Illuminate\Support\Facades\Http;

class NotifController extends Controller
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
        $notifs = $firestore->database()->collection('notifications')->documents();
        $data = [];

        foreach ($notifs as $notif) {
            $notifData = $notif->data();
            $notifData['id'] = $notif->id(); // Menambahkan ID dokumen ke dalam data notif
            $data[] = new NotifResource($notifData);
        }

        return NotifResource::collection($data);
    }

}
