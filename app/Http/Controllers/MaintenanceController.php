<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CashFlow;
use App\Models\VehicleMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index($id)
    {
        $car = Car::findOrFail($id);

        return view(
            'page.maintenance.index',
            compact('car')
        );
    }

    /**
     * Maintenance terakhir
     */
    public function lastMaintenance($id)
    {
        $maintenance = VehicleMaintenance::where(
            'car_id',
            $id
        )
            ->latest('maintenance_date')
            ->latest('id')
            ->first();

        return response()->json($maintenance);
    }

    /**
     * Riwayat maintenance
     */
    public function history($id)
    {
        $data = VehicleMaintenance::where(
            'car_id',
            $id
        )
            ->latest('maintenance_date')
            ->latest('id')
            ->get();

        return response()->json($data);
    }

    /**
     * Simpan maintenance
     */
    public function store(Request $request)
    {
        $request->validate([

            'car_id' => 'required|exists:cars,id',

            'maintenance_date' => 'required|date',

            'km_vehicle' => 'nullable|numeric',

            'cost' => 'nullable'
        ]);

        DB::beginTransaction();

        try {

            $maintenance = VehicleMaintenance::create([

                'car_id' => $request->car_id,

                'maintenance_date'
                => $request->maintenance_date,

                'km_vehicle'
                => $request->km_vehicle,

                'oil_spec'
                => $request->oil_spec,

                'previous_oil_km'
                => $request->previous_oil_km,

                'next_oil_km'
                => $request->next_oil_km,

                'front_right_tire'
                => $request->front_right_tire,

                'front_left_tire'
                => $request->front_left_tire,

                'rear_right_tire'
                => $request->rear_right_tire,

                'rear_left_tire'
                => $request->rear_left_tire,

                'workshop_name'
                => $request->workshop_name,

                'cost'
                => str_replace('.', '', $request->cost),

                'notes'
                => $request->notes
            ]);

            /*
            |--------------------------------------------------------------------------
            | CASH FLOW EXPENSE
            |--------------------------------------------------------------------------
            */
            if (!empty($request->cost) && $request->cost > 0) {

                CashFlow::create([

                    'booking_id' => null,

                    'car_id' => $request->car_id,

                    'type' => 'expense',

                    'title' =>
                    'Maintenance ' . $request->unit,

                    'amount' =>
                    str_replace('.', '', $request->cost),

                    'description' =>
                    $request->notes,

                    'transaction_date' =>
                    $request->maintenance_date

                ]);
            }

            DB::commit();

            return response()->json([

                'success' => true,

                'message' => 'Maintenance berhasil disimpan',

                'data' => $maintenance
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }
}
