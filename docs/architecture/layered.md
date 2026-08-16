# Arquitetura em camadas

Obrigatório em módulos novos e refactors. Referência: **Beneficiário**.

```
Blade (SmartAdmin / Bootstrap 3)
  → Interfaces (Controller / FormRequest)
    → Application (UseCase)
      → Domain (Entity, VO, Domain Service, Repository interface)
        → Infrastructure (Eloquent, MySQL, APIs, Redis)
```

Dependências: `Interfaces → Application → Domain` · `Infrastructure → Domain`.  
Domain sem Laravel. Controller sem regra de negócio.

## Naming

| Artefato | Padrão |
|----------|--------|
| UseCase | `ListX`, `GetX`, `SaveX`, `PrepareXForm` |
| Port | `App\Domain\{X}\{X}RepositoryInterface` |
| Repo | `App\Infrastructure\Persistence\Eloquent\Eloquent{X}Repository` |
| HTTP | `App\Interfaces\Http\Controllers\Admin\{X}Controller` |

## Tenant / ACL

Middleware `auth` + `modulo:{controller}` nas rotas. `TenantScope` (VO) montado em Interfaces; `where`/`whereHas` só no repo Eloquent.

`perfil_modulo.permissao`: 0 bloqueia · 1 lê · 2 grava · 3 exclui. Root (`usuario.id == 1`) bypass.

## UI

Layout `layouts/admin`. Portar o skeleton do `.ctp`: `#ribbon`, `#widget-grid`, `jarviswidget-color-blue`, `smart-form`. Sem Bootstrap 4/5.

## Anti-padrões

UseCase devolvendo Eloquent · Domain importando Laravel · regra (IMC, CPF, status) no Controller · event bus DDD sem necessidade.
