@extends('app', ['showHeader' => false])

@section('field-content')
    <div class="page-header align-items-start min-vh-100"
        style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');"
        loading="lazy">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-12 col-md-8 col-12 mx-auto">
                    <main id="main" class="main">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show col-md-12">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger alert-dismissible fade show col-md-12">
                                {{ session('errors') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <section class="section">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Table Penjualan</h3>
                                        </div>
                                        <div class="card-body">
                                            <a href="{{ route('penjualan.create') }}">
                                                <button class="btn btn-primary" style="margin-bottom: 5px"> + Tambah Data
                                                </button>
                                            </a>
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Id Penjualan</th>
                                                        <th scope="col">User</th>
                                                        <th scope="col">Nama Pembeli</th>
                                                        <th scope="col">Sub_Total</th>
                                                        <th scope="col">Total_Nilai</th>
                                                        <th scope="col">PPN</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($penjualans as $penjualan)
                                                    {{-- @dd($penjualan); --}}
                                                        <tr>
                                                            <td>{{ $penjualan->idpenjualan }}</td>
                                                            <td>{{ $penjualan->username }}</td>
                                                            <td>{{ $penjualan->subtotal_nilai }}</td>
                                                            <td>{{ $penjualan->total_nilai }}</td>
                                                            <td>{{ $penjualan->ppn }}</td>
                                                            <td>{{ $penjualan->margin }}</td>
                                                            <td>
                                                                <button class="btn btn-primary"
                                                                    onclick="detail({{ $penjualan->idpenjualan }})">DETAIL</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Modal Detail Penjualan -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Penjualan
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table id="tableDetail" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Id Detail</th>
                                                                <th scope="col">Id Penjualan</th>
                                                                <th scope="col">Barang</th>
                                                                <th scope="col">Harga Satuan</th>
                                                                <th scope="col">Jumlah</th>
                                                                <th scope="col">Sub Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer" id="modalFooter">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </section>
                    </main>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        function detail(id) {
            $.ajax({
                type: "GET",
                url: `/penjualan/detail/${id}`,
                dataType: "JSON",
                success: function(data) {
                    $('#tableDetail tbody').empty();
                    if (data.length > 0) {
                        data.forEach(item => {
                            let row = `
                        <tr>
                            <td>${item.iddetail_penjualan}</td>
                            <td>${item.idpenjualan}</td>
                            <td>${item.nama}</td>
                            <td>${item.harga_satuan}</td>
                            <td>${item.jumlah}</td>
                            <td>${item.sub_total}</td>
                        </tr>`;
                            $('#tableDetail tbody').append(row);
                        });
                        $('#exampleModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Tidak Ditemukan!',
                            text: `Tidak ada detail penjualan yang ditemukan untuk ID penjualan: ${id}.`,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: 'Terjadi kesalahan saat mengambil data.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    </script>
@endsection
