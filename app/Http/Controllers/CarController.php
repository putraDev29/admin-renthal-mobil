<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        return view('page.car.index');
    }

    // =========================
    // DATA TABLE
    // =========================
    public function data(Request $request)
    {
        $query = Car::query();

        $cars = $query
            ->latest()
            ->get();

        $data = [];

        foreach ($cars as $index => $item) {

            $action = '
                    <div class="d-flex gap-2">

                        <a
                            href="' . route('maintenance.index', $item->id) . '"
                            class="btn btn-info btn-sm"
                            title="Maintenance"
                        >
                            <i
                                class="bi bi-wrench-adjustable-circle"
                                style="font-size:11px;"
                            ></i>
                        </a>

                        <button
                            class="btn btn-warning btn-sm btn-edit"
                            data-id="' . $item->id . '"
                            title="Edit"
                        >
                            <i
                                class="bi bi-pencil-square"
                                style="font-size:11px;"
                            ></i>
                        </button>

                        <button
                            class="btn btn-danger btn-sm btn-delete"
                            data-id="' . $item->id . '"
                            title="Hapus"
                        >
                            <i
                                class="bi bi-trash"
                                style="font-size:11px;"
                            ></i>
                        </button>

                    </div>
                ';

            $data[] = [

                'DT_RowIndex' => $index + 1,

                'unit' => $item->unit,
                'name' => $item->name,
                'plate_number' => $item->plate_number,
                'total_unit' => $item->total_unit,
                'price' => number_format($item->price, 0, ',', '.'),
                'tax' => $item->tax,
                'kir' => $item->kir,
                'status' => $item->status,

                'action' => $action

            ];
        }

        return response()->json([
            'data' => $data
        ]);
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {

        $request->validate([

            'unit' => 'required',
            'name' => 'required',
            'plate_number' => 'required',
            'total_unit' => 'required|integer',
            'price' => 'required|numeric',
            'tax' => 'required|date',
            'kir' => 'required|date',
            'status' => 'required'

        ]);

        Car::create([

            'unit' => $request->unit,
            'name' => $request->name,
            'plate_number' => $request->plate_number,
            'total_unit' => $request->total_unit,
            'price' => $request->price,
            'tax' => $request->tax,
            'kir' => $request->kir,
            'status' => $request->status

        ]);

        return response()->json([
            'success' => true
        ]);
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $car = Car::findOrFail($id);

        return response()->json($car);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $car->update([

            'unit' => $request->unit,
            'plate_number' => $request->plate_number,
            'total_unit' => $request->total_unit,
            'price' => $request->price,
            'tax' => $request->tax,
            'kir' => $request->kir,
            'status' => $request->status

        ]);

        return response()->json([
            'success' => true
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        $car->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
