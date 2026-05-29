<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Server;
use App\Repository\Query\ServerQueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ServerRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Server::class);
    }

    public function query(string $alias = 's'): ServerQueryBuilder
    {
        return new ServerQueryBuilder(
            $this->createQueryBuilder($alias),
            $this->paginator,
            $alias
        );
    }
}
