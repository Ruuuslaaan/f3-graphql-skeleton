<?php
declare(strict_types=1);

namespace App;

use Base;
use Doctrine\DBAL\Exception as DoctrineDbalException;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Exception;
use GraphQL\GraphQL;
use Lib\ConfigProvider;
use Lib\FieldExecutor;
use Lib\QueryParser;
use Lib\SchemaBuilder;

class Bootstrap
{
    /** @var Base */
    private Base $f3;

    /**
     * @return void
     */
    public function run(): void
    {
        try {
            $this->initF3()
                ->initRoute()
                ->parseConfig()
                ->initDatabase();

            $this->f3->run();
        } catch (Exception $exception) {
        }
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
        $configParser = ConfigProvider::getInstance();
        $config = $configParser->getConfig();

        $this->f3->set('CONFIG', $config);

        return $this;
    }

    /**
     * @return self
     * @throws DoctrineDbalException
     * @throws MissingMappingDriverImplementation
     */
    private function initDatabase(): self
    {
        $doctrineBootstrap = new DoctrineBootstrap();
        $doctrine = $doctrineBootstrap->init();
        $this->f3->set('DB', $doctrine);

        return $this;
    }
}
