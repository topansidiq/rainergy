@extends('layout.app')
@section('title', 'Dashboard | Rainergy')
@section('content')
    <main class="m-3">
        <div class="md:flex max-w-[1600px] gap-3 mx-auto">
            @include('components.header')
            <div class="bg-gray-50 border border-gray-200 shadow-lg rounded-md w-full px-3">
                <div>
                    <h1 class="p-2 font-bold text-2xl text-green-500">Rainergy Dashboard</h1>
                    <div class="mx-2 px-1.5 py-0.5 w-fit text-xs border border-gray-300 text-gray-500 rounded-md">
                        User: {{ $user->name }} | Monitor ID: {{ $user->username }} | Membership:
                        @if ($user->membership)
                            Active
                        @else
                            Non-active
                        @endif | IP Address: {{ $current_session->ip_address }} | Device:
                        {{ $current_session->user_agent }}
                    </div>
                </div>

                <div id="activity" class="p-2">
                    <div class="grid grid-cols-3 gap-3 w-full">
                        <div class="border rounded-md border-gray-300 p-2 text-center bg-emerald-300">
                            <label for="units" class="text-sm">Actived Unit</label>
                            <p class="text-2xl font-bold">{{ $total_unit }}</p>
                        </div>
                        <div class="border rounded-md border-gray-300 p-2 text-center bg-sky-300">
                            <label for="units" class="text-sm">Installed Panel</label>
                            <p class="text-2xl font-bold">{{ $total_panel }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
