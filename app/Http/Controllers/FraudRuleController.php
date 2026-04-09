<?php

namespace App\Http\Controllers;

use App\Models\FraudRule;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class FraudRuleController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $rules = FraudRule::latest()->get();
        return view('fraud_rules.index', compact('rules'));
    }
    public function edit($id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $rule = FraudRule::findOrFail($id);
        return view('fraud_rules.edit', compact('rule'));
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
        return redirect()->route('fraud_rules.index')->with('success', 'Fraud rule updated successfully');
    }
}
