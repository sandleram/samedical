# Plan — mod-perfil (Perfil)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** B · **ACL key:** `perfil`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\PerfilController
    → Application\Perfil\*UseCase
      → Domain\Perfil\*RepositoryInterface
        → Infrastructure\EloquentPerfilRepository
  → Blade admin/perfil/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/Perfil/` |
| Application | `app/Application/Perfil/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentPerfilRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/PerfilController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/PerfilController.php` (deprecated) |
| Views | `resources/views/admin/perfil/*` (reutilizadas) |

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