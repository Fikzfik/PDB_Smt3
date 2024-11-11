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
                                <h4 class="text-center">Form Input New Role</h4>
                                <form id="stockUnitForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nama_role" class="form-label">Nama Role</label>
                                        <input type="text" class="form-control" id="nama_role" name="nama_role"
                                            placeholder="Enter role name" required>
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
                                        <th scope="col">Nama Role</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="satuanTableBody">
                                    @foreach ($roles as $item)
                                        <tr id="row-{{ $item->idrole }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_role }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm editSatuan"
                                                    data-id="{{ $item->idrole }}"
                                                    data-nama="{{ $item->nama_role }}">Edit</button>
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
    <!-- Modal Edit -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel"
        aria-hidden="true" data-bs-backdrop="false" data-bs-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRoleForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        <!-- Laravel mendukung metode ini untuk form POST -->
                        <div class="mb-3">
                            <label for="nama_role" class="form-label">Nama Role</label>
                            <input type="text" class="form-control" id="nama_role" name="nama_role" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100">Update Role</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        
        $(document).ready(function() {
            // Handle form submission for Create
            $('#stockUnitForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('roles.create') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#success-alert').removeClass('d-none');
                        setTimeout(function() {
                            $('#success-alert').addClass('d-none');
                        }, 3000);

                        $('#stockUnitForm')[0].reset();
                        $('#satuanTableBody').append(`
                            <tr id="row-${response.idrole}">
                                <td>${response.idrole}</td>
                                <td>${response.nama_role}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm editSatuan" data-id="${response.idrole}" data-nama="${response.nama_role}">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm deleteSatuan" data-id="${response.idrole}">Delete</button>
                                </td>
                            </tr>
                        `);
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error adding role.');
                    }
                });
            });

            // Handle Edit Button Click
            $(document).on('click', '.editSatuan', function() {
                let id = $(this).data('id');
                let nama_role = $(this).data('nama');

                // Prefill modal form dengan data yang ada
                $('#nama_role').val(nama_role);
                $('#editRoleForm').data('id', id); // Simpan ID di form modal

                // Tampilkan modal
                $('#editRoleModal').modal('show');
            });



            // Handle form submission for Update
            // Handle form submission for Update
            // Handle form submission for Update
            $('#editRoleForm').on('submit', function(e) {
                e.preventDefault();

                let id = $(this).data('id'); // Ambil ID dari data form
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('roles.update', ['id' => ':id']) }}".replace(':id',
                        id), // Ganti :id dengan ID role
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Update data di tabel
                        $(`#row-${response.idrole} td:nth-child(2)`).text(response.nama_role);

                        // Tutup modal
                        $('#editRoleModal').modal('hide');

                        // Reset form
                        $('#editRoleForm')[0].reset();
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error updating role.');
                    }
                });
            });



            // Handle Delete
            $(document).on('click', '.deleteSatuan', function() {
                let id = $(this).data('id');

                // SweetAlert2 untuk konfirmasi sebelum menghapus
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
                            url: "{{ route('roles.delete', ['id' => ':id']) }}".replace(
                                ':id', id),
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                // SweetAlert2 untuk notifikasi sukses setelah berhasil menghapus
                                Swal.fire(
                                    "Deleted!",
                                    "The role has been deleted.",
                                    "success"
                                );

                                // Hapus baris dari tabel
                                $(`#row-${id}`).remove();
                            },
                            error: function(error) {
                                console.error(error);
                                Swal.fire(
                                    "Error!",
                                    "There was a problem deleting the role.",
                                    "error"
                                );
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
