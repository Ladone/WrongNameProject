<?php

declare(strict_types=1);

namespace App\Models;

use CurlHandle;

class Client
{
    private CurlHandle $curl;

    public function __construct(
        private string $url,
        private array $options = [],
        private array $headers = []
    )
    {
        $this->curl = curl_init();
        $this->buildQuery()
            ->initHeaders($this->getHeaders());
    }

    public function run(): string
    {
        ob_start();
        curl_exec($this->getCurl());

        $response = ob_get_clean();
        return $response !== false ? $response : '';
    }

    public function getCurl(): CurlHandle
    {
        return $this->curl;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function addOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getTypeProtocol(): string
    {
        return !empty($this->getOptions()['IS_HTTPS']) ? 'https' : 'http';
    }

    public function isPost(): bool
    {
        return !empty($this->options[CURLOPT_POST]);
    }

    private function initHeaders(array $headers): self
    {
        curl_setopt($this->getCurl(), CURLOPT_HEADER, $headers);

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    private function buildQuery(): self
    {
        try {
            if($this->isPost()) {
                curl_setopt($this->getCurl(), CURLOPT_URL, "{$this->getTypeProtocol()}://{$this->getUrl()}");
            } else {
                $fields = http_build_query($this->getOptions()[CURLOPT_POSTFIELDS] ?? []);
                curl_setopt($this->getCurl(), CURLOPT_URL, "{$this->getTypeProtocol()}://{$this->getUrl()}?{$fields}");
            }
        } catch (\Throwable $e) {
            $this->setUrl('');
        }

        return $this;
    }
}
