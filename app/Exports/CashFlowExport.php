<?php

namespace App\Exports;

use App\Models\Car;
use App\Models\CashFlow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CashFlowExport implements
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithColumnWidths,
    WithDrawings,
    WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $carId;
    protected $rowCount = 0;

    public function __construct(
        $startDate = null,
        $endDate = null,
        $carId = null
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->carId = $carId;
    }

    public function collection()
    {
        $query = CashFlow::with([
            'booking.car',
            'car'
        ]);

        // Filter tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('transaction_date', [
                $this->startDate,
                $this->endDate
            ]);
        }

        // Filter mobil
        if ($this->carId) {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('booking_id')
                        ->whereHas('booking', function ($booking) {
                            $booking->where('car_id', $this->carId);
                        });
                })->orWhere(function ($sub) {
                    $sub->whereNull('booking_id')
                        ->where('car_id', $this->carId);
                });
            });
        }

        $items = $query->orderBy('transaction_date', 'asc')->get();

        if ($items->isEmpty()) {
            $this->rowCount = 0;
            return collect([
                [
                    'No' => '-',
                    'Tanggal' => '-',
                    'Unit' => '-',
                    'Jenis' => '-',
                    'Title' => 'Tidak ada data transaksi pada periode yang dipilih',
                    'Keterangan' => '-',
                    'Nominal' => 0
                ]
            ]);
        }

        $counter = 1;
        $data = $items->map(function ($item) use (&$counter) {
            $unit = optional(optional($item->booking)->car)->unit
                ?? optional($item->car)->unit
                ?? '-';

            return [
                'No' => $counter++,
                'Tanggal' => date('d-m-Y', strtotime($item->transaction_date)),
                'Unit' => $unit,
                'Jenis' => $item->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                'Title' => $item->title ?? '-',
                'Keterangan' => preg_replace('/\s+/', ' ', trim($item->description ?? '-')),
                'Nominal' => (float) $item->amount
            ];
        });

        $this->rowCount = $data->count();
        return $data;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Unit Armada',
            'Jenis Transaksi',
            'Judul / Uraian Transaksi',
            'Keterangan',
            'Nominal (Rp)'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 15,
            'C' => 22,
            'D' => 18,
            'E' => 34,
            'F' => 38,
            'G' => 24,
        ];
    }

    public function drawings()
    {
        $logoPath = public_path('assets/images/logo.png');
        if (!file_exists($logoPath)) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Amii Trans Logo');
        $drawing->setDescription('Logo Amii Trans');
        $drawing->setPath($logoPath);
        $drawing->setHeight(65);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(12);

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Build filter description
                if ($this->startDate && $this->endDate) {
                    $periodeText = date('d/m/Y', strtotime($this->startDate)) . ' s/d ' . date('d/m/Y', strtotime($this->endDate));
                } else {
                    $periodeText = 'Semua Periode Transaksi';
                }

                $unitText = 'Semua Unit Armada';
                if ($this->carId) {
                    $car = Car::find($this->carId);
                    if ($car) {
                        $unitText = $car->unit . ($car->plate_number ? ' (' . $car->plate_number . ')' : '');
                    }
                }

                // Title Block (Rows 1-4)
                $sheet->setCellValue('A1', 'AMII TRANS - RENTAL MOBIL & TRANSPORT');
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->getFont()->setSize(15)->setBold(true)->getColor()->setARGB('FF0F2E64');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(1)->setRowHeight(28);

                $sheet->setCellValue('A2', 'LAPORAN ARUS KAS (CASH FLOW)');
                $sheet->mergeCells('A2:G2');
                $sheet->getStyle('A2')->getFont()->setSize(12)->setBold(true)->getColor()->setARGB('FF1E293B');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(2)->setRowHeight(22);

                $sheet->setCellValue('A3', 'Periode: ' . $periodeText . '   |   Unit: ' . $unitText);
                $sheet->mergeCells('A3:G3');
                $sheet->getStyle('A3')->getFont()->setSize(10)->getColor()->setARGB('FF475569');
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(3)->setRowHeight(18);

                $sheet->setCellValue('A4', 'Dicetak Pada: ' . date('d-m-Y H:i:s') . ' WIB');
                $sheet->mergeCells('A4:G4');
                $sheet->getStyle('A4')->getFont()->setSize(9)->setItalic(true)->getColor()->setARGB('FF64748B');
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(4)->setRowHeight(18);

                $sheet->getRowDimension(5)->setRowHeight(10);

                // Table Header (Row 6)
                $sheet->getRowDimension(6)->setRowHeight(26);
                $sheet->getStyle('A6:G6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF1E293B'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF334155'],
                        ],
                    ],
                ]);

                // Data Rows (Row 7 to lastDataRow)
                $dataCount = max(1, $this->rowCount);
                $lastDataRow = 6 + $dataCount;

                for ($r = 7; $r <= $lastDataRow; $r++) {
                    $sheet->getRowDimension($r)->setRowHeight(22);

                    $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle("E{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle("F{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle("G{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);

                    // Number format for nominal
                    $sheet->getStyle("G{$r}")->getNumberFormat()->setFormatCode('#,##0');

                    // Alternating row background
                    if ($r % 2 === 0) {
                        $sheet->getStyle("A{$r}:G{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
                    }

                    // Color code Jenis cell
                    $cellValue = $sheet->getCell("D{$r}")->getValue();
                    if ($cellValue === 'Pemasukan') {
                        $sheet->getStyle("D{$r}")->getFont()->setBold(true)->getColor()->setARGB('FF15803D');
                    } elseif ($cellValue === 'Pengeluaran') {
                        $sheet->getStyle("D{$r}")->getFont()->setBold(true)->getColor()->setARGB('FFB91C1C');
                    }
                }

                // Data cell borders
                $sheet->getStyle("A7:G{$lastDataRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFE2E8F0'],
                        ],
                    ],
                ]);

                // Bottom Summary Rows
                $totalIncomeRow = $lastDataRow + 1;
                $totalExpenseRow = $lastDataRow + 2;
                $netProfitRow = $lastDataRow + 3;

                // Total Pemasukan
                $sheet->setCellValue("A{$totalIncomeRow}", 'TOTAL PEMASUKAN');
                $sheet->mergeCells("A{$totalIncomeRow}:F{$totalIncomeRow}");
                $sheet->setCellValue("G{$totalIncomeRow}", "=SUMIF(D7:D{$lastDataRow}, \"Pemasukan\", G7:G{$lastDataRow})");
                $sheet->getRowDimension($totalIncomeRow)->setRowHeight(24);
                $sheet->getStyle("A{$totalIncomeRow}:F{$totalIncomeRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("G{$totalIncomeRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("G{$totalIncomeRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("A{$totalIncomeRow}:G{$totalIncomeRow}")->getFont()->setBold(true)->getColor()->setARGB('FF166534');
                $sheet->getStyle("A{$totalIncomeRow}:G{$totalIncomeRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');

                // Total Pengeluaran
                $sheet->setCellValue("A{$totalExpenseRow}", 'TOTAL PENGELUARAN');
                $sheet->mergeCells("A{$totalExpenseRow}:F{$totalExpenseRow}");
                $sheet->setCellValue("G{$totalExpenseRow}", "=SUMIF(D7:D{$lastDataRow}, \"Pengeluaran\", G7:G{$lastDataRow})");
                $sheet->getRowDimension($totalExpenseRow)->setRowHeight(24);
                $sheet->getStyle("A{$totalExpenseRow}:F{$totalExpenseRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("G{$totalExpenseRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("G{$totalExpenseRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("A{$totalExpenseRow}:G{$totalExpenseRow}")->getFont()->setBold(true)->getColor()->setARGB('FF991B1B');
                $sheet->getStyle("A{$totalExpenseRow}:G{$totalExpenseRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');

                // Saldo Bersih
                $sheet->setCellValue("A{$netProfitRow}", 'SALDO BERSIH (PROFIT / SISA KAS)');
                $sheet->mergeCells("A{$netProfitRow}:F{$netProfitRow}");
                $sheet->setCellValue("G{$netProfitRow}", "=G{$totalIncomeRow}-G{$totalExpenseRow}");
                $sheet->getRowDimension($netProfitRow)->setRowHeight(26);
                $sheet->getStyle("A{$netProfitRow}:F{$netProfitRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("G{$netProfitRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("G{$netProfitRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("A{$netProfitRow}:G{$netProfitRow}")->getFont()->setBold(true)->setSize(11)->getColor()->setARGB('FF1E40AF');
                $sheet->getStyle("A{$netProfitRow}:G{$netProfitRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBEAFE');

                // Borders on Summary rows
                $sheet->getStyle("A{$totalIncomeRow}:G{$netProfitRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF94A3B8'],
                        ],
                    ],
                ]);
                $sheet->getStyle("G{$netProfitRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

                // Enable gridlines
                $sheet->setShowGridlines(true);
            },
        ];
    }
}