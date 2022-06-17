<?php
declare(strict_types=1);

namespace Lib;

use LogicException;

abstract class AbstractResolver
{
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
