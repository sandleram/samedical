# Plan — mod-tipo_beneficio (Tipo de Benefício)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** C · **ACL key:** `tipo_beneficio`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\TipoBeneficioController
    → Application\TipoBeneficio\*UseCase
      → Domain\TipoBeneficio\*RepositoryInterface
        → Infrastructure\EloquentTipoBeneficioRepository
  → Blade admin/tipo_beneficio/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/TipoBeneficio/` |
| Application | `app/Application/TipoBeneficio/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentTipoBeneficioRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/TipoBeneficioController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/TipoBeneficioController.php` (deprecated) |
| Views | `resources/views/admin/tipo_beneficio/*` |

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