<?php

use App\Models\Appointment;
use App\Models\Pds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

$loginHandler = function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
};

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/', $loginHandler);
Route::post('/login', $loginHandler);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
    
    Route::get('/settings', fn() => view('settings'))->name('settings');
    Route::post('/settings', function (Request $request) {
        $request->validate(['password' => 'required|min:6']);
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Password updated successfully.');
    })->name('settings.update');

    Route::get('/pds/create', fn() => view('pds.create'))->name('pds.create');
    Route::post('/pds', function (Request $request) {
        $data = $request->all();
        $data['unique_id'] = 'PDS-' . strtoupper(uniqid());
        $pds = Pds::create($data);
        return redirect()->route('pds.show', $pds->id);
    })->name('pds.store');

    Route::get('/pds/{pds}', fn(Pds $pds) => view('pds.show', compact('pds')))->name('pds.show');
    Route::get('/masterlist', fn() => view('pds.masterlist', ['list' => Pds::all()]))->name('masterlist');

    Route::get('/appointments', fn() => view('appointments.index', ['appointments' => Appointment::with('pds')->get()]))->name('appointments.index');
    Route::get('/appointments/create', fn() => view('appointments.create', ['pdsList' => Pds::all()]))->name('appointments.create');
    
    Route::post('/appointments', function(Request $request) {
        $contractServiceNo = $request->input('contract_service_no');
        $sourceOfFunds     = $request->input('source_of_funds');

        // Loop and save all employees under this contract batch
        foreach ($request->input('employees', []) as $emp) {
            Appointment::create([
                'contract_service_no' => $contractServiceNo,
                'source_of_funds'     => $sourceOfFunds,
                'pds_id'              => $emp['pds_id'],
                'designation'         => $emp['designation'],
                'rate_per_day'        => $emp['rate_per_day'],
                'period_from'         => $emp['period_from'],
                'period_to'           => $emp['period_to'],
                'office_assignment'   => $emp['office_assignment'],
                'renewal_status'      => $emp['renewal_status'],
            ]);
        }

        // Redirect directly to the multi-employee contract print/view page using the contract service number
        return redirect()->route('appointments.contract', $contractServiceNo);
    })->name('appointments.store');

    // === CONTRACT VIEW ROUTE (PLACED BEFORE THE {appointment} WILDCARD) ===
    Route::get('/appointments/contract/{contractServiceNo}', function($contractServiceNo) {
        $appointments = Appointment::with('pds')
            ->where('contract_service_no', $contractServiceNo)
            ->get();

        if ($appointments->isEmpty()) {
            abort(404, 'Contract document not found.');
        }

        return view('appointments.contract', compact('appointments', 'contractServiceNo'));
    })->name('appointments.contract');

    // === INDIVIDUAL PRINT / VIEW ROUTE ===
    Route::get('/appointments/{appointment}/print', function (Appointment $appointment) {
        $appointment->load('pds');
        return view('appointments.print-template', compact('appointment'));
    })->name('appointments.print');

    Route::get('/appointments/{appointment}', fn(Appointment $appointment) => view('appointments.show', compact('appointment')))->name('appointments.show');

    // === UPDATED POST ROUTE FOR FILE UPLOAD AND STATUS PROCESS ===
    Route::post('/appointments/{appointment}/process', function(Request $request, Appointment $appointment) {
        $data = [
            'status' => $request->input('status'),
        ];

        // Handles document upload if provided via the modal form
        if ($request->hasFile('document_file')) {
            $path = $request->file('document_file')->store('appointment_documents', 'public');
            $data['document_path'] = $path;
        }

        $appointment->update($data);

        return back()->with('success', 'Appointment processed and file uploaded successfully.');
    })->name('appointments.process');
});