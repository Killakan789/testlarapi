<?php

namespace App\Services;

use App\Domain\Client;
use App\Repositories\LoanRepository;
use Illuminate\Support\Facades\Log;

class LoanService
{
    protected LoanRepository $loans;
    protected LoanEligibilityService $eligibility;

    public function __construct(
        LoanRepository         $loans,
        LoanEligibilityService $eligibility
    )
    {
        $this->loans = $loans;
        $this->eligibility = $eligibility;
    }

    /**
     * Checks eligibility for a loan.
     */
    public function checkEligibility(Client $client, array $loanData): array
    {
        return $this->eligibility->check($client, $loanData);
    }

    /**
     * Issues a loan if eligible, logs notification, and returns result.
     */
    public function issueLoan(Client $client, array $loanData): array
    {
        $result = $this->eligibility->check($client, $loanData);

        if (!$result['approved']) {
            $msg = now()->toDateTimeString() . " Notification to client [{$client->name}]: Loan declined.";
            Log::info($msg);
            return [
                'approved' => false,
                'reason' => $result['reason'],
            ];
        }

        // Use updated rate (for Ostrava)
        $loanData['rate'] = $result['rate'] ?? $loanData['rate'];

        $loan = $this->loans->create($loanData);

        $msg = now()->toDateTimeString() . " Notification to client [{$client->name}]: Loan approved.";
        Log::info($msg);

        return [
            'approved' => true,
            'loan' => $loan,
        ];
    }
}
