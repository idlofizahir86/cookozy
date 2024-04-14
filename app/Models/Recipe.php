<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Google\Cloud\Firestore\FieldValue;
use Kreait\Firebase\Factory;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'author', 'timestamp', 'description', 'imageUrl', 'ingredients', 'steps', 'verified'
    ];

    public function getUserById($userId)
{
    $factory = (new Factory)->withServiceAccount(base_path('resources/credentials/firebase_credentials.json'));
    $firestore = $factory->createFirestore();

    $userDoc = $firestore->database()->collection('users')->document($userId)->snapshot();

    if ($userDoc->exists()) {
        return $userDoc->data();
    }

    return null;
}

    public function createOrUpdate(array $data)
    {
        $factory = (new Factory)->withServiceAccount(base_path('resources\credentials\firebase_credentials.json'));
        $firestore = $factory->createFirestore();

        $docRef = $firestore->collection('recipes')->document($data['id']);

        $docRef->set([
            'id' => $docRef->id(), //Mengambil ID Recipe dari generate Firebase
            'title' => $data['title'],
            'author' => $data['author'],
            'timestamp' => $data['timestamp'],
            'description' => $data['description'],
            'imageUrl' => $data['imageUrl'],
            'ingredients' => $data['ingredients'],
            'steps' => $data['steps'],
            'verified' => $data['verified']
        ]);

        return $docRef->snapshot()->data();
    }

    // Jika menggunakan database relational
    // public function writer() {
    //     return $this->belongsTo(User::class, 'user_id','id');
    // }

}
