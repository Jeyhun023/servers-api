<?php

declare(strict_types=1);

namespace App\Response\Pagination;

trait PaginationResponseTrait
{
    public static function paginated(array $result): array
    {
        return [
            'data' => static::collection($result['data']),
            'meta' => $result['meta'],
        ];
    }
}
