<?php

declare(strict_types=1);

namespace App\Models\Providers;

use App\Models\AbstractProvider;
use App\Models\Client;
use App\Models\Config;
use Exception;

class ExchangerRatesApiProvider extends AbstractProvider
{
    protected string $link = 'api.exchangeratesapi.io/v1/latest';

    public function initClient(): AbstractProvider
    {
        $this->setClient(new Client($this->getLink(), [
            CURLOPT_POSTFIELDS => Config::getInstance()->getKey('ExchangerRatesApi')],
        ));

        return $this;
    }

    public function getData(array $params = []): array
    {
        $data = $this->getClient()->run();

        try {
            $parsedData = json_decode($data, true);
            $parsedData = $parsedData['rates'];
        } catch (Exception) {
            $parsedData = [];
        }

        return $parsedData;
    }
}
