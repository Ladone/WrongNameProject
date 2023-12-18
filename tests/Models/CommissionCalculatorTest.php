<?php

declare(strict_types=1);

namespace Tests\Models;

use App\DTO\TransactionDTO;
use App\Models\AbstractProvider;
use App\Models\CommissionCalculator;
use App\Models\Providers\BinInterface;
use App\Models\Providers\BinListLocalProvider;
use App\Models\Providers\ExchangerRatesApiProvider;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private BinInterface $binProvider;
    private AbstractProvider $exchangeProvider;
    private TransactionDTO $dto;
    private CommissionCalculator $commissionCalculator;

    public function setUp(): void
    {
        $this->binProvider = $this->createMock(BinListLocalProvider::class);
        $this->exchangeProvider = $this->createMock(ExchangerRatesApiProvider::class);

        $dto = new TransactionDTO(5375412, 40.70, 'UAH');
        $this->commissionCalculator = new CommissionCalculator($dto, $this->binProvider, $this->exchangeProvider);
    }

    public function testCalculateCommission(): void
    {
        $this->binProvider->method('getData')->willReturn(['country' => 'UA']);
        $this->exchangeProvider->method('getData')->willReturn(['UAH' => 40.583221]);
        $commission = $this->commissionCalculator->run();

        $this->assertEquals(0.02, $commission);
    }

    public function testAnotherCountryButInUAH(): void
    {
        $this->binProvider->method('getData')->willReturn(['country' => 'LT']);
        $this->exchangeProvider->method('getData')->willReturn(['UAH' => 40.583221]);
        $commission = $this->commissionCalculator->run();

        $this->assertEquals(0.01, $commission);
    }
}
