@extends('app', ['showHeader' => true])
@section('field-content')
    <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
        <section class="pt-3 pb-4" id="count-stats">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 mx-auto py-3">
                        <div class="row">
                            <!-- Pengadaan Pending -->
                            <div class="col-md-4 position-relative">
                                <div class="p-3 text-center">
                                    <h1 class="text-gradient text-primary">
                                        <span id="state1" countTo="{{ $jumlahPending }}">0</span>+
                                    </h1>
                                    <h5 class="mt-3">Jumlah Pengadaan Yang Pending</h5>
                                    <p class="text-sm font-weight-normal">Total pengadaan yang berstatus pending.</p>
                                </div>
                                <hr class="vertical dark">
                            </div>
                        
                            <!-- Pengadaan Selesai -->
                            <div class="col-md-4 position-relative">
                                <div class="p-3 text-center">
                                    <h1 class="text-gradient text-primary">
                                        <span id="state2" countTo="{{ $jumlahSucces }}">0</span>+
                                    </h1>
                                    <h5 class="mt-3">Jumlah Pengadaan Yang Selesai</h5>
                                    <p class="text-sm font-weight-normal">Total pengadaan yang sudah selesai diproses.</p>
                                </div>
                            </div>
                        
                            <!-- Pengadaan Return -->
                            <div class="col-md-4 position-relative">
                                <div class="p-3 text-center">
                                    <h1 class="text-gradient text-primary">
                                        <span id="state3" countTo="{{ $jumlahReturn }}">0</span>+
                                    </h1>
                                    <h5 class="mt-3">Jumlah Penerimaan Yang Direturn</h5>
                                    <p class="text-sm font-weight-normal">Total penerimaan yang dikembalikan.</p>
                                </div>
                                <hr class="vertical dark">
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>

        <section class="my-5 py-5">
            <div class="container">
                <h2 class="text-center mb-5">Permintaan Pengadaan</h2>
                <div id="pengadaanCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach (array_chunk($pengadaans, 3) as $chunk)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <div class="row align-items-center">
                                    @foreach ($chunk as $p)
                                        <div class="col-lg-4 ms-auto me-auto p-lg-4 mt-lg-0 mt-4">
                                            <div class="rotating-card-container">
                                                <div class="card card-rotate card-background card-background-mask-primary shadow-primary mt-md-0 mt-5"
                                                    onclick="detail({{ $p->idpengadaan }})">
                                                    <div class="front front-background"
                                                        style="background-image: url('https://images.unsplash.com/photo-1569683795645-b62e50fbf103?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=987&q=80'); background-size: cover;">
                                                        <div class="card-body py-7 text-center">
                                                            <h3 class="text-white">Status Pengadaan</h3>
                                                            <h3>
                                                                <span class="text-warning">
                                                                    {{ $p->status == 'A' ? 'Pending' : 'Completed' }}
                                                                </span>
                                                            </h3>
                                                            <p class="text-white opacity-8">
                                                                <b>Nama Yang Ingin Melakukan Pengadaan:
                                                                    {{ $p->username }}</b><br>
                                                                Total Nilai Pengadaan: {{ $p->total_nilai }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="back back-background"
                                                        style="background-image: url('https://images.unsplash.com/photo-1498889444388-e67ea62c464b?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1365&q=80'); background-size: cover;">
                                                        <div class="card-body pt-7 text-center">
                                                            <h3 class="text-white">Detail Pengadaan</h3>
                                                            <p class="text-white opacity-8">
                                                                <b>Nama Yang Ingin Melakukan Pengadaan:
                                                                    {{ $p->username }}</b><br>
                                                                Total Nilai Pengadaan: {{ $p->total_nilai }}<br>
                                                                Tanggal Pengadaan:
                                                                {{ \Carbon\Carbon::parse($p->timestamp)->format('d-m-Y H:i') }}<br>
                                                                Nilai PPN: {{ $p->ppn }}
                                                            </p>
                                                            <a href="#" class="btn btn-white btn-sm w-50 mx-auto mt-3"
                                                                data-idpengadaan="{{ $p->idpengadaan }}"
                                                                onclick="detail(this)">Lihat Detail</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#pengadaanCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#pengadaanCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center mt-4">
                        @for ($i = 0; $i < ceil(count($pengadaans) / 3); $i++)
                            <li class="page-item {{ $i == 0 ? 'active' : '' }}">
                                <button class="page-link" data-bs-target="#pengadaanCarousel"
                                    data-bs-slide-to="{{ $i }}">
                                    {{ $i + 1 }}
                                </button>
                            </li>
                        @endfor
                    </ul>
                </nav>
            </div>
        </section>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            data-bs-backdrop="false">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Pengadaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="tableDetail">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="checkAll"> <!-- Checkbox untuk Check All -->
                                    </th>
                                    <th>No</th>
                                    <th>Nama Item</th>
                                    <th>Harga Satuan</th>
                                    <th>Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Sub Total</th>
                                    <th>Stock yang Dibutuhkan</th> <!-- Ubah nama kolom ini -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="terimaPengadaan()">Terima
                            Pengadaan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#checkAll').on('change', function() {
            const isChecked = $(this).is(':checked');
            console.log('Check All state changed:', isChecked);

            $('#tableDetail tbody input[type="checkbox"].item-checkbox').each(function() {
                $(this).prop('checked', isChecked);
                const row = $(this).closest('tr');
                const quantityInput = row.find('.quantity-input');

                if (isChecked) {
                    const neededStock = parseInt(row.find('td').eq(7).text());
                    quantityInput.val(neededStock);
                } else {
                    quantityInput.val(0);
                }

                // Update subtotal untuk setiap row
                updateSubtotal(quantityInput, parseInt(quantityInput.val()));
            });
        });

        function detail(button) {
            // Ambil ID pengadaan dari tombol yang ditekan
            let idpengadaan = $(button).data('idpengadaan');

            // Set ID pengadaan ke dalam modal
            $('#exampleModal').data('idpengadaan', idpengadaan);

            // Ambil detail pengadaan menggunakan AJAX
            $.ajax({
                url: '{{ route('pengadaan.detail', ':id') }}'.replace(':id', idpengadaan),
                type: 'GET',
                success: function(response) {
                    $('#tableDetail tbody').empty(); // Kosongkan tbody

                    // Looping untuk setiap item dalam response
                    $.each(response, function(index, item) {
                        const row = `<tr>
                          <td>
                            <input type="checkbox" class="item-checkbox" data-id="${item.idbarang}" 
                                data-stok-yang-dibutuhkan="${item.stok_yang_dibutuhkan}" 
                                onchange="handleCheckboxChange(this)">
                        </td>
                            <td>${index + 1}</td>
                            <td>${item.nama}</td>
                            <td>${item.harga_satuan}</td>
                            <td>${item.nama_satuan}</td>
                            <td>
                                <input type="number" class="quantity-input" value="0" min="0" 
                                       onchange="updateSubtotal(this, parseInt(this.value))">
                            </td>
                            <td>${(item.harga_satuan * item.jumlah).toFixed(2)}</td>
                            <td>${item.stok_yang_dibutuhkan}</td> <!-- Stok yang dibutuhkan -->
                        </tr>`;
                        $('#tableDetail tbody').append(row); // Tambahkan row ke tbody
                    });

                    // Tampilkan modal
                    $('#exampleModal').modal('show');
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        }

        function handleCheckboxChange(checkbox) {
            const row = $(checkbox).closest('tr');
            const quantityInput = row.find('.quantity-input');
            const neededStock = parseInt(row.find('td').eq(7).text());

            if ($(checkbox).is(':checked')) {
                quantityInput.val(neededStock);
            } else {
                quantityInput.val(0);
            }

            // Update subtotal untuk setiap row
            updateSubtotal(quantityInput, parseInt(quantityInput.val()));
        }

        function updateSubtotal(quantityInput, quantity) {
            const row = $(quantityInput).closest('tr');
            const price = parseFloat(row.find('td').eq(3).text());
            const subtotal = quantity * price;
            row.find('td').eq(6).text(subtotal.toFixed(2));
        }

        function terimaPengadaan() {
            // Ambil ID pengadaan dari modal
            let idpengadaan = $('#exampleModal').data('idpengadaan');
            let items = [];

            // Loop untuk setiap baris dalam tabel detail pengadaan
            $('#tableDetail tbody tr').each(function() {
                let idbarang = $(this).find('.item-checkbox').data('id'); // Ambil ID barang dari checkbox
                let quantity = parseInt($(this).find('.quantity-input').val()); // Ambil jumlah dari input quantity

                // Hanya masukkan item jika quantity lebih dari 0
                if (quantity > 0) {
                    items.push({
                        idbarang: idbarang, // ID barang
                        quantity: quantity // Kuantitas
                    });
                }
                // console.log(idbarang); // Pastikan items terisi dengan benar
            });


            // Kirim data ke server menggunakan AJAX
            $.ajax({
                type: "POST",
                url: `/pengadaan/terima/${idpengadaan}`,
                data: {
                    items: items, // Mengirimkan array items
                    _token: $('meta[name="csrf-token"]').attr('content') // Ambil CSRF token dari meta tag
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                    $('#exampleModal').modal('hide'); // Tutup modal
                    location.reload(); // Refresh halaman untuk melihat perubahan
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON
                        .message : 'Terjadi kesalahan saat menyimpan data.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }



        function hapusItem(id) {
            // Logika untuk menghapus item
            alert('Menghapus item dengan id: ' + id);
        }
    </script>
@endsection
