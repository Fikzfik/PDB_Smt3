@extends('app', ['showHeader' => true])

@section('field-content')
    <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
        <div class="col-12">
            <div class="position-relative border-radius-xl overflow-hidden shadow-lg mb-7 px-3">
                <div class="container border-bottom px-2">
                    <div class="row justify-space-between py-2">
                        <div class="col-lg-3 me-auto">
                            <p class="lead text-dark pt-1 mb-0">Manage Margin Penjualan</p>
                        </div>
                        <div class="col-lg-3">
                            <div class="nav-wrapper position-relative end-0">
                                <ul class="nav nav-pills nav-fill flex-row p-1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#create-margin"
                                            role="tab" aria-controls="create" aria-selected="true">
                                            <i class="fas fa-plus text-sm me-2"></i> Create
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#table-margin"
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
                        Margin Penjualan added successfully!
                    </div>

                    <!-- Create Margin Penjualan -->
                    <div class="tab-pane active" id="create-margin">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Margin Penjualan</h4>
                                <form id="marginForm" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group input-group-dynamic mb-4">
                                                    <label class="form-label mt-n3">Persentase Margin</label>
                                                    <input class="form-control" aria-label="Margin" type="number"
                                                        id="persen" name="persen" placeholder="Enter margin persen"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group input-group-dynamic mb-4">
                                                    <label class="form-label mt-n3">Status Margin</label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="">-- Pilih Status --</option>
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary w-100">Add Margin</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table Margin Penjualan -->
                    <div class="tab-pane" id="table-margin">
                        <div class="table-responsive p-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Persentase Margin</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="marginTableBody">
                                    @foreach ($margin as $item)
                                        <tr id="row-{{ $item->idmargin_penjualan }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->persen }}%</td>
                                            <td>{{ ucfirst($item->status) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm editMargin"
                                                    data-id="{{ $item->idmargin_penjualan }}">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm deleteMargin"
                                                    data-id="{{ $item->idmargin_penjualan }}">Delete</button>
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
            // Handle form submission using AJAX for margin penjualan
            $('#marginForm').on('submit', function(e) {
                e.preventDefault(); // Prevent page refresh

                let formData = $(this).serialize(); // Serialize form data
                console.log(formData); // Log formData untuk memastikan data yang dikirim

                $.ajax({
                    url: "{{ route('margin.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#success-alert').removeClass('d-none');
                        setTimeout(function() {
                            $('#success-alert').addClass('d-none');
                        }, 3000);
                        $('#marginForm')[0].reset();
                        $('#marginTableBody').append(
                            `<tr id="row-${response.data.idmargin}">
                    <td>${response.data.idmargin}</td>
                    <td>${response.data.persen}%</td>
                    <td>${response.data.status}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm editMargin" data-id="${response.data.idmargin}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm deleteMargin" data-id="${response.data.idmargin}">Delete</button>
                    </td>
                </tr>`
                        );
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error adding Margin Penjualan.');
                    }
                });
            });

            // Delete Margin Penjualan
            $(document).on('click', '.deleteMargin', function() {
                let id = $(this).data('id');
                if (confirm("Are you sure you want to delete this margin?")) {
                    $.ajax({
                        url: "{{ route('margin.delete', ['id' => ':id']) }}".replace(':id', id),
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
                            alert('Error deleting Margin Penjualan.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
