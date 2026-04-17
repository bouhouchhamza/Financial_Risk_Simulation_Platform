<?php

namespace App\Http\Controllers;

use App\Models\FraudRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FraudRuleController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $rules = FraudRule::latest()->get();

        return view('admin.fraud-rules.index', compact('rules'));
    }
    public function create()
    {
        return view('admin.fraud-rules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:fraud_rules,name',
            'threshold_value' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        \App\Models\FraudRule::create($validated);

        return redirect()->route('admin.fraud_rules.index')
            ->with('success', 'Fraud rule created successfully');
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $rule = FraudRule::findOrFail($id);

        return view('admin.fraud-rules.edit', compact('rule'));
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $rule = FraudRule::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('fraud_rules', 'name')->ignore($rule->id),
            ],
            'threshold_value' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $rule->update($validated);

        return redirect()
            ->route('admin.fraud_rules.index')
            ->with('success', 'Fraud rule updated successfully');
    }
}
