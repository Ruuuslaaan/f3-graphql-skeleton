<?php
declare(strict_types=1);

namespace Lib;

use GraphQL\Type\Schema;
use GraphQL\Utils\BuildSchema;

class SchemaBuilder
{
    /**
     * @return Schema
     */
    public static function build(): Schema
    {
        $content = '';
        $files = array_merge(glob('app/schema/*.graphql'), glob('app/schema/*/*.graphql'));
        foreach ($files as $file) {
            $content .= file_get_contents($file) . PHP_EOL;
        }

        return BuildSchema::build($content);
    }
}
