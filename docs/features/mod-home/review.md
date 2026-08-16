# Review — mod-home (Home / Dashboard)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** A · **ACL key:** `home`

Critérios para code review e QA antes do merge (preencher na implementação).

## Code review — Laravel

- [ ] Implementação cobre o `spec.md`
- [ ] Controllers em `Admin\` com middleware adequado
- [ ] Models Eloquent com `$table`/relations corretos
- [ ] Views Blade no layout `admin`
- [ ] Sem N+1 óbvio; eager load quando necessário
- [ ] Validação via Form Request ou `$request->validate()`

## Code review — SAMED V2

- [ ] Multi-tenant respeitado (`grupo_empresarial_id`, `cliente_id`) conforme spec
- [ ] Permissões `perfil` / `perfil_modulo` / `modulo` — chave `home`
- [ ] UI SmartAdmin/Bootstrap 3 (não introduzir Bootstrap 4/5 sem demanda)
- [ ] Sem secrets no código / commits
- [ ] `legacy/` intacto salvo referência explícita
- [ ] Sem `dd()` / debug esquecido
- [ ] Telas do inventário `.ctp` cobertas ou explicitamente excluídas

## QA / aceite

- [ ] Fluxo principal validado no `/admin/home`
- [ ] Fluxos de erro / borda validados
- [ ] Login com perfil root e perfil restrito
- [ ] Sessão expirada redireciona para login
- [ ] Dados persistidos corretamente no MySQL
- [ ] Listagem, paginação e filtro funcionam (se aplicável)
- [ ] Critérios de aceite do `spec.md` marcados

## Testes

- [ ] Smoke manual
- [ ] `docker compose exec app php artisan test` (se aplicável)

## Checklist do revisor

Comentários / bloqueios:

1. …
2. …

## Resultado

- [ ] Aprovado
- [ ] Aprovado com ressalvas
- [ ] Reprovado — voltar para `tasks.md`


