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

                <!-- Edit Modal -->
                <div class="modal fade" id="editStockUnitModal" tabindex="-1" role="dialog"
                    aria-labelledby="editStockUnitLabel" aria-hidden="true" data-bs-backdrop="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editStockUnitLabel">Edit Stock Unit</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editStockUnitForm">
                                    @csrf
                                    <input type="hidden" id="edit_idsatuan" name="idsatuan">
                                    <div class="mb-3">
                                        <label for="edit_nama_satuan" class="form-label">Nama Satuan</label>
                                        <input type="text" class="form-control" id="edit_nama_satuan" name="nama_satuan"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                                    </div>
                                </form>
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
                                    @foreach ($satuan as $item)
                                        <tr id="row-{{ $item->idsatuan }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_satuan }}</td>
                                            <td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm editSatuan"
                                                    data-id="{{ $item->idsatuan }}"
                                                    data-nama_satuan="{{ $item->nama_satuan }}">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm deleteSatuan"
                                                    data-id="{{ $item->idsatuan }}">Delete</button>
                                            </td>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle form submission using AJAX
            $('#stockUnitForm').on('submit', function(e) {
                e.preventDefault(); // Prevent page refresh

                let formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ route('satuan.create') }}", // Route to handle the form submission
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Show success alert with SweetAlert2
                        Swal.fire({
                            title: 'Success!',
                            text: 'Stock Unit added successfully!',
                            icon: 'success',
                            timer: 2000
                        });

                        $('#stockUnitForm')[0].reset(); // Clear the form

                        // Add new entry to the table dynamically
                        $('#satuanTableBody').append(
                            `<tr id="row-${response.idsatuan}">
                                <td>${response.idsatuan}</td>
                                <td>${response.nama_satuan}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm editSatuan" data-id="${response.idsatuan}" data-nama_satuan="${response.nama_satuan}">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm deleteSatuan" data-id="${response.idsatuan}">Delete</button>
                                </td>
                            </tr>`
                        );
                    },
                    error: function(error) {
                        console.error(error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error adding Stock Unit.',
                            icon: 'error'
                        });
                    }
                });
            });

            // Delete Satuan
            $(document).on('click', '.deleteSatuan', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('satuan.delete', ['id' => ':id']) }}".replace(
                                ':id', id),
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                // Remove the table row
                                $(`#row-${id}`).remove();
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Stock Unit deleted successfully.',
                                    icon: 'success',
                                    timer: 2000
                                });
                            },
                            error: function(error) {
                                console.error(error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Error deleting Stock Unit.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Show edit modal with current data
            $(document).on('click', '.editSatuan', function() {
                let id = $(this).data('id');
                let nama_satuan = $(this).data('nama_satuan');

                // Set values in modal fields
                $('#edit_idsatuan').val(id);
                $('#edit_nama_satuan').val(nama_satuan);

                // Show modal
                $('#editStockUnitModal').modal('show');
            });

            // Handle edit form submission using AJAX
            $('#editStockUnitForm').on('submit', function(e) {
                e.preventDefault();

                let id = $('#edit_idsatuan').val();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('satuan.update', ['id' => ':id']) }}".replace(':id', id),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editStockUnitModal').modal('hide'); // Hide modal

                        // Update table row
                        $(`#row-${response.idsatuan} td:nth-child(2)`).text(response
                            .nama_satuan);

                        Swal.fire({
                            title: 'Success!',
                            text: 'Stock Unit updated successfully.',
                            icon: 'success',
                            timer: 2000
                        });
                    },
                    error: function(error) {
                        console.error(error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update Stock Unit.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@endsection
