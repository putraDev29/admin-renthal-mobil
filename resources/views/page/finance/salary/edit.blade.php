@extends('layouts.app')
@section('content')
    <div class="container-fluid py-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4>Edit Gaji</h4>
                <form method="POST" action="{{ route('finance.salary.update', $salary) }}" class="row g-3">@csrf
                    @method('PUT')
                    <div class="col-md-4"><label class="form-label">Tanggal</label><input type="date"
                            name="transaction_date"
                            value="{{ old('transaction_date', $salary->transaction_date->format('Y-m-d')) }}"
                            class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Driver (opsional)</label><select name="driver_id"
                            class="form-select">
                            <option value="">- Pilih -</option>@foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ $salary->driver_id == $driver->id ? 'selected' : '' }}>
                            {{ $driver->nama_lengkap }}</option>@endforeach
                        </select></div>
                    <div class="col-md-4"><label class="form-label">Penerima</label><input type="text" name="recipient_name"
                            value="{{ old('recipient_name', $salary->recipient_name) }}" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Mobil / Unit</label><select name="car_id"
                            class="form-select">
                            <option value="">- Umum -</option>@foreach($cars as $car)
                                <option value="{{ $car->id }}" {{ $salary->car_id == $car->id ? 'selected' : '' }}>{{ $car->unit }} -
                            {{ $car->name }}</option>@endforeach
                        </select></div>
                    <div class="col-md-4"><label class="form-label">Nominal</label><input type="text" name="amount"
                            value="{{ number_format($salary->amount, 0, ',', '.') }}" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Keterangan</label><input type="text" name="description"
                            value="{{ old('description', $salary->description) }}" class="form-control"></div>
                    <div class="col-12"><button class="btn btn-primary">Update</button> <a
                            href="{{ route('finance.salary.index') }}" class="btn btn-secondary">Kembali</a></div>
                </form>
            </div>
        </div>
    </div>
@endsection