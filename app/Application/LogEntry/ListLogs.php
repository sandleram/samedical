<?php

namespace App\Application\LogEntry;

use App\Domain\LogEntry\LogEntryRepositoryInterface;
use App\Domain\LogEntry\LogEntrySearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListLogs
{
    public function __construct(
        private readonly LogEntryRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\LogEntry\LogEntry>
     */
    public function execute(LogEntrySearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
