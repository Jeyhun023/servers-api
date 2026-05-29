<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\Query\AppQueryBuilder;
use App\Service\Pagination\PagePaginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseRepository extends ServiceEntityRepository
{
    protected PagePaginator $paginator;

    public function setPaginator(PagePaginator $paginator): void
    {
        $this->paginator = $paginator;
    }

    public function query(string $alias = 'entity'): AppQueryBuilder
    {
        return new AppQueryBuilder(
            $this->createQueryBuilder($alias),
            $this->paginator,
            $alias
        );
    }
}
