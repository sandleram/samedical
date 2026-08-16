<?php

namespace App\Domain\Bi;

final class BiSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $titulo = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
