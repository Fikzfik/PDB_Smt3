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
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show col-md-12">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger alert-dismissible fade show col-md-12">
                                {{ session('errors') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <section class="section">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Table Pengadaan</h3>
                                        </div>
                                        <div class="card-body">
                                            <a href="{{ route('pengadaan.create') }}">
                                                <button class="btn btn-primary" style="margin-bottom: 5px"> + Tambah Data
                                                </button>
                                            </a>

                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Id Pengadaan</th>
                                                        <th scope="col">User</th>
                                                        <th scope="col">Vendor</th>
                                                        <th scope="col">Sub_Total</th>
                                                        <th scope="col">Total_Nilai</th>
                                                        <th scope="col">PPN</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @dd($pengadaans); --}}
                                                    @foreach ($pengadaans as $pengadaan)
                                                        <tr>
                                                            <td>{{ $pengadaan->idpengadaan }}</td>
                                                            <td>{{ $pengadaan->username }}</td>
                                                            <td>{{ $pengadaan->nama_vendor }}</td>
                                                            <td>{{ $pengadaan->subtotal_nilai }}</td>
                                                            <td>{{ $pengadaan->total_nilai }}</td>
                                                            <td>{{ $pengadaan->ppn }}</td>
                                                            <td>
                                                                @if ($pengadaan->status == 'A')
                                                                    <p class="text-warning">PENDING</p>
                                                                @elseif ($pengadaan->status == 'B')
                                                                    <p class="text-success">SUCCESS</p>
                                                                @elseif ($pengadaan->status == 'C')
                                                                    <p class="text-danger">CANCEL</p>
                                                                @elseif ($pengadaan->status == 'D')
                                                                    <p class="text-info">PROGRESS</p>
                                                                @endif

                                                            </td>
                                                            <td>
                                                                <button class="btn btn-primary"
                                                                    onclick="detail({{ $pengadaan->idpengadaan }})">DETAIL</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    <!-- Modal Detail Pengadaan -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Pengadaan
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table id="tableDetail" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Id Detail</th>
                                                                <th scope="col">Id Pengadaan</th>
                                                                <th scope="col">Barang</th>
                                                                <th scope="col">Harga Satuan</th>
                                                                <th scope="col">Jumlah</th>
                                                                <th scope="col">Sub Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer" id="modalFooter">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Script untuk ajax detail pengadaan -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        function detail(id) {
            $.ajax({
                type: "GET",
                url: `/pengadaan/detail/${id}`,
                dataType: "JSON",
                success: function(data) {
                    $('#tableDetail tbody').empty();
                    if (data.length > 0) {
                        const procurementStatus = data[0].status;

                        // Populate the table with procurement details
                        data.forEach(item => {
                            let row = `
                    <tr>
                        <td>${item.iddetail_pengadaan}</td>
                        <td>${item.idpengadaan}</td>
                        <td>${item.nama}</td>
                        <td>${item.harga_satuan}</td>
                        <td>${item.jumlah}</td>
                        <td>${item.sub_total}</td>
                    </tr>`;
                            $('#tableDetail tbody').append(row);
                        });

                        // Conditionally render footer buttons based on procurement status
                        if (procurementStatus === "A") {
                            $('#modalFooter').html(`
                        <button class="btn btn-danger" onclick="deletePengadaan(${id})">DELETE PENGADAAN</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    `);
                        } else if (procurementStatus === "B") {
                            $('#modalFooter').html(`
                        <button type="button" class="btn btn-success" onclick="penerimaan(${id})">Lihat Penerimaan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    `);
                        } else if (procurementStatus === "C") {
                            $('#modalFooter').html(`
                        <p class="text-danger">Pengadaan ini telah dibatalkan.</p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    `);
                        } else if (procurementStatus === "D") {
                            $('#modalFooter').html(`
                        <p class="text-primary">Pengadaan sedang dalam proses.</p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    `);
                        } else {
                            $('#modalFooter').html(`
                        <p class="text-muted">Status pengadaan tidak diketahui.</p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    `);
                        }

                        $('#exampleModal').modal('show');
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
                        xhr.responseJSON.error :
                        'Terjadi kesalahan saat mengambil data.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }


        function deletePengadaan(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pengadaan ini akan dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak, Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('pengadaan.delete') }}',
                        data: {
                            idpengadaan: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload(); // Refresh halaman
                            });
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                                xhr.responseJSON.message :
                                'Terjadi kesalahan saat membatalkan pengadaan.';
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
