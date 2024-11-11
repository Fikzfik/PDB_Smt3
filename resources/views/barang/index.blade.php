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

                <!-- Edit Modal -->
                <div class="modal fade modal-lg" id="editBarangModal" tabindex="-1" role="dialog"
                    aria-labelledby="editBarangLabel" aria-hidden="true" data-bs-backdrop="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editBarangLabel">Edit Barang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editBarangForm">
                                    @csrf
                                    <input type="hidden" id="edit_idbarang" name="idbarang">
                                    <div class="mb-3">
                                        <label for="edit_jenis" class="form-label">Jenis Barang</label>
                                        <input type="text" class="form-control" id="edit_jenis" name="jenis" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_nama" class="form-label">Nama Barang</label>
                                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_idsatuan" class="form-label">Satuan</label>
                                        <select class="form-select" id="edit_idsatuan" name="idsatuan" required>
                                            <option value="">Select Satuan</option>
                                            @foreach ($satuan as $satuanItem)
                                                <option value="{{ $satuanItem->idsatuan }}">{{ $satuanItem->nama_satuan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_harga" class="form-label">Harga</label>
                                        <input type="number" class="form-control" id="edit_harga" name="harga" required>
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
                    <div id="success-alert" class="alert alert-success text-white font-weight-bold d-none" role="alert">
                        Barang added successfully!
                    </div>

                    <div class="tab-pane active" id="create-barang">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Barang</h4>
                                <form id="barangForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="jenis" class="form-label">Jenis Barang</label>
                                        <input type="text" class="form-control" id="jenis" name="jenis"
                                            placeholder="Enter jenis barang" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Barang</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            placeholder="Enter nama barang" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="idsatuan" class="form-label">Satuan</label>
                                        <select class="form-select" id="idsatuan" name="idsatuan" required>
                                            <option value="">Select Satuan</option>
                                            @foreach ($satuan as $satuanItem)
                                                <option value="{{ $satuanItem->idsatuan }}">{{ $satuanItem->nama_satuan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga</label>
                                        <input type="number" class="form-control" id="harga" name="harga"
                                            placeholder="Enter harga barang" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Add Barang</button>
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
                                        <th scope="col">Satuan</th>
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
                                            <td>{{ $item->nama_satuan }}</td>
                                            <td>{{ $item->harga }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm editBarang"
                                                    data-id="{{ $item->idbarang }}" data-jenis="{{ $item->jenis }}"
                                                    data-nama="{{ $item->nama }}"
                                                    data-idsatuan="{{ $item->idsatuan }}"
                                                    data-harga="{{ $item->harga }}">Edit</button>
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

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle form submission using AJAX for adding barang
            $('#barangForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('barang.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000
                        });

                        $('#barangForm')[0].reset();

                        $('#barangTableBody').append(`
                            <tr id="row-${response.data.idbarang}">
                                <td>${response.data.idbarang}</td>
                                <td>${response.data.jenis}</td>
                                <td>${response.data.nama}</td>
                                <td>${response.data.satuan}</td>
                                <td>${response.data.harga}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm editBarang" data-id="${response.data.idbarang}" data-jenis="${response.data.jenis}" data-nama="${response.data.nama}" data-idsatuan="${response.data.idsatuan}" data-harga="${response.data.harga}">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm deleteBarang" data-id="${response.data.idbarang}">Delete</button>
                                </td>
                            </tr>
                        `);
                    }
                });
            });

            // Handle editing of barang
            // Handle editing of barang
            $(document).on('click', '.editBarang', function() {
                let id = $(this).data('id');
                let jenis = $(this).data('jenis');
                let nama = $(this).data('nama');
                let idsatuan = $(this).data('idsatuan');
                let harga = $(this).data('harga');

                $('#edit_idbarang').val(id);
                $('#edit_jenis').val(jenis);
                $('#edit_nama').val(nama);
                $('#edit_idsatuan').val(idsatuan);
                $('#edit_harga').val(harga);

                // Disable page scroll when modal is open
                $('body').css('overflow', 'hidden'); // Nonaktifkan scroll halaman utama
                $('#editBarangModal').modal('show');
            });

            // Setelah modal ditutup, kembalikan scroll halaman utama
            $('#editBarangModal').on('shown.bs.modal', function() {
                $('html, body').animate({
                    scrollTop: 400 // Atur nilai scroll lebih banyak, misalnya 200px dari atas
                }, 300); // Durasi animasi 300ms
            });


            // Handle update barang
            $('#editBarangForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let id = $('#edit_idbarang').val();

                $.ajax({
                    url: `/barang/update/${id}`,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000
                        });

                        // Update row in table
                        let row = $('#row-' + id);
                        row.find('td:eq(1)').text($('#edit_jenis').val());
                        row.find('td:eq(2)').text($('#edit_nama').val());
                        row.find('td:eq(3)').text($('#edit_idsatuan').val());
                        row.find('td:eq(4)').text($('#edit_harga').val());

                        $('#editBarangModal').modal('hide');
                    }
                });
            });

            $(document).ready(function() {
                // Menambahkan CSRF token ke header AJAX
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Script untuk tombol delete
                $(document).on('click', '.deleteBarang', function() {
                    let id = $(this).data('id');
                    let row = $('#row-' + id);

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/barang/${id}`,
                                type: 'DELETE',
                                success: function(response) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 2000
                                    });

                                    row.remove();
                                }
                            });
                        }
                    });
                });
            });

        });
    </script>
@endsection
