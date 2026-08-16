# Plan — mod-procedimento (Procedimento)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** C · **ACL key:** `procedimento`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\ProcedimentoController
    → Application\Procedimento\*UseCase
      → Domain\Procedimento\*RepositoryInterface
        → Infrastructure\EloquentProcedimentoRepository
  → Blade admin/procedimento/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/Procedimento/` |
| Application | `app/Application/Procedimento/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentProcedimentoRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/ProcedimentoController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/ProcedimentoController.php` (deprecated) |
| Views | `resources/views/admin/procedimento/*` |

## Etapas

- [x] Extrair UseCases + RepositoryInterface do fat controller
- [x] Entity Domain + SearchCriteria
- [x] Eloquent repository + TenantScope onde aplicável
- [x] Controller Interfaces + FormRequest
- [x] Rotas apontando para Interfaces
- [x] Bindings em AppServiceProvider
- [x] Docs atualizados

## Riscos

- Soft-delete em massa / parity JS legado completa diferidos
- Unit tests com fake de repository (opcional)