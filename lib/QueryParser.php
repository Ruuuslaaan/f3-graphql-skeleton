<?php
declare(strict_types=1);

namespace Lib;

use JsonException;

class QueryParser
{
    /**
     * @param string $query
     * @return array
     * @throws JsonException
     */
    public static function parse(string $query): array
    {
        $queryArray = json_decode($query, true, 512, JSON_THROW_ON_ERROR);
        return [
            'query' => $queryArray['query'] ?? $queryArray['mutation'] ?? '',
            'variables' => $queryArray['variables'] ?? []
        ];
    }
}
