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
                <div class="row align-items-center">
                    @foreach ($pengadaans as $p)
                        <div class="col-lg-4 ms-auto me-auto p-lg-4 mt-lg-0 mt-4">
                            <div class="rotating-card-container">
                                <div class="card card-rotate card-background card-background-mask-primary shadow-primary mt-md-0 mt-5"
                                    onclick="detail({{ $p->idpengadaan }})">
                                    <div class="front front-background"
                                        style="background-image: url(https://images.unsplash.com/photo-1569683795645-b62e50fbf103?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=987&q=80); background-size: cover;">
                                        <div class="card-body py-7 text-center">
                                            <h3 class="text-white">Status Pengadaan</h3>
                                            <h3><span
                                                    class="text-warning">{{ $p->status == 'A' ? 'Pending' : 'Completed' }}</span>
                                            </h3>
                                            <p class="text-white opacity-8"><b>Nama Yang Ingin Melakukan Pengadaan:
                                                    {{ $p->username }} </b><br> Total Nilai Pengadaan:
                                                {{ $p->total_nilai }}</p>
                                        </div>
                                    </div>
                                    <div class="back back-background"
                                        style="background-image: url(https://images.unsplash.com/photo-1498889444388-e67ea62c464b?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1365&q=80); background-size: cover;">
                                        <div class="card-body pt-7 text-center">
                                            <h3 class="text-white">Detail Pengadaan</h3>
                                            <p class="text-white opacity-8"><b>Nama Yang Ingin Melakukan Pengadaan:
                                                    {{ $p->username }}</b><br> Total Nilai Pengadaan: {{ $p->total_nilai }}
                                                <br> Tanggal Pengadaan:
                                                {{ \Carbon\Carbon::parse($p->timestamp)->format('d-m-Y H:i') }} <br> Nilai
                                                PPN: {{ $p->ppn }}
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
        </section>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            data-bs-backdrop="false">
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
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script>
        function detail(id) {
            $.ajax({
                type: "GET",
                url: `/pengadaan/detailvalidasi/${id}`,
                dataType: "JSON",
                success: function(data) {
                    $('#tableDetail tbody').empty(); // Clear the table before appending new data

                    if (data.length > 0) {
                        let totalNilai = 0;
                        let ppn = 0;

                        // Format angka dengan titik ribuan untuk IDR
                        const formatIDR = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0 // Menghilangkan desimal
                        });

                        for (let i = 0; i < data.length; i++) {
                            let row = `
                    <tr>
                        <td>${i + 1}</td> <!-- No -->
                        <td>${data[i].nama}</td> <!-- Nama Item -->
                        <td>${formatIDR.format(data[i].harga_satuan)}</td> <!-- Harga Satuan dengan format IDR -->
                        <td>${data[i].nama_satuan}</td> <!-- Satuan -->
                        <td>${data[i].jumlah}</td> <!-- Jumlah -->
                        <td>${formatIDR.format(data[i].sub_total)}</td> <!-- Subtotal dengan format IDR -->
                        <td>
                            <a href="#" class="btn btn-info btn-sm">Lihat Stok</a> <!-- Aksi -->
                        </td>
                    </tr>`;
                            $('#tableDetail tbody').append(row); // Append each row to the table

                            totalNilai += parseFloat(data[i].total_nilai); // Accumulate total nilai
                            ppn += parseFloat(data[i].ppn); // Accumulate PPN
                        }

                        // Update the Total Nilai and PPN in the modal with formatted values
                        $('#totalNilai').text(formatIDR.format(totalNilai));
                        $('#ppn').text(formatIDR.format(ppn));

                        $('#exampleModal').modal('show'); // Show the modal after populating the table
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
    </script>
@endsection
