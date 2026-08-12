# Tasks — [Nome da feature]

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx

Checklist operacional. Marque `[x]` conforme conclui.

## Backlog

- [ ] Preencher `spec.md`
- [ ] Preencher `plan.md`
- [ ] Preencher `review.md`

## Banco de dados

- [ ] Confirmar tabelas legadas / criar migration se gap
- [ ] Aplicar no ambiente local (`artisan migrate` se houver)
- [ ] Validar rollback documentado

## Model

- [ ] Criar/alterar `app/Models/{Model}.php`
- [ ] Relations e scopes de tenant
- [ ] `$fillable` / casts

## Controller

- [ ] Criar/alterar `app/Http/Controllers/Admin/{Controller}.php`
- [ ] Middleware auth + tenant + permission
- [ ] Actions CRUD necessárias
- [ ] Flash messages

## Views

- [ ] `resources/views/admin/{resource}/index.blade.php`
- [ ] `create` / `edit` / `show` conforme escopo
- [ ] Partials reutilizados

## Config / infra

- [ ] Rotas em `routes/web.php`
- [ ] Permissões: `modulo` + `perfil_modulo`
- [ ] Jobs/commands — se batch/cron
- [ ] Assets em `public/` — se necessário

## Qualidade

- [ ] Smoke manual no `/admin`
- [ ] Testado com perfil root e perfil restrito
- [ ] Passar pelos itens de `review.md`
- [ ] Sem debug/código morto

## Entrega

- [ ] PR aberto com link para `docs/features/{nome}/`
- [ ] Review aprovado
