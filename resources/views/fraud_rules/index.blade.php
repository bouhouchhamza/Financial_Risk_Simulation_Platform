@extends('layouts.app')

@section('content')
<section class="unified-user-page fraud-rules-page">
    <div class="page-header">
        <div class="page-title-wrap">
            <h1 class="page-title">Fraud Rules</h1>
            <p class="page-subtitle">Threshold and scoring configuration used by fraud detection.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('dashboard') }}" class="btn">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="notice notice-success">{{ session('success') }}</div>
    @endif

    <div class="card dashboard-panel">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Threshold</th>
                        <th>Score Weight</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rules as $rule)
                        <tr>
                            <td>{{ $rule->name }}</td>
                            <td>{{ $rule->code }}</td>
                            <td>{{ $rule->threshold_value }}</td>
                            <td>{{ $rule->score_weight }}</td>
                            <td>
                                <span class="badge {{ $rule->is_active ? 'badge-low' : 'badge-high' }}">
                                    {{ $rule->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('fraud_rules.edit', $rule->id) }}" class="btn">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">No fraud rules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
