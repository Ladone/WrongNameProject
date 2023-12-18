<?php

declare(strict_types=1);

namespace App\Models\Providers;

use App\Models\AbstractProvider;
use App\Models\Client;
use Exception;

class BinListProvider extends AbstractProvider implements BinInterface
{
    protected string $link = 'lookup.binlist.net/';

    public function __construct(private int $bin)
    {
        parent::__construct();
    }

    public function initClient(): AbstractProvider
    {
        $this->setClient(new Client($this->getLink() . $this->getBin(), [
            'IS_HTTPS' => true,
        ],
        ));

        return $this;
    }

    public function getData(array $params = []): array
    {
        $data = $this->getClient()->run();

        try {
            $parsedData = json_decode($data);
            $result = [
                'country' => $parsedData?->country?->alpha2,
                'currency' => $parsedData?->country?->currency,
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
