@extends('layout.app')
@section('title', 'Panels | Rainergy')
@section('content')
    <main class="m-5">
        <script src="/js/panels.js"></script>
        <div class="md:flex max-w-[1600px] gap-3 mx-auto">
            @include('components.header')
            <div class="bg-gray-50 border border-gray-300 shadow-lg rounded-md w-full px-3">
                <div>
                    <h1 class="p-2 font-bold text-2xl text-green-500">Panel Monitor</h1>
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
                    @if ($panels->isEmpty())
                        <p class="bg-red-300 border border-gray-400 rounded-md w-fit px-2 text-sm">Nothing panel installed!
                        </p>
                    @else
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 border border-gray-400">No</th>
                                    <th class="px-2 py-1 border border-gray-400">Panel ID</th>
                                    <th class="px-2 py-1 border border-gray-400">Unit ID</th>
                                    <th class="px-2 py-1 border border-gray-400">Dust Level</th>
                                    <th class="px-2 py-1 border border-gray-400">Current (A)</th>
                                    <th class="px-2 py-1 border border-gray-400">Voltage (v)</th>
                                    <th class="px-2 py-1 border border-gray-400">Power (w)</th>
                                    <th class="px-2 py-1 border border-gray-400">Pump Status</th>
                                    <th class="px-2 py-1 border border-gray-400">Wiper Status</th>
                                    <th class="px-2 py-1 border border-gray-400">Installed At</th>
                                    <th class="px-2 py-1 border border-gray-400">Log</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($panels as $index => $panel)
                                    <tr x-data="{ log: false, panel_record: null }">
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $index + 1 }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->panel_id }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->unit_id }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->dust }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->current }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->voltage }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">
                                            {{ $panel->current * $panel->voltage }}
                                        </td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">
                                            {{ $panel->pump_status ? 'Active' : 'Non-active' }}
                                        </td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">
                                            {{ $panel->wiper_status ? 'Active' : 'Non-active' }}
                                        </td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->installed_at }}
                                        </td>
                                        <td class="px-2 py-1 text-sm border border-gray-400 bg-green-500 cursor-pointer"
                                            @click="log = !log" x-init="panel_record = getPanelRecord('{{ route('monitor.panels.log', $panel->panel_id) }}')">
                                            <i data-lucide="terminal" class="w-5 h-5"></i>
                                            <!-- Terminal Window -->
                                            <div x-show="log" x-cloak
                                                class="bg-gray-800 text-green-400 font-mono text-xs rounded-md p-2 border border-gray-600 absolute right-60">
                                                <button class="text-red-500 w-full text-right" @click="!log">Close</button>
                                                <template x-for="data in panel_record" :key="data.id">
                                                    <div>
                                                        <span class="text-gray-400">[<span
                                                                x-text="data.data_id"></span>]</span>
                                                        <span>x: <span x-text="data.panel_id"></span></span>
                                                        <span>dust=<span x-text="data.dust"></span></span>
                                                        <span>current=<span x-text="data.current"></span>A</span>
                                                        <span>voltage=<span x-text="data.voltage"></span>V</span>
                                                        <span>pump=<span
                                                                x-text="data.pump_status ? 'ON' : 'OFF'"></span></span>
                                                        <span>wiper=<span
                                                                x-text="data.wiper_status ? 'ON' : 'OFF'"></span></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>

                                    <div x-data="{
                                        dataset: 'dust',
                                        api: '{{ route('monitor.panels.log', $panel->panel_id) }}',
                                        init() { renderPanelChartD3(this.$refs.chartContainer, this.api, this.dataset) }
                                    }" class="p-4">

                                        <div class="mb-3">
                                            <label class="mr-2 font-semibold">Pilih Data:</label>
                                            <select x-model="dataset"
                                                @change="renderPanelChartD3($refs.chartContainer, api, dataset)"
                                                class="border rounded px-2 py-1">
                                                <option value="dust">Dust</option>
                                                <option value="current">Current</option>
                                                <option value="voltage">Voltage</option>
                                            </select>
                                        </div>

                                        <div x-ref="chartContainer"></div>
                                    </div>
                                @endforeach

                                @php
                                    $index++;
                                @endphp
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
