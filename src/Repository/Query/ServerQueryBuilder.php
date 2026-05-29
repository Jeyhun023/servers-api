<?php

declare(strict_types=1);

namespace App\Repository\Query;

final class ServerQueryBuilder extends AppQueryBuilder
{
    public function applyFilters(array $filters): self
    {
        if (!empty($filters['location'])) {
            $this
                ->andWhere(sprintf('%s.location = :location', $this->alias))
                ->setParameter('location', $filters['location']);
        }

        if (!empty($filters['harddisk_type'])) {
            $this
                ->andWhere(sprintf('LOWER(%s.hdd) LIKE LOWER(:harddiskType)', $this->alias))
                ->setParameter('harddiskType', '%' . $filters['harddisk_type'] . '%');
        }

        if (!empty($filters['ram'])) {
            $clauses = [];
            foreach (array_values($filters['ram']) as $i => $value) {
                $param = 'ram' . $i;
                $clauses[] = sprintf('%s.ram LIKE :%s', $this->alias, $param);
                $this->setParameter($param, $value . '%');
            }
            $this->andWhere('(' . implode(' OR ', $clauses) . ')');
        }

        if (!empty($filters['min_storage'])) {
            $this
                ->andWhere(sprintf('%s >= :minStorage', $this->totalStorage()))
                ->setParameter('minStorage', $filters['min_storage']);
        }

        if (!empty($filters['max_storage'])) {
            $this
                ->andWhere(sprintf('%s <= :maxStorage', $this->totalStorage()))
                ->setParameter('maxStorage', $filters['max_storage']);
        }

        return $this;
    }

    private function totalStorage(): string
    {
        $alias = $this->alias;

        return "(
            CASE WHEN {$alias}.hdd LIKE '%x%' THEN SUBSTRING_INDEX({$alias}.hdd, 'x', 1) ELSE 1 END
            *
            CASE
                WHEN {$alias}.hdd LIKE '%TB%' THEN SUBSTRING_INDEX(SUBSTRING_INDEX({$alias}.hdd, 'TB', 1), 'x', -1) * 1000
                WHEN {$alias}.hdd LIKE '%GB%' THEN SUBSTRING_INDEX(SUBSTRING_INDEX({$alias}.hdd, 'GB', 1), 'x', -1)
                ELSE 0
            END
        )";
    }
}
