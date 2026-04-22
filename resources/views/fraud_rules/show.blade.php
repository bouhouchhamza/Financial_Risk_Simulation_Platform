@extends('layouts.app')

@section('content')
<section class="unified-user-page fraud-rules-page fraud-rule-show-page">
    <div class="page-header">
        <div class="page-title-wrap">
            <h1 class="page-title">Fraud Rule Details</h1>
            <p class="page-subtitle">Complete rule information and runtime scoring configuration.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('fraud_rules.index') }}" class="btn">Back to Rules</a>
        </div>
    </div>

    @if(isset($rule))
        <div class="card dashboard-panel">
            <h2 class="card-title">{{ $rule->name }}</h2>
            <div class="grid">
                <div>
                    <p class="stat-label">Code</p>
                    <p>{{ $rule->code }}</p>
                </div>
                <div>
                    <p class="stat-label">Threshold</p>
                    <p>{{ $rule->threshold_value }}</p>
                </div>
                <div>
                    <p class="stat-label">Score Weight</p>
                    <p>{{ $rule->score_weight }}</p>
                </div>
                <div>
                    <p class="stat-label">Status</p>
                    <p>
                        <span class="badge {{ $rule->is_active ? 'badge-low' : 'badge-high' }}">
                            {{ $rule->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="rule-item">
                <p class="stat-label">Description</p>
                <p>{{ $rule->description ?: 'No description provided.' }}</p>
            </div>
        </div>
    @else
        <div class="card dashboard-panel">
            <p class="empty-state">Rule data is unavailable.</p>
        </div>
    @endif
</section>
@endsection
