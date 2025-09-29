<?php

namespace App\Http\Controllers;

use App\Models\PanelReading;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class PanelController extends Controller
{
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
                'data'    => $panel_record
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
}
