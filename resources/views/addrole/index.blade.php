@extends('app', ['showHeader' => true])

@section('field-content')
    <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
        <div class="col-12">
            <div class="position-relative border-radius-xl overflow-hidden shadow-lg mb-7 px-3">
                <div class="container border-bottom px-2">
                    <div class="row justify-space-between py-2">
                        <div class="col-lg-3 me-auto">
                            <p class="lead text-dark pt-1 mb-0">Manage Sales Unit</p>
                        </div>
                        <div class="col-lg-3">
                            <div class="nav-wrapper position-relative end-0">
                                <ul class="nav nav-pills nav-fill flex-row p-1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab"
                                            href="#create-sales-unit" role="tab" aria-controls="create"
                                            aria-selected="true">
                                            <i class="fas fa-plus text-sm me-2"></i> Create
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#table-sales-unit"
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
                        Sales Unit added successfully!
                    </div>

                    <div class="tab-pane active" id="create-sales-unit">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input New Sales Role</h4>
                                <form id="salesUnitForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nama_role" class="form-label">Nama Sales Role</label>
                                        <input type="text" class="form-control" id="nama_role" name="nama_role"
                                            placeholder="Enter sales role name" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Add Sales Unit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table Sales Unit -->
                    <div class="tab-pane" id="table-sales-unit">
                        <div class="table-responsive p-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Sales Role</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="salesUnitTableBody">
                                    @foreach ($salesRoles as $item)
                                        <tr id="row-{{ $item->idrole }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_role }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm editSalesUnit"
                                                    data-id="{{ $item->idrole }}"
                                                    data-nama="{{ $item->nama_role }}">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm deleteSalesUnit"
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

    <!-- Modal Edit Sales -->
    <div class="modal fade" id="editSalesRoleModal" tabindex="-1" aria-labelledby="editSalesRoleModalLabel"
        aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSalesRoleModalLabel">Edit Sales Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSalesRoleForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        <div class="mb-3">
                            <label for="nama_role" class="form-label">Nama Sales Role</label>
                            <input type="text" class="form-control" id="nama_role" name="nama_role" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100">Update Sales Role</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle form submission for Create Sales Role
            $('#salesUnitForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('salesRoles.create') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#success-alert').removeClass('d-none');
                        setTimeout(function() {
                            $('#success-alert').addClass('d-none');
                        }, 3000);

                        $('#salesUnitForm')[0].reset();
                        $('#salesUnitTableBody').append(`
                            <tr id="row-${response.idrole}">
                                <td>${response.idrole}</td>
                                <td>${response.nama_role}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm editSalesUnit" data-id="${response.idrole}" data-nama="${response.nama_role}">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm deleteSalesUnit" data-id="${response.idrole}">Delete</button>
                                </td>
                            </tr>
                        `);
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error adding sales role.');
                    }
                });
            });

            // Handle Edit Button Click
            $(document).on('click', '.editSalesUnit', function() {
                let id = $(this).data('id');
                let nama_role = $(this).data('nama');

                $('#nama_role').val(nama_role);
                $('#editSalesRoleForm').data('id', id); // Save ID in form modal

                $('#editSalesRoleModal').modal('show');
            });

            // Handle form submission for Update Sales Role
            $('#editSalesRoleForm').on('submit', function(e) {
                e.preventDefault();

                let id = $(this).data('id');
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('salesRoles.update', ['id' => ':id']) }}".replace(':id', id),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Update table data
                        $(`#row-${response.idrole} td:nth-child(2)`).text(response.nama_role);

                        $('#editSalesRoleModal').modal('hide');
                        $('#editSalesRoleForm')[0].reset();
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error updating sales role.');
                    }
                });
            });

            // Handle Delete Sales Role
            $(document).on('click', '.deleteSalesUnit', function() {
                let id = $(this).data('id');

                // SweetAlert2 confirmation
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('salesRoles.delete', ['id' => ':id']) }}".replace(':id', id),
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire("Deleted!", "The sales role has been deleted.", "success");
                                $(`#row-${id}`).remove();
                            },
                            error: function(error) {
                                console.error(error);
                                Swal.fire("Error!", "There was an error deleting the sales role.", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
