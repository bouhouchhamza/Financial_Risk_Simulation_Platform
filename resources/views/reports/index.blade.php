@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Reports</h1>
    <div class="page-actions">
        <a href="{{ route('dashboard') }}" class="btn">Back</a>
    </div>
</div>

@if(session('success'))
    <div class="notice notice-success">{{ session('success') }}</div>
@endif

<div class="card">
    <h2 class="card-title">Generated Reports</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Summary</th>
                    <th>Risk Level</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>#{{ $report->id }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($report->summary, 80) }}</td>
                        <td>
                            @php
                                $risk = strtolower((string) $report->risk_level);
                                $riskClass = match ($risk) {
                                    'high' => 'badge-high',
                                    'medium' => 'badge-medium',
                                    default => 'badge-low',
                                };
                            @endphp
                            <span class="badge {{ $riskClass }}">{{ strtoupper($report->risk_level ?: 'low') }}</span>
                        </td>
                        <td>{{ optional($report->created_at)->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('reports.show', $report->id) }}" class="btn">View</a>
                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Delete this report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">No reports generated yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
