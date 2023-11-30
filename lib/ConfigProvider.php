<?php
declare(strict_types=1);

namespace Lib;

use JsonException;
use RuntimeException;

final class ConfigProvider
{
    /** @var self|null */
    private static ?self $instance = null;

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        try {
            $config = json_decode(
                file_get_contents($this->getConfigFile()),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            $config = [];
        }

        return $config;
    }

    /**
     * @return string
     */
    private function getConfigFile(): string
    {
        $configPath = 'app' . DIRECTORY_SEPARATOR . 'config.local.json';
        if (!file_exists($configPath)) {
            throw new RuntimeException('The config file does not exist.');
        }

         return $configPath;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
