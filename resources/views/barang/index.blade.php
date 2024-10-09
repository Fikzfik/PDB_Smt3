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
                                <h4 class="text-center">Form Input Barang</h4>
                                <form action="{{ route('barang.store') }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group input-group-dynamic mb-4">
                                                    <label class="form-label mt-n3">Jenis</label>
                                                    <input class="form-control" aria-label="Jenis" type="text"
                                                        id="jenis" name="jenis" placeholder="Enter barang jenis"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group input-group-dynamic mb-4">
                                                    <label class="form-label mt-n3">Nama Barang</label>
                                                    <input class="form-control" aria-label="Nama Barang" type="text"
                                                        id="nama" name="nama" placeholder="Enter barang name"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group input-group-dynamic mb-4">
                                                    <label class="form-label mt-n3">Harga Barang</label>
                                                    <input class="form-control" aria-label="Harga" type="number"
                                                        id="harga" name="harga" placeholder="Enter harga barang"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="idsatuan">Pilih Satuan</label>
                                                <select class="form-control" id="idsatuan" name="idsatuan" required>
                                                    <option value="">-- Pilih Satuan --</option>
                                                    @foreach ($satuan as $item)
                                                        <option value="{{ $item->idsatuan }}">{{ $item->nama_satuan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary w-100">Add Barang</button>
                                            </div>
                                        </div>
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
                                        <th scope="col">Jenis Barang</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barang as $item)
                                        <tr>
                                            {{-- @dd($item); --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->nama_satuan }}</td>
                                            <td>{{ $item->harga }}</td>
                                            <td>
                                                <a href="{{ route('barang.edit', $item->idsatuan) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('barang.delete', $item->idsatuan) }}" method="POST"
                                                    class="d-inline">
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
