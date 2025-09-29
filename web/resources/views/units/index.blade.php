@extends('layout.app')
@section('title' | 'Units | Rainergy')
@section('content')
    <main class="m-5">
        <div class="md:flex max-w-[1600px] gap-3 mx-auto">
            @include('components.header')
            <div class="bg-gray-50 border border-gray-300 shadow-lg rounded-md w-full px-3">
                <div>
                    <h1 class="p-2 font-bold text-2xl text-green-500">Unit Monitor</h1>
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

                <div id="table" class="p-2">
                    @if ($units->isEmpty())
                        <p class="bg-red-300 border border-gray-400 rounded-md w-fit px-2 text-sm">Nothing unit installed!
                        </p>
                    @else
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 border border-gray-400">No</th>
                                    <th class="px-2 py-1 border border-gray-400">Unit ID</th>
                                    <th class="px-2 py-1 border border-gray-400">Power</th>
                                    <th class="px-2 py-1 border border-gray-400">Location</th>
                                    <th class="px-2 py-1 border border-gray-400">Installed at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = 1;
                                @endphp
                                @foreach ($units as $unit)
                                    <tr>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $index }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $unit->unit_id }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $total_power }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $unit->location }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $unit->created_at }}</td>
                                    </tr>
                                    @php
                                        $index++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
