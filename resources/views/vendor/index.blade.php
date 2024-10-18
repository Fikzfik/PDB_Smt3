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
                {{-- @dd($vendors); --}}
                <div class="tab-content tab-space">
                    <!-- Success Alert -->
                    <div id="success-alert" class="alert alert-success text-white font-weight-bold d-none" role="alert">
                        Stock Unit added successfully!
                    </div>

                    <div class="tab-pane active" id="create-stock-unit">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Stock Unit</h4>
                                <form id="vendorForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nama_vendor" class="form-label">Nama Vendor</label>
                                        <input type="text" class="form-control" id="nama_vendor" name="nama_vendor"
                                            placeholder="Enter vendor name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="badan_hukum" class="form-label">Badan Hukum</label>
                                        <select class="form-control" id="badan_hukum" name="badan_hukum" required>
                                            <option value="Y">Ya</option>
                                            <option value="N">Tidak</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Add Vendor</button>
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
                                        <th scope="col">Nama Vendor</th>
                                        <th scope="col">Badan Hukum</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="vendorTableBody">
                                    @foreach ($vendors as $vendor)
                                        <tr id="row-{{ $vendor->idvendor }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vendor->nama_vendor }}</td>
                                            <td>{{ $vendor->badan_hukum == 'Y' ? 'Ya' : 'Tidak' }}</td>
                                            <td>{{ $vendor->status == 1 ? 'Aktif' : 'Inactive' }}</td>
                                            <td>
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

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#vendorForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('vendor.create') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#success-alert').removeClass('d-none');

                        // Hide the alert after 3 seconds
                        setTimeout(function() {
                            $('#success-alert').addClass('d-none');
                        }, 3000);

                        $('#vendorForm')[0].reset(); // Reset form
                        $('#vendorTableBody').append(
                            `<tr id="row-${response.idvendor}">
                        <td>${response.idvendor}</td>
                        <td>${response.nama_vendor}</td>
                        <td>${response.badan_hukum == 'Y' ? 'Ya' : 'Tidak'}</td>
                        <td>${response.status == 1 ? 'Aktif' : 'Inactive'}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm deleteVendor" data-id="${response.idvendor}">Delete</button>
                        </td>
                    </tr>`
                        );
                    },
                    error: function(xhr) {
                        alert('Error adding vendor');
                        console.error(xhr.responseText);
                    }
                });
            });

            // Delete Vendor
            $(document).on('click', '.deleteVendor', function() {
                let id = $(this).data('id');
                if (confirm('Are you sure you want to delete this vendor?')) {
                    $.ajax({
                        url: "{{ route('vendor.delete', ['id' => ':id']) }}".replace(':id', id),
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $(`#row-${id}`).remove();
                        },
                        error: function(xhr) {
                            alert('Error deleting vendor');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection
