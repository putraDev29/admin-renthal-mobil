@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Keuangan - Pengeluaran Lainnya</h4>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.salary.index') }}"
                class="btn btn-outline-secondary btn-sm">
                Gaji
            </a>

            <button type="button"
                class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#createExpenseModal">
                + Tambah Pengeluaran
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
                            <th>Kategori</th>
                            <th>Pengeluaran</th>
                            <th>Mobil</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($expenses as $item)

                            <tr>

                                <td>
                                    {{ $item->transaction_date->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ $item->category }}
                                </td>

                                <td>
                                    {{ $item->title }}
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
                                        class="btn btn-warning btn-sm btn-edit-expense"

                                        data-id="{{ $item->id }}"
                                        data-date="{{ $item->transaction_date->format('Y-m-d') }}"
                                        data-category="{{ $item->category }}"
                                        data-title="{{ $item->title }}"
                                        data-car="{{ $item->car_id }}"
                                        data-amount="{{ number_format($item->amount, 0, ',', '.') }}"
                                        data-description="{{ $item->description }}"

                                        data-bs-toggle="modal"
                                        data-bs-target="#editExpenseModal">

                                        Edit

                                    </button>


                                    {{-- DELETE --}}
                                    <form method="POST"
                                        action="{{ route('finance.expense.destroy', $item) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Hapus data ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="btn btn-danger btn-sm">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted">

                                    Belum ada pengeluaran.

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
    id="createExpenseModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="POST"
                action="{{ route('finance.expense.store') }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Pengeluaran
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


                        {{-- KATEGORI --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Kategori
                            </label>

                            <select name="category"
                                class="form-select"
                                required>

                                <option value="Operasional">
                                    Operasional
                                </option>

                                <option value="Garasi">
                                    Garasi
                                </option>

                                <option value="BBM">
                                    BBM
                                </option>

                                <option value="Tol / Parkir">
                                    Tol / Parkir
                                </option>

                                <option value="Perawatan">
                                    Perawatan
                                </option>

                                <option value="Administrasi">
                                    Administrasi
                                </option>

                                <option value="Lainnya">
                                    Lainnya
                                </option>

                            </select>

                        </div>


                        {{-- NAMA PENGELUARAN --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Nama Pengeluaran
                            </label>

                            <input type="text"
                                name="title"
                                value="{{ old('title') }}"
                                class="form-control"
                                required>

                        </div>


                        {{-- MOBIL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Mobil / Unit
                            </label>

                            <select name="car_id"
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
                                Nominal
                            </label>

                            <input type="text"
                                name="amount"
                                class="form-control money"
                                placeholder="0"
                                required>

                        </div>


                        {{-- KETERANGAN --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Keterangan
                            </label>

                            <input type="text"
                                name="description"
                                value="{{ old('description') }}"
                                class="form-control">

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                        class="btn btn-primary">

                        Simpan Pengeluaran

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
    id="editExpenseModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="POST"
                id="editExpenseForm">

                @csrf
                @method('PUT')


                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Pengeluaran
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
                                id="editTransactionDate"
                                class="form-control"
                                required>

                        </div>


                        {{-- KATEGORI --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Kategori
                            </label>

                            <select name="category"
                                id="editCategory"
                                class="form-select"
                                required>

                                <option value="Operasional">
                                    Operasional
                                </option>

                                <option value="Garasi">
                                    Garasi
                                </option>

                                <option value="BBM">
                                    BBM
                                </option>

                                <option value="Tol / Parkir">
                                    Tol / Parkir
                                </option>

                                <option value="Perawatan">
                                    Perawatan
                                </option>

                                <option value="Administrasi">
                                    Administrasi
                                </option>

                                <option value="Lainnya">
                                    Lainnya
                                </option>

                            </select>

                        </div>


                        {{-- NAMA PENGELUARAN --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Nama Pengeluaran
                            </label>

                            <input type="text"
                                name="title"
                                id="editTitle"
                                class="form-control"
                                required>

                        </div>


                        {{-- MOBIL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Mobil / Unit
                            </label>

                            <select name="car_id"
                                id="editCarSelect"
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
                                Nominal
                            </label>

                            <input type="text"
                                name="amount"
                                id="editAmount"
                                class="form-control money"
                                required>

                        </div>


                        {{-- KETERANGAN --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Keterangan
                            </label>

                            <input type="text"
                                name="description"
                                id="editDescription"
                                class="form-control">

                        </div>


                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
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


    document.querySelectorAll('.money')
        .forEach(function (element) {

            element.addEventListener('input', function () {

                this.value = formatMoney(this.value);

            });

        });



    /*
    |--------------------------------------------------------------------------
    | EDIT MODAL
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btn-edit-expense')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const id = this.dataset.id;

                /*
                |--------------------------------------------------------------------------
                | UPDATE FORM ACTION
                |--------------------------------------------------------------------------
                */

                const form =
                    document.getElementById('editExpenseForm');

                form.action =
                    "{{ url('/finance/expense') }}/" + id;


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
                    'editCategory'
                ).value =
                    this.dataset.category || '';


                document.getElementById(
                    'editTitle'
                ).value =
                    this.dataset.title || '';


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