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
                                                        <tr id="detail-{{ $penerimaan->idpenerimaan }}"
                                                            class="collapse detail-row">
                                                            <td colspan="3">
                                                                <table class="table table-bordered mt-2">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Select</th>
                                                                            <th>ID Detail</th>
                                                                            <th>Nama Barang</th>
                                                                            <th>Jumlah Diterima</th>
                                                                            <th>Harga Satuan</th>
                                                                            <th>Subtotal</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($detailPenerimaan[$penerimaan->idpenerimaan] ?? [] as $detail)
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="checkbox"
                                                                                        class="return-checkbox"
                                                                                        data-id="{{ $detail->iddetail_penerimaan }}"
                                                                                        data-stock="{{ $detail->jumlah_terima }}"
                                                                                        data-name="{{ $detail->nama }}"
                                                                                        data-idpenerimaan="{{ $penerimaan->idpenerimaan }}">
                                                                                </td>
                                                                                <td>{{ $detail->iddetail_penerimaan }}</td>
                                                                                <td>{{ $detail->nama }}</td>
                                                                                <td>{{ $detail->jumlah_terima }}</td>
                                                                                <td>{{ number_format($detail->harga_satuan_terima, 0, ',', '.') }}
                                                                                </td>
                                                                                <td>{{ number_format($detail->sub_total_terima, 0, ',', '.') }}
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
                                            <button class="btn btn-danger mt-3" onclick="showReturnModal()">Return Barang
                                                Terpilih</button>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Pengembalian Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="returnItemsList">
                    <!-- Konten dinamis untuk setiap barang yang dipilih akan diisi di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="confirmReturnBtn">Kembalikan Barang</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
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
            detailRow.toggleClass('show');
        }

        // Menampilkan modal return dengan beberapa form untuk barang yang dipilih
        function showReturnModal() {
            let selectedItems = [];
            $('.return-checkbox:checked').each(function() {
                selectedItems.push({
                    id: $(this).data('id'),
                    name: $(this).data('name'),
                    stock: $(this).data('stock'),
                    idPenerimaan: $(this).data('idpenerimaan')
                });
            });

            if (selectedItems.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak ada barang yang dipilih',
                    text: 'Pilih minimal satu barang untuk direturn.'
                });
                return;
            }

            // Hapus konten modal sebelumnya
            $('#returnItemsList').empty();

            // Tambahkan setiap barang yang dipilih ke dalam modal
            selectedItems.forEach(item => {
                $('#returnItemsList').append(`
                    <div class="return-item" data-idpenerimaan="${item.idPenerimaan}">
                        <p>Nama Barang: ${item.name}</p>
                        <p>Stok Tersedia: ${item.stock}</p>
                        <div class="mb-3">
                            <label for="returnQuantity-${item.id}" class="form-label">Jumlah yang ingin dikembalikan:</label>
                            <input type="number" class="form-control return-quantity" id="returnQuantity-${item.id}" data-id="${item.id}" min="1" max="${item.stock}">
                        </div>
                        <div class="mb-3">
                            <label for="returnReason-${item.id}" class="form-label">Alasan Pengembalian:</label>
                            <textarea class="form-control return-reason" id="returnReason-${item.id}" rows="2"></textarea>
                        </div>
                        <hr>
                    </div>
                `);
            });

            $('#returnModal').modal('show');
        }

        $('#confirmReturnBtn').click(function() {
            const itemsData = [];
            let hasEmptyFields = false;

            $('.return-item').each(function() {
                const idPenerimaan = $(this).data('idpenerimaan');
                const quantityInput = $(this).find('.return-quantity');
                const id = quantityInput.data('id');
                const quantity = quantityInput.val();
                const reason = $(`#returnReason-${id}`).val();

                // Validasi setiap field apakah terisi atau tidak
                if (!quantity || !reason) {
                    hasEmptyFields = true;
                    return false; // Keluar dari each jika ada field kosong
                }
                itemsData.push({
                    idPenerimaan: idPenerimaan,
                    idDetailPenerimaan: id,
                    jumlahReturn: quantity,
                    alasan: reason
                });
                console.log(itemsData);
            });

            if (hasEmptyFields) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Lengkap',
                    text: 'Isi jumlah dan alasan untuk setiap barang yang ingin dikembalikan.'
                });
                return;
            }

            $.ajax({
                url: '/return-penerimaan',
                type: 'POST',
                data: {
                    items: itemsData,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pengembalian Berhasil',
                            text: 'Barang telah berhasil dikembalikan.'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pengembalian Gagal',
                            text: 'Terjadi kesalahan saat mengembalikan barang.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Terjadi kesalahan saat memproses pengembalian barang.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: errorMessage
                    });
                    console.error('Error:', error);
                }
            });
        });
    </script>
@endsection
