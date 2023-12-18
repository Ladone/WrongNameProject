<?php

declare(strict_types=1);

namespace App\Models;

class Config
{
    public const PATH_TO_CONFIG = __DIR__ . '/../Config/config.php';

    private static Config $instance;
    private array $config = [];

    protected function __construct()
    {
        $this->initConfig();
    }

    protected function __clone()
    {

    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function initConfig(): self
    {
        if (file_exists(self::PATH_TO_CONFIG)) {
            $this->config = require self::PATH_TO_CONFIG;
        }

        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getKey(string $key): mixed
    {
        return (isset($this->getConfig()[$key])) ? $this->getConfig()[$key] : null;
    }
}
