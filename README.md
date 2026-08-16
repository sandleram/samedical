# SAMED V2

Gestão de saúde ocupacional e benefícios corporativos.

Stack: **Laravel 13** · **PHP 8.4** · **MySQL 8** · **Blade** (SmartAdmin/Bootstrap 3) · **Docker** / **nginx**

O código CakePHP 2.x permanece em [`legacy/`](legacy/) como referência.

## Requisitos

- Docker + Docker Compose

## Quickstart (Docker)

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
```

Schema de produção (estrutura): `database/schema/samed_pro_structure.sql`  
Em ambientes que já tinham a migration piloto antiga, use `migrate:fresh` (apaga dados locais).

| Serviço | URL / porta |
|---------|-------------|
| App | http://localhost:8080 |
| MySQL (host) | `localhost:3307` |
| Login seed | `admin` / `admin123` |
| Login MD5 (upgrade) | `legado` / `legado123` |

## Documentação

- Guia do repositório: [`AGENTS.md`](AGENTS.md)
- Templates de feature: [`docs/features/_template/`](docs/features/_template/)

## Produção

Vhost nginx: [`deploy/nginx/samed.conf`](deploy/nginx/samed.conf)  
Document root: `public/`
