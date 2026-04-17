@extends('layouts.admin')

@section('content')
<div class="rule-form-page">

    <div class="rule-form-header">
        <div>
            <div class="rule-form-kicker">SECURITY GOVERNANCE</div>
            <h1 class="rule-form-title">Create Fraud Rule</h1>
            <p class="rule-form-subtitle">
                Add a new fraud detection rule to extend platform monitoring behavior.
            </p>
        </div>

        <a href="{{ route('admin.fraud_rules.index') }}" class="rule-back-btn">Back</a>
    </div>

    <div class="rule-form-card">
        <form method="POST" action="{{ route('admin.fraud_rules.store') }}">
            @csrf

            <div class="rule-form-grid">
                <div class="rule-form-group">
                    <label for="name">Rule Name</label>
                    <input id="name" type="text" name="name" class="rule-input" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="rule-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rule-form-group">
                    <label for="threshold_value">Threshold Value</label>
                    <input id="threshold_value" type="number" name="threshold_value" class="rule-input" value="{{ old('threshold_value') }}" required>
                    @error('threshold_value')
                        <p class="rule-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rule-form-group full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="rule-textarea">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="rule-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rule-form-group">
                    <label for="is_active">Status</label>
                    <select id="is_active" name="is_active" class="rule-select" required>
                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="rule-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rule-form-actions">
                <a href="{{ route('admin.fraud_rules.index') }}" class="rule-cancel-btn">Cancel</a>
                <button type="submit" class="rule-save-btn">Create Rule</button>
            </div>
        </form>
    </div>
</div>
@endsection