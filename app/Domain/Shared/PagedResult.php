<?php

namespace App\Domain\Shared;

/**
 * Resultado paginado framework-agnostic.
 *
 * @template T
 */
final class PagedResult
{
    /**
     * @param  list<T>  $items
     */
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $perPage,
        public readonly int $currentPage,
    ) {}
}
