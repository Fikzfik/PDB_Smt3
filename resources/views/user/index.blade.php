@extends('app', ['showHeader' => true])

@section('field-content')
    <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
        <div class="col-12">
            <div class="position-relative border-radius-xl overflow-hidden shadow-lg mb-7 px-3">
                <div class="container border-bottom px-2">
                    <div class="row justify-space-between py-2">
                        <div class="col-lg-3 me-auto">
                            <p class="lead text-dark pt-1 mb-0">Manage Users</p>
                        </div>
                        <div class="col-lg-3">
                            <div class="nav-wrapper position-relative end-0">
                                <ul class="nav nav-pills nav-fill flex-row p-1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#create-user"
                                            role="tab" aria-controls="create" aria-selected="true">
                                            <i class="fas fa-plus text-sm me-2"></i> Create
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#table-user"
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
                        User added successfully!
                    </div>

                    <div class="tab-pane active" id="create-user">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input User</h4>
                                <form id="userForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            placeholder="Enter username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Enter password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="idrole" class="form-label">Role</label>
                                        <select class="form-control" id="idrole" name="idrole" required>
                                            <option value="" disabled selected>Select Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->idrole }}">{{ $role->nama_role }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Add User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table Users -->
                    <div class="tab-pane" id="table-user">
                        <div class="table-responsive p-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="userTableBody">
                                    @foreach ($users as $user)
                                        <tr id="row-{{ $user->iduser }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->idrole }}</td>
                                            <td>{{ $user->status == 1 ? 'Aktif' : 'Inactive' }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm deleteUser"
                                                    data-id="{{ $user->iduser }}">Delete</button>
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
            $('#userForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('users.store') }}", // Route untuk create user
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#success-alert').removeClass('d-none');

                        // Hide the alert after 3 seconds
                        setTimeout(function() {
                            $('#success-alert').addClass('d-none');
                        }, 3000);

                        $('#userForm')[0].reset(); // Reset form
                        $('#userTableBody').append(
                            `<tr id="row-${response.id}">
                                <td>${response.iduser}</td>
                                <td>${response.username}</td>
                                <td>${response.email}</td>
                                <td>${response.idrole}</td>
                                <td>${response.status == 1 ? 'Aktif' : 'Inactive'}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm deleteUser" data-id="${response.id}">Delete</button>
                                </td>
                            </tr>`
                        );
                    },
                    error: function(xhr) {
                        alert('Error adding user');
                        console.error(xhr.responseText);
                    }
                });
            });

            // Delete User
            $(document).on('click', '.deleteUser', function() {
                let id = $(this).data('id');
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: "{{ route('users.destroy', ['id' => ':id']) }}".replace(':id',
                            id), // Route untuk delete user
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $(`#row-${id}`).remove();
                        },
                        error: function(xhr) {
                            alert('Error deleting user');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection
