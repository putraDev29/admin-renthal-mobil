# Modul Keuangan Simple

Modul terdiri dari:

1. **Gaji** (`salaries`)
   - tanggal
   - penerima
   - driver opsional
   - mobil/unit opsional
   - nominal
   - keterangan

2. **Pengeluaran Lainnya** (`expenses`)
   - tanggal
   - kategori
   - nama pengeluaran
   - mobil/unit opsional
   - nominal
   - keterangan

Tidak ada status dibayar/belum dibayar. Setiap data yang disimpan langsung dihitung sebagai pengeluaran.

## Integrasi Cash Flow

Setiap Gaji dan Pengeluaran Lainnya otomatis membuat transaksi `expense` pada tabel `cash_flows`.
Kolom `source_type` dan `source_id` dipakai untuk sinkronisasi saat data diedit atau dihapus.

## Instalasi / update database

```bash
php artisan migrate
php artisan optimize:clear
```

Jika ingin mengecek route modul:

```bash
php artisan route:list --name=finance
```
