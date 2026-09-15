@extends('layouts.app')
@section('content')

<!-- Print Isolation and Landscape Orientation Styles -->
<style>
    @media print {
        /* Hide everything else on the layout template (navbars, sidebars, body wrappers) */
        body * {
            visibility: hidden;
        }

        /* Make only the contract print container and its child elements visible */
        #printable-contract, #printable-contract * {
            visibility: visible;
        }

        /* Position the contract container at the top-left of the printed sheet */
        #printable-contract {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 10px;
            border: none !important;
            box-shadow: none !important;
        }

        /* Force Landscape and minimal margins to fit everything in 1 page */
        @page {
            size: landscape;
            margin: 8mm;
        }
    }
</style>

<!-- Contract Container with Unique ID for Print Targeting -->
<div id="printable-contract" class="max-w-7xl mx-auto bg-white border border-gray-200 shadow-sm p-8 text-black font-sans">
    
    <!-- Header Controls (Hidden when printing via Tailwind print:hidden class) -->
    <div class="flex justify-between items-center mb-6 print:hidden">
        <a href="{{ route('appointments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold px-4 py-2 rounded transition">
            ← Back to Appointments
        </a>
        <button onclick="window.print()" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-6 py-2 rounded shadow transition">
            Print Appointment Document
        </button>
    </div>

    <!-- Official Document Header -->
    <div class="text-center mb-4">
        <div class="flex justify-center mb-1">
            <!-- Option A: If your image is in the public folder (e.g., public/seal.jpeg) -->
            <img src="{{ asset('seal.jpg') }}" alt="Official Seal" class="w-16 h-16 rounded-full object-cover border border-gray-400">
            
            <!-- OR Option B: If your image is inside a subfolder like public/images/seal.jpeg, use this instead: -->
            <!-- <img src="{{ asset('images/seal.jpeg') }}" alt="Official Seal" class="w-16 h-16 rounded-full object-cover border border-gray-400"> -->
        </div>
        <h3 class="text-xs font-bold tracking-wider text-gray-900 uppercase">Republic of the Philippines</h3>
        <h2 class="text-sm font-extrabold text-gray-900 uppercase tracking-wide">Office of the Mayor</h2>
        <p class="text-xs font-semibold text-gray-800 uppercase">Lapu-Lapu City</p>
    </div>

    <!-- Contract Meta Info Bar -->
    <div class="flex justify-between items-center mb-4 text-xs font-bold border-b border-black pb-2">
        <div>
            <span>CONTRACT SERVICE NO :</span>
            <span class="ml-2 font-normal">{{ $contractServiceNo }}</span>
        </div>
        <div>
            <span>SOURCE OF FUNDS :</span>
            <span class="ml-2 font-normal">{{ $appointments->first()->source_of_funds ?? 'N/A' }}</span>
        </div>
    </div>

    <!-- Personnel Table -->
    <div class="overflow-x-auto mb-4">
        <table class="w-full text-left border-collapse border border-black text-xs">
            <thead>
                <tr class="text-center uppercase font-bold bg-gray-50">
                    <th class="border border-black p-2 w-10" rowspan="2">#</th>
                    <th class="border border-black p-2" rowspan="2">Name</th>
                    <th class="border border-black p-2" rowspan="2">Designation</th>
                    <th class="border border-black p-2" rowspan="2">Rate/Day</th>
                    <th class="border border-black p-1" colspan="2">Period of Employment</th>
                    <th class="border border-black p-2" rowspan="2">Office Assignment</th>
                    <th class="border border-black p-2" rowspan="2">If Renewal<br><span class="text-[9px] font-normal">(Indicate Previous Employment)</span></th>
                </tr>
                <tr class="text-center uppercase font-bold bg-gray-50 text-[11px]">
                    <th class="border border-black p-1">From</th>
                    <th class="border border-black p-1">To</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $index => $app)
                <tr class="align-top">
                    <td class="border border-black p-2 text-center">{{ $index + 1 }}</td>
                    <td class="border border-black p-2 font-bold uppercase">
                        @if($app->pds)
                            {{ $app->pds->last_name }}, {{ $app->pds->first_name }} {{ $app->pds->middle_initial }}.
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="border border-black p-2 uppercase">{{ $app->designation }}</td>
                    <td class="border border-black p-2 whitespace-nowrap">₱{{ number_format($app->rate_per_day, 2) }}</td>
                    <td class="border border-black p-2 text-center whitespace-nowrap">{{ $app->period_from }}</td>
                    <td class="border border-black p-2 text-center whitespace-nowrap">{{ $app->period_to }}</td>
                    <td class="border border-black p-2 uppercase">{{ $app->office_assignment }}</td>
                    <td class="border border-black p-2 text-center uppercase">{{ $app->renewal_status }}</td>
                </tr>
                @endforeach
                
                <tr>
                    <td class="border border-black p-2 text-center italic text-gray-500" colspan="8">**Nothing Follows**</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Legal Terms & Conditions -->
    <div class="text-[9.5px] text-justify leading-tight text-gray-900 mb-6 px-1">
        The services of the contract workers shall automatically cease upon its expiration as stipulated above, unless renewed, However, services of any or all of the above-named can be terminated prior to the expiration of this contract for lack of funds or when their services are no longer needed. The above-named hereby attest that he/she in not related within the third degree (fourth degree in case of LGUs) of consanguinity or affinity to the: 1) hiring authority and/or 2) representative of the hiring agency, that he/she has not been previously dismissed from government service by reason of an administrative offense: Furthermore, the service rendered hereunder is not considered or will never be credited as government service. All other neccesary provisions required or provided under laws, rule and issuances are likewise deemed stipulated herein. The said contract worker shall also be entitled to Conditional Compensation Premium (CCP) as provided under Sangguniang Panglungsod Resolution No. 13-1980-2015, subject to the condition therein. Said Resolution is applicable to contracts existing or those that are not yet consummated/extinguished at the time of the passage and approval of the said Resolution, and subsequent ones.
    </div>

    <!-- Signatures Section -->
    <div class="text-[11px] space-y-4 pt-2">
        <div class="flex justify-between items-end">
            <div>
                <p class="text-gray-700">Certified as to existence of Appropriation/Obligation:</p>
            </div>
            <div class="text-center font-bold">
                <p class="uppercase">Recommending Approval</p>
            </div>
            <div class="text-center font-bold">
                <p class="uppercase">Approved By:</p>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-4 pt-4 text-center">
            <div>
                <div class="border-b border-black mb-1 font-bold uppercase text-[10px]">ATTY. MARIO DENNIS A. CALVO</div>
                <p class="text-[9px] text-gray-700 uppercase">Highest Ranking HRMO</p>
            </div>
            <div>
                <div class="border-b border-black mb-1 font-bold uppercase text-[10px]">JUDITH D. FURUTA</div>
                <p class="text-[9px] text-gray-700 uppercase">City Budget Officer</p>
            </div>
            <div>
                <div class="border-b border-black mb-1 font-bold uppercase text-[10px]">HELEN G. DUNGOG</div>
                <p class="text-[9px] text-gray-700 uppercase">City Accountant</p>
            </div>
            <div>
                <div class="border-b border-black mb-1 font-bold uppercase text-[10px]">ATTY. DANILO E. ALMENDRAS</div>
                <p class="text-[9px] text-gray-700 uppercase">City Administrator</p>
            </div>
            <div>
                <div class="border-b border-black mb-1 font-bold uppercase text-[10px]">MA. CYNTHIA K. CHAN</div>
                <p class="text-[9px] text-gray-700 uppercase">City Mayor</p>
            </div>
        </div>
    </div>

</div>
@endsection