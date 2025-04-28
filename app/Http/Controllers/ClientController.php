<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Domain\Client;

class ClientController extends Controller
{
    protected ClientRepository $clients;

    public function __construct(ClientRepository $clients)
    {
        $this->clients = $clients;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string',
            'age'    => 'required|integer',
            'region' => 'required|string|in:PR,BR,OS',
            'income' => 'required|numeric',
            'score'  => 'required|integer',
            'pin'    => 'required|string',
            'email'  => 'required|email',
            'phone'  => 'required|string',
        ]);
        $client = $this->clients->create($validated);

        return response()->json([
            'status' => 'success',
            'client' => $client,
        ], 201);
    }
}
