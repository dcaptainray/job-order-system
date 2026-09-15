@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white border border-gray-300 shadow-sm p-8 text-gray-900 font-sans text-xs">
    
    <!-- Header Masthead -->
    <div class="text-center mb-6">
        <p class="text-[11px] tracking-wider text-gray-600 font-medium">REPUBLIC OF THE PHILIPPINES</p>
        <h2 class="text-base font-bold text-blue-900 tracking-tight">OFFICE OF THE MAYOR</h2>
        <p class="text-xs font-semibold text-gray-700">LAPU-LAPU CITY</p>
    </div>

    <!-- Contract Meta Info Bar -->
    <div class="flex justify-between items-center font-bold mb-4 border-b border-gray-300 pb-2 text-xs">
        <div>CONTRACT SERVICE NO : &nbsp; <span class="font-normal text-blue-900">{{ $appointment->contract_service_no }}</span></div>
        <div>SOURCE OF FUNDS: &nbsp; <span class="font-normal text-blue-900">{{ $appointment->source_of_funds }}</span></div>
    </div>

    <!-- Official Document Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-black text-center text-[11px]">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-black p-1.5 w-[5%]"></th>
                    <th class="border border-black p-1.5 w-[25%] font-bold">NAME</th>
                    <th class="border border-black p-1.5 w-[20%] font-bold">DESIGNATION</th>
                    <th class="border border-black p-1.5 w-[10%] font-bold">RATE/DAY</th>
                    <th colspan="2" class="border border-black p-1.5 w-[15%] font-bold">PERIOD OF EMPLOYMENT</th>
                    <th class="border border-black p-1.5 w-[15%] font-bold">OFFICE ASSIGNMENT</th>
                    <th class="border border-black p-1.5 w-[10%] font-bold">IF RENEWAL<br><span class="text-[9px] font-normal">(INDICATE PREVIOUS EMPLOYMENT)</span></th>
                </tr>
                <tr class="bg-gray-50">
                    <th class="border border-black p-1"></th>
                    <th class="border border-black p-1"></th>
                    <th class="border border-black p-1"></th>
                    <th class="border border-black p-1"></th>
                    <th class="border border-black p-1 font-bold">FROM</th>
                    <th class="border border-black p-1 font-bold">TO</th>
                    <th class="border border-black p-1"></th>
                    <th class="border border-black p-1"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-black p-2 font-medium">1</td>
                    <td class="border border-black p-2 text-left font-bold uppercase">
                        {{ strtoupper($appointment->pds->last_name) }}, {{ strtoupper($appointment->pds->first_name) }} {{ strtoupper($appointment->pds->middle_initial) }}.
                    </td>
                    <td class="border border-black p-2 uppercase">{{ strtoupper($appointment->designation) }}</td>
                    <td class="border border-black p-2">{{ number_format($appointment->rate_per_day, 2) }}/DAY</td>
                    <td class="border border-black p-2">{{ date('m/d/Y', strtotime($appointment->period_from)) }}</td>
                    <td class="border border-black p-2">{{ date('m/d/Y', strtotime($appointment->period_to)) }}</td>
                    <td class="border border-black p-2 uppercase">{{ strtoupper($appointment->office_assignment) }}</td>
                    <td class="border border-black p-2 uppercase">{{ strtoupper($appointment->renewal_status) }}</td>
                </tr>
                <tr style="height: 140px;">
                    <td class="border border-black p-2"></td>
                    <td colspan="7" class="border border-black p-2 text-left align-top font-bold text-gray-800">**Nothing Follows**</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Legal Terms and Disclaimers -->
    <div class="text-[9px] text-justify mt-4 leading-tight text-gray-800">
        The services of the contract workers shall automatically cease upon its expiration as stipulated above, unless renewed. However, services of any or all of the above-named can be terminated prior to the expiration of this contract for lack of funds or when their services are no longer needed. The above-named hereby attest that he/she is not related within the third degree (fourth degree in case of LGUs) of consanguinity or affinity to the: 1) hiring authority and/or 2) representative of the hiring agency; that he/she has not been previously dismissed from government service by reason of an administrative offense: Furthermore, the service rendered hereunder is not considered or will never be credited as government service. All other necessary provisions required or provided under laws, rule and issuances are likewise deemed stipulated herein. The said contract worker shall also be entitled to Conditional Compensation Premium (CCP) as provided under Sangguniang Panglungsod Resolution No. 13-1980-2015, subject to the condition therein. Said Resolution is applicable to contracts existing or those that are not yet consummated/extinguished at the time of the passage and approval of the said Resolution, and subsequent ones.
    </div>

    <!-- Certification Line -->
    <div class="mt-4 text-[10px] font-bold text-center">
        Certified as to existence of Appropriation/Obligation:
    </div>

    <!-- First Signatures Row -->
    <div class="grid grid-cols-3 gap-4 mt-8 text-center text-[10px]">
        <div>
            <div class="font-bold border-b border-black pb-0.5">ATTY. MARIO DENNIS A. CALVO</div>
            <div class="text-[9px] text-gray-600 mt-0.5">Highest Ranking HRMO</div>
        </div>
        <div>
            <div class="font-bold border-b border-black pb-0.5">JUDITH D. FURUTA</div>
            <div class="text-[9px] text-gray-600 mt-0.5">City Budget Officer</div>
        </div>
        <div>
            <div class="font-bold border-b border-black pb-0.5">HELEN G. DUNGOG</div>
            <div class="text-[9px] text-gray-600 mt-0.5">City Accountant</div>
        </div>
    </div>

    <!-- Second Signatures Row -->
    <div class="grid grid-cols-2 gap-12 mt-8 text-center text-[10px]">
        <div>
            <div class="font-bold mb-6 text-left">Recommending Approval</div>
            <div class="font-bold border-t border-black pt-1">ATTY. DANILO E. ALMENDRAS</div>
            <div class="text-[9px] text-gray-600 mt-0.5">City Administrator</div>
        </div>
        <div>
            <div class="font-bold mb-6 text-left">Approved By:</div>
            <div class="font-bold border-t border-black pt-1">MA. CYNTHIA K. CHAN</div>
            <div class="text-[9px] text-gray-600 mt-0.5">City Mayor</div>
        </div>
    </div>

</div>

<!-- Portal Action Buttons (Hidden on Print) -->
<div class="no-print text-center mt-6 space-x-3">
    <button onclick="window.print()" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-6 py-2.5 rounded shadow transition cursor-pointer">
        Print Appointment Document
    </button>
    <a href="{{ route('appointments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold px-6 py-2.5 rounded transition inline-block">
        Back to Appointment Lists
    </a>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .max-w-4xl, .max-w-4xl * { visibility: visible; }
        .max-w-4xl { position: absolute; left: 0; top: 0; width: 100%; border: none; padding: 0; margin: 0; box-shadow: none; }
        .no-print { display: none !important; }
    }
</style>
@endsection