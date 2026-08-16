# Plan — mod-plano (Plano)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** B · **ACL key:** `plano`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\PlanoController
    → Application\Plano\*UseCase
      → Domain\Plano\*RepositoryInterface
        → Infrastructure\EloquentPlanoRepository
  → Blade admin/plano/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/Plano/` |
| Application | `app/Application/Plano/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentPlanoRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/PlanoController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/PlanoController.php` (deprecated) |
| Views | `resources/views/admin/plano/*` (reutilizadas) |

## Etapas

- [x] Extrair UseCases + RepositoryInterface do fat controller
- [x] Entity Domain + SearchCriteria
- [x] Eloquent repository + TenantScope onde aplicável
- [x] Controller Interfaces + FormRequest
- [x] Rotas apontando para Interfaces
- [x] Bindings em AppServiceProvider
- [x] Docs atualizados

## Riscos

- Session (`selecione` / `atualiza_session_cliente`) permanece em Interfaces
- Soft-delete em massa / JS legado diferidos