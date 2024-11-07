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
                                            <h3 class="card-title">Daftar Semua Penerimaan untuk Pengadaan
                                                #{{ $idPengadaan }}</h3>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped mt-3">
                                                <thead>
                                                    <tr>
                                                        <th>ID Penerimaan</th>
                                                        <th>Tanggal Penerimaan</th>
                                                        <th>Detail</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($penerimaanList as $penerimaan)
                                                        <tr>
                                                            <td>{{ $penerimaan->idpenerimaan }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($penerimaan->tanggal_penerimaan)->format('d-m-Y H:i') }}
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-info"
                                                                    onclick="toggleDetail({{ $penerimaan->idpenerimaan }})">
                                                                    Lihat Detail
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr id="detail-{{ $penerimaan->idpenerimaan }}" class="collapse detail-row">
                                                            <td colspan="3">
                                                                <table class="table table-bordered mt-2">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>ID Detail</th>
                                                                            <th>Nama Barang</th>
                                                                            <th>Jumlah Diterima</th>
                                                                            <th>Harga Satuan</th>
                                                                            <th>Subtotal</th>
                                                                            <th>Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($detailPenerimaan[$penerimaan->idpenerimaan] ?? [] as $detail)
                                                                            <tr>
                                                                                <td>{{ $detail->iddetail_penerimaan }}</td>
                                                                                <td>{{ $detail->nama }}</td>
                                                                                <td>{{ $detail->jumlah_terima }}</td>
                                                                                <td>{{ number_format($detail->harga_satuan_terima, 0, ',', '.') }}
                                                                                </td>
                                                                                <td>{{ number_format($detail->sub_total_terima, 0, ',', '.') }}
                                                                                </td>
                                                                                <td>
                                                                                    <button class="btn btn-danger"
                                                                                        onclick="showReturnModal({{ $detail->iddetail_penerimaan }}, {{ $detail->jumlah_terima }}, '{{ $detail->nama }}')">
                                                                                        Return
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Kembali ke
                                                Daftar Penerimaan</a>
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

    <!-- Modal untuk Return Item -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Pengembalian Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Nama Barang: <span id="itemName"></span></p>
                    <p>Stok Tersedia: <span id="availableStock"></span></p>
                    <div class="mb-3">
                        <label for="returnQuantity" class="form-label">Jumlah yang ingin dikembalikan:</label>
                        <input type="number" class="form-control" id="returnQuantity" min="1" max="5">
                    </div>
                    <div class="mb-3">
                        <label for="returnReason" class="form-label">Alasan Pengembalian:</label>
                        <textarea class="form-control" id="returnReason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="confirmReturnBtn">Kembalikan Barang</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        .collapse {
            display: none;
        }

        .detail-row {
            transition: max-height 0.3s ease, opacity 0.3s ease;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
        }

        .detail-row.show {
            display: table-row;
            max-height: 500px;
            opacity: 1;
        }
    </style>

    <script>
        function toggleDetail(id) {
            var detailRow = $('#detail-' + id);
            if (detailRow.hasClass('show')) {
                detailRow.removeClass('show');
            } else {
                detailRow.addClass('show');
            }
        }

        function showReturnModal(id, stock, name) {
            $('#itemName').text(name);
            $('#availableStock').text(stock);
            $('#returnModal').modal('show');

            $('#confirmReturnBtn').off('click').on('click', function() {
                var returnQuantity = $('#returnQuantity').val();
                if (returnQuantity > stock) {
                    Swal.fire('Error', 'Jumlah yang ingin dikembalikan tidak boleh lebih dari stok tersedia', 'error');
                } else if (returnQuantity <= 0) {
                    Swal.fire('Error', 'Jumlah yang ingin dikembalikan harus lebih besar dari 0', 'error');
                } else {
                    Swal.fire('Success', 'Pengembalian berhasil dilakukan', 'success');
                    $('#returnModal').modal('hide');
                    // Kirim request ke server untuk memproses pengembalian
                }
            });
        }
    </script>
@endsection
