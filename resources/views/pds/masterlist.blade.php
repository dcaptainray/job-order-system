@extends('layouts.app')
@section('content')

<!-- Print Isolation Styles for PDF/Print Export -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-masterlist, #printable-masterlist * {
            visibility: visible;
        }
        #printable-masterlist {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

@php
    // Prepare records with sorted appointments for the Service Records modal
    $serviceRecordsData = $list->map(function($pds) {
        $sortedAppointments = method_exists($pds, 'appointments') && $pds->appointments 
            ? $pds->appointments->sortByDesc(function($app) {
                return $app->period_to ?? $app->period_from;
              })->values()
            : collect();

        return [
            'id' => $pds->id,
            'name' => strtoupper($pds->last_name) . ', ' . strtoupper($pds->first_name) . ' ' . strtoupper($pds->middle_initial) . '.',
            'unique_id' => $pds->unique_id,
            'appointments' => $sortedAppointments->map(function($app) {
                return [
                    'contract_service_no' => $app->contract_service_no,
                    'designation' => strtoupper($app->designation),
                    'period_from' => $app->period_from ? \Carbon\Carbon::parse($app->period_from)->format('M d, Y') : 'N/A',
                    'period_to' => $app->period_to ? \Carbon\Carbon::parse($app->period_to)->format('M d, Y') : 'N/A',
                    'status' => $app->status,
                ];
            })
        ];
    });
@endphp

<div class="max-w-8xl mx-auto" x-data="{ 
    openServiceModal: false, 
    searchQuery: '', 
    selectedRecord: null,
    allRecords: {{ json_encode($serviceRecordsData) }} 
}">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-xl font-bold text-blue-900">PDS Masterlist</h2>
            <p class="text-xs text-gray-600">Official registry of Personal Data Sheets for Job Order personnel.</p>
        </div>
        <div class="no-print flex items-center gap-2">
            <a href="{{ route('pds.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-4 py-2 rounded shadow transition">
                + New PDS Entry
            </a>
            <button @click="openServiceModal = true" class="bg-slate-700 hover:bg-slate-800 text-white text-xs font-semibold px-4 py-2 rounded shadow transition flex items-center gap-1">
                📋 Service Records
            </button>
        </div>
    </div>

    <!-- Simple Gender Count Display Above Table -->
    <div class="mb-3 text-xs text-gray-700 font-medium">
        Total Records: <span class="font-bold text-gray-900">{{ $list->count() }}</span> | 
        Male: <span class="font-bold text-blue-800">{{ $list->filter(fn($item) => strcasecmp(trim($item->gender), 'Male') === 0)->count() }}</span> | 
        Female: <span class="font-bold text-pink-700">{{ $list->filter(fn($item) => strcasecmp(trim($item->gender), 'Female') === 0)->count() }}</span>
    </div>

    <!-- Masterlist Table Container -->
    <div id="printable-masterlist" class="bg-white overflow-x-auto border border-gray-200 rounded mb-6">
        <div class="p-4 hidden print:block text-center">
            <h2 class="text-sm font-bold uppercase">PDS Masterlist Registry</h2>
            <p class="text-xs text-gray-600">Generated on: {{ date('M d, Y') }}</p>
        </div>
        
        <table id="pdsTable" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-700 tracking-wider">
                    <th class="py-3 px-4 font-bold border-b">Unique ID Code</th>
                    <th class="py-3 px-4 font-bold border-b">Name (Last, First, Middle Initial)</th>
                    <th class="py-3 px-4 font-bold border-b">BirthDate</th>
                    <th class="py-3 px-4 font-bold border-b">Gender</th>
                    <th class="py-3 px-4 font-bold border-b">Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @forelse($list as $pds)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-mono text-xs text-blue-900 font-semibold">{{ $pds->unique_id }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('pds.show', $pds->id) }}" class="text-blue-700 hover:text-blue-900 font-semibold underline no-print">
                            {{ strtoupper($pds->last_name) }}, {{ strtoupper($pds->first_name) }} {{ strtoupper($pds->middle_name) }}.
                        </a>
                        <span class="hidden print:inline uppercase font-semibold">
                            {{ strtoupper($pds->last_name) }}, {{ strtoupper($pds->first_name) }} {{ strtoupper($pds->middle_name) }}.
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $pds->date_of_birth }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $pds->gender }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $pds->permanent_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 px-4 text-center text-gray-500 italic">No Personal Data Sheet records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Export Buttons at the Bottom Side -->
    <div class="flex items-center gap-2 no-print">
        <button onclick="exportTableToExcel('pdsTable', 'pds-masterlist')" class="bg-green-700 hover:bg-green-800 text-white text-xs font-semibold px-4 py-2 rounded shadow transition flex items-center gap-1">
            📊 Export Excel
        </button>
        <button onclick="window.print()" class="bg-red-700 hover:bg-red-800 text-white text-xs font-semibold px-4 py-2 rounded shadow transition flex items-center gap-1">
            📄 Export PDF
        </button>
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
                    <input type="text" x-model="searchQuery" placeholder="Type last name or first name..." class="w-full text-xs border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-grow overflow-hidden">
                    <!-- Left Column: Filtered Personnel List -->
                    <div class="bg-white border border-gray-200 rounded overflow-y-auto p-2 flex flex-col">
                        <p class="text-xs font-bold uppercase text-gray-500 px-2 py-1 mb-1 border-b">Personnel List</p>
                        <template x-for="rec in allRecords.filter(r => r.name.toLowerCase().includes(searchQuery.toLowerCase()))" :key="rec.id">
                            <button @click="selectedRecord = rec" 
                                    :class="selectedRecord && selectedRecord.id === rec.id ? 'bg-blue-900 text-white' : 'hover:bg-gray-100 text-gray-800'"
                                    class="text-left text-xs px-3 py-2 rounded transition font-medium truncate mb-1">
                                <span x-text="rec.name"></span>
                            </button>
                        </template>
                        <div x-show="allRecords.filter(r => r.name.toLowerCase().includes(searchQuery.toLowerCase())).length === 0" class="text-xs text-gray-400 italic p-3 text-center">
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

<!-- Excel Export Script Utility -->
<script>
    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/<a\b[^>]*>(.*?)<\/a>/gi, "$1");

        filename = filename ? filename + '.xls' : 'excel_data.xls';
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + encodeURIComponent(tableHTML);
            downloadLink.download = filename;
            downloadLink.click();
        }
    }
</script>
@endsection