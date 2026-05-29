<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Service\Pagination\PagePaginator;
use Doctrine\ORM\QueryBuilder;

class AppQueryBuilder
{
    public function __construct(
        protected QueryBuilder $queryBuilder,
        protected PagePaginator $paginator,
        protected string $alias,
    ) {
    }

    public function where(string $condition): static
    {
        $this->queryBuilder->where($condition);

        return $this;
    }

    public function andWhere(string $condition): static
    {
        $this->queryBuilder->andWhere($condition);

        return $this;
    }

    public function orWhere(string $condition): static
    {
        $this->queryBuilder->orWhere($condition);

        return $this;
    }

    public function setParameter(string $key, mixed $value): static
    {
        $this->queryBuilder->setParameter($key, $value);

        return $this;
    }

    public function orderBy(string $sort, string $order = 'ASC'): static
    {
        $this->queryBuilder->orderBy($sort, $order);

        return $this;
    }

    public function addOrderBy(string $sort, string $order = 'ASC'): static
    {
        $this->queryBuilder->addOrderBy($sort, $order);

        return $this;
    }

    public function orderByDesc(string $sort): static
    {
        return $this->orderBy($sort, 'DESC');
    }

    public function paginate(int $page = 1): array
    {
        return $this->paginator->paginate($this->queryBuilder, $page);
    }

    public function get(): array
    {
        return $this->queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function first(): ?object
    {
        return $this->queryBuilder
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }
}
