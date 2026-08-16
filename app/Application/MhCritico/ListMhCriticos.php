<?php

namespace App\Application\MhCritico;

use App\Domain\MhCritico\MhCriticoRepositoryInterface;
use App\Domain\MhCritico\MhCriticoSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListMhCriticos
{
    public function __construct(
        private readonly MhCriticoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{principals: PagedResult<\App\Domain\MhCritico\MhCritico>, rowsSub: array<int, list<\App\Domain\MhCritico\MhCritico>>}
     */
    public function execute(MhCriticoSearchCriteria $criteria): array
    {
        return [
            'principals' => $this->repository->searchPrincipals($criteria),
            'rowsSub' => $this->repository->listSubsidiaries($criteria),
        ];
    }
}
