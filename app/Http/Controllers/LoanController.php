<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use App\Repositories\LoanRepository;
use App\Services\LoanEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    protected ClientRepository $clients;
    protected LoanRepository $loans;
    protected LoanEligibilityService $eligibility;

    public function __construct(
        ClientRepository $clients,
        LoanRepository $loans,
        LoanEligibilityService $eligibility
    ) {
        $this->clients = $clients;
        $this->loans = $loans;
        $this->eligibility = $eligibility;
    }

    // 1. Check eligibility
    public function check(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $client = $this->clients->findByPin($validated['pin']);
        if (!$client) {
            return response()->json(['error' => 'Client not found.'], 404);
        }

        $result = $this->eligibility->check($client, $validated);

        return response()->json($result);
    }

    // 2. Issue loan (and log notification)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string',
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'rate' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $client = $this->clients->findByPin($validated['pin']);
        if (!$client) {
            return response()->json(['error' => 'Client not found.'], 404);
        }

        // Check eligibility again before issuing
        $eligibility = $this->eligibility->check($client, $validated);
        if (!$eligibility['approved']) {
            $msg = now()->toDateTimeString()." Notification to client [{$client->name}]: Loan declined.";
            Log::info($msg);
            return response()->json(['status' => 'declined', 'reason' => $eligibility['reason'] ?? null]);
        }

        // Issue loan
        $loanData = [
            'name' => $validated['name'],
            'amount' => $validated['amount'],
            'rate' => $validated['rate'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ];
        $loan = $this->loans->create($loanData);

        $msg = now()->toDateTimeString()." Notification to client [{$client->name}]: Loan approved.";
        Log::info($msg);

        return response()->json([
            'status' => 'approved',
            'loan' => $loan,
        ], 201);
    }
}

