<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contract Service - {{ $appointment->contract_service_no }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #111; line-height: 1.5; margin: 40px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2, .header h3 { margin: 0; }
        .details-box { border: 1px solid #ccc; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .footer-sigs { margin-top: 60px; display: flex; justify-content: space-between; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; background: #fef08a; padding: 10px; text-align: center;">
        <button onclick="window.print()" style="padding: 8px 16px; font-weight: bold; cursor: pointer;">Print Document Again</button>
    </div>

    <div class="header">
        <h3>Republic of the Philippines</h3>
        <h2>LGU Plantilla / Contract Service Record</h2>
        <p>Contract No: <strong>{{ $appointment->contract_service_no }}</strong></p>
    </div>

    <div class="details-box">
        <p><strong>Employee Name:</strong> {{ strtoupper($appointment->pds->last_name) }}, {{ strtoupper($appointment->pds->first_name) }} {{ strtoupper($appointment->pds->middle_initial) }}</p>
        <p><strong>Designation:</strong> {{ strtoupper($appointment->designation) }}</p>
        <p><strong>Office Assignment:</strong> {{ strtoupper($appointment->office_assignment) }}</p>
        <p><strong>Rate Per Day:</strong> ₱{{ number_format($appointment->rate_per_day, 2) }}</p>
        <p><strong>Period:</strong> {{ \Carbon\Carbon::parse($appointment->period_from)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($appointment->period_to)->format('M d, Y') }}</p>
        <p><strong>Source of Funds:</strong> {{ $appointment->source_of_funds }}</p>
        <p><strong>Renewal Status:</strong> {{ $appointment->renewal_status }}</p>
    </div>

</body>
</html>