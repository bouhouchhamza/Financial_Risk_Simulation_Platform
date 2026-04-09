@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Fraud Rule</h1>
    <div class="page-actions">
        <a href="{{ route('fraud_rules.index') }}" class="btn">Back</a>
    </div>
</div>

@if($errors->any())
    <div class="notice notice-error">
        <strong>Please fix the following errors:</strong>
        <ul class="list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <form action="{{ route('fraud_rules.update', $rule->id) }}" method="POST" class="form-grid">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Rule Name</label>
            <input id="name" name="name" type="text" class="input" value="{{ old('name', $rule->name) }}" required>
        </div>

        <div>
            <label for="threshold_value">Threshold Value</label>
            <input id="threshold_value" name="threshold_value" type="number" class="input" value="{{ old('threshold_value', $rule->threshold_value) }}" required>
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" class="input">{{ old('description', $rule->description) }}</textarea>
        </div>

        <div>
            <label for="is_active">Status</label>
            <select id="is_active" name="is_active" class="select" required>
                <option value="1" @selected((string) old('is_active', (int) $rule->is_active) === '1')>Active</option>
                <option value="0" @selected((string) old('is_active', (int) $rule->is_active) === '0')>Inactive</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Rule</button>
        </div>
    </form>
</div>
@endsection
