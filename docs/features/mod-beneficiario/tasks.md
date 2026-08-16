# Tasks — mod-beneficiario (Beneficiário)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** A · **ACL:** `beneficiario`

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

- [ ] Criar/alterar `app/Http/Controllers/Admin/BeneficiarioController.php`
- [ ] Middleware auth + tenant + permission (`beneficiario`)
- [ ] Actions para cada tela do inventário
- [ ] Flash messages

## Views

- [ ] `resources/views/admin/beneficiario/index.blade.php` ← `admin_index.ctp`
- [ ] `resources/views/admin/beneficiario/view.blade.php` ← `admin_view.ctp`
- [ ] `resources/views/admin/beneficiario/view2.blade.php` ← `admin_view2.ctp`
- [ ] `resources/views/admin/beneficiario/add.blade.php` ← `admin_add.ctp`
- [ ] `resources/views/admin/beneficiario/all.blade.php` ← `admin_all.ctp`
- [ ] `resources/views/admin/beneficiario/timeline_example.blade.php` ← `admin_timeline_example.ctp`

## Config / infra

- [ ] Rotas em `routes/web.php` sob `/admin/beneficiario`
- [ ] Permissões: confirmar `modulo.controller = beneficiario`
- [ ] Jobs/commands — se batch/cron
- [ ] Assets em `public/` — só se o `.ctp` exigir

## Qualidade

- [ ] Smoke manual no `/admin`
- [ ] Testado com perfil root e perfil restrito
- [ ] Passar pelos itens de `review.md`
- [ ] Sem debug/código morto
- [ ] **Não** implementar nesta tarefa de catálogo — só docs

## Entrega

- [ ] PR aberto com link para `docs/features/mod-beneficiario/`
- [ ] Review aprovado


