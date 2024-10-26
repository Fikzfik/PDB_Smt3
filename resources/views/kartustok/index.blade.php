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
                                            href="#table-kartu-stok" role="tab" aria-controls="table"
                                            aria-selected="true">
                                            <i class="fas fa-table text-sm me-2"></i> Table
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#historyList"
                                            role="tab" aria-controls="history" aria-selected="false">
                                            <i class="fas fa-list text-sm me-2"></i> History
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content tab-space">
                    <!-- Table with Barang List and Current Stock -->
                    <div class="tab-pane active" id="table-kartu-stok">
                        <input type="text" id="search-barang" placeholder="Cari Barang" class="form-control mb-3" />
                        <div class="table-responsive p-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Barang</th>
                                        <th scope="col">Stok Terakhir</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="kartu-stok-list">
                                    {{-- @dd($barang); --}}
                                    @foreach ($barang as $item)
                                        <tr>
                                            {{-- @dd($item); --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->stok_terakhir }}</td>
                                            <!-- Assuming 'stok_terakhir' is available -->
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm viewHistory"
                                                    data-id="{{ $item->idbarang }}">Lihat Detail</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- History Detail for Selected Barang -->
                    <div class="tab-pane" id="historyList" class="d-none">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Riwayat Kartu Stok</h4>
                                <div id="historyContent">Pilih barang untuk melihat detail stok.</div>
                                <div class="mb-3">
                                    <button id="backToBarang" class="btn btn-secondary w-100">Kembali ke Daftar
                                        Barang</button>
                                </div>
                            </div>
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
            // Event listener untuk tombol viewHistory
            $(document).on('click', '.viewHistory', function() {
                let barangId = $(this).data('id');
                let barangName = $(this).closest('tr').find('td:nth-child(2)').text();
                let stockTerakhir = parseInt($(this).closest('tr').find('td:nth-child(3)').text());

                // Ambil riwayat stok untuk barang yang dipilih
                $.get("{{ url('kartu-stok/history') }}/" + barangId, function(response) {
                    let currentStock = response.stock || 0;

                    // Buat HTML untuk form dan riwayat
                    let historyHtml = `
                <div class="mb-4 text-center">
                    <h5 class="font-weight-bold">Barang: ${barangName}</h5>
                    <h6 class="text-muted">Stok Sekarang: <span class="text-primary">${stockTerakhir}</span></h6>
                </div>
                <form id="kartuStokForm" method="POST" action="{{ url('kartu-stok/store') }}" class="unique-kartu-stok-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="unique_jenis_transaksi" name="jenis_transaksi" value="">
                    <input type="hidden" name="idbarang" value="${barangId}">

                    <div class="form-group mb-3">
                        <label class="form-label">Pilih Transaksi</label>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-light unique-btn-transaksi mx-2" id="btn-masuk">Masuk</button>
                            <button type="button" class="btn btn-light unique-btn-transaksi mx-2" id="btn-keluar">Keluar</button>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="unique_jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control unique-input-jumlah" id="unique_jumlah" name="jumlah" value="0" min="0" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary unique-btn-simpan w-100">Simpan</button>
                    </div>
                </form>

                <div class="history-section mt-4">
                    <h6>Riwayat Transaksi</h6>
                    <table class="table table-bordered" id="historyTableBody">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Transaksi</th>
                            <th>Jumlah</th>
                        </tr>
            `;

                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(entry) {
                            historyHtml += `
                        <tr>
                            <td>${entry.create_at}</td>
                            <td>${entry.jenis_transaksi === 'M' ? 'Masuk' : 'Keluar'}</td>
                            <td>${entry.stock}</td>
                        </tr>
                    `;
                        });
                    } else {
                        historyHtml +=
                            '<tr><td colspan="3" class="text-center">Tidak ada riwayat stok</td></tr>';
                    }

                    historyHtml += `</table></div>`;
                    $('#historyContent').html(historyHtml);

                    // Pindah ke tab riwayat
                    $('.nav-link[href="#historyList"]').tab('show');

                    // Logika perubahan warna tombol
                    $('#btn-masuk').click(function() {
                        $('#unique_jenis_transaksi').val('M');
                        $(this).removeClass('btn-light').addClass('btn-success');
                        $('#btn-keluar').removeClass('btn-danger').addClass('btn-light');
                    });

                    $('#btn-keluar').click(function() {
                        $('#unique_jenis_transaksi').val('K');
                        $(this).removeClass('btn-light').addClass('btn-danger');
                        $('#btn-masuk').removeClass('btn-success').addClass('btn-light');
                    });
                }).fail(function() {
                    Swal.fire({
                        title: 'Gagal',
                        text: 'Error retrieving data',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            });

            // Cek pilihan jenis transaksi sebelum submit
            $(document).on('submit', '#kartuStokForm', function(e) {
                e.preventDefault(); // Prevent form from refreshing page

                if (!$('#unique_jenis_transaksi').val()) {
                    // Jika jenis transaksi belum dipilih, tampilkan SweetAlert
                    Swal.fire({
                        title: 'Jenis Transaksi Belum Dipilih',
                        text: 'Silakan pilih "Masuk" atau "Keluar" sebelum menyimpan.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return; // Hentikan proses submit
                }

                let formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ url('kartu-stok/store') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Menambahkan entri terbaru ke daftar riwayat
                            let entry = `
                        <tr>
                            <td>${response.data.create_at}</td>
                            <td>${response.data.jenis_transaksi === 'M' ? 'Masuk' : 'Keluar'}</td>
                            <td>${response.data.jumlah}</td>
                        </tr>`;

                            $('#historyTableBody').prepend(entry); // Tambah entri terbaru
                            $('#historyContent h6 .text-primary').text(response.data
                                .stock); // Update stok

                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Kartu stok berhasil disimpan!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else if (response.error) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: response.error,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal menyimpan data!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage =
                                `Error: ${xhr.responseJSON.message} di baris ${xhr.responseJSON.line}`;
                        }

                        Swal.fire({
                            title: 'Terjadi Kesalahan',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Kembali ke daftar Barang
            $('#backToBarang').on('click', function() {
                $('.nav-link[href="#table-kartu-stok"]').tab('show');
                $('#historyContent').html(
                    '<p class="text-muted">Pilih barang untuk melihat detail stok.</p>');
            });
        });
    </script>
@endsection
