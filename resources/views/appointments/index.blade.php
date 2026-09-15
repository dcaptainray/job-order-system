@extends('layouts.app')
@section('content')

@php
    // Prepare records with sorted appointments for the Service Records modal from current appointment records
    $serviceRecordsData = $appointments->groupBy('pds_id')->map(function($group) {
        $pds = $group->first()->pds;
        if (!$pds) return null;

        $sortedAppointments = $group->sortByDesc(function($app) {
            return $app->period_to ?? $app->period_from;
        })->values();

        return [
            'id' => $pds->id,
            'name' => strtoupper($pds->last_name ?? '') . ', ' . strtoupper($pds->first_name ?? '') . ' ' . strtoupper($pds->middle_initial ?? '') . '.',
            'unique_id' => $pds->unique_id ?? 'N/A',
            'appointments' => $sortedAppointments->map(function($app) {
                $displayStatus = $app->status;
                if ($app->period_to && \Carbon\Carbon::parse($app->period_to)->isPast() && !in_array($app->status, ['Resigned', 'Terminated', 'Replaced', 'Rejected'])) {
                    $displayStatus = 'Expired';
                }
                return [
                    'contract_service_no' => $app->contract_service_no,
                    'designation' => strtoupper($app->designation),
                    'period_from' => $app->period_from ? \Carbon\Carbon::parse($app->period_from)->format('M d, Y') : 'N/A',
                    'period_to' => $app->period_to ? \Carbon\Carbon::parse($app->period_to)->format('M d, Y') : 'N/A',
                    'status' => $displayStatus,
                ];
            })
        ];
    })->filter()->values();
@endphp

<!-- Alpine Wrapper for Search, Table, and Modals -->
<div x-data="{ 
    openProcessModal: false, 
    openPreviewModal: false, 
    openServiceModal: false,
    currentAppId: null, 
    currentName: '', 
    previewUrl: '', 
    previewTitle: '',
    searchQuery: '',
    serviceSearchQuery: '',
    selectedRecord: null,
    allRecords: {{ json_encode($serviceRecordsData) }}
}">

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-blue-900">Appointment Lists</h2>
            <p class="text-xs text-gray-600">Registry of active and historical contract services and job order assignments.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('appointments.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-4 py-2 rounded shadow transition">
                + Create Appointment
            </a>
            <button type="button" @click="openServiceModal = true" class="bg-slate-700 hover:bg-slate-800 text-white text-xs font-semibold px-4 py-2 rounded shadow transition flex items-center gap-1">
                📋 Service Records
            </button>
        </div>
    </div>

    <!-- Search Bar Filter Above Table -->
    <div class="mb-4 flex items-center justify-between">
        <div class="relative w-full max-w-sm">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                🔍
            </span>
            <input 
                type="text" 
                x-model="searchQuery" 
                placeholder="Search by name, contract no, designation..." 
                class="w-full pl-9 pr-4 py-2 text-xs border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none bg-white shadow-sm"
            >
        </div>
        <div class="text-xs text-gray-500 italic">
            Showing appointments (Pending sorted on top)
        </div>
    </div>

    <div class="overflow-x-auto border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-700 tracking-wider">
                    <th class="py-3 px-4 font-bold">Contract No</th>
                    <th class="py-3 px-4 font-bold">Name</th>
                    <th class="py-3 px-4 font-bold">Designation</th>
                    <th class="py-3 px-4 font-bold">From - To Date</th>
                    <th class="py-3 px-4 font-bold">Duration</th>
                    <th class="py-3 px-4 font-bold">Status</th>
                    <th class="py-3 px-4 font-bold">Supporting File</th>
                    <th class="py-3 px-4 font-bold text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @php
                    // Sort appointments so 'Pending' appears first
                    $sortedAppointments = $appointments->sort(function ($a, $b) {
                        $statusA = $a->status;
                        if ($a->period_to && \Carbon\Carbon::parse($a->period_to)->isPast() && !in_array($a->status, ['Resigned', 'Terminated', 'Replaced', 'Rejected'])) {
                            $statusA = 'Expired';
                        }
                        
                        $statusB = $b->status;
                        if ($b->period_to && \Carbon\Carbon::parse($b->period_to)->isPast() && !in_array($b->status, ['Resigned', 'Terminated', 'Replaced', 'Rejected'])) {
                            $statusB = 'Expired';
                        }

                        if ($statusA === 'Pending' && $statusB !== 'Pending') return -1;
                        if ($statusA !== 'Pending' && $statusB === 'Pending') return 1;
                        return 0;
                    });
                @endphp

                @forelse($sortedAppointments as $app)
                @php
                    $displayStatus = $app->status;
                    if ($app->period_to && \Carbon\Carbon::parse($app->period_to)->isPast() && !in_array($app->status, ['Resigned', 'Terminated', 'Replaced', 'Rejected'])) {
                        $displayStatus = 'Expired';
                    }

                    $fullName = strtoupper($app->pds->last_name ?? '') . ', ' . strtoupper($app->pds->first_name ?? '');
                    $searchableText = strtolower($app->contract_service_no . ' ' . $fullName . ' ' . $app->designation . ' ' . $displayStatus);
                @endphp

                <tr class="hover:bg-gray-50 transition"
                    x-show="searchQuery === '' || '{{ $searchableText }}'.includes(searchQuery.toLowerCase())">
                    
                    <td class="py-3 px-4 font-mono text-xs text-blue-900 font-semibold">{{ $app->contract_service_no }}</td>
                    <td class="py-3 px-4 font-semibold text-gray-800 uppercase text-xs">
                        {{ $fullName }}
                    </td>
                    <td class="py-3 px-4 text-gray-600 uppercase text-xs">{{ strtoupper($app->designation) }}</td>
                    
                    <!-- From - To Date Column -->
                    <td class="py-3 px-4 text-xs text-gray-700 whitespace-nowrap">
                        @if($app->period_from && $app->period_to)
                            {{ \Carbon\Carbon::parse($app->period_from)->format('M d, Y') }} <br>
                            <span class="text-gray-400">to</span> {{ \Carbon\Carbon::parse($app->period_to)->format('M d, Y') }}
                        @else
                            <span class="text-gray-400 italic">Not set</span>
                        @endif
                    </td>

                    <!-- Months & Days Duration Column -->
                    <td class="py-3 px-4 text-xs text-gray-600 font-medium whitespace-nowrap">
                        @if($app->period_from && $app->period_to)
                            @php
                                $start = \Carbon\Carbon::parse($app->period_from);
                                $end = \Carbon\Carbon::parse($app->period_to)->addDay();
                                $diff = $start->diff($end);

                                $parts = [];
                                if ($diff->m > 0) {
                                    $parts[] = $diff->m . ' ' . Str::plural('Month', $diff->m);
                                }
                                if ($diff->d > 0 || empty($parts)) {
                                    $parts[] = $diff->d . ' ' . Str::plural('Day', $diff->d);
                                }
                                if ($diff->y > 0) {
                                    $totalMonths = ($diff->y * 12) + $diff->m;
                                    $parts = [];
                                    if ($totalMonths > 0) {
                                        $parts[] = $totalMonths . ' ' . Str::plural('Month', $totalMonths);
                                    }
                                    if ($diff->d > 0) {
                                        $parts[] = $diff->d . ' ' . Str::plural('Day', $diff->d);
                                    }
                                }
                            @endphp
                            {{ implode(', ', $parts) }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    <!-- Status Badge Column -->
                    <td class="py-3 px-4">
                        @php
                            $statusColors = [
                                'Pending'    => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'Approved'   => 'bg-green-100 text-green-800 border-green-200',
                                'Rejected'   => 'bg-red-100 text-red-800 border-red-200',
                                'Resigned'   => 'bg-gray-100 text-gray-800 border-gray-200',
                                'Terminated' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'Replaced'   => 'bg-purple-100 text-purple-800 border-purple-200',
                                'Expired'    => 'bg-rose-100 text-rose-800 border-rose-200',
                            ];
                            $badgeClass = $statusColors[$displayStatus] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $badgeClass }}">
                            {{ $displayStatus }}
                        </span>
                    </td>

                    <!-- Dedicated File Column -->
                    <td class="py-3 px-4 text-xs">
                        @if($app->document_path)
                            <button 
                                type="button"
                                @click="openPreviewModal = true; previewUrl = '{{ asset('storage/' . $app->document_path) }}'; previewTitle = '{{ basename($app->document_path) }}'"
                                class="text-blue-700 hover:underline font-medium flex items-center space-x-1 truncate max-w-xs">
                                <span>📄</span>
                                <span class="truncate">{{ basename($app->document_path) }}</span>
                            </button>
                        @else
                            <span class="text-gray-400 italic">No file uploaded</span>
                        @endif
                    </td>

                    <td class="py-3 px-4 text-center space-x-1 whitespace-nowrap">
                        <!-- Print Batch Contract Document Button -->
                        <a href="{{ route('appointments.contract', $app->contract_service_no) }}" target="_blank" 
                        class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-300 text-xs font-semibold px-2.5 py-1.5 rounded transition inline-flex items-center space-x-1">
                            <span>🖨️ Print Contract</span>
                        </a>

                        <!-- Process Button -->
                        <button 
                            type="button"
                            @click="openProcessModal = true; currentAppId = '{{ $app->id }}'; currentName = '{{ $fullName }}'"
                            class="bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-300 text-xs font-semibold px-3 py-1.5 rounded transition">
                            Process
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-6 px-4 text-center text-gray-500 italic">No appointment records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PROCESS MODAL OVERLAY -->
    <div x-show="openProcessModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-cloak>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative" @click.away="openProcessModal = false">
            <h3 class="text-lg font-bold text-blue-900 mb-1">Process Appointment</h3>
            <p class="text-xs text-gray-600 mb-4">Employee: <span class="font-semibold text-gray-800" x-text="currentName"></span></p>

            <form :action="'/appointments/' + currentAppId + '/process'" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Upload Supporting Document</label>
                    <input type="file" name="document_file" required class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 border border-gray-300 rounded">
                </div>

                <p class="text-xs font-bold uppercase text-gray-700 mb-2">Select Action Status:</p>
                
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <button type="submit" name="status" value="Approved" class="bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium py-2 px-3 rounded transition">
                        Approved
                    </button>
                    <button type="submit" name="status" value="Rejected" class="bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium py-2 px-3 rounded transition">
                        Rejected
                    </button>
                    <button type="submit" name="status" value="Resigned" class="bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium py-2 px-3 rounded transition">
                        Resigned
                    </button>
                    <button type="submit" name="status" value="Terminated" class="bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium py-2 px-3 rounded transition">
                        Terminated
                    </button>
                </div>
                <div class="mb-4">
                    <button type="submit" name="status" value="Replaced" class="w-full bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium py-2 px-3 rounded transition">
                        Replaced
                    </button>
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="openProcessModal = false" class="text-xs text-gray-500 hover:text-gray-700 font-medium px-3 py-1">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PREVIEW MODAL OVERLAY -->
    <div x-show="openPreviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4" style="display: none;" x-cloak>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl h-[85vh] flex flex-col relative overflow-hidden" @click.away="openPreviewModal = false">
            <div class="px-6 py-3 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="text-sm font-semibold truncate" x-text="'Document Preview: ' + previewTitle"></h3>
                <button type="button" @click="openPreviewModal = false" class="text-gray-300 hover:text-white font-bold text-lg px-2">×</button>
            </div>
            <div class="flex-grow bg-gray-100 p-2 flex items-center justify-center overflow-auto">
                <template x-if="previewUrl.endsWith('.pdf')">
                    <iframe :src="previewUrl" class="w-full h-full rounded border border-gray-300"></iframe>
                </template>
                <template x-if="!previewUrl.endsWith('.pdf')">
                    <img :src="previewUrl" alt="Document Preview" class="max-h-full max-w-full object-contain rounded">
                </template>
            </div>
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-between items-center text-xs">
                <a :href="previewUrl" target="_blank" class="text-blue-700 hover:underline font-semibold flex items-center space-x-1">
                    <span>Open in new tab ↗</span>
                </a>
                <button type="button" @click="openPreviewModal = false" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-1.5 rounded">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- SERVICE RECORDS MODAL OVERLAY -->
    <div x-show="openServiceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4" style="display: none;" x-cloak>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl h-[85vh] flex flex-col relative overflow-hidden" @click.away="openServiceModal = false">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="text-sm font-bold uppercase tracking-wider">Personnel Service Records Lookup</h3>
                <button type="button" @click="openServiceModal = false" class="text-gray-300 hover:text-white font-bold text-lg px-2">×</button>
            </div>

            <!-- Modal Content Body -->
            <div class="p-6 flex-grow flex flex-col overflow-hidden bg-gray-50">
                
                <!-- Search Input Field -->
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Search Personnel by Name</label>
                    <input type="text" x-model="serviceSearchQuery" placeholder="Type last name or first name..." class="w-full text-xs border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-grow overflow-hidden">
                    <!-- Left Column: Filtered Personnel List -->
                    <div class="bg-white border border-gray-200 rounded overflow-y-auto p-2 flex flex-col">
                        <p class="text-xs font-bold uppercase text-gray-500 px-2 py-1 mb-1 border-b">Personnel List</p>
                        <template x-for="rec in allRecords.filter(r => r.name.toLowerCase().includes(serviceSearchQuery.toLowerCase()))" :key="rec.id">
                            <button type="button" @click="selectedRecord = rec" 
                                    :class="selectedRecord && selectedRecord.id === rec.id ? 'bg-blue-900 text-white' : 'hover:bg-gray-100 text-gray-800'"
                                    class="text-left text-xs px-3 py-2 rounded transition font-medium truncate mb-1">
                                <span x-text="rec.name"></span>
                            </button>
                        </template>
                        <div x-show="allRecords.filter(r => r.name.toLowerCase().includes(serviceSearchQuery.toLowerCase())).length === 0" class="text-xs text-gray-400 italic p-3 text-center">
                            No records found.
                        </div>
                    </div>

                    <!-- Right Column: Appointment Records Display -->
                    <div class="md:col-span-2 bg-white border border-gray-200 rounded p-4 overflow-y-auto flex flex-col">
                        <template x-if="!selectedRecord">
                            <div class="flex-grow flex items-center justify-center text-center text-xs text-gray-400 italic p-6">
                                Please select a personnel from the left list to view their service records history.
                            </div>
                        </template>

                        <template x-if="selectedRecord">
                            <div>
                                <div class="border-b pb-3 mb-3">
                                    <h4 class="text-sm font-bold text-blue-900 uppercase" x-text="selectedRecord.name"></h4>
                                    <p class="text-xs text-gray-500">Unique ID: <span class="font-mono font-semibold" x-text="selectedRecord.unique_id"></span></p>
                                </div>

                                <p class="text-xs font-bold uppercase text-gray-700 mb-2">Appointment History (Latest to Oldest):</p>
                                
                                <div class="space-y-2">
                                    <template x-for="app in selectedRecord.appointments" :key="app.contract_service_no">
                                        <div class="border border-gray-200 rounded p-3 text-xs bg-gray-50 flex justify-between items-center">
                                            <div>
                                                <p class="font-bold text-blue-900 font-mono text-xs" x-text="'Contract No: ' + app.contract_service_no"></p>
                                                <p class="font-semibold text-gray-800 uppercase" x-text="app.designation"></p>
                                                <p class="text-gray-600 mt-1">Period: <span class="font-medium text-gray-800" x-text="app.period_from + ' to ' + app.period_to"></span></p>
                                            </div>
                                            <div>
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full border bg-white shadow-sm" x-text="app.status"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="selectedRecord.appointments.length === 0" class="text-xs text-gray-400 italic p-4 text-center bg-gray-50 rounded border border-dashed">
                                        No appointment records recorded for this personnel.
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-gray-100 border-t border-gray-200 flex justify-end">
                <button type="button" @click="openServiceModal = false" class="bg-slate-700 hover:bg-slate-800 text-white text-xs px-4 py-1.5 rounded font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@endsection