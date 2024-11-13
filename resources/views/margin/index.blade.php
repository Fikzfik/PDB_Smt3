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

    <!-- Modal Edit Margin Penjualan -->
    <div class="modal fade" id="editMarginModal" tabindex="-1" aria-labelledby="editMarginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMarginModalLabel">Edit Margin Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMarginForm" method="POST">
                        @csrf
                        <input type="hidden" id="editMarginId" name="id">
                        <div class="mb-3">
                            <label for="editPersen" class="form-label">Persentase Margin</label>
                            <input type="number" class="form-control" id="editPersen" name="persen" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status Margin</label>
                            <select class="form-control" id="editStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Margin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Create Margin Penjualan
            $('#marginForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('margin.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#success-alert').removeClass('d-none').text(
                                'Margin Penjualan added successfully!');
                            $('#marginForm')[0].reset();
                            $('#table-margin').find('tbody').append(`
                        <tr id="row-${response.data.idmargin_penjualan}">
                            <td>${response.data.idmargin_penjualan}</td>
                            <td>${response.data.persen}%</td>
                            <td>${response.data.status}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm editMargin" data-id="${response.data.idmargin_penjualan}">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm deleteMargin" data-id="${response.data.idmargin_penjualan}">Delete</button>
                            </td>
                        </tr>
                    `);
                        } else {
                            alert('Failed to add margin.');
                        }
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error adding margin.');
                    }
                });
            });

            // Edit Margin Penjualan
            $(document).on('click', '.editMargin', function() {
                let id = $(this).data('id');
                let persen = $(`#row-${id} td`).eq(1).text().replace('%', '');
                let status = $(`#row-${id} td`).eq(2).text().toLowerCase();

                $('#editMarginId').val(id);
                $('#editPersen').val(persen);
                $('#editStatus').val(status);

                $('#editMarginModal').modal('show');
            });

            // Update Margin Penjualan
            $('#editMarginForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let id = $('#editMarginId').val();

                $.ajax({
                    url: "{{ route('margin.update', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#editMarginModal').modal('hide');
                            $('#success-alert').removeClass('d-none').text(
                                'Margin Penjualan berhasil diperbarui.');

                            let row = $(`#row-${response.data.idmargin_penjualan}`);
                            row.find('td').eq(1).text(response.data.persen + '%');
                            row.find('td').eq(2).text(response.data.status ? 'Active' :
                                'Inactive');
                        } else {
                            alert('Gagal memperbarui margin.');
                        }
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error updating margin.');
                    }
                });
            });

            // Delete Margin
            $(document).on('click', '.deleteMargin', function() {
                let id = $(this).data('id');
                if (confirm('Are you sure you want to delete this margin?')) {
                    $.ajax({
                        url: `{{ route('margin.delete', ':id') }}`.replace(':id', id),
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                $(`#row-${id}`).remove();
                                alert('Margin Penjualan deleted successfully');
                            } else {
                                alert('Failed to delete margin.');
                            }
                        },
                        error: function(error) {
                            console.error(error);
                            alert('Error deleting margin.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
