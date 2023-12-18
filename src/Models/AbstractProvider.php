<?php

declare(strict_types=1);

namespace App\Models;

use CurlHandle;

abstract class AbstractProvider
{
    public function __construct()
    {
        $this->initClient();
    }

    private Client $client;

    protected string $link;

    public function getLink(): string
    {
        return $this->link;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    abstract public function initClient(): self;

    abstract public function getData(array $params = []): array;
}
