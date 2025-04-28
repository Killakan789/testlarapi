<?php

namespace App\Repositories;

use App\Domain\Loan;

class LoanRepository
{
    protected array $loans = [
        [
            'name' => 'Personal Loan',
            'amount' => 1000,
            'rate' => 10.0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ],
        [
            'name' => 'Personal Loan 2',
            'amount' => 2000,
            'rate' => 10.0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ],
        [
            'name' => 'Personal Loan 2',
            'amount' => 3000,
            'rate' => 10.0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ],
    ];

    /** @return Loan[] */
    public function all(): array
    {
        return array_map([$this, 'arrayToLoan'], $this->loans);
    }

    public function create(array $data): Loan
    {
        $this->loans[] = $data;
        return $this->arrayToLoan($data);
    }

    protected function arrayToLoan(array $data): Loan
    {
        return new Loan(
            $data['name'],
            $data['amount'],
            $data['rate'],
            $data['start_date'],
            $data['end_date'],
        );
    }
}

