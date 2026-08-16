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
| Afastados | `Afastado` |
| Absenteísmo | `Absenteismo` |
| Importação | `Importacao` (carga_*/Excel deferred) |
| BI / relatórios | `Bi`, `Relatorio` (Excel/PDF deferred) |
| MH Crítico | `MhCritico*` |
| Cadastros base | `Cliente`, `Empresa`, `GrupoEmpresarial`, … |
| Permissões | `Perfil`, `PerfilModulo`, `Modulo` |

Schema MySQL **legado** (tabelas singulares em português) é reutilizado via Eloquent `$table`.

## Arquitetura Laravel deste projeto

DDD + Hexagonal + Clean. Narrativa: `docs/architecture/layered.md`. Rules: `.cursor/rules/`. Referência: **Beneficiário**.

```
Blade (SmartAdmin / Bootstrap 3)
  → Controller (Interfaces) → UseCase (Application)
    → Domain (Entity, VO, Domain Service, Repository interface)
      → Infrastructure (Eloquent, MySQL, APIs, Redis)
```

`Interfaces → Application → Domain` · `Infrastructure → Domain`. Domain sem Laravel. Controller: request → FormRequest → UseCase → view.

Tenant/ACL: middleware nas rotas; `TenantScope` em Interfaces; filtro no repo — **não** no Domain.

UI: portar o skeleton do `.ctp` (`#ribbon`, `jarviswidget`, `smart-form`). Sem Bootstrap 4/5.

### Autenticação e autorização

- Guard session; model `Usuario`; campos `usuario` / `senha`
- Senhas legadas MD5 são aceitas e **rehasheadas para bcrypt** no login
- Perfis (root=1, admin=2, …) e permissões por módulo (`perfil_modulo.permissao`: 0–3)
- Tenant na sessão: `grupo_empresarial_id`, `cliente_id`

### Banco de dados

- `.env` / `.env.example` — nunca commitar secrets
- Conexão `mysql` padrão; conexão Proativa robô fica no roadmap
- Schema de produção (estrutura): `database/schema/samed_pro_structure.sql`
- Migrations: `2026_08_15_100000_create_samed_pro_tables.php` + views `report_*`
- Preferir tabelas existentes; migrations só para gaps além do dump
- ACL: coluna `perfil_modulo.permissao` (0–3)
- Tenant em beneficiário: via `cliente_id` → `cliente.grupo_empresarial_id` (não há `grupo_empresarial_id` em `beneficiario`)

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

### Ordem (port CakePHP)

1. Analisar módulo Cake
2. Documentar regras **deste** módulo no `spec.md`
3. Definir entidades
4. Definir UseCases
5. Criar testes (fake de repository)
6. Domain → 7. Infrastructure → 8. Controller → 9. View (skeleton `.ctp`) → 10. Validar contra Cake

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
