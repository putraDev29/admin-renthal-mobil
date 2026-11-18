<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted">Total Gaji</small><h4 class="mb-0">Rp {{ number_format($totalSalary, 0, ',', '.') }}</h4></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted">Pengeluaran Lainnya</small><h4 class="mb-0">Rp {{ number_format($totalOtherExpense, 0, ',', '.') }}</h4></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted">Total Pengeluaran</small><h4 class="mb-0 text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h4></div></div></div>
</div>
