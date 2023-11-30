<?php
declare(strict_types=1);

namespace Lib;

use Closure;
use GraphQL\Executor\Executor;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ResolveInfo;

class FieldExecutor extends Executor
{
    /**
     * @inheritDoc
     */
    public static function defaultFieldResolver($objectValue, $args, $contextValue, ResolveInfo $info)
    {
        $fieldName = $info->fieldName;

        if (($resolverClass = (new self)->getFieldResolver($info->fieldDefinition)) && !empty($resolverClass)) {
            /** @var AbstractResolver $resolver */
            $resolver = new $resolverClass();
            $property = $resolver->resolve($objectValue, $fieldName, $args);

            return $property instanceof Closure
                ? $property($objectValue, $args, $contextValue, $info)
                : $property;
        }

        if (is_object($objectValue)) {
            $methodName = 'get' . str_replace('_', '', $fieldName);

            if (method_exists($objectValue, $methodName)) {
                $property = $objectValue->$methodName();

                return $property instanceof Closure
                    ? $property($objectValue, $args, $contextValue, $info)
                    : $property;
            }
        }

        return parent::defaultFieldResolver($objectValue, $args, $contextValue, $info);
    }

    /**
     * @param FieldDefinition $fieldDefinition
     * @return string
     */
    private function getFieldResolver(FieldDefinition $fieldDefinition): string
    {
        $directives = $fieldDefinition->astNode->directives;
        foreach ($directives as $directive) {
            if ($directive->name->value === 'resolver') {
                foreach ($directive->arguments as $directiveArgument) {
                    if ($directiveArgument->name->value === 'class') {
                        return $directiveArgument->value->value;
                    }
                }
            }
        }

        return '';
    }
}
