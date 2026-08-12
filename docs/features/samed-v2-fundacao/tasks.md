# Tasks — SAMED V2 Fundação

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx

Checklist operacional. Marque `[x]` conforme conclui.

## Backlog

- [x] Preencher `spec.md`
- [x] Preencher `plan.md`
- [x] Preencher `review.md`
- [x] Atualizar `docs/features/_template/` para Laravel
- [x] Atualizar `AGENTS.md` e `README.md`

## Infra

- [x] Scaffold Laravel na raiz
- [x] `docker-compose.yml` (nginx, app, mysql, redis opcional)
- [x] Dockerfile PHP-FPM 8.4
- [x] nginx local + `deploy/nginx/samed.conf`
- [x] `.env.example` sem secrets

## Front

- [x] Copiar assets admin (CSS/JS/img) do legado
- [x] Layout Blade `admin` e `login`
- [x] Partials header/menu

## Auth / Models / Tenant

- [x] Models Eloquent (User→usuario, Perfil, Modulo, PerfilModulo, GrupoEmpresarial, Cliente, Empresa, Beneficiario)
- [x] Auth com campos `usuario` / `senha` (padrão Laravel `User` + `$table`)
- [x] Upgrade MD5 → bcrypt no login
- [x] Middleware tenant
- [x] Middleware permissão de módulo

## Piloto

- [x] `Admin\HomeController`
- [x] `Admin\BeneficiarioController` index/show
- [x] Views Blade correspondentes
- [x] Rotas `/admin/*`

## Qualidade

- [x] Smoke manual Docker
- [x] Passar pelos itens de `review.md`
- [x] Sem debug/código morto
- [x] Feature tests de login

## Entrega

- [x] Critérios de aceite do `spec.md` atendidos
- [ ] PR / branch referenciando `docs/features/samed-v2-fundacao/`
