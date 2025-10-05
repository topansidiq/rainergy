<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'unit_id'     => 'required|string|max:30',
                'location' => 'string|nullable'
            ]);

            $unit = Unit::create([
                'unit_id'     => $request->unit_id,
                'user_id' => Auth::id(),
                'location' => $request->location,
            ]);

            return back()->with('status', 'success')->with('message', 'Successfully installed an unit!')->with('unit', $unit);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to install unit',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
