<?php

declare(strict_types=1);

namespace App\Response;

use App\Entity\Server;
use App\Response\Pagination\PaginationResponseTrait;

final readonly class ServerResponse
{
    use PaginationResponseTrait;

    public static function fromEntity(Server $server): array
    {
        return [
            'id' => $server->getId(),
            'model' => $server->getModel(),
            'ram' => $server->getRam(),
            'hdd' => $server->getHdd(),
            'location' => $server->getLocation(),
            'price' => $server->getPrice(),
        ];
    }

    /**
     * @param iterable<Server> $servers
     */
    public static function collection(iterable $servers): array
    {
        return array_map(
            static function (Server $server): array {
                return self::fromEntity($server);
            },
            $servers
        );
    }
}
