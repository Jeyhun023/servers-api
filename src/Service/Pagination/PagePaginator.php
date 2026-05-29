<?php

declare(strict_types=1);

namespace App\Service\Pagination;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class PagePaginator
{
    private const DEFAULT_PER_PAGE = 10;

    public function paginate(
        QueryBuilder $queryBuilder,
        int $page = 1,
    ): array {
        $page = max(1, $page);

        $queryBuilder
            ->setFirstResult(($page - 1) * self::DEFAULT_PER_PAGE)
            ->setMaxResults(self::DEFAULT_PER_PAGE);

        $paginator = new Paginator($queryBuilder);
        $total = count($paginator);

        return [
            'data' => iterator_to_array($paginator),
            'meta' => [
                'current_page' => $page,
                'per_page' => self::DEFAULT_PER_PAGE,
                'total' => $total,
                'last_page' => (int) ceil($total / self::DEFAULT_PER_PAGE),
            ],
        ];
    }
}
