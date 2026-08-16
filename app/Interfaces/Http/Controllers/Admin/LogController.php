<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\LogEntry\ListLogs;
use App\Domain\LogEntry\LogEntry;
use App\Domain\LogEntry\LogEntrySearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class LogController extends Controller
{
    public function __construct(
        private readonly ListLogs $listLogs,
    ) {}

    public function index(Request $request): View
    {
        $search = [
            'id_' => trim((string) $request->query('id_', '')),
            'log' => trim((string) $request->query('log', '')),
            'description' => trim((string) $request->query('description', '')),
            'data_inicio' => trim((string) $request->query('data_inicio', '')),
            'data_fim' => trim((string) $request->query('data_fim', '')),
        ];

        $criteria = new LogEntrySearchCriteria(
            id: $search['id_'],
            log: $search['log'],
            description: $search['description'],
            dataInicio: $search['data_inicio'],
            dataFim: $search['data_fim'],
            perPage: 30,
            page: max(1, (int) $request->query('page', 1)),
        );

        $result = $this->listLogs->execute($criteria);

        return view('admin.log.index', [
            'title' => 'Logs',
            'rows' => $this->toPaginator($result, $request),
            'search' => $search,
            'permissao' => $this->currentPermissionLevel('log'),
        ]);
    }

    /**
     * @param  PagedResult<LogEntry>  $result
     * @return LengthAwarePaginator<int, LogEntry>
     */
    private function toPaginator(PagedResult $result, Request $request): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            items: $result->items,
            total: $result->total,
            perPage: $result->perPage,
            currentPage: $result->currentPage,
            options: [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );
    }

    private function currentPermissionLevel(string $module): int
    {
        $permissions = session('permissoes', []);
        $entry = $permissions[$module] ?? null;
        if (is_array($entry)) {
            return (int) ($entry['permissao'] ?? 0);
        }

        return (int) ($entry ?? 0);
    }
}
