@extends('layouts.app')
<title>CooKozy | Profile</title>

@section('content')
    {{-- <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        @if (App::environment('production'))
            <link href="{{ secure_url('css/welcome.css') }}" rel="stylesheet">
            <link href="{{ secure_url('css/app.css') }}" rel="stylesheet">
        @else
            <link href="{{ url('css/welcome.css') }}" rel="stylesheet">
            <link href="{{ url('css/app.css') }}" rel="stylesheet">
        @endif
    </head>

    <body>
        !-- isi content -->
        <!-- sampul/cover atas  -->
        <div class="post-wrapper">
            <div id="profile-upper">
                <div id="profile-banner-image">
                    <img src="https://imagizer.imageshack.com/img921/9628/VIaL8H.jpg" alt="Banner image">
                </div>
                <div id="profile-d">
                    <div id="profile-pic">
                    <img src="https://i.insider.com/51dd6b0ceab8eaa223000013" alt="Profile picture">
                    </div>
                    <div id="author">Brithany</div>
                </div>
            </div>
        <!-- tempat postingan -->
            <div id="main-content">
            <div class="mc">
                <div class="mc" id="m-c-s">
                <span>What's on your mind?</span>
                <a href="/post/postdu.html"><img src="/Day 12  Recipe-card - Nothing4us/dist/plus.svg" alt="Add new post"></a>
                </div>
            </div>
            </div>
        </div>

        <!-- semua postingan  -->
        <!-- Rsp 1 -->
        <div class="post-wrapper">
                <div id="profile-pic-posting">
                <img id="img-pic" src="https://i.insider.com/51dd6b0ceab8eaa223000013" ><a class="post-author" href="profil.html">Brithany</a>
                </div>


            <div class="post">
            <div class="post-content">
                <h2 id="produk" class="font-bold text-2xl">Roti Panggang Alpukat, dan Telur Rebus</h2>
                <div id="tag-lines">
                <span class="tag">Breakfast</span>
                <span class="tag">Easy</span>
                </div>
                <p class="text-sm text-[#1c0708]/60">Roti panggang dengan alpukat dan telur rebus.</p>
                <div class="panel-heading active">
                <h4 class="see-more">
                    <a class="btn-rcp" href="/Day 12  Recipe-card - Nothing4us/dist/index.html">See More Recipe</a>
                </h4>
                </div>
            </div>
            <div class="post-image">
                <img alt="" class="recipe_img" src="/Day 12  Recipe-card - Nothing4us/dist/avcToast.jpg">
            </div>
            </div>
        </div>


        <!-- Rsp 2 -->
        <div class="post-wrapper">
            <div id="profile-pic-posting">
                <img id="img-pic" src="https://i.insider.com/51dd6b0ceab8eaa223000013" ><a class="post-author" href="profil.html">Brithany</a>
                </div>

            <div class="post">
            <div class="post-content">
                <h2 class="font-bold text-2xl">Tahu Cabe Garam </h2>
                <div id="tag-lines">
                <span class="tag" href="#">Lunch</span>
                <span class="tag" href="#">Easy</span>
                </div>
                <p id="produk" class="text-sm text-[#1c0708]/60">Tahu yang lembut berpadu dengan rasa pedas dari cabai dan asin dari garam.</p>
                <div class="panel-heading active">
                <h4 class="see-more">
                    <a class="btn-rcp" href="/Day 12  Recipe-card - Nothing4us/dist/index2.html">See More Recipe</a>
                </h4>
                </div>

            </div>
            <div class="post-image">
                <img alt="" class="recipe_img" src="/Day 12  Recipe-card - Nothing4us/dist/thcbGrm.jpg">
            </div>
            </div>
        </div>

        <!-- Rsp 3 -->
        <div class="post-wrapper">
            <div id="profile-pic-posting">
                <img id="img-pic" src="https://i.insider.com/51dd6b0ceab8eaa223000013" ><a class="post-author" href="profil.html">Brithany</a>
                </div>

            <div class="post">
            <div class="post-content">
                <h2 id="produk" class="font-bold text-2xl">Telor Kecap</h2>
                <div id="tag-lines">
                <span class="tag">Breakfast</span> <span class="tag">Easy</span>
                </div>
                <p class="text-sm text-[#1c0708]/60">Telur ceplok kecap dengan telurnya setengah matang direndam saus kecap yang legit gurih.</p>
                <div class="panel-heading active">
                <h4 class="see-more">
                    <a class="btn-rcp" href="/Day 12  Recipe-card - Nothing4us/dist/index3.html">See More Recipe</a>
                </h4>
                </div>
            </div>
            <div class="post-image">
                <img alt="" class="recipe_img" src="/Day 12  Recipe-card - Nothing4us/dist/tlrKcp.jpg">
            </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        @if (App::environment('production'))
            <script src="{{ secure_url('js/welcome.js') }}" ></script>
        @else
            <script src="{{ url('js/welcome.js') }}"></script>
        @endif
    </body> --}}
@endsection

@section('footer')
<div class="container-footer">
    <p>&copy; 2024 Your Company. All Rights Reserved.</p>
    <p>Development by Cookozy</p>
</div>
@endsection
