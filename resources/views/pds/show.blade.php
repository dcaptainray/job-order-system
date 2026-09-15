@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white border border-gray-300 shadow-sm p-8 text-gray-800 text-xs font-sans">
    
    <!-- Header Title Bar -->
    <div class="text-center border-b-2 border-blue-900 pb-3 mb-4">
        <p class="text-[10px] text-gray-500 uppercase tracking-widest">Civil Service Commission (CSC) — CS Form No. 212</p>
        <h2 class="text-xl font-black text-blue-900 tracking-tight">PERSONAL DATA SHEET</h2>
        <p class="text-[10px] text-red-600 font-semibold mt-0.5">WARNING: Any misrepresentation made in the Personal Data Sheet shall cause the filing of administrative/criminal case/s against the person concerned.</p>
    </div>

    <!-- Unique ID Tag & Action Bar with Alpine State -->
    <div class="flex justify-between items-center bg-gray-50 p-3 border border-gray-200 mb-6 no-print" x-data="{ openServiceRecords: false }">
        <div>
            <span class="text-gray-500 font-semibold">CS ID / Unique Code:</span> 
            <span class="font-mono font-bold text-blue-900 bg-blue-50 px-2 py-1 border border-blue-200 rounded">{{ $pds->unique_id }}</span>
        </div>
        <div class="space-x-2 flex items-center">
            <button @click="openServiceRecords = true" class="bg-slate-700 hover:bg-slate-800 text-white font-semibold px-4 py-2 rounded shadow transition">
                Service Records
            </button>
            <button onclick="window.print()" class="bg-blue-900 hover:bg-blue-800 text-white font-semibold px-4 py-2 rounded shadow transition">
                Print Full PDS
            </button>
            <a href="{{ route('masterlist') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded transition inline-block">
                Back to Masterlist
            </a>
        </div>

        <!-- SERVICE RECORDS MODAL OVERLAY -->
        <div x-show="openServiceRecords" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" style="display: none;" x-cloak>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden flex flex-col relative text-left" @click.away="openServiceRecords = false">
                <!-- Modal Header -->
                <div class="bg-slate-900 text-white px-6 py-3 flex justify-between items-center">
                    <h3 class="text-sm font-bold uppercase tracking-wider">Service Records History</h3>
                    <button type="button" @click="openServiceRecords = false" class="text-gray-300 hover:text-white font-bold text-lg">×</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <div class="mb-4 text-xs">
                        <span class="text-gray-500 font-semibold">Employee:</span> 
                        <span class="font-bold uppercase text-gray-900">{{ $pds->last_name }}, {{ $pds->first_name }}</span>
                        <span class="ml-4 text-gray-500 font-semibold">Unique ID:</span>
                        <span class="font-mono font-bold text-blue-900">{{ $pds->unique_id }}</span>
                    </div>

                    @php
                        $appointments = $pds->appointments ?? collect();
                        $latestAppointment = $appointments->sortByDesc('created_at')->first();
                    @endphp

                    @if($latestAppointment)
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded text-xs flex justify-between items-center">
                            <div>
                                <span class="font-bold text-blue-900 uppercase">Latest Status:</span> 
                                @php
                                    $statusColors = [
                                        'Pending'    => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'Approved'   => 'bg-green-100 text-green-800 border-green-200',
                                        'Rejected'   => 'bg-red-100 text-red-800 border-red-200',
                                        'Resigned'   => 'bg-gray-100 text-gray-800 border-gray-200',
                                        'Terminated' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        'Replaced'   => 'bg-purple-100 text-purple-800 border-purple-200',
                                    ];
                                    $latestBadgeClass = $statusColors[$latestAppointment->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                @endphp
                                <span class="px-2 py-0.5 rounded font-semibold border {{ $latestBadgeClass }} ml-1">{{ $latestAppointment->status }}</span>
                                <span class="text-gray-600 ml-3 font-mono">Contract No: {{ $latestAppointment->contract_service_no }}</span>
                            </div>
                            <div class="text-gray-500 text-[11px]">
                                Designation: <span class="font-semibold uppercase text-gray-700">{{ $latestAppointment->designation }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="overflow-x-auto border border-gray-200 rounded">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-gray-100 border-b border-gray-200 font-bold uppercase text-gray-700">
                                    <th class="py-2.5 px-3">Contract No</th>
                                    <th class="py-2.5 px-3">Designation</th>
                                    <th class="py-2.5 px-3">Status</th>
                                    <th class="py-2.5 px-3">Document</th>
                                    <th class="py-2.5 px-3 text-right">Date Recorded</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($appointments as $app)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 px-3 font-mono font-semibold text-blue-900">{{ $app->contract_service_no }}</td>
                                    <td class="py-2.5 px-3 uppercase text-gray-700">{{ $app->designation }}</td>
                                    <td class="py-2.5 px-3">
                                        @php
                                            $badgeClass = $statusColors[$app->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                        @endphp
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded border {{ $badgeClass }}">
                                            {{ $app->status }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        @if($app->document_path)
                                            <a href="{{ asset('storage/' . $app->document_path) }}" target="_blank" class="text-blue-700 hover:underline font-medium truncate max-w-[120px] inline-block align-bottom">
                                                {{ basename($app->document_path) }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">None</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-right text-gray-500 font-mono text-[11px]">
                                        {{ $app->created_at ? $app->created_at->format('m/d/Y') : '' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-500 italic">No appointment history found for this employee.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 flex justify-end">
                    <button type="button" @click="openServiceRecords = false" class="bg-slate-700 hover:bg-slate-800 text-white text-xs font-semibold px-4 py-1.5 rounded transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- I. PERSONAL INFORMATION SECTION -->
    <div class="mb-6">
        <div class="bg-blue-900 text-white font-bold px-3 py-1 text-xs uppercase tracking-wide mb-2">
            I. Personal Information
        </div>
        
        <table class="w-full border-collapse border border-gray-400 text-left">
            <tbody>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold w-1/4 border-r border-gray-400">SURNAME</td>
                    <td class="p-2 uppercase font-semibold text-gray-900" colspan="3">{{ $pds->last_name }}</td>
                </tr>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">FIRST NAME</td>
                    <td class="p-2 uppercase font-semibold text-gray-900 border-r border-gray-400">{{ $pds->first_name }}</td>
                    <td class="bg-gray-100 p-2 font-bold w-1/6 border-r border-gray-400">MIDDLE NAME</td>
                    <td class="p-2 uppercase font-semibold text-gray-900">{{ $pds->middle_name }}</td>
                </tr>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">NAME EXTENSION (JR., SR.)</td>
                    <td class="p-2 uppercase font-semibold text-gray-900 border-r border-gray-400">{{ $pds->name_extension }}</td>
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">DATE OF BIRTH</td>
                    <td class="p-2 uppercase font-semibold text-gray-900">{{ $pds->date_of_birth ? date('m/d/Y', strtotime($pds->date_of_birth)) : '' }}</td>
                </tr>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">PLACE OF BIRTH</td>
                    <td class="p-2 uppercase font-semibold text-gray-900 border-r border-gray-400">{{ $pds->place_of_birth }}</td>
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">GENDER</td>
                    <td class="p-2 uppercase font-semibold text-gray-900">{{ $pds->gender }}</td>
                </tr>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">CIVIL STATUS</td>
                    <td class="p-2 uppercase font-semibold text-gray-900 border-r border-gray-400">{{ $pds->civil_status }}</td>
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">CITIZENSHIP</td>
                    <td class="p-2 uppercase font-semibold text-gray-900">{{ $pds->citizenship }}</td>
                </tr>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">HEIGHT (m) / WEIGHT (kg)</td>
                    <td class="p-2 uppercase font-semibold text-gray-900 border-r border-gray-400">{{ $pds->height }} / {{ $pds->weight }}</td>
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">BLOOD TYPE</td>
                    <td class="p-2 uppercase font-semibold text-gray-900">{{ $pds->blood_type }}</td>
                </tr>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">GOVERNMENT ID NUMBERS</td>
                    <td class="p-2 uppercase font-semibold text-gray-900" colspan="3">
                        GSIS: {{ $pds->gsis }} | PAG-IBIG: {{ $pds->pagibig }} | PHILHEALTH: {{ $pds->philhealth }} | SSS: {{ $pds->sss }} | TIN: {{ $pds->tin }}
                    </td>
                </tr>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">RESIDENTIAL ADDRESS</td>
                    <td class="p-2 uppercase font-semibold text-gray-900" colspan="3">{{ $pds->residential_address }}</td>
                </tr>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">PERMANENT ADDRESS</td>
                    <td class="p-2 uppercase font-semibold text-gray-900" colspan="3">{{ $pds->permanent_address }}</td>
                </tr>
                <tr>
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">CONTACT DETAILS</td>
                    <td class="p-2 uppercase font-semibold text-gray-900" colspan="3">
                        Telephone: {{ $pds->telephone }} | Mobile: {{ $pds->mobile_no }} | Email: {{ $pds->email_address }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- II. FAMILY BACKGROUND SECTION -->
    <div class="mb-6">
        <div class="bg-blue-900 text-white font-bold px-3 py-1 text-xs uppercase tracking-wide mb-2">
            II. Family Background
        </div>
        <table class="w-full border-collapse border border-gray-400 text-left">
            <tbody>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold w-1/4 border-r border-gray-400">SPOUSE'S NAME</td>
                    <td class="p-2 uppercase font-semibold text-gray-900" colspan="3">
                        {{ $pds->spouse_last_name }}, {{ $pds->spouse_first_name }} {{ $pds->spouse_middle_name }}
                    </td>
                </tr>
                <tr class="border-b border-gray-400">
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">FATHER'S NAME</td>
                    <td class="p-2 uppercase font-semibold text-gray-900" colspan="3">
                        {{ $pds->father_last_name }}, {{ $pds->father_first_name }} {{ $pds->father_middle_name }}
                    </td>
                </tr>
                <tr>
                    <td class="bg-gray-100 p-2 font-bold border-r border-gray-400">MOTHER'S MAIDEN NAME</td>
                    <td class="p-2 uppercase font-semibold text-gray-900" colspan="3">
                        {{ $pds->mother_maiden_last_name }}, {{ $pds->mother_maiden_first_name }} {{ $pds->mother_maiden_middle_name }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- III. EDUCATIONAL BACKGROUND -->
    <div class="mb-6">
        <div class="bg-blue-900 text-white font-bold px-3 py-1 text-xs uppercase tracking-wide mb-2">
            III. Educational Background
        </div>
        <table class="w-full border-collapse border border-gray-400 text-center">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-400 font-bold">
                    <td class="p-2 border-r border-gray-400 w-1/3">Name of School</td>
                    <td class="p-2 border-r border-gray-400">Basic Education / Degree / Course</td>
                    <td class="p-2 border-r border-gray-400 w-1/5">Period of Attendance</td>
                    <td class="p-2 w-1/6">Year Graduated</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2 border-r border-gray-400 uppercase font-semibold">{{ $pds->school_name }}</td>
                    <td class="p-2 border-r border-gray-400 uppercase font-semibold">{{ $pds->degree_course }}</td>
                    <td class="p-2 border-r border-gray-400 uppercase font-semibold">{{ $pds->school_period }}</td>
                    <td class="p-2 uppercase font-semibold">{{ $pds->year_graduated }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- IV. CIVIL SERVICE ELIGIBILITY -->
    <div class="mb-6">
        <div class="bg-blue-900 text-white font-bold px-3 py-1 text-xs uppercase tracking-wide mb-2">
            IV. Civil Service Eligibility
        </div>
        <table class="w-full border-collapse border border-gray-400 text-center">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-400 font-bold">
                    <td class="p-2 border-r border-gray-400">Career Service / RA 1080 / Board / Special Laws</td>
                    <td class="p-2 w-1/4">Rating</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-3 border-r border-gray-400 uppercase font-semibold">{{ $pds->eligibility }}</td>
                    <td class="p-3 uppercase font-semibold">{{ $pds->eligibility_rating }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- V. WORK EXPERIENCE -->
    <div class="mb-6">
        <div class="bg-blue-900 text-white font-bold px-3 py-1 text-xs uppercase tracking-wide mb-2">
            V. Work Experience Summary
        </div>
        <div class="border border-gray-400 p-3 uppercase font-semibold text-gray-900 bg-gray-50">
            {{ $pds->work_experience_summary ?: 'No work experience summary provided.' }}
        </div>
    </div>

    <!-- Certification Block -->
    <div class="mt-8 border-t border-gray-400 pt-4 text-[11px] leading-relaxed">
        <p class="text-justify text-gray-700 mb-6">
            I declare under oath that I have personally accomplished this Personal Data Sheet which is a true, correct and complete statement pursuant to the provisions of pertinent laws, rules and regulations of the Republic of the Philippines. I authorize the agency head/authorized representative to verify/validate the contents stated herein.
        </p>
        
        <div class="flex justify-between items-end mt-12">
            <div class="w-1/3 text-center">
                <div class="border-b border-gray-400 h-16 mb-1"></div>
                <p class="font-bold">DATE ACCOMPLISHED</p>
            </div>
            <div class="w-1/3 text-center">
                <div class="border-b border-gray-400 h-16 mb-1 flex items-end justify-center pb-1">
                    <span class="font-bold uppercase text-gray-800">{{ $pds->first_name }} {{ $pds->last_name }}</span>
                </div>
                <p class="font-bold">SIGNATURE (Sign inside the box)</p>
            </div>
        </div>
    </div>

</div>

<div class="text-center mt-6 no-print">
    <button onclick="window.print()" class="bg-blue-900 hover:bg-blue-800 text-white font-semibold px-6 py-2.5 rounded shadow transition">
        Print Full PDS Form
    </button>
    <a href="{{ route('masterlist') }}" class="ml-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2.5 rounded transition inline-block">
        Back to Masterlist
    </a>
</div>

<style>
    [x-cloak] { display: none !important; }
    @media print {
        body * { visibility: hidden; }
        .max-w-4xl, .max-w-4xl * { visibility: visible; }
        .max-w-4xl { position: absolute; left: 0; top: 0; width: 100%; border: none; padding: 10px; margin: 0; }
        .no-print { display: none !important; }
    }
</style>
@endsection