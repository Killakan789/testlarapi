<?php

namespace App\Domain;

class Client
{
    public function __construct(
        public string $name,
        public int $age,
        public string $region,
        public float $income,
        public int $score,
        public string $pin,
        public string $email,
        public string $phone,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['age'],
            $data['region'],
            $data['income'],
            $data['score'],
            $data['pin'],
            $data['email'],
            $data['phone'],
        );
    }

    public function toArray(): array
    {
        return [
            'name'   => $this->name,
            'age'    => $this->age,
            'region' => $this->region,
            'income' => $this->income,
            'score'  => $this->score,
            'pin'    => $this->pin,
            'email'  => $this->email,
            'phone'  => $this->phone,
        ];
    }
}
