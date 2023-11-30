<?php

namespace Lib\Api;

interface ResolverInterface
{
    /**
     * @param mixed $value
     * @param string $fieldName
     * @param array $args
     * @return mixed
     */
    public function resolve(mixed $value, string $fieldName, array $args): mixed;
}
