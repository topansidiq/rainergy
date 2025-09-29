@extends('auth.layout.app')

@section('title', 'Signin | Rainergy')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900 py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-md w-full space-y-8 bg-white/5 backdrop-blur-md border border-emerald-300/30 rounded-xl p-8 shadow-lg shadow-emerald-500/10">
            <!-- Header -->
            <div>
                <h2 class="mt-6 text-center text-3xl font-bold text-emerald-300">Buat Akun</h2>
                <p class="mt-2 text-center text-sm text-gray-300">Bergabunglah dengan energi hijau masa depan</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('auth.signin') }}" class="mt-8 space-y-6">
                @csrf

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-emerald-200">Nama Lengkap</label>
                    <div class="mt-1">
                        <input type="text" id="name" name="name"
                            class="appearance-none relative block w-full px-3 py-2 border border-emerald-300/50 placeholder-emerald-400 text-gray-100 rounded-md focus:outline-none focus:ring-emerald-400/50 focus:border-emerald-400 focus:z-10 bg-white/10 sm:text-sm"
                            placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-emerald-200">Email</label>
                    <div class="mt-1">
                        <input type="email" id="email" name="email"
                            class="appearance-none relative block w-full px-3 py-2 border border-emerald-300/50 placeholder-emerald-400 text-gray-100 rounded-md focus:outline-none focus:ring-emerald-400/50 focus:border-emerald-400 focus:z-10 bg-white/10 sm:text-sm"
                            placeholder="Masukkan email" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-emerald-200">Kata Sandi</label>
                    <div class="mt-1 relative">
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                            class="appearance-none relative block w-full px-3 py-2 border border-emerald-300/50 placeholder-emerald-400 text-gray-100 rounded-md focus:outline-none focus:ring-emerald-400/50 focus:border-emerald-400 focus:z-10 bg-white/10 sm:text-sm"
                            placeholder="Masukkan kata sandi" required>
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-emerald-300 focus:outline-none">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7a9.956 9.956 0 012.603-4.357m3.127-2.42A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.132 5.225M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-sm font-medium text-emerald-200">Konfirmasi Kata
                        Sandi</label>
                    <div class="mt-1 relative">
                        <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                            class="appearance-none relative block w-full px-3 py-2 border border-emerald-300/50 placeholder-emerald-400 text-gray-100 rounded-md focus:outline-none focus:ring-emerald-400/50 focus:border-emerald-400 focus:z-10 bg-white/10 sm:text-sm"
                            placeholder="Konfirmasi kata sandi" required>
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-emerald-300 focus:outline-none">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7a9.956 9.956 0 012.603-4.357m3.127-2.42A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.132 5.225M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600/80 hover:bg-emerald-700/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400/50 transition duration-200 shadow-md shadow-emerald-500/20">
                        Daftar Sekarang
                    </button>
                </div>
            </form>

            <!-- Link Login -->
            <div class="text-center">
                <p class="text-sm text-gray-300">
                    Sudah punya akun?
                    <a href="{{ route('auth.login') }}" class="font-medium text-emerald-300 hover:text-emerald-200">Masuk
                        sekarang</a>
                </p>
            </div>
        </div>
    </div>
@endsection
