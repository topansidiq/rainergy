<?php

namespace App\Http\Controllers;

use App\Models\Panel;
use App\Models\PanelReading;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{

    public function index() {}

    public function log($panel_id): JsonResponse
    {
        try {
            $panel_record = PanelReading::where('panel_id', $panel_id)->get();

            if ($panel_record->isEmpty()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'No records found for this panel.',
                    'data'    => []
                ], 200);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Successfully retrieved panel records.',
                'data'    => $panel_record,
                'panel_id' => $panel_id
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Internal server error.',
                'data'    => null,
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function logs(): JsonResponse
    {
        try {
            $panel_records = PanelReading::orderBy('id', 'desc')->take(20)->get();
            return response()->json([
                'status'  => 'success',
                'message' => 'Successfully retrieved panels record.',
                'data'    => $panel_records
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Internal server error.',
                'data'    => null,
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'panel_id'     => 'required|string|max:30',
                'unit_id'     => 'required|string|max:30',
            ]);

            $panel = Panel::create([
                'panel_id'     => $request->panel_id,
                'unit_id' => $request->unit_id,
                'updated_at' => now()
            ]);

            return back()->with('status', 'success')->with('message', 'Successfully installed an panel!')->with('panel', $panel);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to install panel',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function latest($panel_id)
    {
        try {
            $panels_record = PanelReading::where('panel_id', $panel_id)->latest();
            dump($panels_record);
            return response()->json(['status' => "success", 'message' => "Successfully get latest data!", 'data' => $panels_record]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
