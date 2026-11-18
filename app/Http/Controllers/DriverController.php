<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        return view('page.driver.index');
    }

    // =========================
    // DATA TABLE
    // =========================
    public function data(Request $request)
    {
        $query = Driver::query();

        $drivers = $query
            ->latest()
            ->get();

        $data = [];

        foreach ($drivers as $index => $item) {

            $action = '
                <div class="d-flex gap-2">

                    <button
                        class="btn btn-warning btn-sm btn-edit"
                        data-id="'.$item->id.'"
                    >
                        <i
                            class="bi bi-pencil-square"
                            style="font-size:11px;"
                        ></i>
                    </button>

                    <button
                        class="btn btn-danger btn-sm btn-delete"
                        data-id="'.$item->id.'"
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

                'kode_driver' => $item->kode_driver,
                'nama' => $item->nama,
                'no_hp' => $item->no_hp,
                'email' => $item->email,
                'alamat' => $item->alamat,
                'no_sim' => $item->no_sim,
                'masa_berlaku_sim' => $item->masa_berlaku_sim,
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

            'nama' => 'required',
            'no_hp' => 'nullable',
            'email' => 'nullable|email',
            'alamat' => 'nullable',
            'no_sim' => 'nullable',
            'masa_berlaku_sim' => 'nullable|date',
            'status' => 'required'

        ]);

        $lastDriver = Driver::latest()->first();

        $number = 1;

        if ($lastDriver) {

            $number = (int) substr($lastDriver->kode_driver, 3) + 1;

        }

        $kodeDriver = 'DRV' . str_pad($number, 4, '0', STR_PAD_LEFT);

        Driver::create([

            'kode_driver' => $kodeDriver,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_sim' => $request->no_sim,
            'masa_berlaku_sim' => $request->masa_berlaku_sim,
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
        $driver = Driver::findOrFail($id);

        return response()->json($driver);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([

            'nama' => 'required',
            'email' => 'nullable|email',
            'masa_berlaku_sim' => 'nullable|date',
            'status' => 'required'

        ]);

        $driver->update([

            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_sim' => $request->no_sim,
            'masa_berlaku_sim' => $request->masa_berlaku_sim,
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
        $driver = Driver::findOrFail($id);

        $driver->delete();

        return response()->json([
            'success' => true
        ]);
    }
}