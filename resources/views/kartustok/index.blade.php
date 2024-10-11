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
                    <!-- Success Alert -->
                    <div id="success-alert" class="alert alert-success text-white font-weight-bold d-none" role="alert">
                        Kartu Stok berhasil ditambahkan!
                    </div>

                    <div class="tab-pane active" id="create-kartu-stok">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Kartu Stok</h4>
                                <form id="kartuStokForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                                        <select class="form-control" id="jenis_transaksi" name="jenis_transaksi" required>
                                            <option value="M">Masuk</option>
                                            <option value="K">Keluar</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlah" class="form-label">Jumlah</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah"
                                            value="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="idbarang" class="form-label">Pilih Barang</label>
                                        <select class="form-control" id="idbarang" name="idbarang" required>
                                            <option value="">-- Pilih Barang --</option>
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

                    <div class="tab-pane" id="historyList" class="d-none">
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Riwayat Kartu Stok</h4>
                                <div id="historyContent"></div>
                                <div class="mb-3">
                                    <button id="backToBarang" class="btn btn-secondary w-100">Kembali ke Daftar
                                        Barang</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Kartu Stok (List Barang dengan Tombol View History) -->
                    <div class="tab-pane" id="table-kartu-stok">
                        <div id="barangList">
                            <input type="text" id="search-barang" placeholder="Cari Barang" class="form-control mb-3" />
                            <div class="table-responsive p-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Jenis Transaksi</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Barang</th>
                                        </tr>
                                    </thead>
                                    <tbody id="kartu-stok-list">
                                        @foreach ($kartu_stok as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->create_at }}</td>
                                                <td>{{ $item->jenis_transaksi == 'M' ? 'Masuk' : 'Keluar' }}</td>
                                                <td>{{ $item->jenis_transaksi == 'M' ? $item->masuk : $item->keluar }}</td>
                                                <td>{{ $item->nama }}</td>
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
                $('#search-barang').on('keyup', function() {
                    var searchValue = $(this).val().toLowerCase();

                    // Looping semua baris pada tabel
                    $('#kartu-stok-list tr').filter(function() {
                        // Tampilkan atau sembunyikan baris berdasarkan input pencarian
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
                    });
                });
                $('#kartuStokForm').on('submit', function(e) {
                    e.preventDefault();

                    let formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('kartuStok.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            // Tampilkan success alert jika respons berhasil
                            $('#success-alert').removeClass('d-none').text(
                                'Kartu Stok berhasil ditambahkan!');

                            // Sembunyikan alert setelah 3 detik
                            setTimeout(function() {
                                $('#success-alert').addClass('d-none');
                            }, 3000);
                            $('#kartuStokForm')[0].reset();
                        },
                        error: function(error) {
                            console.error(error);
                            alert('Error adding Kartu Stok.');
                        }
                    });
                });

                // Handle tombol "View History" untuk menampilkan riwayat barang
                $('.viewHistory').on('click', function() {
                    let barangId = $(this).data('id');
                    console.log('View history for Barang ID:', barangId);

                    // Sembunyikan daftar barang dan tampilkan riwayat
                    $('#barangList').hide();
                    $('#historyList').removeClass('d-none');

                    // Panggilan AJAX untuk mengambil data riwayat
                    $.get("{{ url('api/get-history-url') }}")
                        .done(function(response) {
                            var baseUrl = response.url;
                            $('.viewHistory').on('click', function() {
                                let barangId = $(this).data('id');
                                var url = baseUrl + '/' + barangId;

                                $.get(url)
                                    .done(function(response) {
                                        console.log('History response:', response);
                                    })
                                    .fail(function(xhr, status, error) {
                                        console.error('Request failed:', error);
                                        alert(
                                            'Terjadi kesalahan saat mengambil data. Silakan coba lagi nanti.'
                                        );
                                    });
                            });
                        })
                        .fail(function(xhr, status, error) {
                            console.error('Request failed to get URL:', error);
                        });
                });

                // Kembali ke halaman list barang
                $('#backToBarang').on('click', function() {
                    $('#barangList').show();
                    $('#historyList').addClass('d-none');
                });

            });
        </script>
    </div>
@endsection
