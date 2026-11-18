@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Keuangan - Gaji</h4>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.expense.index') }}"
                class="btn btn-outline-secondary btn-sm">
                Pengeluaran Lainnya
            </a>

            <button type="button"
                class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#createSalaryModal">
                + Tambah Gaji
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- SUMMARY --}}
    @include('page.finance.summary')


    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped align-middle">

                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Penerima</th>
                            <th>Mobil</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($salaries as $item)

                            <tr>

                                <td>
                                    {{ $item->transaction_date->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ $item->recipient_name }}
                                </td>

                                <td>
                                    {{ optional($item->car)->unit ?? '-' }}
                                </td>

                                <td>
                                    Rp {{ number_format($item->amount, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ $item->description ?? '-' }}
                                </td>

                                <td>

                                    {{-- EDIT --}}
                                    <button type="button"
                                        class="btn btn-warning btn-sm btn-edit-salary"

                                        data-id="{{ $item->id }}"

                                        data-date="{{ $item->transaction_date->format('Y-m-d') }}"

                                        data-driver="{{ $item->driver_id }}"

                                        data-recipient="{{ $item->recipient_name }}"

                                        data-car="{{ $item->car_id }}"

                                        data-amount="{{ number_format($item->amount, 0, ',', '.') }}"

                                        data-description="{{ $item->description }}"

                                        data-bs-toggle="modal"

                                        data-bs-target="#editSalaryModal">

                                        Edit

                                    </button>


                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('finance.salary.destroy', $item) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Hapus data ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted">

                                    Belum ada data gaji.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>



{{-- ======================================================== --}}
{{-- MODAL CREATE --}}
{{-- ======================================================== --}}

<div class="modal fade"
    id="createSalaryModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="POST"
                action="{{ route('finance.salary.store') }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Pengeluaran Gaji
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">

                        {{-- TANGGAL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Tanggal
                            </label>

                            <input type="date"
                                name="transaction_date"
                                value="{{ old('transaction_date', date('Y-m-d')) }}"
                                class="form-control"
                                required>

                        </div>


                        {{-- DRIVER --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Driver (opsional)
                            </label>

                            <select
                                name="driver_id"
                                class="form-select"
                                id="createDriverSelect">

                                <option value="">
                                    - Pilih -
                                </option>

                                @foreach($drivers as $driver)

                                    <option
                                        value="{{ $driver->id }}"
                                        data-name="{{ $driver->nama_lengkap }}">

                                        {{ $driver->nama_lengkap }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- PENERIMA --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Penerima
                            </label>

                            <input
                                type="text"
                                name="recipient_name"
                                id="createRecipientName"
                                value="{{ old('recipient_name') }}"
                                class="form-control"
                                required>

                        </div>


                        {{-- MOBIL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Mobil / Unit (opsional)
                            </label>

                            <select
                                name="car_id"
                                class="form-select">

                                <option value="">
                                    - Umum -
                                </option>

                                @foreach($cars as $car)

                                    <option value="{{ $car->id }}">

                                        {{ $car->unit }}
                                        -
                                        {{ $car->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- NOMINAL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Nominal Gaji
                            </label>

                            <input
                                type="text"
                                name="amount"
                                class="form-control money"
                                placeholder="0"
                                required>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Keterangan
                            </label>

                            <input
                                type="text"
                                name="description"
                                class="form-control">

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Simpan Gaji

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- ======================================================== --}}
{{-- MODAL EDIT --}}
{{-- ======================================================== --}}

<div class="modal fade"
    id="editSalaryModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="POST"
                id="editSalaryForm">

                @csrf
                @method('PUT')


                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Gaji
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">


                        {{-- TANGGAL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Tanggal
                            </label>

                            <input
                                type="date"
                                name="transaction_date"
                                id="editTransactionDate"
                                class="form-control"
                                required>

                        </div>


                        {{-- DRIVER --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Driver (opsional)
                            </label>

                            <select
                                name="driver_id"
                                id="editDriverSelect"
                                class="form-select">

                                <option value="">
                                    - Pilih -
                                </option>

                                @foreach($drivers as $driver)

                                    <option
                                        value="{{ $driver->id }}"
                                        data-name="{{ $driver->nama_lengkap }}">

                                        {{ $driver->nama_lengkap }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- PENERIMA --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Penerima
                            </label>

                            <input
                                type="text"
                                name="recipient_name"
                                id="editRecipientName"
                                class="form-control"
                                required>

                        </div>


                        {{-- MOBIL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Mobil / Unit
                            </label>

                            <select
                                name="car_id"
                                id="editCarSelect"
                                class="form-select">

                                <option value="">
                                    - Umum -
                                </option>

                                @foreach($cars as $car)

                                    <option
                                        value="{{ $car->id }}">

                                        {{ $car->unit }}
                                        -
                                        {{ $car->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- NOMINAL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Nominal
                            </label>

                            <input
                                type="text"
                                name="amount"
                                id="editAmount"
                                class="form-control money"
                                required>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Keterangan
                            </label>

                            <input
                                type="text"
                                name="description"
                                id="editDescription"
                                class="form-control">

                        </div>


                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Update

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | FORMAT MONEY
    |--------------------------------------------------------------------------
    */

    function formatMoney(value) {

        return value
            .replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    }


    document.querySelectorAll('.money').forEach(function (element) {

        element.addEventListener('input', function () {

            this.value = formatMoney(this.value);

        });

    });



    /*
    |--------------------------------------------------------------------------
    | CREATE DRIVER -> PENERIMA
    |--------------------------------------------------------------------------
    */

    const createDriverSelect =
        document.getElementById('createDriverSelect');

    const createRecipientName =
        document.getElementById('createRecipientName');


    if (createDriverSelect) {

        createDriverSelect.addEventListener('change', function () {

            const option =
                this.options[this.selectedIndex];

            if (option.dataset.name) {

                createRecipientName.value =
                    option.dataset.name;

            }

        });

    }



    /*
    |--------------------------------------------------------------------------
    | EDIT DRIVER -> PENERIMA
    |--------------------------------------------------------------------------
    */

    const editDriverSelect =
        document.getElementById('editDriverSelect');

    const editRecipientName =
        document.getElementById('editRecipientName');


    if (editDriverSelect) {

        editDriverSelect.addEventListener('change', function () {

            const option =
                this.options[this.selectedIndex];

            if (option.dataset.name) {

                editRecipientName.value =
                    option.dataset.name;

            }

        });

    }



    /*
    |--------------------------------------------------------------------------
    | OPEN EDIT MODAL
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btn-edit-salary')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const id =
                    this.dataset.id;

                /*
                |--------------------------------------------------------------------------
                | SET ACTION UPDATE
                |--------------------------------------------------------------------------
                */

                const form =
                    document.getElementById('editSalaryForm');

                form.action =
                    "{{ url('/finance/salary') }}/" + id;


                /*
                |--------------------------------------------------------------------------
                | SET VALUES
                |--------------------------------------------------------------------------
                */

                document.getElementById(
                    'editTransactionDate'
                ).value =
                    this.dataset.date || '';


                document.getElementById(
                    'editDriverSelect'
                ).value =
                    this.dataset.driver || '';


                document.getElementById(
                    'editRecipientName'
                ).value =
                    this.dataset.recipient || '';


                document.getElementById(
                    'editCarSelect'
                ).value =
                    this.dataset.car || '';


                document.getElementById(
                    'editAmount'
                ).value =
                    this.dataset.amount || '';


                document.getElementById(
                    'editDescription'
                ).value =
                    this.dataset.description || '';

            });

        });

});

</script>

@endsection