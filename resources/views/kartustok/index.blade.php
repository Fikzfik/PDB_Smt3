@extends('app', ['showHeader' => true])

@section('field-content')
    <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
        <div class="col-12">
            <div class="position-relative border-radius-xl overflow-hidden shadow-lg mb-7 px-3">
                <div class="container border-bottom px-2">
                    <div class="row justify-space-between py-2">
                        <div class="col-lg-3 me-auto">
                            <p class="lead text-dark pt-1 mb-0">Manage Kartu Stok</p>
                        </div>
                        <div class="col-lg-3">
                            <div class="nav-wrapper position-relative end-0">
                                <ul class="nav nav-pills nav-fill flex-row p-1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab"
                                            href="#create-kartu-stok" role="tab" aria-controls="create"
                                            aria-selected="true">
                                            <i class="fas fa-plus text-sm me-2"></i> Create
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#table-kartu-stok"
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
                    <!-- Form Input Kartu Stok -->
                    <div class="tab-pane active" id="create-kartu-stok">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Kartu Stok</h4>
                                <form action="{{ route('kartuStok.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                                        <select class="form-control" id="jenis_transaksi" name="jenis_transaksi" required>
                                            <option value="M">Masuk</option>
                                            <option value="K">Keluar</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="masuk" class="form-label">Jumlah Masuk</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah"
                                            value="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="idbarang" class="form-label">Pilih Barang</label>
                                        <select class="form-control" id="idbarang" name="idbarang" required>
                                            @foreach ($barang as $item)
                                                <option value="{{ $item->idbarang }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Kartu Stok -->
                    <div class="tab-pane" id="table-kartu-stok">
                        <div class="table-responsive p-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Jenis Transaksi</th>
                                        <th scope="col">Masuk</th>
                                        <th scope="col">Keluar</th>
                                        <th scope="col">Stok</th>
                                        <th scope="col">ID Barang</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kartu_stok as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->jenis_transaksi == 'M' ? 'Masuk' : 'Keluar' }}</td>
                                            <td>{{ $item->masuk }}</td>
                                            <td>{{ $item->keluar }}</td>
                                            <td>{{ $item->stock }}</td>
                                            <td>{{ $item->idbarang }}</td>
                                            <td>{{ $item->create_at }}</td>
                                            <td>
                                                <form action="{{ route('kartuStok.delete', $item->idkartu_stok) }}"
                                                    method="POST" class="d-inline">
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
