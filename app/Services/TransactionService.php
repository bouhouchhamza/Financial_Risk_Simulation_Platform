<?php

namespace App\Services;

use App\Models\Startup;
use App\Models\Transaction;

class TransactionService
{
    public function __construct(
        private readonly FraudAnalysisService $fraudAnalysisService
    ) {}

    public function createForStartup(Startup $startup, array $validatedData): Transaction
    {
        $transaction = $startup->transactions()->create($validatedData);

        // Auto-run fraud checks after each transaction creation for MVP safety.
        $this->fraudAnalysisService->runForStartup($startup);

        return $transaction->fresh();
    }

    public function delete(Transaction $transaction): void
    {
        $transaction->delete();
    }
}
