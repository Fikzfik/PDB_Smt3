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
                    </div>
                </div>

                <!-- Modal Edit Margin Penjualan -->
                <div class="modal fade modal-lg" id="editMarginPenjualanModal" tabindex="-1" role="dialog"
                    aria-labelledby="editMarginPenjualanLabel" aria-hidden="true" data-bs-backdrop="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editMarginPenjualanLabel">Edit Margin Penjualan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editMarginPenjualanForm">
                                    @csrf
                                    <input type="hidden" id="edit_id" name="id">
                                    <div class="mb-3">
                                        <label for="edit_jenis_barang" class="form-label">Jenis Barang</label>
                                        <input type="text" class="form-control" id="edit_jenis_barang" name="jenis_barang" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_harga_jual" class="form-label">Harga Jual</label>
                                        <input type="number" class="form-control" id="edit_harga_jual" name="harga_jual" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_harga_beli" class="form-label">Harga Beli</label>
                                        <input type="number" class="form-control" id="edit_harga_beli" name="harga_beli" required>
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
                    <div class="tab-pane active" id="create-margin-penjualan">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Margin Penjualan</h4>
                                <form id="marginPenjualanForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="jenis_barang" class="form-label">Jenis Barang</label>
                                        <input type="text" class="form-control" id="jenis_barang" name="jenis_barang" placeholder="Enter jenis barang" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga_jual" class="form-label">Harga Jual</label>
                                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" placeholder="Enter harga jual" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga_beli" class="form-label">Harga Beli</label>
                                        <input type="number" class="form-control" id="harga_beli" name="harga_beli" placeholder="Enter harga beli" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Add Margin Penjualan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table Margin Penjualan -->
                    <div class="tab-pane" id="table-margin-penjualan">
                        <div class="table-responsive p-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Jenis Barang</th>
                                        <th scope="col">Harga Jual</th>
                                        <th scope="col">Harga Beli</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="marginPenjualanTableBody">
                                    @foreach ($marginPenjualan as $item)
                                        <tr id="row-{{ $item->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->jenis_barang }}</td>
                                            <td>{{ $item->harga_jual }}</td>
                                            <td>{{ $item->harga_beli }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm editMarginPenjualan"
                                                    data-id="{{ $item->id }}" data-jenis_barang="{{ $item->jenis_barang }}"
                                                    data-harga_jual="{{ $item->harga_jual }}" data-harga_beli="{{ $item->harga_beli }}">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm deleteMarginPenjualan"
                                                    data-id="{{ $item->id }}">Delete</button>
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

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle form submission using AJAX for adding margin penjualan
            $('#marginPenjualanForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('marginPenjualan.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Show Edit Modal with prefilled data
            $('.editMarginPenjualan').on('click', function() {
                var id = $(this).data('id');
                var jenis_barang = $(this).data('jenis_barang');
                var harga_jual = $(this).data('harga_jual');
                var harga_beli = $(this).data('harga_beli');

                $('#edit_id').val(id);
                $('#edit_jenis_barang').val(jenis_barang);
                $('#edit_harga_jual').val(harga_jual);
                $('#edit_harga_beli').val(harga_beli);

                $('#editMarginPenjualanModal').modal('show');
            });

            // Handle form submission for updating margin penjualan
            $('#editMarginPenjualanForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let id = $('#edit_id').val();

                $.ajax({
                    url: "{{ url('/margin-penjualan/update') }}/" + id,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Handle delete margin penjualan
            $('.deleteMarginPenjualan').on('click', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('/margin-penjualan') }}/" + id,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    $('#row-' + id).remove();
                                });
                            },
                            error: function(error) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to delete the item.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
