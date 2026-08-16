# Domain

Regras de negócio e contratos. **Sem** imports Laravel (`Illuminate\*`, Facades, Eloquent, `Request`).

- Entidades / DTOs de domínio
- Value Objects (`TenantScope`, critérios de busca)
- `*RepositoryInterface` (ports)

Implementações ficam em `App\Infrastructure`.
