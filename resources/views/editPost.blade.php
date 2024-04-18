@extends('layouts.app')
<title>CooKozy | Edit Post</title>

@section('content')
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Edit Recipe Form</h2>
    <div class="row">
        <div class="col-md-6">
            <form id="recipeForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul">Recipe Title</label>
                    <input type="text" class="form-control" id="title" name="judul" placeholder="Enter recipe title">
                </div>
                <div class="form-group">
                    <label for="deskripsi">Recipe Description</label>
                    <textarea class="form-control" id="description" name="deskripsi" rows="3" placeholder="Enter recipe description"></textarea>
                </div>
                <div class="form-group">
                    <label for="bahan">Ingredients</label>
                    <textarea class="form-control" id="ingredients" name="bahan" rows="3" placeholder="Add ingredients"></textarea>
                </div>
                <div class="form-group">
                    <label for="langkah">Steps</label>
                    <div id="step-container">
                        <!-- Steps will be dynamically added here -->
                    </div>
                    <button type="button" class="btn btn-success mt-3" onclick="addStep()">Add Step</button>
                </div>

                <button type="button" class="btn btn-primary" onclick="submitForm()">Save</button>
                <div id="loading">
                    <div class="spinner-border text-primary mt-3" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<input type="hidden" id="user_id" value="{{ Auth::id() }}">

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Ambil id resep dari URL
    const recipeId = window.location.pathname.split('/')[2]; // Untuk URL seperti /post/{id}/edit

    // Buat permintaan GET untuk mengambil data resep
    fetch(`/api/recipes/${recipeId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(recipe => {
            console.log(recipe)
            // Isi nilai input dengan data resep yang diterima
            document.getElementById('title').value = recipe.data.title;
            document.getElementById('description').value = recipe.data.description;
            document.getElementById('ingredients').value = recipe.data.ingredients;
            // Pisahkan langkah-langkah menjadi array
            const steps = recipe.data.steps.split('\r\n');

            // Isi langkah-langkah ke dalam input
            const stepInputs = document.querySelectorAll('input[name="step[]"]');
            steps.forEach((step, index) => {
                if (stepInputs[index]) {
                    // Jika input sudah ada, set nilainya
                    stepInputs[index].value = step;
                } else {
                    // Jika input belum ada, tambahkan input baru dan set nilainya
                    addStep();
                    const newStepInputs = document.querySelectorAll('input[name="step[]"]');
                    newStepInputs[index].value = step;
                }
            });
        })
        .catch(error => {
            console.error('There was an error!', error);
            alert('Error fetching recipe data. Please try again later.');
        });


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

    // Function to add step input dynamically
    function addStep() {
        var stepContainer = document.getElementById('step-container');
        var newStepRow = document.createElement('div');
        newStepRow.classList.add('step-row');
        newStepRow.innerHTML = `
            <input type="text" name="step[]" placeholder="Step" class="form-control-2 step-input">
            <button type="button" class="btn btn-danger" onclick="removeStep(this)">Remove</button>
        `;
        stepContainer.appendChild(newStepRow);
    }

    // Function to remove step input dynamically
    function removeStep(btn) {
        var stepContainer = document.getElementById('step-container');
        stepContainer.removeChild(btn.parentNode);
    }

    // Function to submit the form
    function submitForm() {
        var form = document.getElementById('recipeForm');
        var formData = new FormData(form);
        var loading = document.getElementById('loading'); // Mengambil gambar yang dipilih oleh pengguna

        loading.style.display = 'block';
        let baseUrl;

        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            // Lokal (Development)
            baseUrl = 'http://localhost:8000';
        } else {
            // Produksi
            baseUrl = 'https://cookozy.web.app'; // Ganti dengan URL produksi Anda
        }

        fetch(`${baseUrl}/api/recipes/${recipeId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                title: document.getElementById('title').value,
                description: document.getElementById('description').value,
                ingredients: document.getElementById('ingredients').value,
                steps: getAllSteps(),
                // Sisipkan atribut lain yang ingin Anda perbarui
            }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to update recipe');
            }
            // Handle jika berhasil diperbarui
            const editForm = document.getElementById('recipeForm');
            editForm.reset(); // Mengosongkan formulir setelah pembaruan berhasil
            alert('Recipe updated successfully!');


        })
        .catch(error => {
            console.error('Error updating recipe:', error);
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
