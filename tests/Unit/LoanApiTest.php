<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoanApiTest extends TestCase
{


    public function test_create_client_success()
    {
        $response = $this->postJson('/api/clients', [
            'name' => 'Ivan Ivanov',
            'age' => 30,
            'region' => 'PR',
            'income' => 1200,
            'score' => 700,
            'pin' => '321-54-9876',
            'email' => 'ivan.ivanov@example.com',
            'phone' => '+420111111111',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['status', 'client']);
    }

    public function test_loan_eligibility_check_success()
    {
        // Create client first
        $this->postJson('/api/clients', [
            'name' => 'Olga Novak',
            'age' => 28,
            'region' => 'BR',
            'income' => 1500,
            'score' => 650,
            'pin' => '987-65-4321',
            'email' => 'olga.novak@example.com',
            'phone' => '+420222222222',
        ]);

        $response = $this->postJson('/api/loans/check', [
            'pin' => '987-65-4321',
            'amount' => 1000,
            'rate' => 10,
        ]);

        $response->assertStatus(200)
            ->assertJson(['approved' => true]);
    }

    public function test_loan_issue_and_notification_success()
    {
        // Create client first
        $this->postJson('/api/clients', [
            'name' => 'Pavel Skala',
            'age' => 40,
            'region' => 'OS',
            'income' => 2000,
            'score' => 800,
            'pin' => '456-78-1234',
            'email' => 'pavel.skala@example.com',
            'phone' => '+420333333333',
        ]);

        $response = $this->postJson('/api/loans', [
            'pin' => '456-78-1234',
            'name' => 'Personal Loan',
            'amount' => 2000,
            'rate' => 10,
            'start_date' => '2024-05-01',
            'end_date' => '2025-05-01',
        ]);

        $response->assertStatus(201)
            ->assertJson(['status' => 'approved'])
            ->assertJsonStructure(['status', 'loan']);
    }

    public function test_loan_eligibility_low_score()
    {
        // Create client with low score
        $this->postJson('/api/clients', [
            'name' => 'Low Score',
            'age' => 25,
            'region' => 'BR',
            'income' => 1200,
            'score' => 400,
            'pin' => '111-22-3333',
            'email' => 'low.score@example.com',
            'phone' => '+420444444444',
        ]);

        $response = $this->postJson('/api/loans/check', [
            'pin' => '111-22-3333',
            'amount' => 500,
            'rate' => 10,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'approved' => false,
                'reason' => 'Low credit score'
            ]);
    }
}
