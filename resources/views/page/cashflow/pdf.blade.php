<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan AMII TRANS</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm 12mm 15mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #222;
            background: #fff;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .header-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .header-subtitle {
            text-align: center;
            font-size: 11px;
            color: #444;
            margin-bottom: 12px;
        }

        .page-break {
            page-break-after: always;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9.5px;
        }

        th, td {
            border: 1px solid #999;
            padding: 4.5px 6px;
            vertical-align: middle;
        }

        th {
            background-color: #e2e8f0;
            color: #1e293b;
            font-weight: bold;
            text-align: center;
        }

        .th-green {
            background-color: #dcfce7;
            color: #166534;
        }

        .th-red {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .bg-total {
            background-color: #f1f5f9;
            font-weight: bold;
        }

        .bg-light-row {
            background-color: #fcfcfc;
        }

        .unit-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 8px;
            color: #0f172a;
        }

        .badge-income {
            color: #16a34a;
            font-weight: bold;
        }

        .badge-expense {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>
<body>

{{-- =========================================================================
     SHEET 1: TOTAL KESELURUHAN
========================================================================= --}}
@if($tab === 'all' || $tab === 'keseluruhan')
    <div class="sheet-container">
        <div class="header-title">LAPORAN KEUANGAN AMII TRANS</div>
        <div class="header-subtitle">Periode: {{ $report['periode_label'] }}</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 16%;">Tanggal</th>
                    <th style="width: 25%;">Keterangan</th>
                    <th style="width: 13%;">Tanggal Keluar</th>
                    <th style="width: 16%;">Tujuan</th>
                    <th style="width: 10%;">Pemasukan (Rp)</th>
                    <th style="width: 10%;">Pengeluaran (Rp)</th>
                    <th style="width: 10%;">Saldo (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['keseluruhan'] as $row)
                    <tr class="{{ $loop->iteration % 2 == 0 ? 'bg-light-row' : '' }}">
                        <td class="text-start">{{ $row['tanggal'] }}</td>
                        <td class="text-start">{{ $row['keterangan'] }}</td>
                        <td class="text-center">{{ $row['tgl_keluar'] }}</td>
                        <td class="text-start">{{ $row['tujuan'] }}</td>
                        <td class="text-end">
                            {{ $row['pemasukan'] > 0 ? number_format($row['pemasukan'], 0, ',', '.') : '' }}
                        </td>
                        <td class="text-end">
                            {{ $row['pengeluaran'] > 0 ? number_format($row['pengeluaran'], 0, ',', '.') : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ number_format($row['saldo'], 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada transaksi pada periode ini</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-total">
                    <td colspan="4" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold" style="color: #166534;">
                        {{ number_format($report['total_income'], 0, ',', '.') }}
                    </td>
                    <td class="text-end fw-bold" style="color: #991b1b;">
                        {{ number_format($report['total_expense'], 0, ',', '.') }}
                    </td>
                    <td class="text-end fw-bold" style="color: #1e40af;">
                        {{ number_format($report['saldo_akhir'], 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($tab === 'all')
        <div class="page-break"></div>
    @endif
@endif


{{-- =========================================================================
     SHEET 2: GARASI
========================================================================= --}}
@if($tab === 'all' || $tab === 'garasi')
    <div class="sheet-container">
        <div class="header-title">LAPORAN KEUANGAN AMII TRANS</div>
        <div class="header-subtitle" style="font-weight: bold; color: #1e293b;">(GARASI)</div>
        <div class="header-subtitle" style="margin-top: -8px;">Periode: {{ $report['periode_label'] }}</div>

        <table style="max-width: 650px; margin: 0 auto;">
            <thead>
                <tr>
                    <th style="width: 25%;">Tanggal</th>
                    <th style="width: 50%;">Kebutuhan</th>
                    <th style="width: 25%;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['garasi'] as $row)
                    <tr>
                        <td class="text-center">{{ $row['tanggal'] }}</td>
                        <td class="text-start">{{ $row['kebutuhan'] }}</td>
                        <td class="text-end">{{ number_format($row['jumlah'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center" style="padding: 15px; color: #64748b;">
                            Tidak ada pengeluaran garasi pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-total">
                    <td colspan="2" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold">{{ number_format($report['total_garasi'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($tab === 'all')
        <div class="page-break"></div>
    @endif
@endif


{{-- =========================================================================
     SHEETS 3 - 6: PER UNIT (AM-01, AM-02, AM-03, AM-04)
========================================================================= --}}
@php
    $targetUnits = ['AM-01', 'AM-02', 'AM-03', 'AM-04'];
@endphp

@foreach($targetUnits as $uKey)
    @php
        $unitData = $report['cars_report'][$uKey] ?? null;
        $tabSlug = strtolower(str_replace([' ', '-'], '', $uKey)); // am01, am02, etc.
    @endphp

    @if($unitData && ($tab === 'all' || $tab === $uKey || $tab === $tabSlug || $tab === strtolower($uKey)))
        <div class="sheet-container">
            <div class="unit-title text-center">{{ $unitData['label'] }}</div>
            <div class="header-subtitle">Periode: {{ $report['periode_label'] }}</div>

            @php
                $pemasukan = $unitData['pemasukan'];
                $pengeluaran = $unitData['pengeluaran'];
                $maxRows = max(count($pemasukan), count($pengeluaran), 1);
            @endphp

            <table>
                <thead>
                    <tr>
                        <th colspan="5" class="th-green">PEMASUKAN</th>
                        <th colspan="3" class="th-red">PENGELUARAN</th>
                    </tr>
                    <tr>
                        <th style="width: 11%;">Jadwal</th>
                        <th style="width: 14%;">Order</th>
                        <th style="width: 17%;">Tujuan</th>
                        <th style="width: 11%;">Tgl</th>
                        <th style="width: 12%;">Setoran (Rp)</th>
                        <th style="width: 11%;">Tanggal</th>
                        <th style="width: 14%;">Keterangan</th>
                        <th style="width: 10%;">Pengeluaran (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < $maxRows; $i++)
                        @php
                            $in = $pemasukan[$i] ?? null;
                            $ex = $pengeluaran[$i] ?? null;
                        @endphp
                        <tr>
                            {{-- Pemasukan columns --}}
                            <td class="text-center">{{ $in['jadwal'] ?? '' }}</td>
                            <td class="text-start">{{ $in['order'] ?? '' }}</td>
                            <td class="text-start">{{ $in['tujuan'] ?? '' }}</td>
                            <td class="text-center">{{ $in['tgl_setor'] ?? '' }}</td>
                            <td class="text-end">
                                {{ isset($in['setoran']) && $in['setoran'] > 0 ? number_format($in['setoran'], 0, ',', '.') : '' }}
                            </td>

                            {{-- Pengeluaran columns --}}
                            <td class="text-center">{{ $ex['tanggal'] ?? '' }}</td>
                            <td class="text-start">{{ $ex['keterangan'] ?? '' }}</td>
                            <td class="text-end">
                                {{ isset($ex['pengeluaran']) && $ex['pengeluaran'] > 0 ? number_format($ex['pengeluaran'], 0, ',', '.') : '' }}
                            </td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="bg-total">
                        <td colspan="4" class="text-end fw-bold">Total</td>
                        <td class="text-end fw-bold" style="color: #166534;">
                            {{ number_format($unitData['total_pemasukan'], 0, ',', '.') }}
                        </td>
                        <td colspan="2" class="text-end fw-bold">Total</td>
                        <td class="text-end fw-bold" style="color: #991b1b;">
                            {{ number_format($unitData['total_pengeluaran'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($tab === 'all')
            <div class="page-break"></div>
        @endif
    @endif
@endforeach


{{-- =========================================================================
     SHEET 7: LAIN LAIN
========================================================================= --}}
@if($tab === 'all' || $tab === 'lainlain' || $tab === 'lain-lain')
    <div class="sheet-container">
        <div class="header-title">LAPORAN KEUANGAN AMII TRANS</div>
        <div class="header-subtitle" style="font-weight: bold; color: #1e293b;">(Lain Lain)</div>
        <div class="header-subtitle" style="margin-top: -8px;">Periode: {{ $report['periode_label'] }}</div>

        <table style="max-width: 650px; margin: 0 auto;">
            <thead>
                <tr>
                    <th style="width: 25%;">Tanggal</th>
                    <th style="width: 50%;">Keterangan</th>
                    <th style="width: 25%;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['lain_lain'] as $row)
                    <tr>
                        <td class="text-center">{{ $row['tanggal'] }}</td>
                        <td class="text-start">{{ $row['keterangan'] }}</td>
                        <td class="text-end">{{ number_format($row['jumlah'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center" style="padding: 15px; color: #64748b;">
                            Tidak ada pengeluaran lain-lain pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-total">
                    <td colspan="2" class="text-end fw-bold">TOTAL</td>
                    <td class="text-end fw-bold">{{ number_format($report['total_lain_lain'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endif

</body>
</html>

