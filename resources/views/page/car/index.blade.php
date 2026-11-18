@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Mobil
            </h3>

            <p class="text-muted mb-0">
                Monitoring data mobil rental
            </p>
        </div>

    </div>

    <!-- TABLE -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            <div class="table-responsive">

                <div class="d-flex justify-content-end mb-3">

                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCar"
                    >
                        + Tambah Mobil
                    </button>

                </div>

                <table
                    class="table table-hover align-middle mb-0"
                    id="table-car"
                    width="100%"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="5%">No</th>
                            <th>Unit</th>
                            <th>Nama Mobil</th>
                            <th>Nopol</th>
                            <th>Total Unit</th>
                            <th>Harga</th>
                            <th>Pajak</th>
                            <th>KIR</th>
                            <th>Status</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>

@include('page.car.form-car')

@endsection

@push('styles')

<link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"
/>

@endpush

@push('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>

    window.carUrl = {
        data: "{{ route('car.data') }}",
    };

</script>

<script src="{{ asset('assets/js/car.js') }}"></script>

@endpush