# Plan — mod-modulo (Modulo)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** B · **ACL key:** `modulo`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\ModuloController
    → Application\Modulo\*UseCase
      → Domain\Modulo\*RepositoryInterface
        → Infrastructure\EloquentModuloRepository
  → Blade admin/modulo/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/Modulo/` |
| Application | `app/Application/Modulo/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentModuloRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/ModuloController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/ModuloController.php` (deprecated) |
| Views | `resources/views/admin/modulo/*` (reutilizadas) |

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