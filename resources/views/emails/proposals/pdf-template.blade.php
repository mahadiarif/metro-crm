<!DOCTYPE html>
<html>
<head>
    <title>Service Proposal</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 50px; }
        .details { margin-bottom: 30px; }
        .footer { margin-top: 50px; font-size: 12px; color: #777; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Service Proposal</h1>
        <p>Prepared for: {{ $lead->company_name }}</p>
    </div>

    <div class="details">
        <p><strong>Date:</strong> {{ $date }}</p>
        <p><strong>Attention:</strong> {{ $lead->contact_person }} ({{ $lead->designation }})</p>
    </div>

    <h3>Proposal Details</h3>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Package</th>
                <th>Price (BDT)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $proposal->service->name }}</td>
                <td>{{ $proposal->servicePackage->name ?? 'Custom' }}</td>
                <td>৳ {{ number_format($proposal->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer-generated proposal prepared by {{ $proposal->user->name }}.</p>
    </div>
</body>
</html>
