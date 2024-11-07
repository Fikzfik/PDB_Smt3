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
                        <section class="section">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Table Penerimaan Diterima</h3>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">ID Penerimaan</th>
                                                        <th scope="col">Tanggal</th>
                                                        <th scope="col">User</th>
                                                        <th scope="col">ID Pengadaan</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($penerimaans as $penerimaan)
                                                        <tr>
                                                            <td>{{ $penerimaan->idpenerimaan }}</td>
                                                            <td>{{ $penerimaan->created_at }}</td>
                                                            <td>{{ $penerimaan->username }}</td>
                                                            <td>{{ $penerimaan->idpengadaan }}</td>
                                                            <td>
                                                                @if ($penerimaan->status == 'A')
                                                                    <p class="text-success">DITERIMA</p>
                                                                @elseif ($penerimaan->status == 'B' || $penerimaan->status == 'C')
                                                                    <p class="text-danger">RETURNED ALL</p>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-primary"
                                                                    onclick="detailPenerimaan({{ $penerimaan->idpengadaan }})">DETAIL</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Modal Detail Penerimaan -->
                                    <div class="modal fade" id="modalDetailPenerimaan" tabindex="-1"
                                        aria-labelledby="modalDetailPenerimaanLabel"
                                        aria-hidden="true"data-bs-backdrop="false">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="modalDetailPenerimaanLabel">Detail
                                                        Penerimaan</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Tabel Ringkasan Penerimaan -->
                                                    <h5>Ringkasan Jumlah Barang Diterima</h5>
                                                    <table id="tableRingkasanPenerimaan"
                                                        class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Nama Barang</th>
                                                                <th scope="col">Total Jumlah Diterima</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>

                                                    <!-- Tabel Detail Penerimaan -->
                                                    <h5>Detail Barang Diterima</h5>
                                                    <table id="tableDetailPenerimaan"
                                                        class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">ID Detail</th>
                                                                <th scope="col">Barang</th>
                                                                <th scope="col">Harga Satuan</th>
                                                                <th scope="col">Jumlah Terima</th>
                                                                <th scope="col">Sub Total</th>
                                                                <th scope="col">Tanggal Penerimaan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
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

    <!-- Script untuk ajax detail penerimaan -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        function detailPenerimaan(idPengadaan) {
            $.ajax({
                type: "GET",
                url: `/penerimaan/detail/${idPengadaan}`,
                dataType: "JSON",
                success: function(data) {
                    $('#tableDetailPenerimaan tbody').empty();
                    $('#tableRingkasanPenerimaan tbody').empty();

                    // Hitung total jumlah per barang
                    const ringkasan = {};
                    data.forEach(item => {
                        if (ringkasan[item.nama_barang]) {
                            ringkasan[item.nama_barang] += item.jumlah_terima;
                        } else {
                            ringkasan[item.nama_barang] = item.jumlah_terima;
                        }
                    });

                    // Tambahkan data ke tabel ringkasan dan tombol Return
                    for (const [namaBarang, totalJumlah] of Object.entries(ringkasan)) {
                        let row = `
                            <tr>
                                <td>${namaBarang}</td>
                                <td>${totalJumlah}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="viewReturnList(${idPengadaan})">
                                        Return
                                    </button>
                                </td>
                            </tr>`;
                        $('#tableRingkasanPenerimaan tbody').append(row);
                    }

                    // Tampilkan detail penerimaan barang
                    data.forEach(item => {
                        let rowClass = item.jumlah_terima < item.jumlah_pengadaan ? 'table-danger' : '';
                        let row = `
                        <tr class="${rowClass}">
                            <td>${item.iddetail_penerimaan}</td>
                            <td>${item.nama_barang}</td>
                            <td>${item.harga_satuan_terima}</td>
                            <td>${item.jumlah_terima}</td>
                            <td>${item.sub_total_terima}</td>
                            <td>${item.tanggal_penerimaan}</td>
                        </tr>`;
                        $('#tableDetailPenerimaan tbody').append(row);
                    });

                    $('#modalDetailPenerimaan').modal('show');
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error :
                        'Gagal mengambil detail penerimaan.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: errorMsg,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function viewReturnList(idPengadaan) {
            // Redirect ke route untuk melihat daftar penerimaan berdasarkan idPengadaan
            window.location.href = `{{ route('penerimaan.list', '') }}/${idPengadaan}`;
        }
    </script>
@endsection
