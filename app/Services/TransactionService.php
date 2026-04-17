<?php

namespace App\Services;

use App\Models\Startup;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        private readonly FraudAnalysisService $fraudAnalysisService
    ) {}

    public function createForStartup(Startup $startup, array $validatedData): Transaction
    {
        return DB::transaction(function () use ($startup, $validatedData): Transaction {
            $transaction = $startup->transactions()->create($validatedData);

            // Auto-run fraud checks in the same transaction to keep state consistent.
            $this->fraudAnalysisService->runForStartup($startup->fresh());

            return $transaction->fresh();
        }, 3);
    }

    public function delete(Transaction $transaction): void
    {
        $transaction->delete();
    }
}
