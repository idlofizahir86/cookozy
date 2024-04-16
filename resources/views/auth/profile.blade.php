@extends('layouts.app')
<title>CooKozy | Profile</title>

@section('content')
    <head>
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

        <!-- isi content -->
        <!-- sampul/cover atas  -->
        <div class="banner-wrapper">
            <div id="profile-upper">
                <div id="profile-banner-image">
                    <img src="https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2FBanner-profile.png?alt=media&token=942378fe-27c6-4861-a85c-7fee004acd07" alt="Banner image">
                </div>
                <div id="profile-d">
                    <div id="profile-pic">
                    <img id="profile-image" src="https://i.insider.com/51dd6b0ceab8eaa223000013" alt="Profile picture">
                    </div>
                    <div id="author">Loading..</div>
                </div>
            </div>
        </div>

        <!-- tempat postingan -->
        <a href="{{ url('/post') }}" class="float">
            <i class="fa fa-plus my-float"></i>
        </a>

        <script>
            document.addEventListener('DOMContentLoaded', async () => {
            const userId = "{{ Auth::id() }}"; // Mendapatkan user ID yang login dari Laravel
            let baseUrl;

            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                // Lokal (Development)
                baseUrl = 'http://localhost:8000';
            } else {
                // Produksi
                baseUrl = 'https://cookozy-pwohh4kjqa-et.a.run.app'; // Ganti dengan URL produksi Anda
            }
            try {
                const response = await fetch(`${baseUrl}/api/users/${userId}`);
                const userData = await response.json();

                // Menampilkan data pengguna di halaman web
                document.getElementById('author').textContent = userData.data.nama;
                document.getElementById('profile-image').src = userData.data.imageUrl;
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        });
        </script>


        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        @if (App::environment('production'))
            <script src="{{ secure_url('js/welcome.js') }}" ></script>
        @else
            <script src="{{ url('js/welcome.js') }}"></script>
        @endif
    </body>

    <div id="loadingIndicator" class="spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div class="container mt-5">
        <h1 class="mb-4">Recipe Yang Telah dibuat</h1>
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
                baseUrl = 'https://cookozy-pwohh4kjqa-et.a.run.app'; // Ganti dengan URL produksi Anda
            }

            fetch(`${baseUrl}/api/recipes`)
                .then(response => response.json())
                .then(data => {
                    const filteredRecipes = data.data.filter(recipe => recipe.user_id === userId);
                    loadingIndicator.style.display = 'none'; // Menyembunyikan indikator loading setelah proses selesai

                    filteredRecipes.forEach(recipe => {
                        const recipeItem = document.createElement('div');
                        recipeItem.classList.add('card', 'mb-3');
                        recipeItem.innerHTML = `
                            <div class="card-body">
                                <h5 class="card-title">${recipe.title}</h5>
                                <p class="card-text">${recipe.description}</p>
                                <p class="card-text">Author: ${recipe.user_name}</p>

                                <form class="edit-form d-none">
                                    <div class="form-group">
                                        <label for="editTitle_${recipe.id}">Title</label>
                                        <input type="text" class="form-control" id="editTitle_${recipe.id}" value="${recipe.title}">
                                    </div>
                                    <div class="form-group">
                                        <label for="editDescription_${recipe.id}">Description</label>
                                        <textarea class="form-control" id="editDescription_${recipe.id}" rows="3">${recipe.description}</textarea>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm save-btn" data-id="${recipe.id}">Save</button>
                                    <br>
                                    <br>
                                    </form>

                                <button type="button" class="btn btn-secondary btn-sm edit-btn">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm delete-btn text-white" data-id="${recipe.id}" data-title="${recipe.title}">Delete</button>
                            </div>
                        `;
                        recipeList.appendChild(recipeItem);
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

                    const saveButtons = document.querySelectorAll('.save-btn');
                    saveButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            const recipeId = button.dataset.id;
                            const editTitle = document.querySelector(`#editTitle_${recipeId}`).value;
                            const editDescription = document.querySelector(`#editDescription_${recipeId}`).value;

                            fetch(`${baseUrl}/api/recipes/${recipeId}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    title: editTitle,
                                    description: editDescription,
                                }),
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to update recipe');
                                }
                                // Handle jika berhasil diperbarui
                                const editForm = button.closest('.edit-form');
                                editForm.classList.add('d-none');

                                // Perbarui halaman
                                location.reload();
                            })
                            .catch(error => {
                                console.error('Error updating recipe:', error);
                            });
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
@endsection

@section('footer')
<div class="container-footer">
    <p>&copy; 2024 Your Company. All Rights Reserved.</p>
    <p>Development by Cookozy</p>
</div>
@endsection
