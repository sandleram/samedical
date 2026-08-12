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
├── app/                      # Código Laravel (Http, Models, Support…)
├── bootstrap/
├── config/
├── database/                 # migrations (gaps), seeders, factories
├── docker/                   # Dockerfile, nginx/php configs locais
├── deploy/nginx/             # Vhost produção
├── docs/
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
| Importação | `Importacao` (roadmap) |
| BI / relatórios | `Bi`, `Relatorio` (roadmap) |
| MH Crítico | `MhCritico*` (roadmap) |
| Cadastros base | `Cliente`, `Empresa`, `GrupoEmpresarial`, … |
| Permissões | `Perfil`, `PerfilModulo`, `Modulo` |

Schema MySQL **legado** (tabelas singulares em português) é reutilizado via Eloquent `$table`.

## Arquitetura Laravel deste projeto

```
Request → routes/web.php → Admin\*Controller → Eloquent → Blade
                              ↓
                 Middleware: auth, tenant, permission
                              ↓
                 App\Support\Funcoes (helpers sob demanda)
```

### Prefixo admin

Rotas sob `/admin` com middleware de autenticação e tenant. Controllers em `App\Http\Controllers\Admin`.

### Autenticação e autorização

- Guard session; model `Usuario`; campos `usuario` / `senha`
- Senhas legadas MD5 são aceitas e **rehasheadas para bcrypt** no login
- Perfis (root=1, admin=2, …) e permissões por módulo (`perfil_modulo.nivel`: 0–3)
- Tenant na sessão: `grupo_empresarial_id`, `cliente_id`

### Banco de dados

- `.env` / `.env.example` — nunca commitar secrets
- Conexão `mysql` padrão; conexão Proativa robô fica no roadmap
- Preferir tabelas existentes; migrations só para gaps

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

- [ ] Qual controller/rota `admin`?
- [ ] Quais models/tabelas MySQL?
- [ ] Impacto em `perfil_modulo`?
- [ ] Escopo `grupo_empresarial_id` / `cliente_id`?
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
