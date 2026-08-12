# Review — SAMED V2 Fundação

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx

Critérios para code review e QA antes do merge.

## Code review — Laravel MVC

- [x] Implementação cobre o `spec.md` (escopo e regras)
- [x] Controllers finos; lógica reutilizável em Services/Support quando necessário
- [x] Models Eloquent com `$table` correto (schema legado)
- [x] Views Blade (não `.ctp`)
- [x] Rotas em `routes/web.php` com prefixo `admin` e middleware
- [x] Form Requests / validação onde aplicável (`LoginRequest`)
- [x] Sem N+1 óbvio nas listagens (eager load)

## Code review — SAMED V2

- [x] Filtro multi-tenant: `grupo_empresarial_id`, `cliente_id` respeitados
- [x] Permissões via `perfil` / `perfil_modulo` / `modulo`
- [x] Login MD5 legado + rehash bcrypt
- [x] Layouts SmartAdmin/Bootstrap 3 preservados (sem Bootstrap 4/5)
- [x] Assets em `public/css/admin` e `public/js/admin`
- [x] Sem secrets: `.env` não commitado; `.env.example` sem senhas reais
- [x] `legacy/` não alterado sem necessidade
- [x] Docker Compose sobe app localmente
- [x] nginx produção em `deploy/nginx/`
- [x] Sem `dd()`, `dump()`, credenciais hardcoded

## QA / aceite

- [x] `docker compose up -d` sobe nginx + app + mysql
- [x] Login com usuário legado / seed (`admin` / `legado`)
- [x] Home autenticada
- [x] Listagem/detalhe beneficiário com tenant
- [x] Logout e sessão expirada redirecionam login
- [x] UI ok em desktop (layout admin)
- [x] Critérios de aceite do `spec.md` marcados

## Testes

- [x] Smoke manual dos critérios de aceite
- [x] Feature tests Laravel passam:

```bash
docker compose exec app php artisan test --filter=AuthLoginTest
```

## Checklist do revisor

Comentários / bloqueios:

1. —

## Resultado

- [x] Aprovado
- [ ] Aprovado com ressalvas
- [ ] Reprovado — voltar para `tasks.md`
