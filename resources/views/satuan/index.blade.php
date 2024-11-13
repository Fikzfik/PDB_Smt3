@extends('app', ['showHeader' => true])

@section('field-content')
    <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
        <div class="col-12">
            <div class="position-relative border-radius-xl overflow-hidden shadow-lg mb-7 px-3">
                <div class="container border-bottom px-2">
                    <div class="row justify-space-between py-2">
                        <div class="col-lg-3 me-auto">
                            <p class="lead text-dark pt-1 mb-0">Manage Vendor</p>
                        </div>
                        <div class="col-lg-3">
                            <div class="nav-wrapper position-relative end-0">
                                <ul class="nav nav-pills nav-fill flex-row p-1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab"
                                            href="#create-vendor" role="tab" aria-controls="create"
                                            aria-selected="true">
                                            <i class="fas fa-plus text-sm me-2"></i> Create
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#table-vendor"
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
                <div class="modal fade" id="editVendorModal" tabindex="-1" role="dialog"
                    aria-labelledby="editVendorLabel" aria-hidden="true" data-bs-backdrop="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editVendorLabel">Edit Vendor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editVendorForm">
                                    @csrf
                                    <input type="hidden" id="edit_idvendor" name="idvendor">
                                    <div class="mb-3">
                                        <label for="edit_nama_vendor" class="form-label">Nama Vendor</label>
                                        <input type="text" class="form-control" id="edit_nama_vendor" name="nama_vendor"
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
                        Vendor added successfully!
                    </div>

                    <div class="tab-pane active" id="create-vendor">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Vendor</h4>
                                <form id="vendorForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nama_vendor" class="form-label">Nama Vendor</label>
                                        <input type="text" class="form-control" id="nama_vendor" name="nama_vendor"
                                            placeholder="Enter vendor name" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Add Vendor</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table Vendor -->
                    <div class="tab-pane" id="table-vendor">
                        <div class="table-responsive p-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Vendor</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="vendorTableBody">
                                    @foreach ($vendors as $vendor)
                                        <tr id="row-{{ $vendor->idvendor }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vendor->nama_vendor }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm editVendor"
                                                    data-id="{{ $vendor->idvendor }}"
                                                    data-nama_vendor="{{ $vendor->nama_vendor }}">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm deleteVendor"
                                                    data-id="{{ $vendor->idvendor }}">Delete</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#vendorForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('vendor.create') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire('Success!', 'Vendor added successfully!', 'success');
                        $('#vendorForm')[0].reset();
                        $('#vendorTableBody').append(
                            `<tr id="row-${response.idvendor}">
                                <td>${response.idvendor}</td>
                                <td>${response.nama_vendor}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm editVendor" data-id="${response.idvendor}" data-nama_vendor="${response.nama_vendor}">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm deleteVendor" data-id="${response.idvendor}">Delete</button>
                                </td>
                            </tr>`
                        );
                    },
                    error: function(error) {
                        Swal.fire('Error!', 'Failed to add vendor.', 'error');
                    }
                });
            });

            $(document).on('click', '.deleteVendor', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete the vendor.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('vendor.delete', ['id' => ':id']) }}".replace(':id', id),
                            type: 'DELETE',
                            data: { "_token": "{{ csrf_token() }}" },
                            success: function() {
                                $(`#row-${id}`).remove();
                                Swal.fire('Deleted!', 'Vendor deleted.', 'success');
                            },
                            error: function() {
                                Swal.fire('Error!', 'Failed to delete vendor.', 'error');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.editVendor', function() {
                let id = $(this).data('id');
                let nama_vendor = $(this).data('nama_vendor');
                $('#edit_idvendor').val(id);
                $('#edit_nama_vendor').val(nama_vendor);
                $('#editVendorModal').modal('show');
            });

            $('#editVendorForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#edit_idvendor').val();
                let formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('vendor.update', ['id' => ':id']) }}".replace(':id', id),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editVendorModal').modal('hide');
                        $(`#row-${response.idvendor} td:nth-child(2)`).text(response.nama_vendor);
                        Swal.fire('Success!', 'Vendor updated successfully.', 'success');
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to update vendor.', 'error');
                    }
                });
            });
        });
    </script>
@endsection
