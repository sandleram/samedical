# Plan — mod-mh_critico_historico (MH Crítico Histórico)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** D · **ACL key:** `mh_critico_historico`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\MhCriticoHistoricoController
    → Application\MhCriticoHistorico\*UseCase
      → Domain\MhCriticoHistorico\*RepositoryInterface
        → Infrastructure\EloquentMhCriticoHistoricoRepository
  → Blade admin/mh_critico_historico/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/MhCriticoHistorico/` |
| Application | `app/Application/MhCriticoHistorico/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentMhCriticoHistoricoRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/MhCriticoHistoricoController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/MhCriticoHistoricoController.php` (deprecated) |
| Views | `resources/views/admin/mh_critico_historico/*` |

## Etapas

- [x] Extrair UseCases + RepositoryInterface do fat controller
- [x] Entity Domain + SearchCriteria (aninhado em `mh_critico_id`)
- [x] Eloquent repository
- [x] Controller Interfaces + FormRequest
- [x] Rotas apontando para Interfaces
- [x] Bindings em AppServiceProvider
- [x] Docs atualizados

## Riscos

- Soft-delete / bulk delete / log diferidos
- Campos `nome`/`opcao` do `.ctp` view (não existem na tabela) — view usa `descricao`
