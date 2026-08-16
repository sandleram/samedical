# Plan — mod-beneficio_previdenciario (Benefício Previdenciário)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** C · **ACL key:** `beneficio_previdenciario`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\BeneficioPrevidenciarioController
    → Application\BeneficioPrevidenciario\*UseCase
      → Domain\BeneficioPrevidenciario\*RepositoryInterface
        → Infrastructure\EloquentBeneficioPrevidenciarioRepository
  → Blade admin/beneficio_previdenciario/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/BeneficioPrevidenciario/` |
| Application | `app/Application/BeneficioPrevidenciario/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentBeneficioPrevidenciarioRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/BeneficioPrevidenciarioController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/BeneficioPrevidenciarioController.php` (deprecated) |
| Views | `resources/views/admin/beneficio_previdenciario/*` |

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