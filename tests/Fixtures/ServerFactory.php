<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Server;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

final class ServerFactory
{
    public static function make(
        string $model = 'Dell R210',
        string $ram = '4GB',
        string $hdd = '1x500GB',
        string $location = 'AMS-01',
        string $price = '49.99',
    ): Server {
        $server = new Server();
        $reflection = new ReflectionClass($server);

        foreach (compact('model', 'ram', 'hdd', 'location', 'price') as $field => $value) {
            $property = $reflection->getProperty($field);
            $property->setValue($server, $value);
        }

        return $server;
    }

    public static function persistMany(EntityManagerInterface $entityManager, array $rows): array
    {
        $servers = [];
        foreach ($rows as $row) {
            $server = self::make(...$row);
            $entityManager->persist($server);
            $servers[] = $server;
        }
        $entityManager->flush();

        return $servers;
    }
}
