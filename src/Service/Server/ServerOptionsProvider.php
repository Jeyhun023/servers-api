<?php

declare(strict_types=1);

namespace App\Service\Server;

use App\Repository\ServerRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class ServerOptionsProvider
{
    private const CACHE_TTL = 3600;

    private const RAM_VALUES = ['2GB', '4GB', '8GB', '12GB', '16GB', '24GB', '32GB', '48GB', '64GB', '96GB'];

    private const HARDDISK_TYPES = ['SAS', 'SATA', 'SSD'];
    
    private const STORAGE_SLICES = [0, 250, 500, 1000, 2000, 3000, 4000, 8000, 12000, 24000, 48000, 72000];

    public function __construct(
        private readonly ServerRepository $servers,
        private readonly CacheInterface $cache,
    ) {
    }

    /** @return list<string> */
    public function getLocationOptions(): array
    {
        return $this->cache->get('server_options.locations', function (ItemInterface $item): array {
            $item->expiresAfter(self::CACHE_TTL);

            $rows = $this->servers->createQueryBuilder('s')
                ->select('DISTINCT s.location AS location')
                ->orderBy('s.location', 'ASC')
                ->getQuery()
                ->getArrayResult();

            return array_column($rows, 'location');
        });
    }

    /** @return list<string> */
    public function getRamValues(): array
    {
        return self::RAM_VALUES;
    }

    /** @return list<string> */
    public function getHarddiskTypes(): array
    {
        return self::HARDDISK_TYPES;
    }

    /** @return list<int> */
    public function getStorageSlices(): array
    {
        return self::STORAGE_SLICES;
    }
}
