<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPrice;
use App\Models\Car;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        return view('page.booking.index');
    }

    public function data(Request $request)
{
    $search = $request->search['value'] ?? '';

    $query = Booking::with([
        'customer',
        'car'
    ]);

    // =========================
    // SEARCH
    // =========================
    if ($search) {

        $query->where(function ($q) use ($search) {

            $q->where('booking_code', 'like', "%{$search}%")

                ->orWhere('status', 'like', "%{$search}%")

                ->orWhereHas('customer', function ($c) use ($search) {

                    $c->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                })

                ->orWhereHas('car', function ($c) use ($search) {

                    $c->where(
                        'unit',
                        'like',
                        "%{$search}%"
                    );
                });
        });
    }

    $recordsTotal = Booking::count();

    $recordsFiltered = $query->count();

    $bookings = $query
        ->latest()
        ->skip($request->start)
        ->take($request->length)
        ->get();

    $data = [];

    foreach ($bookings as $item) {

        // =========================
        // STATUS
        // =========================
        $status = '<span class="badge bg-secondary">-</span>';

        if ($item->status == 'Booking') {

            $status = '<span class="badge bg-success">Booking</span>';

        } elseif ($item->status == 'Selesai') {

            $status = '<span class="badge bg-danger">Selesai</span>';
        }

        // =========================
        // BUTTON PAYMENT / INVOICE
        // =========================
        $paymentButton = '';

        if ($item->payment_status !== 'Lunas') {

            $paymentButton = '

                <button
                    class="btn btn-warning btn-sm btn-payment px-2 py-1"
                    data-id="' . $item->id . '"
                    style="font-size:11px;"
                >
                    Bayar
                </button>

            ';

        } else {

            $paymentButton = '

                <a
                    href="' . route('booking.invoice', $item->id) . '"
                    target="_blank"
                    class="btn btn-success btn-sm px-2 py-1"
                    style="font-size:11px;"
                >
                    <i class="fas fa-download"></i>
                </a>

            ';
        }

        // =========================
        // BUTTON EDIT
        // =========================
        $editButton = '';

        if ($item->payment_status !== 'Lunas') {

            $editButton = '

                <button
                    class="btn btn-primary btn-sm btn-edit-booking px-2 py-1"
                    data-id="' . $item->id . '"
                    style="font-size:11px;"
                >
                    <i
                        class="bi bi-pencil-square"
                        style="font-size:11px;"
                    ></i>
                </button>

            ';
        }

        // =========================
        // BUTTON DELETE (SUPER ADMIN ONLY)
        // =========================
        $deleteButton = '';
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            $deleteButton = '
                <button
                    type="button"
                    class="btn btn-danger btn-sm px-2 py-1 btn-delete-booking"
                    data-id="' . $item->id . '"
                    style="font-size:11px;"
                >
                    <i class="bi bi-trash" style="font-size:11px;"></i>
                </button>
            ';
        }

        // =========================
        // ACTION
        // =========================
        $action = '
            ' . $paymentButton . '
            ' . $editButton . '
            ' . $deleteButton . '
        ';

        $data[] = [

            'DT_RowClass' => $item->open_status == false
                ? 'table-danger'
                : '',

            'booking_code' => '

                <button
                    class="btn btn-link p-0 text-decoration-none fw-bold btn-detail-booking"
                    data-id="' . $item->id . '"
                >
                    ' . $item->booking_code . '
                </button>

            ',

            'customer' => $item->customer->name ?? '-',

            'car' => $item->car->unit ?? '-',

            'start_date' => date(
                'd M Y',
                strtotime($item->start_date)
            ),

            'end_date' => date(
                'd M Y',
                strtotime($item->end_date)
            ),

            'description' => $item->description ?? '-',

            'driver_price' => 'Rp ' .
                number_format(
                    $item->driver_price,
                    0,
                    ',',
                    '.'
                ),

            'total_price' => 'Rp ' .
                number_format(
                    $item->total_price,
                    0,
                    ',',
                    '.'
                ),

            'dp_amount' => 'Rp ' .
                number_format(
                    $item->dp_amount,
                    0,
                    ',',
                    '.'
                ),

            'status' => $status,

            'destination' => $item->destination ?? '-',

            'action' => $action
        ];
    }

    return response()->json([

        'draw' => intval($request->draw),

        'recordsTotal' => $recordsTotal,

        'recordsFiltered' => $recordsFiltered,

        'data' => $data
    ]);
}

    public function search(Request $request)
    {
        $q = $request->q;

        $query = Customer::query();

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%");
            });
        } else {
            return response()->json([]);
        }

        $data = $query->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'text' => $item->name,
                    'nik' => $item->nik,
                    'gender' => $item->gender,
                    'phone' => $item->phone,
                    'address' => $item->address,
                ];
            });

        return response()->json($data);
    }

    public function searchCar(Request $request)
    {
        $q = $request->q;

        $query = Car::where('status', 'available');

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('unit', 'like', "%{$q}%")
                    ->orWhere('plate_number', 'like', "%{$q}%");
            });
        } else {
            return response()->json([]);
        }

        $data = $query->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->unit . ' - ' . $item->plate_number,
                    'price' => $item->price
                ];
            });

        return response()->json($data);
    }

    public function searchDriver(Request $request)
    {
        $q = $request->q;

        $query = Driver::where('status', 'aktif');

        if (!empty($q)) {

            $query->where(function ($sub) use ($q) {

                $sub->where('kode_driver', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%")
                    ->orWhere('no_hp', 'like', "%{$q}%");
            });
        } else {

            return response()->json([]);
        }

        $data = $query->limit(10)
            ->get()
            ->map(function ($item) {

                return [

                    'id' => $item->id,

                    'text' => $item->kode_driver . ' - ' . $item->nama,

                    'no_hp' => $item->no_hp,

                    'no_sim' => $item->no_sim

                ];
            });

        return response()->json($data);
    }

    public function show($id)
    {
        $booking = Booking::with(['customer', 'car', 'driver'])->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $booking->id,

                // customer
                'customer_id' => $booking->customer_id,
                'customer_name' => $booking->customer->name ?? null,

                // car
                'car_id' => $booking->car_id,
                'car_name' => $booking->car->name ?? null,

                'driver_id' => $booking->driver_id,
                'driver_name' => $booking->driver->nama ?? null,

                // data utama
                'nik' => $booking->customer->nik,
                'gender' => $booking->customer->gender,
                'phone' => $booking->customer->phone,
                'address' => $booking->customer->address,

                'start_date' => $booking->start_date,
                'end_date' => $booking->end_date,

                'price' => $booking->car->price,
                'price_driver' => $booking->driver_price,
                'total_price' => $booking->total_price,

                'status' => $booking->status,
                'destination' => $booking->destination,
                'pickup' => $booking->pickup,
            ]
        ]);
    }

    public function storeBooking(Request $request)
    {
        DB::beginTransaction();

        try {

            // =========================
            // VALIDATION
            // =========================
            $request->validate([

                'car_id'         => 'required|exists:cars,id',
                'start_date'     => 'required|date',
                'end_date'       => 'required|date|after_or_equal:start_date',
                'total_price'    => 'required',
                'payment_status' => 'required|in:Belum Bayar,DP,Lunas',

            ]);


            // =========================
            // FORMAT NUMBER
            // =========================
            $totalPrice = preg_replace(
                '/[^0-9]/',
                '',
                $request->total_price
            );

            $dpAmount = preg_replace(
                '/[^0-9]/',
                '',
                $request->dp_amount
            );


            // =========================
            // CUSTOMER
            // =========================
            $customerId = $request->customer_id;

            if (empty($customerId)) {

                $customer = Customer::create([

                    'name'      => $request->customer_name,
                    'nik'       => $request->nik,
                    'gender'    => $request->gender,
                    'phone'     => $request->phone,
                    'address'   => $request->address,

                ]);

                $customerId = $customer->id;
            }


            // =========================
            // GENERATE BOOKING CODE
            // =========================
            $lastBooking = Booking::latest()->first();

            $number = 1;

            if ($lastBooking) {

                $lastNumber = (int) substr(
                    $lastBooking->booking_code,
                    2
                );

                $number = $lastNumber + 1;
            }

            $bookingCode = 'BK' . str_pad(
                $number,
                4,
                '0',
                STR_PAD_LEFT
            );


            // =========================
            // CHECK BOOKING BENTROK
            // =========================
            $isBooked = Booking::where('car_id', $request->car_id)

                ->where('status', 'Booking')

                ->where(function ($query) use ($request) {

                    $query

                        ->whereBetween(
                            'start_date',
                            [
                                $request->start_date,
                                $request->end_date
                            ]
                        )

                        ->orWhereBetween(
                            'end_date',
                            [
                                $request->start_date,
                                $request->end_date
                            ]
                        )

                        ->orWhere(function ($q) use ($request) {

                            $q->where(
                                'start_date',
                                '<=',
                                $request->start_date
                            )

                                ->where(
                                    'end_date',
                                    '>=',
                                    $request->end_date
                                );
                        });
                })

                ->exists();


            // =========================
            // BOOKING SUDAH ADA
            // =========================
            if ($isBooked) {

                DB::rollBack();

                return response()->json([

                    'success' => false,

                    'message' => 'Mobil sudah dibooking pada tanggal tersebut'

                ], 422);
            }


            // =========================
            // STORE BOOKING
            // =========================
            $booking = Booking::create([

                'customer_id'   => $customerId,
                'car_id'        => $request->car_id,
                'driver_id'     => $request->driver_id,
                'booking_code'  => $bookingCode,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'total_price'   => $totalPrice ?: 0,
                'dp_amount'     => $dpAmount ?: 0,
                'status'        => 'Booking',
                'payment_status' => $request->payment_status,
                'destination'   => $request->destination,
                'pickup'        => $request->pickup,
                'open_status'   => true

            ]);

            DB::commit();

            return response()->json([

                'success' => true,

                'message' => 'Booking berhasil disimpan',

                'data' => $booking

            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            // =========================
            // LOG ERROR
            // =========================
            Log::error('STORE BOOKING ERROR', [

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

                'request' => $request->all(),

                'trace' => $e->getTraceAsString()

            ]);

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }

    public function updateBooking(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            // =========================
            // FIND BOOKING
            // =========================
            $booking = Booking::findOrFail($id);


            // =========================
            // VALIDATION
            // =========================
            $request->validate([

                'car_id'         => 'required|exists:cars,id',
                'start_date'     => 'required|date',
                'end_date'       => 'required|date|after_or_equal:start_date',
                'total_price'    => 'required',
                'payment_status' => 'required|in:Belum Bayar,DP,Lunas',

            ]);


            // =========================
            // FORMAT NUMBER
            // =========================
            $totalPrice = preg_replace(
                '/[^0-9]/',
                '',
                $request->total_price
            );

            $dpAmount = preg_replace(
                '/[^0-9]/',
                '',
                $request->dp_amount
            );


            // =========================
            // CUSTOMER
            // =========================
            $customerId = $request->customer_id;

            // CUSTOMER BARU
            if (empty($customerId)) {

                $customer = Customer::create([

                    'name'      => $request->customer_name,
                    'nik'       => $request->nik,
                    'gender'    => $request->gender,
                    'phone'     => $request->phone,
                    'address'   => $request->address,

                ]);

                $customerId = $customer->id;
            } else {

                // UPDATE CUSTOMER
                Customer::where('id', $customerId)
                    ->update([

                        'name'      => $request->customer_name,
                        'nik'       => $request->nik,
                        'gender'    => $request->gender,
                        'phone'     => $request->phone,
                        'address'   => $request->address,

                    ]);
            }


            // =========================
            // CHECK BOOKING BENTROK
            // =========================
            $isBooked = Booking::where('car_id', $request->car_id)

                ->where('status', 'Booking')

                ->where('id', '!=', $booking->id)

                ->where(function ($query) use ($request) {

                    $query

                        ->whereBetween(
                            'start_date',
                            [
                                $request->start_date,
                                $request->end_date
                            ]
                        )

                        ->orWhereBetween(
                            'end_date',
                            [
                                $request->start_date,
                                $request->end_date
                            ]
                        )

                        ->orWhere(function ($q) use ($request) {

                            $q->where(
                                'start_date',
                                '<=',
                                $request->start_date
                            )

                                ->where(
                                    'end_date',
                                    '>=',
                                    $request->end_date
                                );
                        });
                })

                ->exists();


            // =========================
            // BOOKING SUDAH ADA
            // =========================
            if ($isBooked) {

                DB::rollBack();

                return response()->json([

                    'success' => false,

                    'message' => 'Mobil sudah dibooking pada tanggal tersebut'

                ], 422);
            }


            // =========================
            // UPDATE BOOKING
            // =========================
            $booking->update([

                'customer_id'    => $customerId,
                'car_id'         => $request->car_id,
                'driver_id'      => $request->driver_id,

                'start_date'     => $request->start_date,
                'end_date'       => $request->end_date,

                'total_price'    => $totalPrice ?: 0,
                'dp_amount'      => $dpAmount ?: 0,

                'status'         => $booking->status,
                'payment_status' => $request->payment_status,

                'destination'    => $request->destination,
                'pickup'         => $request->pickup,
                'open_status'   => true

            ]);

            DB::commit();

            return response()->json([

                'success' => true,

                'message' => 'Booking berhasil diupdate',

                'data' => $booking

            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('UPDATE BOOKING ERROR', [

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

                'request' => $request->all(),

                'trace' => $e->getTraceAsString()

            ]);

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }

    public function paymentShow($id)
    {
        try {

            $booking = Booking::with([
                'customer',
                'car'
            ])->findOrFail($id);

            return response()->json([

                'success' => true,

                'id' => $booking->id,

                'booking_code' => $booking->booking_code,

                'customer' => [
                    'name' => $booking->customer->name ?? '-'
                ],

                'car' => [
                    'unit' => $booking->car->unit ?? '-'
                ],

                'start_date' => $booking->start_date,
                'end_date' => $booking->end_date,
                'dates' => ($booking->start_date && $booking->end_date)
                    ? (\Carbon\Carbon::parse($booking->start_date)->translatedFormat('d M Y') . ' s/d ' . \Carbon\Carbon::parse($booking->end_date)->translatedFormat('d M Y'))
                    : '-',

                'destination' => $booking->destination ?? '-',
                'pickup' => $booking->pickup ?? '-',

                'total_price' => $booking->total_price,

                'dp_amount' => $booking->dp_amount,

                'payment_status' => $booking->payment_status

            ]);
        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }

    public function paymentBooking(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'booking_id' => 'required|exists:bookings,booking_code',
                'payment_amount' => 'required'
            ]);

            $booking = Booking::with(['customer', 'car'])
                ->where('booking_code', $request->booking_id)
                ->firstOrFail();

            // =========================
            // CLEAN PAYMENT AMOUNT
            // =========================
            $paymentAmount = preg_replace('/[^0-9]/', '', $request->payment_amount);
            $paymentAmount = (int) $paymentAmount;

            // =========================
            // OTHER PRICE DATA
            // =========================
            $otherNames  = $request->other_name ?? [];
            $otherPrices = $request->other_price ?? [];

            $otherTotal = 0;

            // =========================
            // SAVE BOOKING PRICES
            // =========================
            foreach ($otherNames as $index => $name) {

                $price = isset($otherPrices[$index])
                    ? (int) $otherPrices[$index]
                    : 0;

                if ($price <= 0 && !$name) {
                    continue;
                }

                $qty = 1; // default (belum ada input qty di UI)
                $subtotal = $price * $qty;

                $otherTotal += $subtotal;

                BookingPrice::create([
                    'booking_id' => $booking->id,
                    'name'       => $name ?? 'Other Charge',
                    'price'      => $price,
                    'qty'        => $qty,
                    'subtotal'   => $subtotal
                ]);

                CashFlow::create([
                    'booking_id' => $booking->id,
                    'type'       => 'income',
                    'title'      => 'Pembayaran Booking - ' . $booking->booking_code,
                    'amount'     => $price,
                    'description' => 'Pembayaran ' . $name . ' customer ' .
                        ($booking->customer->name ?? '-') .
                        ' mobil ' .
                        ($booking->car->name ?? '-'),
                    'transaction_date' => now()
                ]);
            }

            // =========================
            // TOTAL PAYMENT
            // =========================
            $totalPaid = ($paymentAmount + $otherTotal) + $booking->dp_amount;
            $totalPaymentAmount = $paymentAmount;
            if ($booking->payment_status === 'DP') {

                $totalPaymentAmount = $paymentAmount + $booking->dp_amount;
            } else {
                $totalPaymentAmount;
            }

            // =========================
            // STATUS LOGIC (FIXED)
            // =========================
            $today   = Carbon::now();
            $endDate = Carbon::parse($booking->end_date);

            $isPaidOff        = $totalPaid >= $booking->total_price;
            $isBookingExpired = $endDate->lessThanOrEqualTo($today);

            if ($isPaidOff && $isBookingExpired) {
                $status = 'Selesai';
            } else {
                // tetap status sebelumnya (DP / Aktif / dll)
                $status = $booking->status;
            }

            // =========================
            // UPDATE BOOKING
            // =========================
            $booking->update([
                'total_price'       => $otherTotal,
                'status'            => $status,
                'payment_status'    => 'Lunas'
            ]);

            // =========================
            // CASH FLOW (ONLY MAIN PAYMENT)
            // =========================

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disimpan'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('STORE BOOKING ERROR', [

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

                'request' => $request->all(),

                'trace' => $e->getTraceAsString()

            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function invoice($id)
    {
        $booking = Booking::with([
            'customer',
            'car',
            'driver',
            'bookingPrices'
        ])->findOrFail($id);

        return view(
            'page.booking.invoice',
            compact('booking')
        );
    }

    public function detail($id)
    {
        $booking = Booking::with([
            'customer',
            'car'
        ])->findOrFail($id);

        // =========================
        // UPDATE OPEN STATUS
        // =========================
        $booking->update([
            'open_status' => true
        ]);

        return response()->json($booking);
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $booking = Booking::findOrFail($id);

            // hapus relasi jika ada
            BookingPrice::where('booking_id', $booking->id)->delete();

            CashFlow::where('booking_id', $booking->id)->delete();

            $booking->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dihapus'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('DELETE BOOKING ERROR', [

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
