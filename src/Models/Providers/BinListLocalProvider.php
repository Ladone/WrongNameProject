<?php

declare(strict_types=1);

namespace App\Models\Providers;

use App\Models\AbstractProvider;
use Exception;

class BinListLocalProvider extends AbstractProvider implements BinInterface
{
    protected string $link = '';

    public function __construct(private int $bin)
    {
        parent::__construct();
    }

    public function initClient(): AbstractProvider
    {
        return $this;
    }

    public function getData(array $params = []): array
    {
        $data = file_get_contents(__DIR__ . '/../../dist/responses/binlist.json');

        try {
            $parsedData = json_decode($data, true);
            $result = [
                'country' => $parsedData[$this->getBin()]['country']['alpha2'] ?? null,
                'currency' => $parsedData[$this->getBin()]['country']['currency'] ?? null,
            ];
        } catch (Exception) {
            $result = [];
        }
        return $result;
    }

    public function setBin(int $bin): BinInterface
    {
        $this->bin = $bin;

        return $this;
    }

    public function getBin(): int
    {
        return $this->bin;
    }
}
