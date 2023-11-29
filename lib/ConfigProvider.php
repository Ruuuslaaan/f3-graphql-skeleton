<?php
declare(strict_types=1);

namespace Lib;

use JsonException;

final class ConfigProvider
{
    /** @var self */
    private static $instance;

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
        $appPath = 'app';
        return file_exists($appPath . DIRECTORY_SEPARATOR . 'config.local.json') ?
            $appPath . DIRECTORY_SEPARATOR . 'config.local.json' :
            $appPath . DIRECTORY_SEPARATOR . 'config.json';
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
