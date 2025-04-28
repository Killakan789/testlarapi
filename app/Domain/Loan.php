<?php

namespace App\Domain;

class Loan
{
    public function __construct(
        public string $name,
        public float $amount,
        public float $rate,
        public string $start_date,
        public string $end_date,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['amount'],
            $data['rate'],
            $data['start_date'],
            $data['end_date'],
        );
    }

    public function toArray(): array
    {
        return [
            'name'       => $this->name,
            'amount'     => $this->amount,
            'rate'       => $this->rate,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
        ];
    }
}
