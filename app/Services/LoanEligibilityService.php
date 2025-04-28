<?php

namespace App\Services;

use App\Domain\Client;

class LoanEligibilityService
{
    public function check(Client $client, array $loanData): array
    {

        // 1. Credit score > 500
        if ($client->score <= 500) {
            return ['approved' => false, 'reason' => 'Low credit score'];
        }
        // 2. Income >= 1000
        if ($client->income < 1000) {
            return ['approved' => false, 'reason' => 'Low income'];
        }
        // 3. Age 18–60
        if ($client->age < 18 || $client->age > 60) {
            return ['approved' => false, 'reason' => 'Age not eligible'];
        }
        // 4. Region PR, BR, OS
        if (!in_array($client->region, ['PR', 'BR', 'OS'])) {
            return ['approved' => false, 'reason' => 'Region not supported'];
        }

        // 5. Random denial for Prague
        if ($client->region === 'PR' && rand(0, 1) === 0) {
            return ['approved' => false, 'reason' => 'Random denial for Prague'];
        }
        // 6. Ostrava: increase rate by 5%
        $rate = $loanData['rate'] ?? 10;
        if ($client->region === 'OS') {
            $rate += 5;
        }

        return [
            'approved' => true,
            'rate' => $rate,
            'reason' => null,
        ];
    }
}

