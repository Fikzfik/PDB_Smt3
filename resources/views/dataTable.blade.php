@extends('app', ['showHeader' => true])
@section('field-content')
    <div class="container my-auto">
        <div class="row">
            <div class="col-lg-12 col-md-8 col-12 mx-auto">
                <main id="main" class="main">
                    <section class="section">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Table Penerimaan</h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ID Penerimaan</th>
                                                    <th scope="col">ID Pengadaan</th>
                                                    <th scope="col">User</th>
                                                    <th scope="col">Tanggal</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($penerimaans as $penerimaan)
                                                    <tr>
                                                        <td>{{ $penerimaan->idpenerimaan }}</td>
                                                        <td>{{ $penerimaan->idpengadaan }}</td>
                                                        <td>{{ $penerimaan->username }}</td>
                                                        <td>{{ $penerimaan->created_at }}</td>
                                                        <td>
                                                            @if ($penerimaan->status == 'B')
                                                                <span class="text-success">DITERIMA</span>
                                                            @else
                                                                <span class="text-warning">PENDING</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary"
                                                                onclick="viewDetail({{ $penerimaan->idpenerimaan }})">DETAIL</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
    
    <!-- Script untuk ajax detail penerimaan -->
    <script>
        function viewDetail(id) {
            $.ajax({
                type: "GET",
                url: `/penerimaan/detail/${id}`,
                dataType: "JSON",
                success: function(data) {
                    $('#tableDetail tbody').empty();
                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
                            let row = `
                                <tr>
                                    <td>${data[i].iddetail_penerimaan}</td>
                                    <td>${data[i].idbarang}</td>
                                    <td>${data[i].jumlah_terima}</td>
                                    <td>${data[i].harga_satuan_terima}</td>
                                    <td>${data[i].sub_total_terima}</td>
                                </tr>`;
                            $('#tableDetail tbody').append(row);
                        }
                        $('#detailModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Tidak Ditemukan!',
                            text: 'Tidak ada detail penerimaan ditemukan.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Terjadi kesalahan saat mengambil data.';
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