<?php

namespace App;

require_once __DIR__ . '/vendor/autoload.php';

use App\DTO\TransactionDTO;
use App\Models\CommissionCalculator;
use App\Models\DataReader;
use App\Models\Providers\BinListLocalProvider;
use App\Models\Providers\BinListProvider;
use App\Models\Providers\ExchangerRatesApiProvider;

if (isset($argv[1]) && file_exists($argv[1])) {
    foreach (DataReader::readData($argv[1]) as $n => $transaction) {
        if ($transaction !== null) {
            $dto = new TransactionDTO($transaction?->bin, $transaction?->amount, $transaction?->currency);
            $commissionCalculator = new CommissionCalculator($dto, new BinListLocalProvider($dto->getBin()), new ExchangerRatesApiProvider());
            $commission = $commissionCalculator->run();
        } else {
            $commission = 0;
        }

        echo $commission . "\n";
    }
} else {
    echo "File not found.\n";
}
