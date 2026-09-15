@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto my-12 bg-white border border-gray-200 shadow-sm p-8">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-xl font-bold text-blue-900 tracking-tight">Employee Portal Login</h2>
        <p class="text-xs text-gray-600 mt-0.5">Sign in to access your administrative dashboard.</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded text-xs mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
        </div>
        
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-1 focus:ring-blue-900 focus:outline-none">
        </div>
        
        <div class="pt-2">
            <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold py-2.5 rounded shadow transition cursor-pointer">
                Login
            </button>
        </div>
    </form>
</div>
@endsection