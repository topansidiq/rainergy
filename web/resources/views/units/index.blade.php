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

                <div class="px-2 pt-2" x-data="{ modalAddUnit: false }">
                    <button @click="modalAddUnit=true"
                        class="cursor-pointer text-xs py-1 px-2 rounded-md bg-gray-700 text-white">
                        Add New Unit
                    </button>

                    <div x-show="modalAddUnit" class="mt-2 w-full rounded-lg shadow-md p-2 border border-gray-300">
                        <div class="flex justify-end pr-3">
                            <button @click="modalAddUnit=false" class="text-sm text-red-500 cursor-pointer">
                                Close
                            </button>
                        </div>

                        <form action="{{ route('units.store') }}" method="POST" class="grid gap-3 grid-cols-2">
                            @csrf
                            <div class="grid grid-cols-1 gap-1">
                                <label for="unit_id" class="text-xs font-bold text-gray-700">Unit ID<sup
                                        class="text-red-500">*</sup></label>
                                <input type="text" name="unit_id"
                                    class="rounded-sm placeholder:text-sm px-2 outline outline-gray-400"
                                    placeholder="Enter new unit ID" required value="{{ old('unit_id') }}">
                            </div>
                            <div class="grid grid-cols-1 gap-1">
                                <label for="location" class="text-xs font-bold text-gray-700">Location</label>
                                <input type="text" name="location"
                                    class="rounded-sm placeholder:text-sm px-2 outline outline-gray-400"
                                    placeholder="Enter unit location" value="{{ old('location') }}">
                            </div>
                            <div>
                                <button type="submit"
                                    class="px-2 py-1 bg-green-500 text-white text-sm rounded-md w-[100px] cursor-pointer">Install</button>
                            </div>
                        </form>
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
