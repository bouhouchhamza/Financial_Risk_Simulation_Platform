<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Startup;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService
    ) {}

    public function index(): View|RedirectResponse
    {
        $startup = $this->currentStartup();

        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }

        $transactions = $startup->transactions()
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10);

        return view('transactions.index', compact('transactions', 'startup'));
    }

    public function create(): View|RedirectResponse
    {
        $startup = $this->currentStartup();

        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }

        return view('transactions.create');
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $startup = $this->currentStartup();

        if (! $startup) {
            return redirect()->route('startup.create');
        }

        $transaction = $this->transactionService->createForStartup($startup, $request->validated());

        return redirect()
            ->route('transactions.show', $transaction->id)
            ->with('success', 'Transaction created successfully. Fraud detection was executed automatically.');
    }

    public function show(int $id): View|RedirectResponse
    {
        $startup = $this->currentStartup();

        if (! $startup) {
            return redirect()->route('startup.create');
        }

        $transaction = $startup->transactions()
            ->with('alerts')
            ->findOrFail($id);

        return view('transactions.show', compact('transaction', 'startup'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $startup = $this->currentStartup();

        if (! $startup) {
            return redirect()->route('startup.create');
        }

        $transaction = $startup->transactions()->findOrFail($id);
        $this->transactionService->delete($transaction);

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted.');
    }

    private function currentStartup(): ?Startup
    {
        return auth()->user()?->startup;
    }
}
