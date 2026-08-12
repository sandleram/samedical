# Spec — SAMED V2 Fundação

> Stack: PHP 8.4+ · Laravel (última estável) · MySQL 8 · Blade (SmartAdmin/Bootstrap 3) · Docker / nginx

## Contexto

O SAMED V1 (CakePHP 2.x em `legacy/`) é legado e difícil de evoluir. O V2 reconstrói a aplicação em Laravel, mantendo o domínio de saúde ocupacional/benefícios corporativos e o front admin SmartAdmin já conhecido pelos usuários.

## Objetivo

Entregar a **fundação** do SAMED V2 (infra Docker/nginx, auth, multi-tenant, layouts Blade) e um **piloto** funcional (login, home, beneficiário listagem/detalhe) sobre o schema MySQL existente.

## Escopo

### Inclui

- Scaffold Laravel na raiz do repositório
- Docker Compose local (`nginx`, `php-fpm`, `mysql`, `redis`)
- Config nginx de produção em `deploy/nginx/`
- Portar layouts `admin` e `login` + assets CSS/JS do legado para Blade
- Auth session (`usuario` / `senha`) com upgrade MD5 → bcrypt no login
- ACL por `perfil` / `perfil_modulo` / `modulo` (níveis 0–3)
- Multi-tenant via sessão (`grupo_empresarial_id`, `cliente_id`)
- Piloto: Home + Beneficiario (index/show)
- Documentação: `AGENTS.md`, `README.md`, esta feature e `_template` Laravel

### Não inclui

- Portar todos os módulos (Importacao, BI, MH, Relatorio, Rest, Ws, shells)
- Redesign visual / Bootstrap 4/5 / SPA
- Dual connection `default_pro_robo` (só documentar no roadmap)
- Pipeline de deploy completo (além do vhost nginx versionado)
- Redesign do schema MySQL

## Atores / perfis

Mesmos perfis do legado (IDs preservados):

| Perfil | ID | Escopo típico |
|--------|----|---------------|
| Root | 1 | Tudo (todas contas) |
| Administrador | 2 | Cadastro e atualizações no GE |
| TI | 3 | Cadastro e atualizações no GE |
| Operador | 4 | Visualização (cliente selecionado) |
| Auditoria | 5 | Relatórios (todos clientes) |
| Backoffice | 6 | Relatórios (todos clientes) |
| Cliente | 7 | Gerencial da própria empresa |

- Perfis com acesso ao piloto: todos autenticados com permissão de módulo
- Módulos afetados: `home`, `beneficiario`, `usuario` (login)

## Regras de negócio

1. Schema MySQL legado é a fonte de verdade (tabelas singulares: `usuario`, `beneficiario`, etc.).
2. Login aceita senha MD5 legada e regrava como bcrypt na autenticação bem-sucedida.
3. Listagens de beneficiário respeitam escopo de `grupo_empresarial_id` / `cliente_id` da sessão.
4. Permissão de módulo: nível 0 = negar; ≥1 = visualizar; ≥2 = editar; 3 = full.
5. Assets e classes SmartAdmin/Bootstrap 3 devem permanecer compatíveis com o JS existente.
6. Credenciais e tokens só em `.env` — nunca commitados.

## Fluxo principal

1. Usuário acessa `/` ou `/admin` → tela de login
2. Autentica com `usuario` / `senha`
3. Sessão carrega perfil, permissões e contexto tenant
4. Redirect para `/admin/home`
5. Usuário navega para `/admin/beneficiarios` → listagem filtrada
6. Abre `/admin/beneficiarios/{id}` → detalhe

## Fluxos alternativos / erros

- Credenciais inválidas → mensagem de erro no login
- Sessão expirada → redirect login
- Sem permissão de módulo → 403
- Beneficiário fora do tenant → 404

## Dados

### Models / tabelas envolvidas

| Model | Tabela | Uso |
|-------|--------|-----|
| Usuario | `usuario` | Auth |
| Perfil | `perfil` | Perfil do usuário |
| Modulo | `modulo` | Menu / ACL |
| PerfilModulo | `perfil_modulo` | Nível de acesso |
| GrupoEmpresarial | `grupo_empresarial` | Tenant GE |
| Cliente | `cliente` | Tenant cliente |
| Empresa | `empresa` | Empresa do beneficiário |
| Beneficiario | `beneficiario` | Piloto core |

### Campos novos ou alterados

- Nenhum DDL obrigatório nesta fundação (schema legado).
- `usuario.senha` passa a armazenar bcrypt após primeiro login bem-sucedido no V2 (campo existente).

### Escopo multi-tenant

- Filtrar por `grupo_empresarial_id`? Sim
- Filtrar por `cliente_id`? Sim (quando selecionado na sessão)
- Scopes Eloquent equivalentes ao `$conditionsDefault` do legado

### Integrações

- [ ] Importação de planilha (Excel/CSV)
- [ ] API REST
- [ ] Web service / cron
- [ ] E-mail
- [ ] Geração PDF
- [x] Docker local + nginx produção (infra)

## Interface (admin)

| Action | URL | Descrição |
|--------|-----|-----------|
| login | `/`, `/admin` | Login |
| logout | `/admin/logout` | Logout |
| home | `/admin/home` | Dashboard |
| beneficiarios.index | `/admin/beneficiarios` | Listagem |
| beneficiarios.show | `/admin/beneficiarios/{id}` | Detalhe |

Layout: `resources/views/layouts/admin.blade.php` · Login: `login.blade.php`

## Critérios de aceite

- [x] `docker compose up` sobe app + nginx + mysql localmente
- [x] Login funciona com usuário legado (MD5) e rehash para bcrypt
- [x] Layout admin carrega CSS/JS SmartAdmin sem quebrar shell
- [x] Home autenticada acessível
- [x] Listagem/detalhe de beneficiário respeitam tenant
- [x] Sem secrets no repositório
- [x] `docs/features/samed-v2-fundacao/` e `AGENTS.md` atualizados para Laravel

## Referências

- Legado: `legacy/`
- Plano Cursor: SAMED V2 Fundação
- Layouts legado: `legacy/app/View/Layouts/admin.ctp`, `login.ctp`
- Auth legado: `legacy/app/Controller/AppController.php`
