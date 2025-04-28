<?php

namespace App\Repositories;

use App\Domain\Client;

class ClientRepository
{
    protected static array $clients = [];

    public function create(array $data)
    {
        $client = new Client(
            $data['name'],
            $data['age'],
            $data['region'],
            $data['income'],
            $data['score'],
            $data['pin'],
            $data['email'],
            $data['phone']
        );
        self::$clients[] = $client;
        return $client;
    }

    public function findByPin($pin)
    {
        foreach (self::$clients as $client) {
            if ($client->pin === $pin) {
                return $client;
            }
        }
        return null;
    }

    // (Optionally for tests, reset between tests)
    public static function reset()
    {
        self::$clients = [];
    }
}
