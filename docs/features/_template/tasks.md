# Tasks — [Nome da feature]

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> Camadas: ver `docs/architecture/layered.md`

Checklist operacional. Marque `[x]` conforme conclui.

## Backlog

- [ ] Preencher `spec.md`
- [ ] Preencher `plan.md`
- [ ] Preencher `review.md`

## Banco de dados

- [ ] Confirmar tabelas legadas / criar migration se gap
- [ ] Aplicar no ambiente local (`artisan migrate` se houver)
- [ ] Validar rollback documentado

## Domain

- [ ] Entidade/DTO em `app/Domain/{X}/`
- [ ] `*RepositoryInterface` (+ critérios / `TenantScope`)
- [ ] Regras puras no Domain (sem Laravel)

## Application

- [ ] UseCases (`List*`, `Get*`, `Save*`, …)
- [ ] Input DTOs tipados

## Infrastructure

- [ ] `Eloquent*Repository` em `app/Infrastructure/Persistence/Eloquent/`
- [ ] Model Eloquent em `app/Models` (só persistência)
- [ ] Binding no `AppServiceProvider`

## Interfaces (HTTP)

- [ ] Controller fino em `app/Interfaces/Http/Controllers/Admin/`
- [ ] FormRequest em `app/Interfaces/Http/Requests/`
- [ ] Sem regras de negócio no Controller

## Views

- [ ] `resources/views/admin/{resource}/index.blade.php`
- [ ] `create` / `edit` / `show` conforme escopo
- [ ] Partials reutilizados

## Config / infra

- [ ] Rotas em `routes/web.php` → controller Interfaces
- [ ] Middleware auth + tenant + permission
- [ ] Entrada em `modulo` / `perfil_modulo` se novo menu
- [ ] `.env.example` se nova config

## Testes / review

- [ ] Smoke manual das actions
- [ ] Preencher `review.md` (pass/fail)
- [ ] Testes automatizados se existirem no projeto
