<?php

namespace App\Model\Api\GraphQL;

interface Resolver
{
    /**
     * @param $value
     * @param string $fieldName
     * @param array $args
     * @return mixed
     */
    public function resolve($value, string $fieldName, array $args);
}
