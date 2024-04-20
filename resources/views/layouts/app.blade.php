<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cookozy') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        #app {
            /* display: flex;
            flex-direction: column;
            min-height: 100vh; */
        }

        main {
            flex-grow: 1; /* Konten utama akan mengisi sisa ruang */
        }

        footer {
            margin-top: auto;
        }


    </style>
    @if (App::environment('production'))
        <link href="{{ secure_url('css/app.css') }}" rel="stylesheet">
    @else
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body style="min-height:100vh;">
    <div id="app">

        <nav class="navbar navbar-expand-md navbar-light bg-surface-secondary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2FCookozy-svg.svg?alt=media&token=7a4164c2-2734-4928-8363-37af32ca3656"
                         class="h-10" alt="Cookozy-icon">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="display: none;">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto" id="navItems">
                        <!-- Authentication Links -->
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mendapatkan uid pengguna dari sesi atau dari suatu sumber lainnya
            const uid = "{{ Auth::id() }}"; // Sesuaikan dengan cara Anda mendapatkan uid

            let baseUrl;

            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                // Lokal (Development)
                baseUrl = 'http://localhost:8000';
            } else {
                // Produksi
                baseUrl = 'https://cookozy.web.app'; // Ganti dengan URL produksi Anda
            }

            // Permintaan Fetch untuk mendapatkan detail pengguna dari Firestore
            fetch(`${baseUrl}/api/users/${uid}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    // Periksa apakah pengguna sudah login atau tidak
                    if (!uid) {
                        // Jika pengguna belum login, tampilkan item navbar yang sesuai untuk guest
                        document.getElementById('navItems').innerHTML = `
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/about') }}">{{ __('About') }}</a>
                            </li>
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif`;
                    } else {
                        // Periksa nilai 'role' dari data pengguna
                        const role = data.data.role;

                        // Tangkap elemen navbar
                        const navbar = document.getElementById('navbarSupportedContent');

                        // Tampilkan navbar sesuai dengan peran pengguna
                        if (role === 'admin') {
                            navbar.style.display = 'block';
                            // Tampilkan item navbar yang sesuai dengan peran admin
                            document.getElementById('navItems').innerHTML = `
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ url('/admin') }}">{{ __('Dashboard Admin') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>`;
                        } else {
                            navbar.style.display = 'block';
                            // Tampilkan item navbar yang sesuai dengan peran user
                            document.getElementById('navItems').innerHTML = `
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ url('/profile') }}">{{ __('Profile') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ url('/account') }}">{{ __('Account') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/about') }}">{{ __('About') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>`;
                        }
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        });
    </script>
     <script>
        // Tentukan URL berdasarkan lingkungan
        const baseUrl = "{{ app()->isProduction() ? 'https://cookozy.web.app' : 'http://127.0.0.1:8000' }}";

        // Tentukan URL saat ini
        const currentUrl = window.location.href;

        // Tentukan URL yang seharusnya
        const desiredUrl = baseUrl + window.location.pathname;

        // Redirect ke URL yang disesuaikan jika tidak berada di sana
        if (currentUrl !== desiredUrl) {
            window.location.href = desiredUrl;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

</body>
<footer>
    @yield('footer')
</footer>
</html>
