# Plan — mod-beneficio (Benefício)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** C · **ACL key:** `beneficio`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\BeneficioController
    → Application\Beneficio\*UseCase
      → Domain\Beneficio\*RepositoryInterface
        → Infrastructure\EloquentBeneficioRepository
  → Blade admin/beneficio/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/Beneficio/` |
| Application | `app/Application/Beneficio/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentBeneficioRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/BeneficioController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/BeneficioController.php` (deprecated) |
| Views | `resources/views/admin/beneficio/*` |

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