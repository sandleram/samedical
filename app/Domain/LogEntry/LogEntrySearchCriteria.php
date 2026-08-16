<?php

namespace App\Domain\LogEntry;

final class LogEntrySearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $log = '',
        public readonly string $description = '',
        public readonly string $dataInicio = '',
        public readonly string $dataFim = '',
        public readonly int $perPage = 30,
        public readonly int $page = 1,
    ) {}
}
