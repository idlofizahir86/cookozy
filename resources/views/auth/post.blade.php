@extends('layouts.app')
<title>CooKozy | Post</title>

@section('content')
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Unggahan Resep</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Custom styling */
        /* body {
            background-color: #f8f9fa;
        } */
        /* .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        } */
        .custom-file-label {
            overflow: hidden;
            white-space: nowrap;
        }
        .preview-image {
            max-width: 300px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
        #loading {
            display: none;
            text-align: center;
        }
        /* Additional styling from previous HTML */
        .step-row {
            display: flex;
            align-items: center;
        }
        .step-input {
            flex: 1;
            margin-right: 10px;
        }
        </style>
        @if (App::environment('production'))
            <link href="{{ secure_url('css/welcome.css') }}" rel="stylesheet">
            <link href="{{ secure_url('css/app.css') }}" rel="stylesheet">
        @else
            <link href="{{ url('css/welcome.css') }}" rel="stylesheet">
            <link href="{{ url('css/app.css') }}" rel="stylesheet">
        @endif
</head>
<body>

    <div class="container mt-5">
    <h2 class="mb-4">Form Unggahan Resep</h2>
    <div class="row">
        <div class="col-md-6">
            <form id="recipeForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul">Judul Resep</label>
                    <input type="text" class="form-control" id="title" name="judul" placeholder="Masukkan judul resep">
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Resep</label>
                    <textarea class="form-control" id="description" name="deskripsi" rows="3" placeholder="Masukkan deskripsi resep"></textarea>
                </div>
                <div class="form-group">
                    <label for="bahan">Bahan-bahan</label>
                    <textarea class="form-control" id="ingredients" name="bahan" rows="3" placeholder="Tambahkan bahan-bahan"></textarea>
                </div>
                <div class="form-group">
                    <label for="langkah">Langkah-langkah</label>
                    <div id="step-container">
                        <div class="step-row" >
                            <input type="text" name="step[]" placeholder="Step" class="form-control step-input">
                            <!-- Tombol Remove hanya muncul jika ada lebih dari satu langkah -->
                            <!-- Jika hanya ada satu langkah, tombol Remove akan dinonaktifkan -->
                            <button type="button" class="btn btn-danger" onclick="removeStep(this)" @if(count(old('step', [])) <= 1) disabled @endif>Remove</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success mt-3" onclick="addStep()">Add Step</button>
                </div>
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select class="form-control" id="type" name="kategori">
                        <option>Sarapan</option>
                        <option>Makan Siang</option>
                        <option>Makan Malam</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="kesulitan">Tingkat Kesulitan</label>
                    <select class="form-control" id="level" name="kesulitan">
                        <option>Mudah</option>
                        <option>Menengah</option>
                        <option>Sulit</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Submit</button>
                <div id="loading">
                    <div class="spinner-border text-primary mt-3" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="gambar">Unggah Gambar</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="gambar" name="image" onchange="previewImage(event)">
                    <label class="custom-file-label" for="gambar">Pilih file</label>
                    <img id="preview" class="preview-image" src="#" alt="Preview">
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="user_id" value="{{ Auth::id() }}">

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    function addStep() {
    var stepContainer = document.getElementById('step-container');
    var newStepRow = document.createElement('div');
    newStepRow.classList.add('step-row');
    newStepRow.innerHTML = `
        <input type="text" name="step[]" placeholder="Step" class="form-control step-input">
        <button type="button" class="btn btn-danger" onclick="removeStep(this)">Remove</button>
    `;
    stepContainer.appendChild(newStepRow);
}

function removeStep(btn) {
    var stepContainer = document.getElementById('step-container');
    stepContainer.removeChild(btn.parentNode);
}

function previewImage(event) {
    var preview = document.getElementById('preview');
    var file = event.target.files[0];
    var reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
        preview.style.display = 'block';
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
    }
}

function getAllSteps() {
    // Mengambil semua elemen input dengan nama 'step[]'
    var stepInputs = document.querySelectorAll('input[name="step[]"]');

    // Inisialisasi variabel untuk menyimpan nilai-nilai input
    var allSteps = '';

    // Menggunakan loop untuk mengambil nilai dari setiap input dan menggabungkannya menjadi satu string
    stepInputs.forEach(function(stepInput) {
        // Menambahkan nilai input ke dalam variabel allSteps, dipisahkan oleh newline jika bukan input pertama
        if (allSteps !== '') {
            allSteps += '\n';
        }
        allSteps += stepInput.value;
    });

    // Mengembalikan string yang berisi semua nilai input
    return allSteps;
}
</script>

<script>

function submitForm() {
    var form = document.getElementById('recipeForm');
    var formData = new FormData(form);
    var loading = document.getElementById('loading');
    var imageFile = document.getElementById('gambar').files[0]; // Mengambil gambar yang dipilih oleh pengguna

    loading.style.display = 'block';
    let baseUrl;

    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        // Lokal (Development)
        baseUrl = 'http://localhost:8000';
    } else {
        // Produksi
        baseUrl = 'https://cookozy-pwohh4kjqa-et.a.run.app'; // Ganti dengan URL produksi Anda
    }

    // const userId = {{ Auth::id() }};
    var title = document.getElementById('title').value;
    var description = document.getElementById('description').value;
    var ingredients = document.getElementById('ingredients').value;
    var type = document.getElementById('type').value;
    var level = document.getElementById('level').value;
    var steps = getAllSteps();
    const user_id = document.getElementById('user_id').value;

    formData.append('title', title);
    formData.append('description', description);
    formData.append('ingredients', ingredients);
    formData.append('type', type);
    formData.append('level', level);
    formData.append('steps', steps);
    formData.append('user_id', user_id);
    console.log(user_id);


    // Jika pengguna telah memilih gambar, unggah ke Firebase Storage
    if (imageFile) {
        var formDataWithImage = new FormData();
        formDataWithImage.append('image', imageFile);

        fetch(`${baseUrl}/api/upload-image`, {
            method: 'POST',
            body: formDataWithImage
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Simpan URL gambar untuk digunakan saat mengirimkan formulir
            formData.append('image_url', data.image_url);

            // Kirim formulir resep dengan imageUrl yang sudah ditambahkan
            fetch(`${baseUrl}/api/recipes`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                loading.style.display = 'none';
                alert('Recipe submitted successfully!');
                form.reset();
                document.getElementById('preview').style.display = 'none';
            })
            .catch(error => {
                console.error('There was an error!', error);
                loading.style.display = 'none';
                alert('Error submitting recipe. Please try again later.');

                // Jika permintaan gagal, hapus foto yang telah diunggah
                deleteUploadedFile(data.image_url);
            });
        })
        .catch(error => {
            console.error('There was an error!', error);
            loading.style.display = 'none';
            alert('Error uploading image. Please try again later.');
        });
    } else {
        // Jika pengguna tidak memilih gambar, langsung kirim formulir resep
        fetch('/api/recipes', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            loading.style.display = 'none';
            alert('Recipe submitted successfully!');
            form.reset();
        })
        .catch(error => {
            console.error('There was an error!', error);
            loading.style.display = 'none';
            alert('Error submitting recipe. Please try again later.');
        });
    }
}

// Fungsi untuk menghapus foto yang telah diunggah
function deleteUploadedFile(imageUrl) {
    let baseUrl;

    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        // Lokal (Development)
        baseUrl = 'http://localhost:8000';
    } else {
        // Produksi
        baseUrl = 'https://cookozy-pwohh4kjqa-et.a.run.app'; // Ganti dengan URL produksi Anda
    }

    fetch(`${baseUrl}/api/delete-image`, {
        method: 'DELETE',
        body: JSON.stringify({ image_url: imageUrl }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        console.log('Image deleted successfully');
    })
    .catch(error => {
        console.error('Error deleting image:', error);
    });
}

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
