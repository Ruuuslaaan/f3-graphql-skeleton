<?php
declare(strict_types=1);

namespace App\Code\Resolver\Query;

use Lib\AbstractResolver;

class Hello extends AbstractResolver
{
    /**
     * @inheritDoc
     * @return string
     */
    public function resolve($value, string $fieldName, array $args): string
    {
        return 'Hello';
    }
}
