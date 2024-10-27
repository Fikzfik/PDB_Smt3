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

                            <!-- Pengadaan Return -->
                            <div class="col-md-4 position-relative">
                                <div class="p-3 text-center">
                                    <h1 class="text-gradient text-primary">
                                        <span id="state2" countTo="20">0</span>+
                                    </h1>
                                    <h5 class="mt-3">Jumlah Pengadaan Yang Direturn</h5>
                                    <p class="text-sm font-weight-normal">Total pengadaan yang dikembalikan.</p>
                                </div>
                                <hr class="vertical dark">
                            </div>

                            <!-- Pengadaan Selesai -->
                            <div class="col-md-4 position-relative">
                                <div class="p-3 text-center">
                                    <h1 class="text-gradient text-primary">
                                        <span id="state3" countTo="10">0</span>
                                    </h1>
                                    <h5 class="mt-3">Jumlah Pengadaan Yang Selesai</h5>
                                    <p class="text-sm font-weight-normal">Total pengadaan yang sudah selesai diproses.</p>
                                </div>
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
                                                                onclick="detail({{ $p->idpengadaan }})">Lihat Detail</a>
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
            data-bs-backdrop="false" style="">
            <div class="modal-dialog modal-xl" role="document">
                <!-- Use modal-xl to make it wider -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Pengadaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="tableDetail">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Item</th>
                                    <th>Harga Satuan</th>
                                    <th>Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Sub Total</th>
                                    <th>Stock</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be populated dynamically -->
                            </tbody>
                        </table>

                        <!-- Total Nilai and PPN section -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold">PPN:</h6>
                                <p id="ppn" class="text-primary fs-5 fw-bold">Rp 0</p>
                                <!-- This will be updated via JavaScript -->
                            </div>
                            <div class="col-md-6 text-end"> <!-- Align Total Nilai to the right -->
                                <h6 class="fw-bold">Total Nilai:</h6>
                                <p id="totalNilai" class="text-success fs-5 fw-bold">Rp 0</p>
                                <!-- This will be updated via JavaScript -->
                            </div>
                        </div>
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
    <script>
        const carousel = document.querySelector('#pengadaanCarousel');
        const paginationItems = document.querySelectorAll('.pagination .page-item');

        carousel.addEventListener('slide.bs.carousel', function(event) {
            const activeIndex = event.to;

            paginationItems.forEach((item, index) => {
                if (index === activeIndex) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        });
    </script>
    <script>
        function terimaPengadaan() {
            let idpengadaan = $('#exampleModal').data('idpengadaan');
            let items = [];

            $('#tableDetail tbody tr').each(function() {
                let idbarang = $(this).find('.quantity-display').data('id');
                let quantity = parseInt($(this).find('.quantity-display').text());

                items.push({
                    idbarang: idbarang,
                    quantity: quantity
                });
            });

            $.ajax({
                type: "POST",
                url: `/pengadaan/terima/${idpengadaan}`,
                data: {
                    items: items,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                    $('#exampleModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                        xhr.responseJSON.message : 'Terjadi kesalahan saat menyimpan data.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }


        function detail(id) {
            $('#exampleModal').data('idpengadaan', id); // Set ID pengadaan ke modal
            $.ajax({
                type: "GET",
                url: `/pengadaan/detailvalidasi/${id}`,
                dataType: "JSON",
                success: function(data) {
                    $('#tableDetail tbody').empty(); // Clear the table before appending new data

                    if (data.detailPengadaan.length > 0) {
                        let totalSubtotal = 0;

                        // Format currency
                        const formatIDR = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        });

                        // Iterate over detailPengadaan
                        data.detailPengadaan.forEach((item, index) => {
                            // Find the latest stock for this item from stoktrakhir data
                            const latestStock = data.stoktrakhir.find(stock => stock.idbarang === item
                                .idbarang);
                            const stockValue = latestStock ? latestStock.stock : '-';

                            let row = `
                        <tr>
                            <td>${index + 1}</td> <!-- No -->
                            <td>${item.nama_barang}</td> <!-- Nama Item -->
                            <td>${formatIDR.format(item.harga_satuan)}</td> <!-- Harga Satuan with IDR formatting -->
                            <td>${item.nama_satuan}</td> <!-- Satuan -->
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-decrease">-</button>
                                    <p class="mx-2 mb-0 quantity-display px-2 py-1 bg-light border rounded" style="min-width: 30px; text-align: center; font-size: 0.9em;" data-stock="${stockValue}" data-id="${item.idbarang}">
                                        ${item.jumlah}
                                    </p>
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-increase">+</button>
                                </div>
                            </td>
                            <td class="subtotal">${formatIDR.format(item.sub_total)}</td> <!-- Subtotal with IDR formatting -->
                            <td>${stockValue}</td> <!-- Latest Stock -->
                            <td>
                                <a href="#" class="btn btn-info btn-sm" onclick="showStock(${item.idbarang})">Lihat Stok</a> <!-- Actions -->
                            </td>
                        </tr>`;
                            $('#tableDetail tbody').append(row);

                            // Update subtotal sum
                            totalSubtotal += parseFloat(item.sub_total);
                        });

                        // Calculate PPN (11% of total subtotal)
                        const ppn = totalSubtotal * 0.11;
                        const totalNilai = totalSubtotal + ppn;

                        // Update the Total Nilai and PPN in the modal with formatted values
                        $('#totalNilai').text(formatIDR.format(totalNilai));
                        $('#ppn').text(formatIDR.format(ppn));

                        // Show the modal
                        $('#exampleModal').modal('show');

                        // Bind event listeners for the increase/decrease buttons after rows are added
                        bindQuantityButtons();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Tidak Ditemukan!',
                            text: `Tidak ada detail pengadaan yang ditemukan untuk ID pengadaan: ${id}.`,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.error ?
                        xhr.responseJSON.error : 'Terjadi kesalahan saat mengambil data.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }


        // Function to handle the quantity adjustment buttons
        function bindQuantityButtons() {
            $('.btn-increase').off('click').on('click', function() {
                let quantityDisplay = $(this).siblings('.quantity-display');
                let stock = parseInt(quantityDisplay.data('stock'));
                let currentQuantity = parseInt(quantityDisplay.text());
                let newQuantity = currentQuantity + 1;

                if (newQuantity <= stock) {
                    quantityDisplay.text(newQuantity);
                    updateSubtotal(quantityDisplay, newQuantity);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Tidak Mencukupi',
                        text: `Jumlah yang diminta melebihi stok tersedia: ${stock}`,
                        confirmButtonText: 'OK'
                    });
                }
            });

            $('.btn-decrease').off('click').on('click', function() {
                let quantityDisplay = $(this).siblings('.quantity-display');
                let currentQuantity = parseInt(quantityDisplay.text());
                let newQuantity = currentQuantity - 1;

                if (newQuantity >= 1) {
                    quantityDisplay.text(newQuantity);
                    updateSubtotal(quantityDisplay, newQuantity);
                }
            });
        }

        // Update subtotal for the row based on the new quantity
        function updateSubtotal(quantityDisplay, newQuantity) {
            let row = quantityDisplay.closest('tr');
            let hargaSatuan = parseFloat(row.find('td').eq(2).text().replace(/[^\d]/g, '')); // Extract number
            let newSubtotal = hargaSatuan * newQuantity;

            // Format and display new subtotal
            row.find('.subtotal').text(new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(newSubtotal));

            // Recalculate total and ppn
            recalculateTotals();
        }

        // Recalculate the overall totals
        function recalculateTotals() {
            let totalSubtotal = 0;

            $('#tableDetail .subtotal').each(function() {
                totalSubtotal += parseFloat($(this).text().replace(/[^\d]/g, ''));
            });

            const ppn = totalSubtotal * 0.11; // Calculate PPN (11%)
            const totalNilai = totalSubtotal + ppn; // Total is subtotal + PPN

            // Update Total Nilai and PPN
            $('#totalNilai').text(new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(totalNilai));
            $('#ppn').text(new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(ppn));
        }

        // Function to show stock details for a specific item
        function showStock(itemId) {
            // Implement the logic to fetch and display stock details
            console.log(`Show stock details for item ID: ${itemId}`);
            // You can add another AJAX call here to fetch stock details based on itemId
        }
    </script>
@endsection
