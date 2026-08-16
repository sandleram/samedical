# Tasks — mod-home (Home / Dashboard)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** A · **ACL:** `home`

Checklist operacional. Marque `[x]` conforme conclui.

## Backlog

- [x] Preencher `spec.md` (stub catálogo)
- [x] Preencher `plan.md` (stub catálogo)
- [x] Preencher `review.md` (stub catálogo)
- [ ] Detalhar regras de negócio ao iniciar implementação (Onda A)

## Banco de dados

- [ ] Confirmar tabelas legadas / criar migration se gap
- [ ] Aplicar no ambiente local se houver migration
- [ ] Validar rollback documentado

## Model

- [ ] Criar/alterar `app/Models/` correspondente
- [ ] Relations e scopes de tenant
- [ ] `$fillable` / casts

## Controller

- [ ] Criar/alterar `app/Http/Controllers/Admin/HomeController.php`
- [ ] Middleware auth + tenant + permission (`home`)
- [ ] Actions para cada tela do inventário
- [ ] Flash messages

## Views

- [ ] `resources/views/admin/home/index.blade.php` ← `admin_index.ctp`

## Config / infra

- [ ] Rotas em `routes/web.php` sob `/admin/home`
- [ ] Permissões: confirmar `modulo.controller = home`
- [ ] Jobs/commands — se batch/cron
- [ ] Assets em `public/` — só se o `.ctp` exigir

## Qualidade

- [ ] Smoke manual no `/admin`
- [ ] Testado com perfil root e perfil restrito
- [ ] Passar pelos itens de `review.md`
- [ ] Sem debug/código morto
- [ ] **Não** implementar nesta tarefa de catálogo — só docs

## Entrega

- [ ] PR aberto com link para `docs/features/mod-home/`
- [ ] Review aprovado


