@extends('app', ['showHeader' => false])

@section('field-content')
    <h1>Halo data table</h1>
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Umur</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1.</td>
                <td>Andi</td>
                <td>25</td>
                <td>Laki-laki</td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Budi</td>
                <td>30</td>
                <td>Laki-laki</td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Citra</td>
                <td>28</td>
                <td>Perempuan</td>
            </tr>
            <tr>
                <td>4.</td>
                <td>Dewi</td>
                <td>22</td>
                <td>Perempuan</td>
            </tr>
        </tbody>

    </table>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
@endsection