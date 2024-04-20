@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
    <title>Recipe App</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    @if (App::environment('production'))
    <link href="{{ secure_url('css/welcome.css') }}" rel="stylesheet">
    <link href="{{ secure_url('css/app.css') }}" rel="stylesheet">
@else
    <link href="{{ url('css/welcome.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
@endif
</head>
<body>

    <div id="loadingIndicator" class="spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div class="container mt-5">
        <h1 class="mb-4">Admin Dashboard</h1>
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
                baseUrl = 'https://cookozy.web.app'; // Ganti dengan URL produksi Anda
            }

            fetch(`${baseUrl}/api/recipes`)
                .then(response => response.json())
                .then(data => {
                    const filteredRecipes = data.data.filter(recipe => recipe.verified === false);
                    loadingIndicator.style.display = 'none'; // Menyembunyikan indikator loading setelah proses selesai

                    filteredRecipes.forEach(recipe => {
                        const recipeItem = document.createElement('div');
                        recipeItem.classList.add('card', 'mb-3');
                        recipeItem.innerHTML = `
                            <div class="card-body">
                                <h5 class="card-title">${recipe.title}</h5>
                                <p class="card-text">${recipe.description}</p>
                                <p class="card-text">Author: ${recipe.user_name}</p>


                                <button type="button" class="btn btn-secondary btn-sm edit-btn" data-id="${recipe.id}" >See</button>
                                <button type="button" class="btn btn-danger btn-sm delete-btn text-white" data-id="${recipe.id}" data-title="${recipe.title}">Delete</button>
                            </div>
                        `;
                        recipeList.appendChild(recipeItem);
                    });

                    document.querySelectorAll('.edit-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            // Dapatkan ID resep dari atribut data-id
                            const recipeId = btn.getAttribute('data-id');
                            // Arahkan ke halaman edit dengan ID resep
                            window.location.href = `/recipes/verified/${recipeId}`;
                        });
                    });

                    const editButtons = document.querySelectorAll('.edit-btn');
                    editButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            const cardBody = button.parentNode;
                            const editForm = cardBody.querySelector('.edit-form');
                            if (editForm.classList.contains('d-none')) {
                                // Jika form sedang ditutup, maka buka
                                editForm.classList.remove('d-none');
                            } else {
                                // Jika form sedang terbuka, maka tutup
                                editForm.classList.add('d-none');
                            }
                        });
                    });


                    const deleteButtons = document.querySelectorAll('.delete-btn');
                    deleteButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            const recipeId = button.dataset.id;
                            const recipeTitle = button.dataset.title;
                            const isConfirmed = confirm(`Apakah anda yakin mengahupus resep "${recipeTitle}"?`);
                            if (isConfirmed) {
                                // Lakukan penghapusan menggunakan fetch atau metode lainnya
                                fetch(`/api/recipes/delete/${recipeId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Failed to delete recipe');
                                    }
                                    // Handle jika berhasil dihapus
                                    location.reload();
                                })
                                .catch(error => {
                                    console.error('Error deleting recipe:', error);
                                });
                            }
                        });
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>


    @endsection

    @section('footer')
<div class="container-footer">
    <p>&copy; 2024 Your Company. All Rights Reserved.</p>
    <p>Development by Cookozy</p>
</div>
@endsection



