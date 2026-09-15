@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Title Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-4">
        <div>
            <h2 class="text-xl font-bold text-blue-900 uppercase tracking-tight">Operations Control Center</h2>
            <p class="text-xs text-gray-600">Real-time summary of personnel records, job orders, and system activities.</p>
        </div>
        <div class="mt-2 md:mt-0 flex space-x-2">
            <a href="{{ route('pds.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-3 py-2 rounded shadow transition">
                + New PDS Entry
            </a>
            <a href="{{ route('appointments.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-3 py-2 rounded shadow transition">
                + Schedule Appointment
            </a>
        </div>
    </div>

    <!-- Top Statistical KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total PDS Records -->
        <div class="bg-white border-t-4 border-blue-900 p-4 shadow-sm border-x border-b border-gray-200">
            <p class="text-xs font-bold text-gray-500 uppercase">Registered PDS Profiles</p>
            <div class="flex justify-between items-baseline mt-2">
                <span class="text-3xl font-extrabold text-blue-900">{{ \App\Models\Pds::count() }}</span>
                <span class="text-xs text-green-600 font-semibold bg-green-50 px-2 py-0.5 rounded">Active Database</span>
            </div>
            <a href="{{ route('masterlist') }}" class="text-xs text-blue-600 hover:underline mt-3 block">View Masterlist &rarr;</a>
        </div>

        <!-- Total Appointments -->
        <div class="bg-white border-t-4 border-amber-500 p-4 shadow-sm border-x border-b border-gray-200">
            <p class="text-xs font-bold text-gray-500 uppercase">Total Appointments</p>
            <div class="flex justify-between items-baseline mt-2">
                <span class="text-3xl font-extrabold text-amber-700">{{ \App\Models\Appointment::count() }}</span>
                <span class="text-xs text-amber-700 font-semibold bg-amber-50 px-2 py-0.5 rounded">All Schedules</span>
            </div>
            <a href="{{ route('appointments.index') }}" class="text-xs text-amber-700 hover:underline mt-3 block">Manage Queue &rarr;</a>
        </div>

        <!-- Pending Appointments -->
        <div class="bg-white border-t-4 border-blue-700 p-4 shadow-sm border-x border-b border-gray-200">
            <p class="text-xs font-bold text-gray-500 uppercase">Pending Review</p>
            <div class="flex justify-between items-baseline mt-2">
                <span class="text-3xl font-extrabold text-blue-800">
                    {{ \App\Models\Appointment::where('status', 'Pending')->count() }}
                </span>
                <span class="text-xs text-blue-700 font-semibold bg-blue-50 px-2 py-0.5 rounded">Queue Waiting</span>
            </div>
            <p class="text-xs text-gray-400 mt-3">Requires administrative action</p>
        </div>

        <!-- System Status -->
        <div class="bg-white border-t-4 border-green-700 p-4 shadow-sm border-x border-b border-gray-200">
            <p class="text-xs font-bold text-gray-500 uppercase">Server & DB Status</p>
            <div class="flex items-center space-x-2 mt-3">
                <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-sm font-bold text-gray-700">Online & Synchronized</span>
            </div>
            <p class="text-xs text-gray-400 mt-2">Environment: Laragon / PHP {{ PHP_VERSION }}</p>
        </div>
    </div>

    <!-- Data Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent PDS Entries -->
        <div class="bg-white border border-gray-200 shadow-sm">
            <div class="bg-blue-900 text-white px-4 py-3 flex justify-between items-center">
                <h3 class="text-sm font-bold uppercase tracking-wider">Recently Added PDS Profiles</h3>
                <a href="{{ route('masterlist') }}" class="text-xs text-amber-300 hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200 text-gray-700 uppercase">
                            <th class="p-3">Unique ID</th>
                            <th class="p-3">Full Name</th>
                            <th class="p-3">Gender</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse(\App\Models\Pds::latest()->take(5)->get() as $pds)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-mono font-bold text-blue-900">{{ $pds->unique_id }}</td>
                            <td class="p-3 font-semibold">{{ $pds->first_name }} {{ $pds->last_name }}</td>
                            <td class="p-3">{{ $pds->gender }}</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('pds.show', $pds->id) }}" class="text-blue-700 hover:underline font-semibold">Inspect</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">No personnel records found in the database.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Expiring Soon Appointments -->
        <div class="bg-white border border-gray-200 shadow-sm">
            <div class="bg-blue-900 text-white px-4 py-3 flex justify-between items-center">
                <h3 class="text-sm font-bold uppercase tracking-wider">Expiring Soon Appointments</h3>
                <a href="{{ route('appointments.index') }}" class="text-xs text-amber-300 hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200 text-gray-700 uppercase">
                            <th class="p-3">Personnel / PDS</th>
                            <th class="p-3">Schedule Date</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @php
                        $expiringAppointments = \App\Models\Appointment::with('pds')
                            ->where('status', 'Approved')
                            ->whereNotNull('period_to')
                            ->whereBetween('period_to', [now()->toDateString(), now()->addMonth()->toDateString()])
                            ->take(5)
                            ->get();
                    @endphp

                    @forelse($expiringAppointments as $app)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold">
                            {{ optional($app->pds)->first_name ?? 'N/A' }} {{ optional($app->pds)->last_name ?? '' }}
                        </td>
                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($app->period_from)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($app->period_to)->format('M d, Y') }}
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                Expiring Soon
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            <a href="{{ route('appointments.create', [
                                'pds_id' => $app->pds_id,
                                'designation' => $app->designation,
                                'rate_per_day' => $app->rate_per_day,
                                'office_assignment' => $app->office_assignment,
                                'renewal_status' => 'RENEWAL'
                            ]) }}" class="bg-emerald-700 hover:bg-emerald-800 text-white text-[10px] font-semibold px-2.5 py-1 rounded shadow transition">
                                Renew
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">No approved appointments expiring within the next month.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection