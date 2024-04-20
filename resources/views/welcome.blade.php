@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CooKozy</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CooKozy</title>
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        {{-- <link rel="stylesheet" href="./style.css"> --}}
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
            @if (App::environment('production'))
                <link href="{{ secure_url('css/welcome.css') }}" rel="stylesheet">
                <link href="{{ secure_url('css/app.css') }}" rel="stylesheet">
            @else
                <link href="{{ url('css/welcome.css') }}" rel="stylesheet">
                <link href="{{ url('css/app.css') }}" rel="stylesheet">
            @endif
        <!-- Bootstrap CSS -->
        <!-- Include Bootstrap CSS -->
        <style>
             .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-items: center;
        }
        </style>

    </head>
<body>
    <!-- isi content -->
    <div id="loadingIndicator" class="spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>



    <div class="container">
        <div id="carouselContainer"></div>
        <div class="mt-10" id="recipeList"></div>
    </div>

    <!-- Bootstrap JavaScript dan jQuery (diperlukan untuk Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JavaScript -->

    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const userId = "{{ Auth::id() }}"; // Mendapatkan user ID yang login dari Laravel
    const recipeList = document.getElementById('recipeList');
    const carouselContainer = document.getElementById('carouselContainer');

    loadingIndicator.style.display = 'block'; // Menampilkan indikator loading

    let baseUrl;

    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        // Lokal (Development)
        baseUrl = 'http://localhost:8000';
    } else {
        // Produksi
        baseUrl = 'https://cookozy.web.app'; // Ganti dengan URL produksi Anda
    }

    // Membuat promise untuk memuat data dari API `banners`
    const fetchBanners = fetch(`${baseUrl}/api/banners`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch banners');
            }
            return response.json();
        });

    // Membuat promise untuk memuat data dari API `recipes`
    const fetchRecipes = fetch(`${baseUrl}/api/recipes`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch recipes');
            }
            return response.json();
        });

    Promise.all([fetchBanners, fetchRecipes])
    .then(([banners, recipes]) => {
        // Proses data dari API `banners`
        const carouselIndicators = document.createElement('ol');
        carouselIndicators.classList.add('carousel-indicators');
        const carouselInner = document.createElement('div');
        carouselInner.classList.add('carousel-inner');
        let isFirst = true;

        banners.data.forEach((banner, index) => {
            const carouselItem = document.createElement('div');
            carouselItem.classList.add('carousel-item');
            if (isFirst) {
                carouselItem.classList.add('active');
                isFirst = false;
            }

            const img = document.createElement('img');
            img.classList.add('d-block', 'w-100');
            img.src = banner.imageUrl;
            img.alt = `Slide ${index + 1}`;

            carouselItem.appendChild(img);
            carouselInner.appendChild(carouselItem);

            const indicator = document.createElement('li');
            indicator.setAttribute('data-target', '#carouselExampleIndicators');
            indicator.setAttribute('data-slide-to', index.toString());
            if (isFirst) {
                indicator.classList.add('active');
                isFirst = false;
            }

            carouselIndicators.appendChild(indicator);
        });

        const prevControl = document.createElement('a');
        prevControl.classList.add('carousel-control-prev');
        prevControl.setAttribute('href', '#carouselExampleIndicators');
        prevControl.setAttribute('role', 'button');
        prevControl.setAttribute('data-slide', 'prev');

        const prevIcon = document.createElement('span');
        prevIcon.classList.add('carousel-control-prev-icon');
        prevIcon.setAttribute('aria-hidden', 'true');

        prevControl.appendChild(prevIcon);

        const prevText = document.createElement('span');
        prevText.classList.add('sr-only');
        prevText.textContent = 'Previous';

        prevControl.appendChild(prevText);

        const nextControl = document.createElement('a');
        nextControl.classList.add('carousel-control-next');
        nextControl.setAttribute('href', '#carouselExampleIndicators');
        nextControl.setAttribute('role', 'button');
        nextControl.setAttribute('data-slide', 'next');

        const nextIcon = document.createElement('span');
        nextIcon.classList.add('carousel-control-next-icon');
        nextIcon.setAttribute('aria-hidden', 'true');

        nextControl.appendChild(nextIcon);

        const nextText = document.createElement('span');
        nextText.classList.add('sr-only');
        nextText.textContent = 'Next';

        nextControl.appendChild(nextText);

        const carousel = document.createElement('div');
        carousel.classList.add('carousel', 'slide');
        carousel.setAttribute('id', 'carouselExampleIndicators');
        carousel.setAttribute('data-ride', 'carousel');

        carousel.appendChild(carouselIndicators);
        carousel.appendChild(carouselInner);
        carousel.appendChild(prevControl);
        carousel.appendChild(nextControl);

        carouselContainer.appendChild(carousel);

        // Proses data dari API `recipes`
        recipes.data.forEach(recipe => {
            const recipeItem = document.createElement('div');
            recipeItem.classList.add('card', 'p-0');
            recipeItem.innerHTML = `
                <img class="card-img-top" src="${recipe.image_url}" alt="Recipe" style="object-fit: cover; width: 100%; height: 220px;">
                <div class="card-body">
                    <h6 class="card-title">Posted by <a id="post-by">${recipe.user_name}</a></h6>
                    <div id="tag-lines">
                        <span class="tag">${recipe.type}</span>
                        <span class="tag">${recipe.level}</span>
                    </div>
                    <h5 class="card-title">${recipe.title}</h5>
                    <p class="card-text">${recipe.description}</p>
                </div>
                <div class="card-body">
                    <a href="/recipes/detail/${recipe.id}" class="btn btn-primary" style="position: absolute; bottom: 15px;">See More Recipe <i class="fas fa-chevron-right"></i></a>
                </div>`;
            recipeList.appendChild(recipeItem);
        });

        // Sembunyikan indikator loading setelah kedua proses selesai
        loadingIndicator.style.display = 'none';
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle error
        loadingIndicator.style.display = 'none';
    });

});
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    {{-- <script src="script.js"></script> --}}
        @if (App::environment('production'))
            <script src="{{ secure_url('js/welcome.js') }}" ></script>
        @else
            <script src="{{ url('js/welcome.js') }}"></script>
        @endif

</body>

{{-- <style>
    #recipeList {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    align-items: stretch; /* Mengatur semua item agar tingginya sama */
}

.post-wrapper {
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
    display: flex; /* Membuat item menjadi flex container */
    flex-direction: column; /* Menjadikan konten dalam item berorientasi vertikal */
}

.post-wrapper:hover {
    transform: translateY(-5px);
}

.post-content {
    padding: 20px;
    flex: 1; /* Mengatur konten agar dapat meregang secara vertikal */
}

.post-content h2 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.post-content p {
    font-size: 1rem;
    line-height: 1.4;
}

.tag {
    background-color: #f2f2f2;
    color: #555;
    padding: 5px 10px;
    border-radius: 5px;
    margin-right: 5px;
    font-size: 0.8rem;
}

.post-image img {
    max-width: 100%;
    height: auto;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}

</style> --}}


@endsection

@section('footer')
<div class="container-footer">
    <p>&copy; 2024 Your Company. All Rights Reserved.</p>
    <p>Development by Cookozy</p>
</div>
@endsection
