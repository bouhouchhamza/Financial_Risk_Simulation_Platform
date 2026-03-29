<?php
namespace App\Services;

use App\Models\Startup;

class AlertService{
    public function storeFraudAlerts(Startup $startup, array $fraudResult): void{
        foreach ($fraudResult['flags'] as $flag) {
            $startup->alerts()->firstOrCreate([
                'type' => $flag,
                'severity' => $this->mapFraudSeverity($flag),
                'message' => $this->fraudMessage($flag),
                'status' => 'new',
                'data' => $fraudResult['details'][$flag] ?? null,
            ]);
        }
    }
    public function storeSimulationAlerts(Startup $startup, array $simulationResult){
        if (($simulationResult['risk_level'] ?? 'low') === 'high') {
            $startup->alerts()->firstOrCreate([
                'type' => 'simulation_risk',
                'severity' => 'high',
                'message' => 'Simulation shows a high financial risk level.',
                'status' => 'new',
                'data' => $simulationResult,
            ]);
        }       
    }
    private function mapFraudSeverity(string $flag): string{
        return match ($flag) {
            'high_amount', 'invalid_amount' => 'high',
            'duplicate_pattern', 'unusual_activity' => 'medium',
            default => 'low',
        };
    }
    private function fraudMessage(string $flag): string
    {
        return match ($flag) {
            'high_amount' => 'High amount transaction detected.',
            'duplicate_pattern' => 'Possible duplicate transactions detected.',
            'invalid_amount' => 'Invalid transaction amount detected.',
            'unusual_activity' => 'Unusual transaction activity detected.',
            default => 'Fraud alert detected.',
        };
    }
}