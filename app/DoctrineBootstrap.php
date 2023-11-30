<?php
declare(strict_types=1);

namespace App;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Lib\ConfigProvider;

class DoctrineBootstrap
{
    /**
     * @return EntityManager
     * @throws Exception
     * @throws MissingMappingDriverImplementation
     */
    public function init(): EntityManager
    {
        $configParser = ConfigProvider::getInstance();
        $appConfig = $configParser->getConfig();
        $appConfig['database']['driver'] = 'pdo_mysql';

        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(['app/code/Entity'], $appConfig['devMode']);
        $connection = DriverManager::getConnection($appConfig['database']);

        return new EntityManager($connection, $ormConfig);
    }
}
