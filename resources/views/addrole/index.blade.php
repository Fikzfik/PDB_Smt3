@extends('app', ['showHeader' => true])

@section('field-content')
    <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
        <div class="col-12">
            <div class="position-relative border-radius-xl overflow-hidden shadow-lg mb-7 px-3">
                <div class="container border-bottom px-2">
                    <div class="row justify-space-between py-2">
                        <div class="col-lg-3 me-auto">
                            <p class="lead text-dark pt-1 mb-0">Manage Stock Unit</p>
                        </div>
                        <div class="col-lg-3">
                            <div class="nav-wrapper position-relative end-0">
                                <ul class="nav nav-pills nav-fill flex-row p-1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab"
                                            href="#create-stock-unit" role="tab" aria-controls="create"
                                            aria-selected="true">
                                            <i class="fas fa-plus text-sm me-2"></i> Create
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#table-stock-unit"
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
                        Stock Unit added successfully!
                    </div>

                    <div class="tab-pane active" id="create-stock-unit">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Stock Unit</h4>
                                <form id="stockUnitForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nama_satuan" class="form-label">Nama Satuan</label>
                                        <input type="text" class="form-control" id="nama_satuan" name="nama_satuan"
                                            placeholder="Enter stock unit name" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Add Unit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table Stock Unit -->
                    <div class="tab-pane" id="table-stock-unit">
                        <div class="table-responsive p-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Satuan</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="satuanTableBody">
                                    @foreach ($roles as $item)
                                        <tr id="row-{{ $item->idrole }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_role }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm deleteSatuan"
                                                    data-id="{{ $item->idrole }}">Delete</button>
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

            $('#stockUnitForm').on('submit', function(e) {
                e.preventDefault(); // Prevent page refresh

                let formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ route('roles.create') }}", // Route to handle the form submission
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Show success alert
                        $('#success-alert').removeClass('d-none');

                        // Hide the alert after 3 seconds
                        setTimeout(function() {
                            $('#success-alert').addClass('d-none');
                        }, 3000);

                        $('#stockUnitForm')[0].reset(); // Clear the form

                        // Add new entry to the table dynamically
                        $('#satuanTableBody').append(
                            `<tr id="row-${response.idrole}">
                        <td>${response.idrole}</td>
                        <td>${response.nama_role}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm deleteSatuan" data-id="${response.idrole}">Delete</button>
                        </td>
                    </tr>`
                        );
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error adding Stock Unit.');
                    }
                });
            });

            // Delete Satuan
            $(document).on('click', '.deleteSatuan', function() {
                let id = $(this).data('id');
                if (confirm("Are you sure you want to delete this stock unit?")) {
                    $.ajax({
                        url: "{{ route('roles.delete', ['id' => ':id']) }}".replace(':id', id),
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
                            alert('Error deleting Stock Unit.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
