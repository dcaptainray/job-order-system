@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white border border-gray-200 shadow-sm p-8" x-data="{ showConfirmModal: false }">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-xl font-bold text-blue-900 tracking-tight">Create Appointment / Contract Service</h2>
        <p class="text-xs text-gray-600 mt-0.5">Input official contract details matching the LGU plantilla format for multiple personnel.</p>
    </div>

    <form id="appointment-form" method="POST" action="{{ route('appointments.store') }}" class="space-y-6">
        @csrf
        
        <!-- Shared Contract Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded border border-gray-200">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Contract Service No</label>
                <input type="text" name="contract_service_no" placeholder="e.g., HR-2026-1" required
                    class="w-full text-sm border border-gray-300 rounded p-2 bg-white focus:ring-1 focus:ring-blue-900 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Source of Funds</label>
                <input type="text" name="source_of_funds" placeholder="e.g., 8731-5-02-12-010" required
                    class="w-full text-sm border border-gray-300 rounded p-2 bg-white focus:ring-1 focus:ring-blue-900 focus:outline-none">
            </div>
        </div>

        <!-- Employees Container -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wide">Assigned Personnel</h3>
                <button type="button" id="add-employee-btn" class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold px-3 py-1.5 rounded shadow transition flex items-center space-x-1">
                    <span>+ Add Another Employee</span>
                </button>
            </div>

            <div id="employee-rows-container" class="space-y-4">
                <!-- Employee Row Template / Initial Row -->
                <div class="employee-row bg-white border border-gray-300 rounded p-4 relative shadow-sm">
                    <button type="button" class="remove-row-btn absolute top-3 right-3 text-red-500 hover:text-red-700 text-xs font-bold hidden">
                        ✕ Remove
                    </button>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Employee (PDS Record)</label>
                            <select name="employees[0][pds_id]" required
                                class="w-full text-sm border border-gray-300 rounded p-2 bg-white focus:ring-1 focus:ring-blue-900 focus:outline-none">
                                <option value="">-- Select Employee from Masterlist --</option>
                                @foreach($pdsList as $pds)
                                    <option value="{{ $pds->id }}" {{ old('employees.0.pds_id', request('pds_id')) == $pds->id ? 'selected' : '' }}>
                                        {{ strtoupper($pds->last_name) }}, {{ strtoupper($pds->first_name) }} {{ strtoupper($pds->middle_initial) }}. ({{ $pds->unique_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Designation</label>
                                <input type="text" name="employees[0][designation]" placeholder="e.g., ADMINISTRATIVE AIDE" required
                                    value="{{ old('employees.0.designation', request('designation')) }}"
                                    class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none uppercase">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Rate / Day</label>
                                <input type="number" step="0.01" name="employees[0][rate_per_day]" placeholder="550.00" required
                                    value="{{ old('employees.0.rate_per_day', request('rate_per_day')) }}"
                                    class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Period From</label>
                                <input type="date" name="employees[0][period_from]" required
                                    value="{{ old('employees.0.period_from', request('period_from')) }}"
                                    class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Period To</label>
                                <input type="date" name="employees[0][period_to]" required
                                    value="{{ old('employees.0.period_to', request('period_to')) }}"
                                    class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Office Assignment</label>
                                <input type="text" name="employees[0][office_assignment]" placeholder="e.g., BRGY. PANGAN-AN" required
                                    value="{{ old('employees.0.office_assignment', request('office_assignment')) }}"
                                    class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none uppercase">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Renewal Status</label>
                                <input type="text" name="employees[0][renewal_status]" value="{{ old('employees.0.renewal_status', request('renewal_status', 'NEW')) }}" required
                                    class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none uppercase">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 mt-6">
            <a href="{{ route('appointments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold px-4 py-2.5 rounded transition">
                Cancel
            </a>
            <!-- Trigger confirmation modal instead of direct submit -->
            <button type="button" @click="if(document.getElementById('appointment-form').reportValidity()) { showConfirmModal = true; }" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-6 py-2.5 rounded shadow transition">
                Save and Generate Appointment Documents
            </button>
        </div>

        <!-- Confirmation Modal Overlay -->
        <div x-show="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" style="display: none;" x-cloak>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative" @click.away="showConfirmModal = false">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-amber-100 text-amber-700 p-2 rounded-full font-bold">
                        ⚠️
                    </div>
                    <h3 class="text-base font-bold text-blue-900">Confirm Data Accuracy</h3>
                </div>
                
                <p class="text-xs text-gray-600 mb-6 leading-relaxed">
                    Have you already checked or double-checked the data you inputted? Please make sure all details match the requirements before proceeding to generate documents.
                </p>

                <div class="flex items-center justify-end space-x-2">
                    <button type="button" @click="showConfirmModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold px-4 py-2 rounded transition">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-4 py-2 rounded shadow transition">
                        Proceed & Save
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript to handle dynamic row additions -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('employee-rows-container');
        const addButton = document.getElementById('add-employee-btn');

        // Capture options HTML from the first select element so we can reuse it dynamically
        const pdsOptions = container.querySelector('select[name="employees[0][pds_id]"]').innerHTML;

        let rowIndex = 0;

        function updateRemoveButtons() {
            const rows = container.querySelectorAll('.employee-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('.remove-row-btn');
                if (rows.length > 1) {
                    removeBtn.classList.remove('hidden');
                } else {
                    removeBtn.classList.add('hidden');
                }
            });
        }

        addButton.addEventListener('click', function () {
            rowIndex++;
            const newRow = document.createElement('div');
            newRow.className = 'employee-row bg-white border border-gray-300 rounded p-4 relative shadow-sm';
            newRow.innerHTML = `
                <button type="button" class="remove-row-btn absolute top-3 right-3 text-red-500 hover:text-red-700 text-xs font-bold">
                    ✕ Remove
                </button>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Employee (PDS Record)</label>
                        <select name="employees[${rowIndex}][pds_id]" required
                            class="w-full text-sm border border-gray-300 rounded p-2 bg-white focus:ring-1 focus:ring-blue-900 focus:outline-none">
                            ${pdsOptions}
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Designation</label>
                            <input type="text" name="employees[${rowIndex}][designation]" placeholder="e.g., ADMINISTRATIVE AIDE" required
                                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none uppercase">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Rate / Day</label>
                            <input type="number" step="0.01" name="employees[${rowIndex}][rate_per_day]" placeholder="550.00" required
                                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Period From</label>
                            <input type="date" name="employees[${rowIndex}][period_from]" required
                                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Period To</label>
                            <input type="date" name="employees[${rowIndex}][period_to]" required
                                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Office Assignment</label>
                            <input type="text" name="employees[${rowIndex}][office_assignment]" placeholder="e.g., BRGY. PANGAN-AN" required
                                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none uppercase">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Renewal Status</label>
                            <input type="text" name="employees[${rowIndex}][renewal_status]" value="NEW" required
                                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none uppercase">
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            updateRemoveButtons();
        });

        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row-btn')) {
                e.target.closest('.employee-row').remove();
                updateRemoveButtons();
            }
        });

        updateRemoveButtons();
    });
</script>
@endsection