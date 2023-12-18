<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Providers\BinInterface;
use App\Models\Helper\CommissionHelper;
use Exception;
use App\DTO\TransactionDTO;

class CommissionCalculator
{
    public function __construct(
        private TransactionDTO $transaction,
        private BinInterface $binProvider,
        private AbstractProvider $exchangeProvider
    ){}

    public function getTransaction(): TransactionDTO
    {
        return $this->transaction;
    }

    public function setTransaction(TransactionDTO $transaction): self
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function getBinProvider(): BinInterface
    {
        return $this->binProvider;
    }

    public function getExchangeProvider(): AbstractProvider
    {
        return $this->exchangeProvider;
    }

    public function run(): float
    {
        try {
            $bin = $this->getBinProvider();
            $binData = $bin->getData();
            $rateList = $this->getExchangeProvider();
            $rateListData = $rateList->getData();
            $rate = $rateListData[$this->getTransaction()->getCurrency()] ?? null;

            if ($this->getTransaction()->getCurrency() === 'EUR' || $rate === 0) {
                $amountFixed = $this->getTransaction()->getAmount();
            }

            if ($this->getTransaction()->getCurrency() !== 'EUR' || $rate > 0) {
                $amountFixed = $this->getTransaction()->getAmount() / $rate;
            }

            return round($amountFixed * CommissionHelper::getRate($binData['country']), 2, PHP_ROUND_HALF_EVEN);
        } catch (Exception $e) {
            throw new Exception( "Something went wrong");
        }
    }
}
