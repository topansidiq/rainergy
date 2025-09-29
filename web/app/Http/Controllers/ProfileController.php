<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function username(Request $request, $id)
    {
        try {
            $request->validate([
                'username' => [
                    'required',
                    'string',
                    'min:3',
                    'max:20',
                ],
            ]);


            if (User::where('username', $request->username)->exists()) {
                return back()->with('status', 'failed')->with('message', 'Username is already taken!');
            }

            if (!preg_match('/^[a-z0-9._]+$/', $request->username)) {
                return back()->with('status', 'failed')->with('message', 'Only alphebet, number, underscore, and full stop is accepted!');
            }

            $user = User::findOrFail($id);
            $user->update(['username' => $request->username]);

            return redirect()->route('monitor.dashbboard')->with('status', 'success')->with('message', 'Username updated successfully!');
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }
}
