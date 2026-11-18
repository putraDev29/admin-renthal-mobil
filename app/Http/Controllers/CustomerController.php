<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('page.customer.index');
    }

    // =========================
    // DATA TABLE
    // =========================
    public function data(Request $request)
    {
        $query = Customer::query();


        $cutomers = $query
            ->latest()
            ->get();

        $data = [];

        foreach ($cutomers as $index => $item) {

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

                'nik' => $item->nik,
                'name' => $item->name,
                'gender' => $item->gender,
                'phone' => $item->phone,
                'address' => $item->address,

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

            'nik' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'address' => 'required'

        ]);

        Customer::create([

            'nik' => $request->nik,
            'name' => $request->name,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address

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
        $customer = Customer::findOrFail($id);

        return response()->json($customer);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $customer->update([

            'nik' => $request->nik,
            'name' => $request->name,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address

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
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return response()->json([
            'success' => true
        ]);
    }
}