@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="border-b border-gray-300 pb-4 flex justify-between items-center">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase">CS Form No. 212 (Revised 2017)</p>
            <h2 class="text-2xl font-bold text-blue-900 uppercase">Personal Data Sheet - Master Entry</h2>
        </div>
        <a href="{{ route('masterlist') }}" class="bg-gray-600 hover:bg-gray-700 text-white text-xs font-bold px-3 py-2 rounded shadow">
            &larr; Back to Masterlist
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-600 p-4 text-sm text-red-700">
            <p class="font-bold">Please check the form below for missing required inputs or validation errors.</p>
            <ul class="list-disc list-inside mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pds.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- I. PERSONAL INFORMATION -->
        <div class="bg-white border border-gray-300 shadow-sm">
            <div class="bg-blue-900 text-white px-4 py-2 text-sm font-bold uppercase">
                I. Personal Information
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Name Extension (Jr., III)</label>
                    <input type="text" name="name_extension" value="{{ old('name_extension') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Date of Birth (mm/dd/yyyy) *</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Place of Birth</label>
                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Sex *</label>
                    <select name="gender" required class="w-full border border-gray-300 p-2 rounded">
                        <option value="">Select Sex</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Civil Status</label>
                    <select name="civil_status" class="w-full border border-gray-300 p-2 rounded">
                        <option value="">Select Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Separated">Separated</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Height (m)</label>
                    <input type="text" name="height" value="{{ old('height') }}" class="w-full border border-gray-300 p-2 rounded" placeholder="e.g. 1.70">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Weight (kg)</label>
                    <input type="text" name="weight" value="{{ old('weight') }}" class="w-full border border-gray-300 p-2 rounded" placeholder="e.g. 65">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Blood Type</label>
                    <input type="text" name="blood_type" value="{{ old('blood_type') }}" class="w-full border border-gray-300 p-2 rounded" placeholder="e.g. O+">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Citizenship</label>
                    <input type="text" name="citizenship" value="Filipino" class="w-full border border-gray-300 p-2 rounded">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">GSIS ID No.</label>
                    <input type="text" name="gsis" value="{{ old('gsis') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Pag-IBIG ID No.</label>
                    <input type="text" name="pagibig" value="{{ old('pagibig') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">PhilHealth No.</label>
                    <input type="text" name="philhealth" value="{{ old('philhealth') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">SSS No.</label>
                    <input type="text" name="sss" value="{{ old('sss') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-1">TIN No.</label>
                    <input type="text" name="tin" value="{{ old('tin') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-1">Agency Employee No.</label>
                    <input type="text" name="agency_employee_no" value="{{ old('agency_employee_no') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
            </div>
        </div>

        <!-- ADDRESS & CONTACT DETAILS -->
        <div class="bg-white border border-gray-300 shadow-sm">
            <div class="bg-blue-900 text-white px-4 py-2 text-sm font-bold uppercase">
                Residential & Permanent Address / Contact Details
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Residential Address (House/Block/Lot, Street, Subdivision, Barangay, City/Municipality, Province)</label>
                    <textarea name="residential_address" rows="3" class="w-full border border-gray-300 p-2 rounded">{{ old('residential_address') }}</textarea>
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Permanent Address (House/Block/Lot, Street, Subdivision, Barangay, City/Municipality, Province)</label>
                    <textarea name="permanent_address" rows="3" class="w-full border border-gray-300 p-2 rounded">{{ old('permanent_address') }}</textarea>
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Telephone No.</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Mobile No. *</label>
                    <input type="text" name="mobile_no" value="{{ old('mobile_no') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-1">E-mail Address</label>
                    <input type="email" name="email_address" value="{{ old('email_address') }}" class="w-full border border-gray-300 p-2 rounded">
                </div>
            </div>
        </div>

        <!-- II. FAMILY BACKGROUND -->
        <div class="bg-white border border-gray-300 shadow-sm">
            <div class="bg-blue-900 text-white px-4 py-2 text-sm font-bold uppercase">
                II. Family Background
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Spouse's Surname</label>
                    <input type="text" name="spouse_last_name" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Spouse's First Name</label>
                    <input type="text" name="spouse_first_name" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Spouse's Middle Name</label>
                    <input type="text" name="spouse_middle_name" class="w-full border border-gray-300 p-2 rounded">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Father's Surname</label>
                    <input type="text" name="father_last_name" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Father's First Name</label>
                    <input type="text" name="father_first_name" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Father's Middle Name</label>
                    <input type="text" name="father_middle_name" class="w-full border border-gray-300 p-2 rounded">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Mother's Maiden Surname</label>
                    <input type="text" name="mother_maiden_last_name" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Mother's First Name</label>
                    <input type="text" name="mother_maiden_first_name" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Mother's Middle Name</label>
                    <input type="text" name="mother_maiden_middle_name" class="w-full border border-gray-300 p-2 rounded">
                </div>
            </div>
        </div>

        <!-- III. EDUCATIONAL BACKGROUND -->
        <div class="bg-white border border-gray-300 shadow-sm">
            <div class="bg-blue-900 text-white px-4 py-2 text-sm font-bold uppercase">
                III. Educational Background (College / Vocational)
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Name of School</label>
                    <input type="text" name="school_name" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Basic Education / Degree Course</label>
                    <input type="text" name="degree_course" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Period of Attendance (From - To)</label>
                    <input type="text" name="school_period" placeholder="e.g. 2018-2022" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Year Graduated</label>
                    <input type="text" name="year_graduated" class="w-full border border-gray-300 p-2 rounded">
                </div>
            </div>
        </div>

        <!-- IV. CIVIL SERVICE ELIGIBILITY & WORK EXPERIENCE -->
        <div class="bg-white border border-gray-300 shadow-sm">
            <div class="bg-blue-900 text-white px-4 py-2 text-sm font-bold uppercase">
                IV. Civil Service Eligibility & V. Work Experience Summary
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Civil Service Eligibility / Board Exam Passed</label>
                    <input type="text" name="eligibility" placeholder="e.g. Professional Board / CSC Sub-Professional" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Rating / Date of Conferment</label>
                    <input type="text" name="eligibility_rating" placeholder="e.g. 85.50% / 03/2021" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-1">Latest Position / Work Experience Summary</label>
                    <textarea name="work_experience_summary" rows="2" placeholder="Position Title, Department / Agency, Inclusive Dates" class="w-full border border-gray-300 p-2 rounded"></textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button Row -->
        <div class="flex justify-end space-x-3 pb-12">
            <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs font-bold px-5 py-2.5 rounded shadow">
                Clear Form
            </button>
            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold px-6 py-2.5 rounded shadow tracking-wider uppercase">
                Save and Submit PDS Entry
            </button>
        </div>
    </form>
</div>
@endsection