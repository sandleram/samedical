# Plan — mod-mh_negociacao (MH Negociação)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** D · **ACL key:** `mh_negociacao`
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Refactor Clean/Hexagonal. Controllers finos em Interfaces; Eloquent só em Infrastructure.

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\MhNegociacaoController
    → Application\MhNegociacao\*UseCase
      → Domain\MhNegociacao\*RepositoryInterface
        → Infrastructure\EloquentMhNegociacaoRepository
  → Blade admin/mh_negociacao/*
```

## Arquivos

| Camada | Path |
|--------|------|
| Domain | `app/Domain/MhNegociacao/` |
| Application | `app/Application/MhNegociacao/` |
| Infrastructure | `app/Infrastructure/Persistence/Eloquent/EloquentMhNegociacaoRepository.php` |
| Interfaces | `app/Interfaces/Http/Controllers/Admin/MhNegociacaoController.php` + FormRequest |
| Shim | `app/Http/Controllers/Admin/MhNegociacaoController.php` (deprecated) |
| Views | `resources/views/admin/mh_negociacao/*` |

## Etapas

- [x] Extrair UseCases + RepositoryInterface do fat controller
- [x] Entity Domain + SearchCriteria
- [x] Eloquent repository (sem TenantScope — coluna GE ausente)
- [x] Controller Interfaces + FormRequest
- [x] Rotas apontando para Interfaces
- [x] Bindings em AppServiceProvider
- [x] Docs atualizados

## Riscos

- Soft-delete / log / busca sessão diferidos
- Views legadas com campos fantasmas (`nome`, `data_cancelamento`) — UI usa prestador + `tipo_negocio`
