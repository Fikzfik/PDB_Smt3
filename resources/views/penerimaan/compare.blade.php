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
                        <section class="section">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card" style="padding: 20px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>Pengadaan Details</h4>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Item</th>
                                                            <th>Unit Price</th>
                                                            <th>Quantity</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($pengadaanDetails as $item)
                                                            <tr>
                                                                <td>{{ $item->barang }}</td>
                                                                <td>{{ $item->harga_satuan }}</td>
                                                                <td>{{ $item->jumlah }}</td>
                                                                <td>{{ $item->sub_total }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <a href="{{ route('index.user') }}" class="btn btn-secondary mb-3">Back</a>
                                            </div>

                                            <div class="col-md-6">
                                                <h4>Reception Details</h4>
                                                <p class="text-muted">Click rows to select items for return. Use "Select
                                                    All" if needed.</p>
                                                <button type="button" class="btn btn-secondary mb-3"
                                                    id="selectAllButton">Select All</button>

                                                <!-- Return Items Form -->
                                                <form id="returnForm" action="{{ route('returnItems') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="idpenerimaan" value="{{ $idPenerimaan }}">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Received Quantity</th>
                                                                <th>Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($penerimaanDetails as $item)
                                                                <tr class="selectable-row"
                                                                    data-id="{{ $item->iddetail_penerimaan }}">
                                                                    <td>{{ $item->barang }}</td>
                                                                    <td>{{ $item->jumlah_diterima }}</td>
                                                                    <td>{{ $item->sub_total_terima }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <div id="selectedItemsContainer"></div>
                                                    <button type="submit" class="btn btn-warning">Return Selected
                                                        Items</button>
                                                </form>
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

    <!-- JavaScript for AJAX Submission and SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const returnForm = document.getElementById("returnForm");
            const selectedItemsContainer = document.getElementById("selectedItemsContainer");
            const selectAllButton = document.getElementById("selectAllButton");
            const selectableRows = document.querySelectorAll(".selectable-row");

            // Row Selection
            selectableRows.forEach(row => {
                row.addEventListener("click", function() {
                    row.classList.toggle("selected");
                    const itemId = row.getAttribute("data-id");

                    if (row.classList.contains("selected")) {
                        selectedItemsContainer.innerHTML +=
                            `<input type="hidden" name="selectedItems[]" value="${itemId}">`;
                    } else {
                        const input = selectedItemsContainer.querySelector(
                            `input[value="${itemId}"]`);
                        if (input) selectedItemsContainer.removeChild(input);
                    }
                });
            });

            // Select All Button
            selectAllButton.addEventListener("click", function() {
                selectableRows.forEach(row => {
                    const itemId = row.getAttribute("data-id");
                    if (!row.classList.contains("selected")) {
                        row.classList.add("selected");
                        selectedItemsContainer.innerHTML +=
                            `<input type="hidden" name="selectedItems[]" value="${itemId}">`;
                    }
                });
            });

            // AJAX Submission with SweetAlert
            returnForm.addEventListener("submit", function(event) {
                event.preventDefault();
                const formData = new FormData(returnForm);

                // Log data in FormData to the console
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }

                fetch(returnForm.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-Token": document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred.'
                        });
                        console.error('Error:', error);
                    });
            });
        });
    </script>

    <style>
        .selectable-row.selected {
            background-color: #ffcccc;
        }
    </style>
@endsection
