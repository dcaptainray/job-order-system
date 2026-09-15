@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto bg-white border border-gray-200 shadow-sm p-8">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-xl font-bold text-blue-900 tracking-tight">Account Settings</h2>
        <p class="text-xs text-gray-600 mt-0.5">Manage your account security and update your password credentials.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-3 rounded text-xs mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded text-xs mb-4">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
        @csrf
        
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Current Password</label>
            <input type="password" name="current_password" required
                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">New Password</label>
            <input type="password" name="password" required
                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
            <p class="text-[10px] text-gray-500 mt-1">Must be at least 8 characters long and include a mix of letters, numbers, and symbols.</p>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Confirm New Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
        </div>

        <div class="pt-4 flex items-center justify-end border-t border-gray-100 mt-6">
            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-6 py-2.5 rounded shadow transition">
                Update Password
            </button>
        </div>
    </form>
</div>
@endsection