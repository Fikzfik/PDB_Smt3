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
                                    <p class="lead text-dark pt-1 mb-0">Manage Pengadaan</p>
                                </div>
                                <div class="col-lg-3">
                                    <div class="nav-wrapper position-relative end-0">
                                        <ul class="nav nav-pills nav-fill flex-row p-1" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab"
                                                    href="#create-pengadaan" role="tab" aria-controls="create"
                                                    aria-selected="true">
                                                    <i class="fas fa-plus text-sm me-2"></i> Create
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                    href="#table-pengadaan" role="tab" aria-controls="table"
                                                    aria-selected="false">
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
                            <div id="success-alert" class="alert alert-success text-white font-weight-bold d-none"
                                role="alert">
                                Pengadaan added successfully!
                            </div>
                            <div class="tab-pane active" id="create-pengadaan">
                                <div class="row mb-4 px-5">
                                    <div class="col-md-12">
                                        <h4 class="text-center">Form Input Pengadaan Barang</h4>
                                        <form id="pengadaanForm" method="POST">
                                            @csrf
                                            <!-- Pilih Vendor -->
                                            <div class="mb-3">
                                                <label for="vendor" class="form-label">Pilih Vendor</label>
                                                <select id="id_vendor" name="id_vendor" class="form-select">
                                                    <option value="">--Pilih Vendor--</option>
                                                    @foreach ($vendors as $vendor)
                                                        <option value="{{ $vendor->idvendor }}">{{ $vendor->nama_vendor }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

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
                                                        <input type="number" class="form-control" id="quantity"
                                                            name="jumlah" value="0" readonly>
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
                                                <input type="text" class="form-control" id="total_harga"
                                                    name="total_harga" readonly>
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
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table Pengadaan -->
                            <div class="tab-pane" id="table-pengadaan">
                                <div class="table-responsive p-4">
                                    <table class="table table-striped" id="tableList">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID Barang</th>
                                                <th scope="col">Satuan</th>
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
                            </div>

                            <!-- Total Harga dan PPN -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Total Harga Barang:</h4>
                                        <h5 id="displayTotal">Rp. 0,00</h5>
                                        <input type="hidden" id="value_totalnilai" name="value_totalnilai"
                                            value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <h4>PPN (11%):</h4>
                                        <h5 id="displayPPN">Rp. 0,00</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Pengadaan -->
                            <div class="text-center">
                                <form id="pengadaanSubmitForm" method="POST" action="{{ route('pengadaan.store') }}">
                                    @csrf
                                    <input type="hidden" name="dataPengadaan" id="dataPengadaan" value="">
                                    <button type="button" id="simpan" class="btn btn-primary w-100">Submit
                                        Pengadaan</button>
                                </form>
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
                                                        <th>Aksi</th>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let barangPilih = [];

        // FUNCTION CARI BARANG (INPUT BARANG)
        function caribarang2() {
            var inputBarang = document.getElementById('inputbarang');

            $.ajax({
                type: "POST",
                url: "/caribarang",
                data: {
                    _token: "{{ csrf_token() }}",
                    barang: inputBarang.value
                },
                success: function(data) {
                    // Kosongkan tabel produk
                    $('#tableproduk tbody').empty();

                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
                            let row = `<tr>
                                <td>${data[i].idbarang}</td>
                                <td>${data[i].nama_satuan}</td>
                                <td>${data[i].nama}</td>
                                <td>${data[i].harga}</td>
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

        function pilihBarang(idBarang, namaBarang, hargaBarang, namaSatuan) {
            $('#exampleModal').modal('hide');
            $('#id_barang').val(idBarang);
            $('#inputbarang').val(namaBarang);
            $('#nama_satuan').val(namaSatuan);
            $('#harga_barang').val(hargaBarang);
            $('#quantity').prop('readonly', false); // Mengaktifkan input quantity
            $('#quantity').val(1); // Set default quantity to 1
        }

        // Menghapus backdrop secara manual setelah modal ditutup
        $('#exampleModal').on('hidden.bs.modal', function() {
            $('.modal-backdrop').remove(); // Menghapus backdrop jika masih ada
        });

        // FUNCTION RESET BARANG
        function resetBarang() {
            $('#inputbarang').val('');
            $('#id_barang').val('');
            $('#nama_satuan').val('');
            $('#harga_barang').val('');
            $('#quantity').val(1); // Reset quantity to 1
            $('#quantity').prop('readonly', true);
        }

        // FUNCTION TAMBAH LIST
        function tambahList() {
            let idBarang = parseInt($('#id_barang').val());
            let namaBarang = $('#inputbarang').val();
            let namaSatuan = $('#nama_satuan').val();
            let hargaBarang = parseInt($('#harga_barang').val().replace(/,/g, '')); // Ensure price is parsed correctly
            let quantity = parseInt($('#quantity').val());

            if (!idBarang || !namaBarang || !namaSatuan || isNaN(hargaBarang) || isNaN(quantity) || quantity <= 0) {
                alert('Lengkapi semua data sebelum menambahkan ke daftar list.');
                return;
            } else {
                let existingItem = barangPilih.find(item => item.id_barang === idBarang);

                if (existingItem) {
                    existingItem.quantity += quantity; // Update quantity jika barang sudah ada
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

        function updateTable() {
            $('#tableList tbody').empty(); // Bersihkan tabel sebelum menambahkan data

            barangPilih.forEach(item => {
                let row = `<tr id="row-${item.id_barang}">
            <td>${item.id_barang}</td>
            <td>${item.nama_satuan}</td> <!-- Tambahkan kolom satuan -->
            <td>${item.nama_barang}</td>
            <td>${item.harga.toLocaleString('id-ID')}</td>
            <td><input type="number" id="quantity-${item.id_barang}" value="${item.quantity}" onchange="updateSubtotal(${item.id_barang})" style="width: 10%;"></td>
            <td id="subtotal-${item.id_barang}">${item.subtotal.toLocaleString('id-ID')}</td>
            <td><button type="button" onclick="hapusBarang(${item.id_barang})" class="btn btn-danger"><i class="bi bi-trash3-fill"></i>Delete</button></td>
        </tr>`;
                $('#tableList tbody').append(row);
            });
        }

        // UPDATE SUB TOTAL
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

        function totalNilai() {
            let total = 0;
            barangPilih.forEach(item => total += item.subtotal);

            let ppn = total * 0.11;
            let displayTotal = total + ppn;

            $('#displayPPN').text(ppn.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }));

            $('#value_totalnilai').val(total); // Set hidden field for subtotal
            $('#displayTotal').text(displayTotal.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }));
        }

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

        document.getElementById('quantity').addEventListener('input', function() {
            let quantity = parseInt(this.value);
            let hargaBarang = parseFloat(document.getElementById('harga_barang').value.replace(/,/g, ''));

            if (!isNaN(quantity) && !isNaN(hargaBarang)) {
                let totalHarga = quantity * hargaBarang;
                document.getElementById('total_harga').value = totalHarga.toLocaleString(
                    'id-ID'); // Update total harga
            } else {
                document.getElementById('total_harga').value = ''; // Kosongkan jika tidak valid
            }
        });

        $(document).ready(function() {
            $(document).on('click', '#simpan', function() {
                // Ambil nilai id_vendor dari dropdown
                let idVendor = parseInt($('#id_vendor').val());

                // Ambil subtotal dari input hidden atau elemen lain yang menyimpan subtotal
                let subtotal = parseInt($('#value_totalnilai').val());
                console.log(subtotal);

                // Cek apakah idVendor dipilih dan subtotal valid
                if (isNaN(idVendor) || idVendor <= 0) {
                    Swal.fire("ERROR!", "Pilih Vendor yang valid.", "error");
                    return;
                }

                if (isNaN(subtotal) || subtotal <= 0) {
                    Swal.fire("ERROR!", "Subtotal tidak valid.", "error");
                    return;
                }

                if (barangPilih.length === 0) {
                    Swal.fire("ERROR!", "Tambahkan setidaknya satu barang ke dalam daftar.", "error");
                    return;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/pengadaan/store",
                    data: {
                        id_vendor: idVendor,
                        subtotal: subtotal,
                        barangPilih: JSON.stringify(barangPilih)
                    },
                    success: function(response) {
                        console.log(response); // Cek response dari server
                        if (response.message === 'success') {
                            Swal.fire("SUCCESS!", "Data Berhasil Disimpan", "success").then(
                                () => {
                                    window.location.href = '/pengadaan/create';
                                });
                        } else {
                            console.log('Response tidak sesuai');
                        }
                    },
                    error: function(error) {
                        console.log(error); // Cek data error dari server
                        Swal.fire({
                            title: "ERROR!",
                            text: error.responseJSON.error + "\nData yang dikirim: " + JSON.stringify(error.responseJSON.data),
                            icon: "error"
                        });
                    }   
                });
            });
        });
    </script>
@endsection
{{-- @dd($pengadaans,$vendors,$validUser); --}}
