<?php

namespace App\Http\Controllers;

use App\Models\Panel;
use App\Models\PanelReading;
use App\Models\Unit;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $user_id = $user->id;
            $total_unit = Unit::where('user_id', $user->id)->count();
            $total_panel = Panel::whereHas('unit', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->count();
            $panels = Panel::with('unit')->get();
            $current_id = session()->getId();
            $current_session = DB::table('sessions')->where('id', $current_id)->first();
            dump($panels);
            return view('dashboard.index', compact('user', 'current_session', 'total_unit', 'total_panel'));
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function panel()
    {
        try {
            $user = Auth::user();
            $user_id = $user->id;
            $panels = Panel::whereHas('unit', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->get();
            $current_id = session()->getId();
            $current_session = DB::table('sessions')->where('id', $current_id)->first();
            return view('panels.index', compact('panels', 'current_session', 'user'));
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function unit()
    {
        try {
            $user = Auth::user();
            $user_id = $user->id;
            $units = Unit::where('user_id', $user->id)->get();
            $total_power = 0;
            $panels = Panel::whereHas('unit', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->get();
            $total_power = 0;
            foreach ($panels as $panel) {
                $power = $panel->voltage * $panel->current;
                $total_power += $power;
            }
            $current_id = session()->getId();
            $current_session = DB::table('sessions')->where('id', $current_id)->first();
            return view('units.index', compact('panels', 'user', 'current_session', 'total_power', 'units'));
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }
}
