@extends('layouts.admin')

@section('content')
<div class="fraud-rules-page">

    <div class="fraud-rules-header">
        <div>
            <div class="fraud-rules-kicker">SECURITY GOVERNANCE</div>
            <h1 class="fraud-rules-title">Fraud Rules</h1>
            <p class="fraud-rules-subtitle">
                Manage detection logic, thresholds, and activation state for the fraud monitoring engine.
            </p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="fraud-rules-back-btn">Back to Dashboard</a>
    </div>

    <div class="fraud-rules-table-card">
        <div class="table-card-header">
            <div>
                <h3>Detection Rules Registry</h3>
                <p>Review and update core fraud logic used by the admin monitoring system.</p>
            </div>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
            <h1>Fraud Rules</h1>

            <a href="{{ route('admin.fraud_rules.create') }}" class="btn-primary">
                + Add Rule
            </a>
        </div>

        <div class="table-scroll">
            <table class="fraud-rules-table">
                <thead>
                    <tr>
                        <th>Rule</th>
                        <th>Code</th>
                        <th>Threshold</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rules as $rule)
                    <tr>
                        <td>
                            <div class="rule-name-cell">
                                <div class="rule-icon">
                                    {{ strtoupper(substr($rule->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="rule-name">{{ str_replace('_', ' ', $rule->name) }}</div>
                                    <div class="rule-meta">Fraud detection policy</div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="code-pill">{{ $rule->code ?? 'N/A' }}</span>
                        </td>

                        <td class="rule-number">
                            {{ $rule->threshold_value }}
                        </td>

                        <td class="rule-description">
                            {{ $rule->description ?? 'No description provided.' }}
                        </td>

                        <td>
                            @if($rule->is_active)
                            <span class="status-pill active">Active</span>
                            @else
                            <span class="status-pill inactive">Inactive</span>
                            @endif
                        </td>

                        <td class="text-right">
                            <a href="{{ route('admin.fraud_rules.edit', $rule->id) }}" class="edit-btn">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-row">
                            No fraud rules found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection