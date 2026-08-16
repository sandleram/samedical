# Plan — mod-mh_prestador (MH Prestador)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** D · **ACL key:** `mh_prestador`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\MhPrestadorController
    → Application\MhPrestador\*UseCase
      → Domain\MhPrestador\*RepositoryInterface
        → Infrastructure\EloquentMhPrestadorRepository
  → Blade admin/mh_prestador/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/MhPrestador/` |
| Application | `app/Application/MhPrestador/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentMhPrestadorRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/MhPrestadorController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/MhPrestadorController.php` (deprecated) |
| Views | `resources/views/admin/mh_prestador/*` |

## Etapas

- [x] Extrair UseCases + RepositoryInterface do fat controller
- [x] Entity Domain + SearchCriteria
- [x] Eloquent repository (sem TenantScope — dump sem GE/cliente)
- [x] Controller Interfaces + FormRequest
- [x] Rotas apontando para Interfaces
- [x] Bindings em AppServiceProvider
- [x] Docs atualizados

## Riscos

- Soft-delete / log / busca sessão diferidos
- Campos fantasma do `.ctp` (`cnpj`, `tipo_negocio`) omitidos
