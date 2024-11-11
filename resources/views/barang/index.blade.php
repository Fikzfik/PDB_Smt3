@extends('app', ['showHeader' => true])

@section('field-content')
    <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
        <div class="col-12">
            <div class="position-relative border-radius-xl overflow-hidden shadow-lg mb-7 px-3">
                <div class="container border-bottom px-2">
                    <div class="row justify-space-between py-2">
                        <div class="col-lg-3 me-auto">
                            <p class="lead text-dark pt-1 mb-0">Manage Barang</p>
                        </div>
                        <div class="col-lg-3">
                            <div class="nav-wrapper position-relative end-0">
                                <ul class="nav nav-pills nav-fill flex-row p-1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#create-barang"
                                            role="tab" aria-controls="create" aria-selected="true">
                                            <i class="fas fa-plus text-sm me-2"></i> Create
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#table-barang"
                                            role="tab" aria-controls="table" aria-selected="false">
                                            <i class="fas fa-table text-sm me-2"></i> Table
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content tab-space">
                    <!-- Success Alert -->
                    <div id="success-alert" class="alert alert-success text-white font-weight-bold d-none" role="alert">
                        Barang added successfully!
                    </div>

                    <!-- Create Barang -->
                    <div class="tab-pane active" id="create-barang">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Barang</h4>
                                <form id="barangForm" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group input-group-dynamic mb-4">
                                                    <label class="form-label mt-n3">Jenis Barang</label>
                                                    <input class="form-control" aria-label="Jenis" type="text"
                                                        id="jenis" name="jenis" placeholder="Enter barang jenis"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group input-group-dynamic mb-4">
                                                    <label class="form-label mt-n3">Nama Barang</label>
                                                    <input class="form-control" aria-label="Nama Barang" type="text"
                                                        id="nama" name="nama" placeholder="Enter barang name"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group input-group-dynamic mb-4">
                                                    <label class="form-label mt-n3">Harga Barang</label>
                                                    <input class="form-control" aria-label="Harga" type="number"
                                                        id="harga" name="harga" placeholder="Enter harga barang"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="idsatuan">Pilih Satuan</label>
                                                <select class="form-control" id="idsatuan" name="idsatuan" required>
                                                    <option value="">-- Pilih Satuan --</option>
                                                    @foreach ($satuan as $item)
                                                        <option value="{{ $item->idsatuan }}">{{ $item->nama_satuan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary w-100">Add Barang</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table Barang -->
                    <div class="tab-pane" id="table-barang">
                        <div class="table-responsive p-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Jenis Barang</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="barangTableBody">
                                    @foreach ($barang as $item)
                                        <tr id="row-{{ $item->idbarang }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->jenis }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->harga }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm editBarang"
                                                    data-id="{{ $item->idbarang }}">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm deleteBarang"
                                                    data-id="{{ $item->idbarang }}">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle form submission using AJAX
            $('#barangForm').on('submit', function(e) {
                e.preventDefault(); // Prevent page refresh

                let formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ route('barang.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Show success alert
                        $('#success-alert').removeClass('d-none');

                        // Hide the alert after 3 seconds
                        setTimeout(function() {
                            $('#success-alert').addClass('d-none');
                        }, 3000);

                        $('#barangForm')[0].reset();  // Reset the correct form
                        // Add new entry to the table dynamically
                        $('#barangTableBody').append(
                            `<tr id="row-${response.data.idbarang}">
                            <td>${response.data.idbarang}</td>
                            <td>${response.data.jenis}</td>
                            <td>${response.data.nama}</td>
                            <td>${response.data.harga}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm editBarang" data-id="${response.data.idbarang}">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm deleteBarang" data-id="${response.data.idbarang}">Delete</button>
                            </td>
                        </tr>`
                        );

                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error adding Barang.');
                    }
                });

            });

            // Delete Barang
            $(document).on('click', '.deleteBarang', function() {
                let id = $(this).data('id');
                if (confirm("Are you sure you want to delete this barang?")) {
                    $.ajax({
                        url: "{{ route('barang.delete', ['id' => ':id']) }}".replace(':id', id),
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Remove the table row
                            $(`#row-${id}`).remove();
                        },
                        error: function(error) {
                            console.error(error);
                            alert('Error deleting Barang.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
