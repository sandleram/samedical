# Plan — mod-mh_critico (MH Crítico)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** D · **ACL key:** `mh_critico`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\MhCriticoController
    → Application\MhCritico\*UseCase
      → Domain\MhCritico\*RepositoryInterface
        → Infrastructure\EloquentMhCriticoRepository
  → Blade admin/mh_critico/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/MhCritico/` |
| Application | `app/Application/MhCritico/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentMhCriticoRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/MhCriticoController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/MhCriticoController.php` (deprecated) |
| Views | `resources/views/admin/mh_critico/*` |

## Etapas

- [x] Extrair UseCases + RepositoryInterface do fat controller
- [x] Entity Domain + SearchCriteria (principals + subsidiaries)
- [x] Eloquent repository (sem TenantScope — colunas ausentes no dump)
- [x] Controller Interfaces + FormRequest
- [x] Rotas apontando para Interfaces
- [x] Bindings em AppServiceProvider
- [x] Docs atualizados

## Riscos

- Soft-delete (`status=2`) / bulk delete / log diferidos
- Busca em sessão (`admin_busca_unset`) diferida
