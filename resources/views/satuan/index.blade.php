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
                        <div class="col-lg-3 ">
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
                    <div class="tab-pane active" id="create-stock-unit">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Stock Unit</h4>
                                <form action="{{ route('satuan.create') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nama_satuan" class="form-label">Nama Satuan</label>
                                        <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" placeholder="Enter stock unit name" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Add Unit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                                <tbody>
                                    @foreach ($satuan as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_satuan }}</td>
                                            <td>
                                                <a href="{{ route('satuan.edit', $item->idsatuan) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('satuan.delete', $item->idsatuan) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
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
@endsection
