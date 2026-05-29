<?php

declare(strict_types=1);

namespace App\Repository\Query;

final class ServerQueryBuilder extends AppQueryBuilder
{
    public function applyFilters(array $filters): self
    {
        if (!empty($filters['model'])) {
            $this
                ->andWhere(sprintf('LOWER(%s.model) LIKE LOWER(:model)', $this->alias))
                ->setParameter('model', '%' . $filters['model'] . '%');
        }

        if (!empty($filters['location'])) {
            $this
                ->andWhere(sprintf('LOWER(%s.location) LIKE LOWER(:location)', $this->alias))
                ->setParameter('location', '%' . $filters['location'] . '%');
        }

        if (!empty($filters['ram'])) {
            $this
                ->andWhere(sprintf('%s.ram = :ram', $this->alias))
                ->setParameter('ram', $filters['ram']);
        }

        if (!empty($filters['hdd'])) {
            $this
                ->andWhere(sprintf('%s.hdd = :hdd', $this->alias))
                ->setParameter('hdd', $filters['hdd']);
        }

        if (!empty($filters['min_price'])) {
            $this
                ->andWhere(sprintf('%s.price >= :minPrice', $this->alias))
                ->setParameter('minPrice', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $this
                ->andWhere(sprintf('%s.price <= :maxPrice', $this->alias))
                ->setParameter('maxPrice', $filters['max_price']);
        }

        return $this;
    }
}
