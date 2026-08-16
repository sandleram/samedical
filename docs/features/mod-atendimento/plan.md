# Plan — mod-atendimento (Atendimento)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** C · **ACL key:** `atendimento`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\AtendimentoController
    → Application\Atendimento\*UseCase
      → Domain\Atendimento\*RepositoryInterface
        → Infrastructure\EloquentAtendimentoRepository
  → Blade admin/atendimento/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/Atendimento/` |
| Application | `app/Application/Atendimento/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentAtendimentoRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/AtendimentoController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/AtendimentoController.php` (deprecated) |
| Views | `resources/views/admin/atendimento/*` |

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