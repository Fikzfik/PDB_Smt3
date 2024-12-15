@extends('app', ['showHeader' => false])

@section('field-content')
    <div class="page-header align-items-start min-vh-100"
        style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');"
        loading="lazy">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="card card-body blur shadow-blur mx-md-4">
                <div class="col-12">
                    <div class="position-relative border-radius-xl overflow-hidden shadow-lg mb-7 px-20">
                        <div class="container border-bottom px-2">
                            <div class="row justify-space-between py-2">
                                <div class="col-lg-3 me-auto">
                                    <p class="lead text-dark pt-1 mb-0">Manage Penjualan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Input Penjualan Barang -->
                        <div class="row mb-4 px-5">
                            <div class="col-md-12">
                                <h4 class="text-center">Form Input Penjualan Barang</h4>
                                <form id="pengadaanForm" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="inputbarang" class="form-label">Barang</label>
                                        <input type="text" class="form-control" id="inputbarang" name="barang"
                                            placeholder="Cari barang" readonly>
                                        <input type="hidden" id="id_barang" name="id_barang">
                                        <span id="pesan"></span>
                                    </div>

                                    <!-- Quantity dan Harga -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">Quantity</label>
                                                <input type="number" class="form-control" id="quantity" name="jumlah"
                                                    value="0" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="harga_barang" class="form-label">Harga Barang</label>
                                                <input type="text" class="form-control" id="harga_barang"
                                                    name="harga_satuan" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="total_harga" class="form-label">Total Harga</label>
                                        <input type="text" class="form-control" id="total_harga" name="total_harga"
                                            readonly>
                                    </div>

                                    <!-- Satuan -->
                                    <div class="mb-3">
                                        <label for="nama_satuan" class="form-label">Satuan</label>
                                        <input type="text" class="form-control" id="nama_satuan" readonly>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-start mb-3">
                                        <button class="btn btn-primary" id="cari_barang" onclick="caribarang2()"
                                            type="button" style="margin-right:10px">Cari Barang</button>
                                        <button type="button" onclick="tambahList()" class="btn btn-primary"
                                            style="margin-right: 10px;">Tambah List</button>
                                        <button type="button" onclick="resetBarang()"
                                            class="btn btn-secondary">Reset</button>
                                        <button type="button" class="btn btn-warning ms-2" id="cariMargin"
                                            data-bs-toggle="modal" data-bs-target="#marginModal">Cari Margin</button>
                                    </div>

                                </form>
                            </div>
                        </div>

                        <!-- Table Pengadaan -->
                        <div class="table-responsive p-4">
                            <table class="table table-striped" id="tableList">
                                <thead>
                                    <tr>
                                        <th scope="col">ID Barang</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Harga Barang</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Sub Total</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Barang yang ditambahkan akan ditampilkan di sini -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Total Harga dan Margin -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Total Harga Barang:</h4>
                                    <h5 id="displayTotal">Rp. 0,00</h5>
                                    <input type="hidden" id="value_totalnilai" name="value_totalnilai" value="0">
                                </div>
                                <div class="col-md-6">
                                    <h4>Margin (<span id="displayMargin">0%</span>):</h4>
                                    <h5 id="displayMarginTotal">Rp. 0,00</h5> <!-- Ganti id menjadi displayMarginTotal -->
                                </div>
                            </div>
                        </div>

                        <!-- Submit Pengadaan -->
                        <div class="text-center">
                            <form id="pengadaanSubmitForm" method="POST" action="{{ route('pengadaan.store') }}">
                                @csrf
                                <input type="hidden" name="dataPengadaan" id="dataPengadaan" value="">
                                <button type="button" id="simpan" class="btn btn-primary w-100">Submit
                                    Penjualan</button>
                            </form>
                        </div>
                        <div class="modal fade" id="marginModal" tabindex="-1" aria-labelledby="marginModalLabel"
                            aria-hidden="true" data-bs-backdrop="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="marginModalLabel">Pilih Margin</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table" id="tableMargin">
                                            <thead>
                                                <tr>
                                                    <th>ID Margin</th>
                                                    <th>Margin (%)</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Daftar margin akan dimuat di sini -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal untuk menampilkan hasil pencarian barang -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true" data-bs-backdrop="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Pilih Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table" id="tableproduk">
                                            <thead>
                                                <tr>
                                                    <th>ID Barang</th>
                                                    <th>Satuan</th>
                                                    <th>Nama Barang</th>
                                                    <th>Harga</th>
                                                    <th>Stock Dimiliki</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Barang hasil pencarian akan tampil di sini -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let barangPilih = [];
        let selectedMargin = 0;

        // Function to show the margin modal and fetch margin data
        $('#cariMargin').on('click', function() {
            $.ajax({
                url: "{{ route('getMargins') }}", // Make sure this route exists in your web.php
                type: 'GET',
                success: function(response) {
                    $('#tableMargin tbody').empty();
                    if (response.margins.length > 0) {
                        for (let i = 0; i < response.margins.length; i++) {
                            let row = `<tr>
                                <td>${response.margins[i].idmargin_penjualan}</td>
                                <td>${response.margins[i].persen}%</td>
                                <td>${response.margins[i].status === 1 ? 'Active' : 'Inactive'}</td>
                                <td>
                                    <button type="button" onclick="pilihMargin(${response.margins[i].idmargin_penjualan}, ${response.margins[i].persen})" class="btn btn-success">
                                        Pilih
                                    </button>
                                </td>
                            </tr>`;
                            $('#tableMargin tbody').append(row);
                        }
                        $('#marginModal').modal('show');
                    } else {
                        alert('Tidak ada margin ditemukan');
                    }
                },
                error: function(error) {
                    console.error('Error fetching margins', error);
                }
            });
        });

        // Function to select the margin and fill the input field
        window.pilihMargin = function(idMargin, persenMargin) {
            selectedMargin = persenMargin;
            selectedMarginId = idMargin; // Store the selected margin ID
            $('#margin').val(persenMargin);
            $('#marginModal').modal('hide');
            totalNilai();
            $('#displayMargin').text(`${persenMargin}%`);
        };


        // Function to calculate the total value including margin
        function totalNilai() {
            let total = 0;
            barangPilih.forEach(item => total += item.subtotal);
            let margin = (total * selectedMargin) / 100;
            let totalWithMargin = total + margin;
            $('#value_totalnilai').val(total);
            $('#displayTotal').text(totalWithMargin.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }));
            $('#displayMarginTotal').text(margin.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }));
            $('#displayMargin').text(selectedMargin + '%');
        }

        // Function to search products
        function caribarang2() {
            var inputBarang = document.getElementById('inputbarang');

            $.ajax({
                type: "POST",
                url: "/caribarang2",
                data: {
                    _token: "{{ csrf_token() }}",
                    barang: inputBarang.value
                },
                success: function(data) {
                    $('#tableproduk tbody').empty();
                    console.log(data);
                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
                            let row = `<tr>
                            <td>${data[i].idbarang}</td>
                            <td>${data[i].nama_satuan}</td>
                            <td>${data[i].nama}</td>
                            <td>${data[i].harga}</td>
                            <td>${data[i].stock}</td> <!-- Tambahkan kolom stock -->
                            <td>
                                <button type="button" onclick="pilihBarang(${data[i].idbarang}, '${data[i].nama}', ${data[i].harga}, '${data[i].nama_satuan}')" class="btn btn-success">
                                    Pilih
                                </button>
                            </td>
                            </tr>`;
                            $('#tableproduk tbody').append(row);
                        }
                        $('#exampleModal').modal('show');
                    } else {
                        alert('Barang tidak ditemukan');
                    }
                },
                error: function() {
                    console.log('Error in AJAX request');
                }
            });
        }

        // Function to select a product
        function pilihBarang(idBarang, namaBarang, hargaBarang, namaSatuan) {
            $('#exampleModal').modal('hide');
            $('#id_barang').val(idBarang);
            $('#inputbarang').val(namaBarang);
            $('#nama_satuan').val(namaSatuan);
            $('#harga_barang').val(hargaBarang);
            $('#quantity').prop('readonly', false);
            $('#quantity').val(1);
        }

        // Function to reset the product fields
        function resetBarang() {
            $('#inputbarang').val('');
            $('#id_barang').val('');
            $('#nama_satuan').val('');
            $('#harga_barang').val('');
            $('#quantity').val(1);
            $('#quantity').prop('readonly', true);
        }

        // Function to add products to the list
        function tambahList() {
            let idBarang = parseInt($('#id_barang').val());
            let namaBarang = $('#inputbarang').val();
            let namaSatuan = $('#nama_satuan').val();
            let hargaBarang = parseInt($('#harga_barang').val().replace(/,/g, ''));
            let quantity = parseInt($('#quantity').val());

            if (!idBarang || !namaBarang || !namaSatuan || isNaN(hargaBarang) || isNaN(quantity) || quantity <= 0) {
                alert('Lengkapi semua data sebelum menambahkan ke daftar list.');
                return;
            } else {
                let existingItem = barangPilih.find(item => item.id_barang === idBarang);

                if (existingItem) {
                    existingItem.quantity += quantity;
                    existingItem.subtotal = existingItem.harga * existingItem.quantity;
                } else {
                    barangPilih.push({
                        id_barang: idBarang,
                        nama_barang: namaBarang,
                        harga: hargaBarang,
                        quantity: quantity,
                        subtotal: hargaBarang * quantity
                    });
                }

                updateTable();
                totalNilai();
                resetBarang();
            }
        }

        // Function to update the product list table
        function updateTable() {
            $('#tableList tbody').empty();

            barangPilih.forEach(item => {
                let row = `<tr id="row-${item.id_barang}">
                    <td>${item.id_barang}</td>
                    <td>${item.nama_barang}</td>
                    <td>${item.harga.toLocaleString('id-ID')}</td>
                    <td><input type="number" id="quantity-${item.id_barang}" value="${item.quantity}" onchange="updateSubtotal(${item.id_barang})" style="width: 20%;"></td>
                    <td id="subtotal-${item.id_barang}">${item.subtotal.toLocaleString('id-ID')}</td>
                    <td><button type="button" onclick="hapusBarang(${item.id_barang})" class="btn btn-danger"><i class="bi bi-trash3-fill"></i>Delete</button></td>
                </tr>`;
                $('#tableList tbody').append(row);
            });
        }

        // Function to update the subtotal when quantity changes
        function updateSubtotal(idBarang) {
            let quantityInput = parseInt($(`#quantity-${idBarang}`).val());
            let index = barangPilih.findIndex(item => item.id_barang === idBarang);

            if (quantityInput < 0) {
                alert('QUANTITY TIDAK BOLEH MINUS');
                $(`#quantity-${idBarang}`).val(0);
                return;
            }

            barangPilih[index].quantity = quantityInput;
            barangPilih[index].subtotal = barangPilih[index].harga * quantityInput;

            $(`#subtotal-${idBarang}`).text(barangPilih[index].subtotal.toLocaleString('id-ID'));
            totalNilai();
        }

        // Function to delete a product from the list
        function hapusBarang(idBarang) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("Deleted!", "Your List has been deleted.", "success");
                    barangPilih = barangPilih.filter(item => item.id_barang !== idBarang);
                    $(`#row-${idBarang}`).remove();
                    totalNilai();
                }
            });
        }

        // Event listener for quantity input
        document.getElementById('quantity').addEventListener('input', function() {
            let quantity = parseInt(this.value);
            let hargaBarang = parseFloat(document.getElementById('harga_barang').value.replace(/,/g, ''));

            if (!isNaN(quantity) && !isNaN(hargaBarang)) {
                let totalHarga = quantity * hargaBarang;
                document.getElementById('total_harga').value = totalHarga.toLocaleString('id-ID');
            } else {
                document.getElementById('total_harga').value = '';
            }
        });

        // Save button functionality
        $(document).ready(function() {
            $(document).on('click', '#simpan', function() {
                let total = parseInt($('#value_totalnilai').val());
                let margin = (total * selectedMargin) / 100;

                let subtotal = total;

                if (isNaN(subtotal) || subtotal <= 0) {
                    Swal.fire("ERROR!", "Subtotal cannot be zero or negative", "error");
                    return;
                }

                let data = {
                    barang: barangPilih,
                    margin: selectedMargin,
                    total: subtotal,
                    marginValue: margin,
                    idmargin_penjualan: selectedMarginId,
                    _token: "{{ csrf_token() }}"
                };
                console.log(data);
                // Melakukan AJAX POST ke server
                $.ajax({
                    type: "POST",
                    url: "/penjualan/store", // Arahkan ke controller PenjualanController
                    data: data, // Mengirimkan data yang sudah dikumpulkan
                    success: function(response) {
                        console.log(response); // Cek response dari server
                        if (response.message === 'success') {
                            Swal.fire("SUCCESS!", "Data Berhasil Disimpan", "success").then(
                                () => {
                                    window.location.href =
                                        '/penjualan/create'; // Redirect ke halaman penjualan setelah berhasil
                                }
                            );
                        } else {
                            console.log('Response tidak sesuai');
                            Swal.fire("ERROR!", "Terjadi kesalahan saat menyimpan data.",
                                "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON); // Cek data error dari server
                        Swal.fire({
                            title: "ERROR!",
                            text: `Terjadi kesalahan: ${xhr.responseJSON.error}\nData yang dikirim: ${JSON.stringify(xhr.responseJSON.data)}`,
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>
@endsection
