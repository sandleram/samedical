# Plan — SAMED V2 Fundação

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx

## Abordagem

Greenfield Laravel na raiz; `legacy/` só como referência. Reutilizar schema MySQL e front SmartAdmin. Camadas:

```
routes/web.php → Admin\*Controller → Eloquent Model → Blade view
                         ↓
              Middleware (auth, tenant, permission)
                         ↓
              App\Support\Funcoes (helpers sob demanda)
```

## Arquivos a criar ou alterar

| Arquivo | Ação | Descrição |
|---------|------|-----------|
| `composer.json` / app Laravel | criar | Scaffold Laravel |
| `docker-compose.yml` | criar | nginx, app, mysql, redis |
| `docker/` | criar | Dockerfile PHP-FPM, nginx.conf |
| `deploy/nginx/samed.conf` | criar | Vhost produção |
| `.env.example` | criar | Config sem secrets |
| `app/Models/*` | criar | Eloquent mapeando tabelas legadas |
| `app/Http/Controllers/Auth/*` | criar | Login/logout |
| `app/Http/Controllers/Admin/*` | criar | Home, Beneficiario |
| `app/Http/Middleware/*` | criar | Tenant + permissões |
| `resources/views/layouts/*` | criar | admin + login Blade |
| `resources/views/partials/admin/*` | criar | header, menu |
| `public/css/admin`, `public/js/admin` | criar | Assets do legado |
| `AGENTS.md` | alterar | Guia Laravel V2 |
| `docs/features/_template/*` | alterar | Templates Laravel |
| `README.md` | alterar | Quickstart Docker |

## Etapas

### 1. Documentação

- [x] `spec.md` / `plan.md` / `review.md` / `tasks.md`
- [ ] Atualizar `_template` e `AGENTS.md`

### 2. Scaffold + Docker

- [x] `composer create-project laravel/laravel` (via Docker; PHP local < 8.4)
- [ ] `docker-compose.yml` + Dockerfile
- [ ] nginx local e `deploy/nginx/samed.conf`

### 3. Front

- [ ] Copiar assets `legacy/app/webroot/css|js/admin` → `public/`
- [ ] Portar layouts e partials essenciais para Blade

### 4. Auth + Models + Tenant

- [ ] Models Eloquent com `$table` legado
- [ ] Provider Auth custom (campo `usuario`, `senha`)
- [ ] Login MD5 → bcrypt
- [ ] Middleware tenant e permissão de módulo

### 5. Piloto

- [ ] Home dashboard
- [ ] Beneficiario index/show com scopes

### 6. Validação

- [ ] Smoke Docker + checklist `review.md`

## Riscos e dependências

- Helpers Cake nas views exigem adaptação Blade
- Schema precisa bater com dump real do MySQL
- PHP local 7.4 — desenvolvimento via Docker PHP 8.4

## Ordem sugerida de PRs

1. Docs + scaffold + Docker
2. Front + Auth + Models
3. Piloto Home/Beneficiario + smoke
