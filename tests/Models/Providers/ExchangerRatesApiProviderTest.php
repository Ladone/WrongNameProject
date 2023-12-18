<?php

declare(strict_types=1);


namespace Tests\Models\Providers;

use App\Models\Client;
use App\Models\Providers\ExchangerRatesApiProvider;
use PHPUnit\Framework\TestCase;

class ExchangerRatesApiProviderTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
    }

    public function testGetData(): void
    {
        $this->client->method('run')->willReturn('{"success":true,"timestamp":1702858203,"base":"EUR","date":"2023-12-18","rates":{"AED":4.001885,"AFN":76.528771,"ALL":103.87617}}');
        $exchangeProvider = new ExchangerRatesApiProvider();
        $exchangeProvider->setClient($this->client);
        $data = $exchangeProvider->getData();
        $this->assertIsArray($data);
    }

    public function testEqualData()
    {
        $this->client->method('run')->willReturn('{"success":true,"timestamp":1702858203,"base":"EUR","date":"2023-12-18","rates":{"AED":4.001885,"AFN":76.528771,"ALL":103.87617}}');
        $exchangeProvider = new ExchangerRatesApiProvider();
        $exchangeProvider->setClient($this->client);
        $data = $exchangeProvider->getData();

        foreach (['AED' => 4.001885, 'AFN' => 76.528771, 'ALL' => 103.87617] as $key => $value) {
            $this->assertArrayHasKey($key, $data);
            $this->assertEquals($value, $data[$key]);
        }
    }
}
