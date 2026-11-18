<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Keuangan AMII TRANS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            font-size: 11px;
            padding: 20px 0;
        }

        .no-print {
            display: block;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .sheet-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin-bottom: 0 !important;
            }
            .page-break {
                page-break-after: always;
            }
            @page {
                size: A4 landscape;
                margin: 10mm 12mm 10mm 12mm;
            }
        }

        .sheet-card {
            background: #fff;
            max-width: 1100px;
            margin: 0 auto 30px auto;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }

        .header-title {
            text-align: center;
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-subtitle {
            text-align: center;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }

        th {
            background-color: #f1f5f9;
            font-weight: 700;
            color: #334155;
            text-align: center;
        }

        .th-green {
            background-color: #dcfce7 !important;
            color: #166534 !important;
        }

        .th-red {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
        }

        .bg-total {
            background-color: #f8fafc;
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="container no-print mb-3 text-center">
    <div class="d-inline-flex gap-2 p-2 bg-white rounded-3 shadow-sm border">
        <a href="{{ route('cashflow.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Cash Flow
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak / Simpan PDF
        </button>
        <a href="{{ route('cashflow.export.pdf', request()->all()) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF Langsung
        </a>
    </div>
</div>

@include('page.cashflow.pdf', ['report' => $report, 'tab' => $tab])

<script>
    // Auto trigger print dialog if requested
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('autoprint') === '1') {
        window.addEventListener('load', () => window.print());
    }
</script>

</body>
</html>

