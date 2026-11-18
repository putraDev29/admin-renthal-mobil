<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishController extends Controller
{
    public function index()
    {
        $wishes = Wish::orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'data' => $wishes
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'message' => 'required|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $wish = Wish::create([
            'name' => $request->name,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'data' => $wish
        ]);
    }
}