<?php

namespace App;

use Base;
use DB\SQL;
use Exception;
use GraphQL\GraphQL;
use JsonException;
use Lib\FieldExecutor;
use Lib\QueryParser;
use Lib\SchemaBuilder;

class Bootstrap
{
    /** @var Base */
    private $f3;

    /** @var array */
    private $config;

    /**
     * @return void
     */
    public function run(): void
    {
        $this->initF3()
            ->initRoute()
            ->parseConfig()
            ->initDatabase();

        $this->f3->run();
    }

    /**
     * @param Base $f3
     * @return void
     */
    public function route(Base $f3): void
    {
        try {
            $query = QueryParser::parse($f3->get('BODY'));
            $result = GraphQL::executeQuery(
                SchemaBuilder::build(),
                $query['query'],
                null,
                null,
                $query['variables'],
                null,
                [FieldExecutor::class, 'defaultFieldResolver']
            );

            header('Content-Type: application/json');
            echo json_encode($result, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            echo $exception;
        }
    }

    /**
     * @return self
     */
    private function initF3(): self
    {
        $this->f3 = Base::instance();
        return $this;
    }

    /**
     * @return self
     */
    private function initRoute(): self
    {
        $this->f3->route('GET /graphql', self::class . '->route');
        return $this;
    }

    /**
     * @return self
     */
    private function parseConfig(): self
    {
        try {
            $appPath = 'app';
            $this->config = file_exists($appPath . DIRECTORY_SEPARATOR . 'config.local.json') ?
                json_decode(
                    file_get_contents($appPath . DIRECTORY_SEPARATOR . 'config.local.json'),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                ) : json_decode(
                    file_get_contents($appPath . DIRECTORY_SEPARATOR . 'config.json'),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

            $this->f3->set('CONFIG', $this->config);
        } catch (JsonException $e) {
        }

        return $this;
    }

    /**
     * @return self
     */
    private function initDatabase(): self
    {
        $db = new SQL(
            sprintf(
                'mysql:host=%s;port=%s;dbname=%s',
                $this->config['database']['host'],
                $this->config['database']['port'],
                $this->config['database']['dbname']
            ),
            $this->config['database']['user'],
            $this->config['database']['password']
        );
        $this->f3->set('DB', $db);

        return $this;
    }
}
