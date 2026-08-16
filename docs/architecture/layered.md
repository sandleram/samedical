# Arquitetura em camadas (Clean / Hexagonal)

Convenção obrigatória para novos módulos e refactors. Referência canônica: **Beneficiário**.

## Fluxo

```
HTTP Request
  → Interfaces (Controller / FormRequest)
    → Application (UseCase)
      → Domain (Entity / regras / Repository Interface)
        → Infrastructure (Eloquent Repository)
  → Blade / Redirect / JSON
```

Controllers **não** contêm regras de negócio. Eloquent e Facades Laravel ficam **somente** em Infrastructure (e, pontualmente, em Interfaces para response/session).

## Namespaces

| Camada | Namespace | Responsabilidade |
|--------|-----------|------------------|
| Domain | `App\Domain\…` | Entidades, Value Objects, interfaces de repositório, regras puras |
| Application | `App\Application\…` | UseCases orquestrando Domain + ports |
| Infrastructure | `App\Infrastructure\…` | Eloquent, filesystem, HTTP externos, bindings |
| Interfaces | `App\Interfaces\Http\…` | Controllers, FormRequests, presenters HTTP |

PSR-4: `App\` → `app/` (sem alteração no Composer).

## Naming

- UseCase: verbo + substantivo — `ListBeneficiarios`, `GetBeneficiario`, `SaveBeneficiario`
- Interface de repositório: `{Aggregate}RepositoryInterface` em Domain
- Implementação Eloquent: `Eloquent{Aggregate}Repository` em `Infrastructure\Persistence\Eloquent`
- Controller HTTP: `App\Interfaces\Http\Controllers\Admin\{Resource}Controller`

## O que NÃO vai no Domain

- `Illuminate\*`, Facades, `Request`, Eloquent Models
- Sessão, cookies, flash
- ACL / tenant via `session()` ou middleware

## ACL e tenant

| Concern | Onde |
|---------|------|
| Middleware `auth` / `modulo:` | Rotas (`routes/web.php`) |
| Nível de permissão na view | Interfaces (lê `session('permissoes')`) |
| Filtro multi-tenant | Application passa `TenantScope`; Infrastructure aplica no query Eloquent |
| Decorators / policies futuras | Application ou Infrastructure — **nunca** Domain |

`TenantScope` (`App\Domain\Shared\TenantScope`) é um VO puro (`grupoEmpresarialId`, `clienteId`). A implementação Eloquent traduz isso para `where` / `whereHas`.

## Models Eloquent legados

`app/Models\*` permanece como adapter de persistência usado **apenas** por Infrastructure. Novos módulos não devem injetar Models em Controllers ou UseCases.

## Módulos ainda não portados

Controllers em `App\Http\Controllers\Admin\*` são o legado pré-camadas. Ao portar um módulo, seguir Beneficiário: UseCases + repo Domain/Infrastructure + controller em `Interfaces`.

## Anti-padrões (evitar)

- Full DDD event bus / aggregates complexos sem necessidade
- UseCase que devolve Eloquent Model
- Domain importando Laravel
- Regras de IMC, CPF, status, etc. no Controller
