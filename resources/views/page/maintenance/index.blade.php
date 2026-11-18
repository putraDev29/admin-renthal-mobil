@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-0">
                Maintenance Mobil
            </h2>
        </div>

        <a href="{{ route('car.index') }}"
            class="btn btn-primary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

    </div>

    <div class="row g-4">

        {{-- INFO MAINTENANCE TERAKHIR --}}
        <div class="col-lg-4">

            <div id="maintenanceInfo">

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <div class="spinner-border text-primary"></div>

                        <div class="mt-2">
                            Memuat data...
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- RIWAYAT --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white fw-semibold">

                    Riwayat Maintenance

                </div>

                <div class="card-body">

                    <div id="historyList">

                        <div class="text-center py-4">

                            <div class="spinner-border spinner-border-sm"></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- FORM --}}
        <div class="col-lg-4">

            @include('page.maintenance.form-maintenance')

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>
    const historyUrl =
        "{{ route('maintenance.history', $car->id) }}";

    const lastMaintenanceUrl =
        "{{ route('maintenance.last', $car->id) }}";

    const storeUrl =
        "{{ route('maintenance.store') }}";

    const carId =
        "{{ $car->id }}";

    const unit =
        "{{ $car->unit }}";


    const carName = "{{ $car->unit }}";

    const plateNumber = "{{ $car->plate_number }}";
</script>

<script src="{{ asset('assets/js/maintenance.js') }}"></script>

@endpush