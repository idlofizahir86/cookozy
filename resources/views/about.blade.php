@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>CooKozy | About</title>
        {{-- <initial-scale=1.0> --}}
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        @if (App::environment('production'))
                <link href="{{ secure_url('css/about.css') }}" rel="stylesheet">
                <link href="{{ secure_url('css/app.css') }}" rel="stylesheet">
                <link href="{{ secure_url('css/welcome.css') }}" rel="stylesheet">
            @else
                <link href="{{ url('css/about.css') }}" rel="stylesheet">
                <link href="{{ url('css/app.css') }}" rel="stylesheet">
                <link href="{{ url('css/welcome.css') }}" rel="stylesheet">
            @endif

        <script src="minified/gsap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.0.5/gsap.min.js"
        integrity="sha256-CkQcTxuQyZLqzqWqntH3FDxeDKMV0m7cw0aM5eph4Do="
        crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.6/ScrollMagic.min.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.6/plugins/animation.gsap.min.js">
        </script>
    </head>

    <body>
        <!-- Bungkus bawang sama tepung dalam satu div -->
    <div class="content-wrapper">
        <section class="first-section">
            <div class="images-wrapper">
                <div class="ingredient-images">
                    <img class="section_1_01"
                        src="https://www.dropbox.com/s/atuvpzvo59wxeef/section_1_01.webp?raw=1" />
                    <img class="section_1_03"
                        src="https://www.dropbox.com/s/oewc2bioujvzlp7/section_1_03.webp?raw=1" />
                    <img class="section_1_04"
                        src="https://www.dropbox.com/s/bxezwiwtnkx1cw0/section_1_04.webp?raw=1" />
                    <img class="section_1_05"
                        src="https://www.dropbox.com/s/bftnm0v5j33uv9c/section_1_05.webp?raw=1" />
                    <img class="section_1_06"
                        src="https://www.dropbox.com/s/hz9jbiak3laqrqp/section_1_06.webp?raw=1" />
                    <img class="section_1_07"
                        src="https://www.dropbox.com/s/jdlxt8xxdrquh9a/section_1_07.webp?raw=1" />
                    <img class="section_1_08"
                        src="https://www.dropbox.com/s/s41b0ualql6b74d/section_1_08.webp?raw=1" />
                </div>
            </div>
            <!-- isi kontent -->
            <div class="content-detail">
                <h1>ABOUT <span class="span-us"> CooKozy</span>  </h1>
                <h3>Cooking Together, <Span class="span-grow">Growing Together</Span></h3>
                <p>Selamat datang di Cookozy, teman terbaikmu di dunia kuliner anak kost! Di Cookozy, kami berkomitmen untuk menyediakan pengalaman kuliner yang sehat, praktis, inspiratif, dan mendidik bagi para anak kost.</p>
                <p>Cookozy hadir untuk membantu kamu menemukan resep praktis, menyehatkan, berbagi inspirasi, dan mengembangkan keterampilan memasak dengan mudah. Bersama Cookozy, mari jelajahi dunia kuliner dan buatlah momen-momen tak terlupakan di dapur kecil kos mu!</p>
                <h4>Selamat Memasak!</h4>
            </div>
            <div class="content-team">
                <h1>TEAM<span class="span-us">US</span></h1>
                <div class="allmem">
                    <img class="mem1" src="https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2FMember%2FIdlofi.png?alt=media&token=bd0d8b0d-b99d-45d2-8c46-00d975774748" alt="Idlofi">
                    <img class="mem2" src="https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2FMember%2FClara.png?alt=media&token=5be19a31-3ba1-40d8-bef1-2b94a3dd02a2" alt="Clara">
                    <img class="mem3" src="https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2FMember%2FBrit.png?alt=media&token=280a3e8f-f34c-47a4-87d9-f3de014fbb9d" alt="Brit">
                    <img class="mem4" src="https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2FMember%2FNeha.png?alt=media&token=43a1b0fe-66f1-4afb-98f2-a07a13214a28" alt="Neha">
                    <img class="mem4" src="https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2FMember%2FCisi.png?alt=media&token=f2b5c720-e948-4e05-b986-82a413a69bdb" alt="Cisi">
                </div>
            </div>
            </body>
        </section>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @if (App::environment('production'))
        <script src="{{ secure_url('js/about.js') }}" ></script>
    @else
        <script src="{{ url('js/about.js') }}"></script>
    @endif
</html>
@endsection

@section('footer')
<div class="container-footer">
    <p>&copy; 2024 Your Company. All Rights Reserved.</p>
    <p>Development by Cookozy</p>
</div>
@endsection
