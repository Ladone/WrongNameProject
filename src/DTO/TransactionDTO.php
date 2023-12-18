<?php

declare(strict_types=1);

namespace App\DTO;

class TransactionDTO
{
    public function __construct(private ?int $bin, private ?float $amount, private ?string $currency)
    {
    }

    public function setBin(int $bin): self
    {
        $this->bin = $bin;
        return $this;
    }

    public function getBin(): ?int
    {
        return $this->bin;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }
}
