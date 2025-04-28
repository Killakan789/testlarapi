<?php

namespace App\Services;

use App\Domain\Client;
use App\Repositories\ClientRepository;

class ClientService
{
    protected ClientRepository $clients;

    public function __construct(ClientRepository $clients)
    {
        $this->clients = $clients;
    }

    /**
     * Create a new client.
     *
     * @param array $data
     * @return Client
     * @throws \Exception if client with the same PIN already exists
     */
    public function create(array $data): Client
    {
        // Prevent duplicate client by PIN
        if ($this->clients->findByPin($data['pin'])) {
            throw new \Exception('Client with this PIN already exists.');
        }

        return $this->clients->create($data);
    }

    /**
     * Find a client by PIN.
     *
     * @param string $pin
     * @return Client|null
     */
    public function findByPin(string $pin): ?Client
    {
        return $this->clients->findByPin($pin);
    }

    /**
     * Get all clients.
     *
     * @return Client[]
     */
    public function all(): array
    {
        return $this->clients->all();
    }
}
