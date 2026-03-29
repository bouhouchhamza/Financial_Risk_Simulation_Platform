<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $transactions = $startup->transactions;
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please Create your startup first');
        }
        return view('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create');
        }
        $validated = $request->validate([
            'type' => ['required', 'in:sale,purchase,transfer'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);
        $startup->transactions()->create($validated);
        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create');
        }
        $transaction = auth()->user()->startup->transactions()->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit()
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create');
        }
        $transaction = auth()->user()->startup->transactions()->findOrFail($id);
        $validated = $request->validate([
            'type' => ['sometimes', 'in:sale,purchase,transfer'],
            'amount' => ['sometimes', 'numeric', 'gt:0'],
            'transaction_date' => ['sometimes', 'date'],
            'description' => ['sometimes',  'nullable', 'string'],
            'is_suspicious' => ['sometimes','boolean'],
        ]);
        $transaction->update($validated);
        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create');
        }
        $transaction = auth()->user()->startup->transactions()->findOrFail($id);
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted.');
    }
}
