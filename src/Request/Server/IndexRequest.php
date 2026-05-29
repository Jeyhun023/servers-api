<?php

declare(strict_types=1);

namespace App\Request\Server;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class IndexRequest
{
    /**
     * @param list<string> $ram
     */
    public function __construct(
        public ?string $location = null,

        #[SerializedName('harddisk_type')]
        public ?string $harddiskType = null,

        public array $ram = [],

        #[SerializedName('min_storage')]
        #[Assert\PositiveOrZero]
        public ?int $minStorage = null,

        #[SerializedName('max_storage')]
        #[Assert\PositiveOrZero]
        public ?int $maxStorage = null,

        #[Assert\Positive]
        public int $page = 1,
    ) {
    }

    public function filters(): array
    {
        return [
            'location' => $this->location,
            'harddisk_type' => $this->harddiskType,
            'ram' => $this->ram,
            'min_storage' => $this->minStorage,
            'max_storage' => $this->maxStorage,
        ];
    }
}
