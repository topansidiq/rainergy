<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'login'    => 'required|string',
                'password' => 'required|string|min:8',
            ]);

            // Cek apakah input adalah email atau username
            $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            // Attempt login
            if (Auth::attempt([$login_type => $request->login, 'password' => $request->password], $request->filled('remember'))) {
                $request->session()->regenerate();

                return redirect()
                    ->route('monitor.dashbboard')
                    ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
            }

            return back()->with('status', 'error')->with('message', 'Invalid credential!');
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong during login!',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show_login()
    {
        try {
            return view('auth.login.index');
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function signin(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            $uuid = \Illuminate\Support\Str::uuid()->toString();
            $customUuid = 'user-' . substr(str_replace('-', '', $uuid), 0, 20);

            $user = User::create([
                'name'     => $request->name,
                'username' => $customUuid,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('auth.show_username')->with('status', 'success')->with('message', 'Successfully create an account!')->with('user', $user);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to register user',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show_username()
    {
        try {
            $user = Auth::user();
            return view('profile.username.index', compact('user'));
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function show_signin()
    {
        try {
            return view('auth.signin.index');
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }
}
