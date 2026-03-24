<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Quarterly Sales Performance Report</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a2e; background: #fff; }
    .header { background: #0f172a; color: #fff; padding: 20px 24px; margin-bottom: 20px; }
    .header h1 { font-size: 16px; font-weight: 700; }
    .header p { font-size: 9px; color: #94a3b8; margin-top: 3px; }
    .meta { display: flex; gap: 15px; margin-bottom: 20px; padding: 0 24px; }
    .stat { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; flex: 1; }
    .stat-label { font-size: 8px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 14px; font-weight: 700; margin-top: 2px; }
    .stat-value.green { color: #059669; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
    thead tr { background: #0f172a; color: #fff; }
    thead th { padding: 6px 8px; text-align: left; font-size: 8px; font-weight: 600; text-transform: uppercase; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
    .total-col { background: rgba(15, 23, 42, 0.03); font-weight: 700; }
    .year-total { background: rgba(15, 23, 42, 0.08); font-weight: 800; }
    .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; padding: 0 24px; }
    .section-title { font-size: 12px; font-weight: 700; margin: 0 24px 10px; padding-bottom: 5px; border-bottom: 2px solid #0f172a; color: #0f172a; }
</style>
</head>
<body>
<div class="header">
    <h1>Quarterly Sales Performance — {{ $year }}</h1>
    <p>Generated on: {{ $generated_at }}</p>
</div>

<div class="section-title">Quarterly Summary</div>
<div class="meta">
    @foreach($summary as $q => $data)
    <div class="stat">
        <div class="stat-label">Q{{ $q }} Achievement</div>
        <div class="stat-value {{ $data['percent'] >= 100 ? 'green' : '' }}">৳ {{ number_format($data['achieved'], 0) }}</div>
        <div style="font-size: 8px; color: #64748b; margin-top: 4px;">Target: ৳ {{ number_format($data['target'], 0) }} ({{ $data['percent'] }}%)</div>
    </div>
    @endforeach
</div>

<div class="section-title">Individual Performance Breakdown</div>
<div style="padding: 0 24px;">
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Sales Person</th>
                @foreach(['Jan', 'Feb', 'Mar', 'Q1', 'Apr', 'May', 'Jun', 'Q2', 'Jul', 'Aug', 'Sep', 'Q3', 'Oct', 'Nov', 'Dec', 'Q4', 'Total'] as $col)
                    <th style="text-align: center;">{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($performance as $perf)
            <tr>
                <td style="font-weight: 600;">{{ $perf['name'] }}</td>
                @foreach($perf['months'] as $m => $amt)
                    <td style="text-align: center;">{{ number_format($amt, 0) }}</td>
                    @if($m == 3) <td style="text-align: center;" class="total-col">{{ number_format($perf['q1'], 0) }}</td> @endif
                    @if($m == 6) <td style="text-align: center;" class="total-col">{{ number_format($perf['q2'], 0) }}</td> @endif
                    @if($m == 9) <td style="text-align: center;" class="total-col">{{ number_format($perf['q3'], 0) }}</td> @endif
                    @if($m == 12) <td style="text-align: center;" class="total-col">{{ number_format($perf['q4'], 0) }}</td> @endif
                @endforeach
                <td style="text-align: center;" class="year-total">৳ {{ number_format($perf['total'], 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="footer">MetroNet Sales CRM &bull; Quarterly Sales Report &bull; {{ $generated_at }}</div>
</body>
</html>
