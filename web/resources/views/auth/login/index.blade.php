@extends('auth.layout.app')

@section('title', 'Login | Rainergy')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900 py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-md w-full space-y-8 bg-white/5 backdrop-blur-md border border-emerald-300/30 rounded-xl p-8 shadow-lg shadow-emerald-500/10">
            <!-- Header -->
            <div>
                <h2 class="mt-6 text-center text-3xl font-bold text-emerald-300">Login</h2>
                <p class="mt-2 text-center text-sm text-gray-300">Bergabunglah dengan energi hijau masa depan</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('auth.login') }}" class="mt-8 space-y-6" x-data="{ mode: 'email', loginValue: '' }">
                @csrf

                <!-- Hidden input supaya field login selalu terkirim -->
                <input type="hidden" name="login" x-model="loginValue">

                <!-- Tombol pilih mode -->
                <div class="flex gap-2 mb-4">
                    <button type="button" @click="mode = 'email'; loginValue=''"
                        :class="mode === 'email' ? 'bg-emerald-500 text-white' : 'bg-neutral-50 text-neutral-700'"
                        class="text-sm px-3 py-1 rounded-2xl transition">
                        <i data-lucide="at-sign" class="w-5 h-5"></i>
                    </button>
                    <button type="button" @click="mode = 'username'; loginValue=''"
                        :class="mode === 'username' ? 'bg-emerald-500 text-white' : 'bg-neutral-50 text-neutral-700'"
                        class="text-sm px-3 py-1 rounded-2xl transition">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Input Email -->
                <div class="mt-1" x-show="mode === 'email'" x-cloak>
                    <label for="email" class="block text-sm font-medium text-emerald-200">Email</label>
                    <input type="email" id="email" x-model="loginValue"
                        class="appearance-none relative block w-full px-3 py-2 border border-emerald-300/50 placeholder-emerald-400 text-gray-100 rounded-md
                   focus:outline-none focus:ring-emerald-400/50 focus:border-emerald-400 focus:z-10 bg-white/10 sm:text-sm"
                        placeholder="Enter your email">
                </div>

                <!-- Input Username -->
                <div class="mt-1" x-show="mode === 'username'" x-cloak>
                    <label for="username" class="block text-sm font-medium text-emerald-200">Username</label>
                    <input type="text" id="username" x-model="loginValue"
                        class="appearance-none relative block w-full px-3 py-2 border border-emerald-300/50 placeholder-emerald-400 text-gray-100 rounded-md
                   focus:outline-none focus:ring-emerald-400/50 focus:border-emerald-400 focus:z-10 bg-white/10 sm:text-sm"
                        placeholder="Enter your username">
                </div>

                <!-- Password -->
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-emerald-200">Password</label>
                    <div class="mt-1 relative">
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                            class="appearance-none relative block w-full px-3 py-2 border border-emerald-300/50 placeholder-emerald-400 text-gray-100 rounded-md
                       focus:outline-none focus:ring-emerald-400/50 focus:border-emerald-400 focus:z-10 bg-white/10 sm:text-sm"
                            placeholder="Enter your password" required>
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-emerald-300 focus:outline-none">
                            <i x-show="!show" data-lucide="eye" class="w-5 h-5 text-emerald-400"></i>
                            <i x-show="show" data-lucide="eye-off" class="w-5 h-5 text-emerald-400"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md
                   text-white bg-emerald-600/80 hover:bg-emerald-700/80 focus:outline-none focus:ring-2 focus:ring-offset-2
                   focus:ring-emerald-400/50 transition duration-200 shadow-md shadow-emerald-500/20">
                        Masuk
                    </button>
                </div>
            </form>

            <!-- Link Login -->
            <div class="text-center">
                <p class="text-sm text-gray-300">
                    Belum punya akun?
                    <a href="{{ route('auth.signin') }}" class="font-medium text-emerald-300 hover:text-emerald-200">Daftar
                        sekarang</a>
                </p>
            </div>
        </div>
    </div>
@endsection
