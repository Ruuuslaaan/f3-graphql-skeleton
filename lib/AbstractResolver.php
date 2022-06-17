<?php
declare(strict_types=1);

namespace Lib;

use Base;
use DB\SQL;
use LogicException;
use NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder;

abstract class AbstractResolver
{
    /** @var SQL */
    protected $database;

    /** @var GenericBuilder */
    protected $queryBuilder;

    public function __construct()
    {
        $this->database = Base::instance()->get('DB');
        $this->queryBuilder = new GenericBuilder();
    }

    /**
     * @param mixed $value
     * @param string $fieldName
     * @param array $args
     * @return mixed
     */
    public function resolve($value, string $fieldName, array $args)
    {
        throw new LogicException('You must override the resolve() method in the concrete resolver class.');
    }
}
