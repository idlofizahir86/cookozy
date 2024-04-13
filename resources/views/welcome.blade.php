@extends('layouts.app')

@section('content')
{{-- <!DOCTYPE html>
<html lang="en">
<head> --}}
  <meta charset="UTF-8">
  <title>CooKozy</title>
  {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  {{-- <link rel="stylesheet" href="./style.css"> --}}
  @if (App::environment('production'))
        <link href="{{ secure_url('css/welcome.css') }}" rel="stylesheet">
        <link href="{{ secure_url('css/app.css') }}" rel="stylesheet">
    @else
        <link href="{{ url('css/welcome.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
    @endif
{{-- </head>
<body> --}}
    <!-- isi content -->
    <div id="loadingIndicator" class="spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div class="container">
        <div id="recipeList"></div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const userId = "{{ Auth::id() }}"; // Mendapatkan user ID yang login dari Laravel
        const recipeList = document.getElementById('recipeList');

        loadingIndicator.style.display = 'block'; // Menampilkan indikator loading

        let baseUrl;

        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            // Lokal (Development)
            baseUrl = 'http://localhost:8000';
        } else {
            // Produksi
            baseUrl = 'https://cookozy-abp-vctfn27zsa-et.a.run.app'; // Ganti dengan URL produksi Anda
        }

        fetch(`${baseUrl}/api/recipes`)
        .then(response => response.json())
        .then(data => {
            loadingIndicator.style.display = 'none'; // Menyembunyikan indikator loading setelah proses selesai

            data.data.forEach(recipe => {
                const recipeItem = document.createElement('div');
                recipeItem.innerHTML = `
                    <div class="post-wrapper">
                        <div class="post">
                            <div class="post-content">
                                <h2 class="font-bold text-2xl break-long-text">${recipe.title}</h2>
                                <div id="tag-lines">
                                    <span class="tag">${recipe.type}</span>
                                    <span class="tag">${recipe.level}</span>
                                </div>
                                <p class="text-sm text-[#1c0708]/60">${recipe.description}</p>
                                <div class="panel-heading active">
                                    <h4 class="see-more">
                                        <a class="btn-rcp" href="index.html">See More Recipe</a>
                                    </h4>
                                </div>
                                <div class="post-author">
                                    Posted by <a id="post-by">${recipe.user_name}</a>
                                </div>
                            </div>
                            <div class="post-image">
                                <img alt="" class="recipe_img" src="${recipe.image_url}">
                            </div>
                        </div>
                    </div>
                `;
                recipeList.appendChild(recipeItem);
            });

            // recipeList.innerHTML = htmlString;
        })
            .catch(error => {
                console.error('Error fetching recipes:', error);
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



{{-- </body> --}}

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
