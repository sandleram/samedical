# Plan — mod-cliente (Cliente)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** B · **ACL key:** `cliente`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\ClienteController
    → Application\Cliente\*UseCase
      → Domain\Cliente\*RepositoryInterface
        → Infrastructure\EloquentClienteRepository
  → Blade admin/cliente/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/Cliente/` |
| Application | `app/Application/Cliente/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentClienteRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/ClienteController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/ClienteController.php` (deprecated) |
| Views | `resources/views/admin/cliente/*` (reutilizadas) |

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