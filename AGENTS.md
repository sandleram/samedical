# AGENTS.md — SAMED V2

Guia para agentes de IA (Cursor e similares) trabalharem neste repositório.

## Visão geral

**SAMED** é uma aplicação web de gestão de saúde ocupacional e benefícios corporativos (afastados, absenteísmo, beneficiários, importações, BI, MH crítico, agendamentos, etc.).

| Item | Valor |
|------|-------|
| Linguagem | PHP **8.4+** |
| Framework | **Laravel** (última estável) |
| Banco | **MySQL 8** |
| Views | **Blade** + SmartAdmin / Bootstrap **3** |
| Local | Docker Compose (nginx + php-fpm + mysql + redis) |
| Produção | nginx + php-fpm (`deploy/nginx/`) |
| Legado | `legacy/` — CakePHP 2.x (somente referência) |

## Estrutura do repositório

```
samedical/
├── app/
│   ├── Domain/               # Entidades, VOs, *RepositoryInterface (sem Laravel)
│   ├── Application/          # UseCases
│   ├── Infrastructure/       # Eloquent repos, adapters
│   ├── Interfaces/           # Http Controllers, FormRequests
│   ├── Http/                 # Legado pré-camadas (migrar gradualmente)
│   ├── Models/               # Eloquent — só Infrastructure
│   └── Support/
├── bootstrap/
├── config/
├── database/                 # migrations (gaps), seeders, factories
├── docker/                   # Dockerfile, nginx/php configs locais
├── deploy/nginx/             # Vhost produção
├── docs/
│   ├── architecture/         # Convenções de camadas (layered.md)
│   ├── features/             # Documentação de features (ler e usar)
│   └── files/                # ⚠️ NÃO LER — uploads, backups, SQL operacionais
├── legacy/                   # CakePHP 2.x — referência; não alterar sem necessidade
├── public/                   # Document root (css/js/admin SmartAdmin)
├── resources/views/          # Blade (layouts, admin, partials)
├── routes/
├── docker-compose.yml
├── AGENTS.md
└── README.md
```

## Domínio e módulos principais

| Área | Model / Controller Admin |
|------|--------------------------|
| Autenticação | `Usuario` / `Auth\LoginController` |
| Dashboard | `HomeController` |
| Beneficiários | `Beneficiario` |
| Afastados | `Afastado` (roadmap) |
| Absenteísmo | `Absenteismo` (roadmap) |
| Importação | `Importacao` (Onda E layered) |
| BI / relatórios | `Bi`, `Relatorio` (Onda E layered; Excel/PDF deferred) |
| MH Crítico | `MhCritico*` (roadmap) |
| Cadastros base | `Cliente`, `Empresa`, `GrupoEmpresarial`, … |
| Permissões | `Perfil`, `PerfilModulo`, `Modulo` |

Schema MySQL **legado** (tabelas singulares em português) é reutilizado via Eloquent `$table`.

## Arquitetura Laravel deste projeto

Camadas **Clean / Hexagonal** (obrigatório em módulos novos e refactors).

| Fonte | Onde |
|-------|------|
| Narrativa / naming | `docs/architecture/layered.md` |
| Regras Cursor (agent) | `.cursor/rules/` — `architecture`, `domain`, `laravel`, `database`, `testing`, `migration-cakephp` |

Referência canônica de código: **Beneficiário**.

```
Request → routes/web.php
       → Interfaces\Http\Controllers (fino: FormRequest + orquestração)
       → Application\*UseCase
       → Domain (entidade / regras / *RepositoryInterface)
       → Infrastructure\Persistence\Eloquent (*Repository)
       → Blade / Redirect
                              ↓
                 Middleware: auth, tenant, permission (rotas)
                              ↓
                 App\Support\Funcoes (helpers pontuais; preferir Domain puro)
```

### Dependências permitidas

```
Interfaces → Application → Domain
Infrastructure → Domain
```

Domain **não** depende de Application, Infrastructure, Interfaces nem Laravel.

### Regras duras das camadas

1. **Nunca** colocar regras de negócio no Controller
2. **Domain** não importa classes Laravel (sem Eloquent, Facades, `Request`, etc.)
3. Interface de repositório em **Domain**; implementação em **Infrastructure**
4. **Eloquent só** em Infrastructure (`app/Models` é adapter de persistência)
5. Controllers finos: validar input (FormRequest em Interfaces), chamar UseCase, devolver view/response
6. Preferir `App\Interfaces\Http\Controllers\…`; adapters em `App\Http\Controllers\Admin\*` só até o módulo ser migrado

Tenant/ACL: middleware nas rotas; `TenantScope` montado na Interfaces e aplicado na Infrastructure — **não** no Domain.

### Prefixo admin

Rotas sob `/admin` com middleware de autenticação e tenant. Controllers migrados: `App\Interfaces\Http\Controllers\Admin`. Demais módulos ainda podem estar em `App\Http\Controllers\Admin` até o port.

### Autenticação e autorização

- Guard session; model `Usuario`; campos `usuario` / `senha`
- Senhas legadas MD5 são aceitas e **rehasheadas para bcrypt** no login
- Perfis (root=1, admin=2, …) e permissões por módulo (`perfil_modulo.nivel`: 0–3)
- Tenant na sessão: `grupo_empresarial_id`, `cliente_id`

### Banco de dados

- `.env` / `.env.example` — nunca commitar secrets
- Conexão `mysql` padrão; conexão Proativa robô fica no roadmap
- Schema de produção (estrutura): `database/schema/samed_pro_structure.sql`
- Migrations: `2026_08_15_100000_create_samed_pro_tables.php` + views `report_*`
- Preferir tabelas existentes; migrations só para gaps além do dump
- ACL: coluna `perfil_modulo.permissao` (0–3)
- Tenant em beneficiário: via `cliente_id` → `cliente.grupo_empresarial_id` (não há `grupo_empresarial_id` em `beneficiario`)

### Front admin

- Layout: `resources/views/layouts/admin.blade.php`
- Assets: `public/css/admin/`, `public/js/admin/`
- **Não** introduzir Bootstrap 4/5 sem demanda explícita
- Portar HTML/JS do legado preservando IDs/classes usados pelo JS

## Restrição para agentes de IA

| Pasta | Ação |
|-------|------|
| `docs/features/` | **Ler e usar** |
| `docs/files/` | **Não ler** |
| `legacy/` | Ler como referência; não alterar sem pedido explícito |

## Fluxo de trabalho com IA — Features

```
docs/features/{nome-da-feature}/
├── spec.md
├── plan.md
├── review.md
└── tasks.md
```

Template: `docs/features/_template/`.

### Ordem recomendada

1. Ler este `AGENTS.md`
2. Consultar `legacy/` se for portar comportamento
3. Preencher `spec.md` → `plan.md`
4. Implementar (diff mínimo)
5. Atualizar `tasks.md` e validar `review.md`
6. Smoke via Docker

### Ao receber uma demanda

- [ ] Qual controller/rota `admin`? (`Interfaces\Http` para módulos migrados)
- [ ] Quais UseCases + `*RepositoryInterface` + Eloquent repo?
- [ ] Quais models/tabelas MySQL?
- [ ] Impacto em `perfil_modulo`?
- [ ] Escopo `grupo_empresarial_id` / `cliente_id` (`TenantScope`)?
- [ ] Migration necessária ou só schema legado?
- [ ] API / Job / Schedule?

## Ambiente local

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate
# Importar dump MySQL legado no serviço mysql, se necessário
```

URL típica: `http://localhost:8080/`

## Produção

- Document root: `public/`
- Vhost de referência: `deploy/nginx/samed.conf`
- PHP-FPM 8.4+

## Commits e PRs

- Mensagens descritivas (foco no **porquê**)
- PR referencia `docs/features/{feature}/`
- Não incluir `.env`, uploads, backups ou credenciais

---

Quando o usuário passar a demanda, comece pelo **`spec.md`** da feature e só então implemente.
