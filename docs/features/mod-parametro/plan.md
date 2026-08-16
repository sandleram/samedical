# Plan — mod-parametro (Parametro)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** B · **ACL key:** `parametro`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\ParametroController
    → Application\Parametro\*UseCase
      → Domain\Parametro\*RepositoryInterface
        → Infrastructure\EloquentParametroRepository
  → Blade admin/parametro/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/Parametro/` |
| Application | `app/Application/Parametro/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentParametroRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/ParametroController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/ParametroController.php` (deprecated) |
| Views | `resources/views/admin/parametro/*` (reutilizadas) |

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