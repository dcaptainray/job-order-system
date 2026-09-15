<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Order System - Government Portal</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .gov-blue { background-color: #003366; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen m-0 p-0">

    <!-- Top Republic Banner -->
    <div class="bg-gray-900 text-gray-300 text-xs py-1 px-6 border-b border-gray-800 w-full">
        <div class="flex justify-between items-center w-full">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                <span>Republic of the Philippines | Official Government Portal</span>
            </div>
            <div>
                <span>System Time: {{ date('F d, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Agency Masthead Header -->
    <header class="bg-white border-b-4 border-blue-900 shadow-sm w-full">
        <div class="px-6 py-4 flex flex-col md:flex-row justify-between items-center w-full">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('seal.jpg') }}" alt="Official Seal" class="w-16 h-16 rounded-full object-cover border border-gray-400">
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Republic of the Philippines</p>
                    <h1 class="text-xl font-bold text-blue-900 tracking-tight">LAPU LAPU CITY - HRMDO</h1>
                    <p class="text-xs text-gray-600">Job Order Processing & Information Management System</p>
                </div>
            </div>
            
            @auth
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-xs text-gray-500">Logged in as:</p>
                    <p class="text-sm font-bold text-blue-900">{{ Auth::user()->email }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-700 hover:bg-red-800 text-white text-xs font-semibold px-3 py-2 rounded shadow transition">
                        Sign Out
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </header>

    <!-- Navigation Bar -->
    @auth
    <nav class="bg-blue-900 text-white shadow-inner w-full">
        <div class="px-6 flex space-x-1 overflow-x-auto w-full">
            <a href="{{ route('dashboard') }}" class="px-4 py-2.5 text-sm font-medium hover:bg-blue-800 transition {{ request()->routeIs('dashboard') ? 'bg-blue-950 border-b-2 border-amber-400' : '' }}">Dashboard</a>
            <a href="{{ route('masterlist') }}" class="px-4 py-2.5 text-sm font-medium hover:bg-blue-800 transition {{ request()->routeIs('masterlist*') ? 'bg-blue-950 border-b-2 border-amber-400' : '' }}">PDS Masterlist</a>
            <a href="{{ route('pds.create') }}" class="px-4 py-2.5 text-sm font-medium hover:bg-blue-800 transition {{ request()->routeIs('pds.create') ? 'bg-blue-950 border-b-2 border-amber-400' : '' }}">New PDS Entry</a>
            <a href="{{ route('appointments.index') }}" class="px-4 py-2.5 text-sm font-medium hover:bg-blue-800 transition {{ request()->routeIs('appointments*') ? 'bg-blue-950 border-b-2 border-amber-400' : '' }}">Appointments</a>
            <a href="{{ route('settings') }}" class="px-4 py-2.5 text-sm font-medium hover:bg-blue-800 transition {{ request()->routeIs('settings') ? 'bg-blue-950 border-b-2 border-amber-400' : '' }}">Settings</a>
        </div>
    </nav>
    @endauth

    <!-- Main Content Container (Full Width Fluid) -->
    <main class="flex-grow w-full px-6 py-6">
        <div class="bg-amber-50 border-l-4 border-amber-500 p-3 mb-6 text-xs text-amber-800 flex justify-between items-center w-full">
            <span><strong>Notice:</strong> All transactions and profile updates are monitored under standard data privacy and government compliance protocols.</span>
            <span class="uppercase font-bold text-blue-900 bg-amber-200 px-2 py-0.5 rounded">Secure Portal v1.0</span>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 text-sm w-full" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm border border-gray-200 p-6 rounded-none w-full">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 text-xs mt-auto border-t border-gray-800 w-full">
        <div class="px-6 py-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 w-full">
            <div>
                <p class="font-bold text-gray-200">Job Order Management System</p>
                <p>Department / Local Government Unit Information Communications Technology Unit</p>
            </div>
            <div class="text-right">
                <p>Standard Government Web Template Compliance</p>
                <p class="text-gray-500">All rights reserved © {{ date('Y') }}</p>
            </div>
        </div>
    </footer>

</body>
</html>