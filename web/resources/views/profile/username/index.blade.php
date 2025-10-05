@extends('auth.layout.app')

@section('title', 'New User | Rainergy')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900 py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-md w-full space-y-8 bg-white/5 backdrop-blur-md border border-emerald-300/30 rounded-xl p-8 shadow-lg shadow-emerald-500/10">
            <!-- Header -->
            <div>
                <h2 class="mt-6 text-center text-3xl font-bold text-emerald-300">Create New Username</h2>
            </div>

            <!-- Form -->
            <form action="{{ route('profile.username') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div>
                    <label for="username" class="block text-sm font-medium text-emerald-200">Username</label>
                    <div class="mt-1">
                        <input type="text" id="username" name="username"
                            class="appearance-none relative block w-full px-3 py-2 border border-emerald-300/50 placeholder-emerald-400 text-gray-100 rounded-md focus:outline-none focus:ring-emerald-400/50 focus:border-emerald-400 focus:z-10 bg-white/10 sm:text-sm"
                            placeholder="Enter new username" value="{{ old('username') }}" required autofocus>
                        @error('username')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600/80 hover:bg-emerald-700/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400/50 transition duration-200 shadow-md shadow-emerald-500/20">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
