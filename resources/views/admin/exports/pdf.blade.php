<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Export {{ $summary['type'] }}</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.25rem; font-size: 11px; }
        th { background: #f5f5f5; font-weight: bold; }
        .summary { margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="summary">
        <h3>Export Summary</h3>
        <p>Type: {{ $summary['type'] }}</p>
        <p>Total rows shown: {{ $summary['total'] }}</p>
        @if (! empty($summary['filters']))
            <p>Filters: {{ json_encode($summary['filters']) }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($row as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
