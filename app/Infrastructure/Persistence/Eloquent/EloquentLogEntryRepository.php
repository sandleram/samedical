<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\LogEntry\LogEntry as LogEntryEntity;
use App\Domain\LogEntry\LogEntryRepositoryInterface;
use App\Domain\LogEntry\LogEntrySearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\Log as LogModel;
use DateTimeImmutable;

final class EloquentLogEntryRepository implements LogEntryRepositoryInterface
{
    public function search(LogEntrySearchCriteria $criteria): PagedResult
    {
        $query = LogModel::query()->with('usuario');

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('id', (int) $criteria->id);
        }

        if ($criteria->log !== '') {
            foreach (preg_split('/\s+/', $criteria->log) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('log', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->description !== '') {
            foreach (preg_split('/\s+/', $criteria->description) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('description', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->dataInicio !== '' && $criteria->dataFim === '') {
            $query->where('data_cadastro', '>', $criteria->dataInicio);
        }

        if ($criteria->dataInicio === '' && $criteria->dataFim !== '') {
            $query->where('data_cadastro', '<', $criteria->dataFim);
        }

        if ($criteria->dataInicio !== '' && $criteria->dataFim !== '') {
            $query->whereBetween('data_cadastro', [$criteria->dataInicio, $criteria->dataFim]);
        }

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        $items = array_map(
            fn (LogModel $model) => $this->toEntity($model),
            $paginator->items(),
        );

        return new PagedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    private function toEntity(LogModel $model): LogEntryEntity
    {
        return new LogEntryEntity(
            id: $model->id ? (int) $model->id : null,
            log: $model->log,
            mensagem: $model->mensagem,
            description: $model->description,
            serverDescription: $model->server_description,
            dataCadastro: $this->toImmutable($model->data_cadastro),
            usuarioId: $model->usuario_id !== null ? (int) $model->usuario_id : null,
            usuarioNome: $model->usuario?->nome,
        );
    }

    private function toImmutable(mixed $value): ?DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return new DateTimeImmutable((string) $value);
    }
}
