@extends('layouts.admin')

@section('content')
<div class="edit-rule-page">

    <div class="edit-header">
        <div>
            <span class="kicker">SECURITY CONFIG</span>
            <h1>Edit Fraud Rule</h1>
            <p>Modify rule behavior, thresholds, and activation state.</p>
        </div>

        <a href="{{ route('admin.fraud_rules.index') }}" class="btn-back">
            Back
        </a>
    </div>

    <div class="edit-card">
        <form method="POST" action="{{ route('admin.fraud_rules.update', $rule->id) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <!-- NAME -->
                <div>
                    <label>Rule Name</label>
                    <input type="text" name="name" value="{{ old('name', $rule->name) }}" class="input">
                    @error('name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- THRESHOLD -->
                <div>
                    <label>Threshold Value</label>
                    <input type="number" name="threshold_value" value="{{ old('threshold_value', $rule->threshold_value) }}" class="input">
                    @error('threshold_value')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- DESCRIPTION -->
                <div style="grid-column: span 2;">
                    <label>Description</label>
                    <textarea name="description" class="input" rows="3">{{ old('description', $rule->description) }}</textarea>
                </div>

                <!-- STATUS -->
                <div>
                    <label>Status</label>
                    <select name="is_active" class="input">
                        <option value="1" {{ $rule->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$rule->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
