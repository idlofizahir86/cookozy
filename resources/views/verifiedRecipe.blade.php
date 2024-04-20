@extends('layouts.app')
<title>Cookozy | Recipe</title>

@section('content')


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    @if (App::environment('production'))
        <link href="{{ secure_url('css/welcome.css') }}" rel="stylesheet">
        <link href="{{ secure_url('css/app.css') }}" rel="stylesheet">
    @else
        <link href="{{ url('css/welcome.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
    @endif
    <style>
    /* Tambahkan gaya CSS di sini */

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
    }

    .stack {
    position: relative;
    }

    .verified-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #4caf50;
        color: #fff;
        padding: 5px 10px;
        border-radius: 20px;
    }

    .unverified-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #ff0000;
        color: #fff;
        padding: 5px 10px;
        border-radius: 20px;
    }

    .row {
        display: flex;
        align-items: center;
        padding: 10px 0;
    }

    .user-info {
        display: flex;
        align-items: center;
        padding: 10px;
        border-top: 1px solid #ddd;
    }

    .user-image {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .user-details {
        flex-grow: 1;
    }

    .user-name,
    .timestamp {
        margin: 0;
        color: #666;
    }

    .recipe-image {
        width: 100%;
        max-height: 720px;
        object-fit: cover;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .recipe {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .recipe-header {
        position: relative;
    }

    .image-container {
        position: relative;
    }


    .recipe-body {
        padding: 20px;
    }

    .recipe-title {
        font-size: 35px;
        margin-top: 0;
        color: #333;
    }

    .recipe-description {
        color: #555;
    }

    .section-title {
        font-size: 20px;
        color: #333;
    }

    .recipe-ingredients,
    .recipe-steps {
        margin-top: 20px;
    }

    .recipe-ingredients p,
    .recipe-steps ol {
        margin: 0;
        color: #555;
    }

    .recipe-steps ol {
        counter-reset: step-counter;
    }

    .recipe-steps ol li {
        counter-increment: step-counter;
        list-style-type: none;
        margin-bottom: 10px;
    }

    .recipe-steps ol li::before {
        content: counter(step-counter);
        background-color: #4caf50;
        color: #fff;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-block;
        text-align: center;
        line-height: 20px;
        margin-right: 10px;
    }



    </style>
</head>
<body>

    <div id="loadingIndicator" class="spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>


    <div class="container">

        <div class="recipe">
            <div class="recipe-header">
                <div class="image-container">
                    <img id="recipeImage" src="{{ $recipe['image_url'] }}" alt="Recipe Image" class="recipe-image">
                    @if($recipe['verified'])
                        <div class="verified-badge">Verified</div>
                    @else
                        <div class="unverified-badge">Unverified</div>
                    @endif
                </div>
                <div class="user-info">
                    <img src="{{ $recipe['user_image'] }}" alt="User Image" class="user-image">
                    <div class="user-details">
                        <p class="user-name">By {{ $recipe['user_name'] }}</p>
                        <p class="timestamp">Posted on {{ $recipe['timestamp'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="recipe mt-3">
            <div class="recipe-body">
                <h1 class="recipe-title">{{ $recipe['title'] }}</h1>
                <h2 class="section-title mt-2">Deskripsi:</h2>
                <p class="recipe-description">{!! nl2br(e($recipe['description'])) !!}</p>
            </div>
        </div>

        <div class="recipe mt-3">
            <div class="recipe-body">
                <div class="recipe-ingredients">
                    <h2 class="section-title">Bahan-bahan:</h2>
                    <p>{!! nl2br(e($recipe['ingredients'])) !!}</p>
                </div>
                <div class="recipe-steps">
                    <h2 class="section-title">Langkah-langkah:</h2>
                    <ol>
                        @foreach(explode("\r\n", $recipe['steps']) as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>

        <button id="verifyButton" class="btn btn-success btn-block mt-3">
            <i class="fas fa-check-circle mr-1"></i>Verified
        </button>

    </div>
        <script>
                // Ambil recipeId dari data yang digunakan untuk mengisi halaman
                const recipeId = window.location.pathname.split('/')[3];


                fetch(`/api/recipes/${recipeId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(recipe => {
                    console.log(recipe)
                })
                .catch(error => {
                    console.error('There was an error!', error);
                    alert('Error fetching recipe data. Please try again later.');
                });

                const verifyButton = document.getElementById('verifyButton');

                verifyButton.addEventListener('click', function(event) {
                    event.preventDefault();

                    verifSend();
                });

                function verifSend() {
                    // Kirim permintaan untuk mengubah status verified menjadi true
                    let baseUrl;

                    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                        // Lokal (Development)
                        baseUrl = 'http://localhost:8000';
                    } else {
                        // Produksi
                        baseUrl = 'https://cookozy.web.app'; // Ganti dengan URL produksi Anda
                    }
                    fetch(`${baseUrl}/api/recipes/verified/${recipeId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            verified: true,
                            // Sisipkan atribut lain yang ingin Anda perbarui
                        }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to update recipe');
                        }
                        alert('Recipe verified successfully!');
                        window.location.href = `${baseUrl}/admin`;
                    })
                    .catch(error => {
                        console.error('Error verifying recipe:', error);
                    });
                }
        </script>

        <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Sertakan script font awesome untuk ikon -->
    </body>


</html>


    @endsection

    @section('footer')
<div class="container-footer">
    <p>&copy; 2024 Your Company. All Rights Reserved.</p>
    <p>Development by Cookozy</p>
</div>
@endsection
