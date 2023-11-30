<?php
declare(strict_types=1);

namespace App\Code\Resolver\Mutation;

use Lib\Api\ResolverInterface;

class Hello implements ResolverInterface
{
    /**
     * @inheritDoc
     * @return string
     */
    public function resolve($value, string $fieldName, array $args): string
    {
        return $args['text'];
    }
}
