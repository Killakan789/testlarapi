<?php

namespace Tests\Unit;

use App\Domain\Client;
use App\Services\LoanEligibilityService;
use PHPUnit\Framework\TestCase;

class LoanEligibilityServiceTest extends TestCase
{
    public function test_ineligible_due_to_low_score()
    {
        $service = new LoanEligibilityService();
        $client = new Client(
            'Test',
            30,
            'PR',
            1500,
            400, // low score
            '999-99-9999',
            'test@example.com',
            '+420000000000'
        );
        $result = $service->check($client, ['amount' => 1000, 'rate' => 10]);
        $this->assertFalse($result['approved']);
        $this->assertSame('Low credit score', $result['reason']);
    }

    public function test_ostava_increases_rate()
    {
        $service = new LoanEligibilityService();
        $client = new Client(
            'Ostrava',
            30,
            'OS',
            2000,
            700,
            '888-88-8888',
            'ost@example.com',
            '+420888888888'
        );
        $result = $service->check($client, ['amount' => 1000, 'rate' => 10]);
        $this->assertTrue($result['approved']);
        $this->assertEquals(15, $result['rate']);
    }

    public function test_prague_random_denial_runs()
    {
        $service = new LoanEligibilityService();
        $client = new Client(
            'Prague',
            30,
            'PR',
            1500,
            700,
            '777-77-7777',
            'prg@example.com',
            '+420777777777'
        );
        $runs = 0;
        $denials = 0;
        for ($i = 0; $i < 20; $i++) {
            $result = $service->check($client, ['amount' => 1000, 'rate' => 10]);
            $runs++;
            if (!$result['approved'] && $result['reason'] === 'Random denial for Prague') {
                $denials++;
            }
        }
        $this->assertGreaterThan(0, $denials, 'Random denial should sometimes happen.');
    }
}
