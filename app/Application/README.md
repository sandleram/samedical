# Application

UseCases que orquestram o Domain. Um UseCase = uma intenção de negócio (listar, obter, salvar).

- Recebe input tipado (DTO / primitives)
- Chama ports (`*RepositoryInterface`)
- Não conhece HTTP, Blade nem Eloquent
- Tenant/ACL: recebe `TenantScope` / flags do caller; não lê `session()`
