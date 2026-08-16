<?php

namespace App\Domain\LogEntry;

use App\Domain\Shared\PagedResult;

interface LogEntryRepositoryInterface
{
    /**
     * @return PagedResult<LogEntry>
     */
    public function search(LogEntrySearchCriteria $criteria): PagedResult;
}
