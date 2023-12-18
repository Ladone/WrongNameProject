<?php

namespace App\Models\Providers;

interface BinInterface
{
    public function setBin(int $bin): self;
    public function getBin(): int;
}