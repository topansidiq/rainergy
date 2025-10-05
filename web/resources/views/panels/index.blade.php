@extends('layout.app')
@section('title', 'Panels | Rainergy')

@section('content')
    <main class="m-5">
        <script src="/js/panels.js"></script>

        <div class="md:flex max-w-[1600px] gap-3 mx-auto" x-data="{
            modalAddPanel: false,
            panel_latest: null,
            log: false,
            panel_id: null,
            panel_records: [],
            async openLog(url) {
                const data = await getPanelReading(url);
                this.panel_records = data;
            },
            closeLog() {
                this.log = false;
                this.panel_records = null;
                this.panel_id = null;
            }
        }">
            @include('components.header')

            <div class="bg-gray-50 border border-gray-300 shadow-lg rounded-md w-full px-3 relative">
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

                <div class="px-2 pt-2">
                    <button @click="modalAddPanel = true" class="text-sm py-1 px-2 rounded-md bg-gray-700 text-white">
                        Add New Panel
                    </button>
                    <button id="start-sound" class="text-sm py-1 px-2 rounded-md bg-gray-700 text-white">
                        Enable Notification Sound
                    </button>
                    <button @click="pageReload()" class="text-sm py-1 px-2 rounded-md bg-gray-700 text-white">
                        Reload Data
                    </button>

                    <!-- Modal Add Panel -->
                    <div x-show="modalAddPanel" x-cloak class="mt-2 w-full rounded-lg shadow-md p-2 border border-gray-300">
                        <div class="flex justify-end pr-3">
                            <button @click="modalAddPanel = false" class="text-sm text-red-500">Close</button>
                        </div>

                        <form action="{{ route('panels.store') }}" method="POST" class="grid gap-3 grid-cols-2">
                            @csrf
                            <div class="grid grid-cols-1 gap-1">
                                <label for="panel_id" class="text-xs font-bold text-gray-700">
                                    Panel ID<sup class="text-red-500">*</sup>
                                </label>
                                <input type="text" name="panel_id"
                                    class="rounded-sm placeholder:text-sm px-2 outline outline-gray-400"
                                    placeholder="Enter new panel ID" required value="{{ old('panel_id') }}">
                            </div>
                            <div class="grid grid-cols-1 gap-1">
                                <label for="unit_id" class="text-xs font-bold text-gray-700">
                                    Unit ID<sup class="text-red-500">*</sup>
                                </label>
                                <input type="text" name="unit_id"
                                    class="rounded-sm placeholder:text-sm px-2 outline outline-gray-400"
                                    placeholder="Enter new unit ID" required value="{{ old('unit_id') }}">
                            </div>
                            <div>
                                <button type="submit"
                                    class="px-2 py-1 bg-green-500 text-white text-sm rounded-md w-[100px]">
                                    Install
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="table" class="p-2">
                    @if ($panels->isEmpty())
                        <p class="bg-red-300 border border-gray-400 rounded-md w-fit px-2 text-sm">
                            Nothing panel installed!
                        </p>
                    @else
                        <table class="w-full relative">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 border border-gray-400">No</th>
                                    <th class="px-2 py-1 border border-gray-400">Panel ID</th>
                                    <th class="px-2 py-1 border border-gray-400">Unit ID</th>
                                    <th class="px-2 py-1 border border-gray-400">Current (A)</th>
                                    <th class="px-2 py-1 border border-gray-400">Voltage (V)</th>
                                    <th class="px-2 py-1 border border-gray-400">Power (W)</th>
                                    <th class="px-2 py-1 border border-gray-400">Rain Status</th>
                                    <th class="px-2 py-1 border border-gray-400">Wiper Status</th>
                                    <th class="px-2 py-1 border border-gray-400">Installed At</th>
                                    <th class="px-2 py-1 border border-gray-400">Log</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($panels as $index => $panel)
                                    <tr>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $index + 1 }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->panel_id }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->unit_id }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->current }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->voltage }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">
                                            {{ $panel->current * $panel->voltage }}
                                        </td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">
                                            <span>{{ $panel->rain_status ? 'Active' : 'Non-active' }}</span>
                                            <span class="block text-xs">
                                                {{ $panel->rain_status ? 'Cleaning...' : $panel->last_clean }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">
                                            {{ $panel->wiper_status ? 'Active' : 'Non-active' }}
                                        </td>
                                        <td class="px-2 py-1 text-sm border border-gray-400">{{ $panel->created_at }}</td>
                                        <td class="px-2 py-1 text-sm border border-gray-400 bg-green-500 cursor-pointer text-white"
                                            @click="openLog('{{ route('panels.log', $panel->panel_id) }}'), log = true, panel_id = '{{ $panel->panel_id }}'">
                                            Detail
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- Floating Log Panel -->
                <div x-show="log" x-cloak
                    class="absolute top-16 right-3 z-20 bg-gray-900 text-green-400 font-mono text-xs rounded-md p-3 border border-gray-600 w-[550px] max-h-[500px] overflow-y-auto shadow-xl">
                    <div class="flex justify-between items-center border-b border-gray-700 pb-1 mb-2">
                        <h3 class="font-bold text-sky-400 text-sm">Panel Log — <span x-text="panel_id"></span></h3>
                        <button @click="closeLog()" class="text-red-400 hover:text-red-600 text-xs">Close ✕</button>
                    </div>

                    <template x-if="panel_records === 0">
                        <p class="text-gray-400">No log data available...</p>
                    </template>

                    <!-- ✅ gunakan index fallback agar key selalu unik -->
                    <template x-for="(data, index) in panel_records" :key="data.data_id || index">
                        <div class="border-b border-gray-700 py-1">
                            <span class="text-gray-400">[<span x-text="data.data_id ?? '—'"></span>]</span>
                            <span> current=<span x-text="data.current ?? 0"></span>A</span>
                            <span> power=<span x-text="data.power ?? 0"></span>W</span>
                            <span> time=<span x-text="data.recorded_at ?? '—'"></span></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Log Activity Sidebar -->
            <div class="bg-gray-900 border border-gray-300 shadow-lg rounded-md w-1/3 px-3" x-data="{
                logs: [],
                maxLogs: 100,
                async addLog(message) {
                    const now = new Date();
                    const time = now.toLocaleTimeString('id-ID', { hour12: false });
                    this.logs.unshift(`[${time}] ${message}`);
                    if (this.logs.length > this.maxLogs) this.logs.pop();
                },
                async fetchNewData() {
                    try {
                        const res = await fetch('{{ route('panels.logs') }}');
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        const json = await res.json();
                        playNotification();
            
                        if (json.status === 'success' &&
                            Array.isArray(json.data) && json.data.length > 0) {
                            const latest = json.data[0]; // ambil record terbaru
                            const power = (latest.current * latest.voltage).toFixed(2);
                            const msg = `Panel #${latest.panel_id} — New record: ${latest.current}A / ${latest.voltage}V / ${power}W`;
                            this.addLog(msg);
                        }
            
                    } catch (e) {
                        console.warn('Failed to fetch new data:', e);
                    }
                },
                init() {
                    setInterval(() => this.fetchNewData(), 15000);
                }
            }"
                x-init="init()">
                <h1 class="px-2 p-3 font-bold text-sky-500 border-b border-gray-400 text-sm">
                    Log Activity
                </h1>

                <div class="max-h-[600px] overflow-y-auto py-2 space-y-1 text-sm font-mono text-green-400">
                    <template x-for="(log, index) in logs" :key="index">
                        <div x-text="log" class="border-b border-gray-700 pb-1"></div>
                    </template>

                    <template x-if="logs.length === 0">
                        <div class="text-gray-500 text-center py-3 text-xs">
                            Nothing...
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>
@endsection
