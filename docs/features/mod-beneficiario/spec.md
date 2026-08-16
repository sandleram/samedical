# Spec — mod-beneficiario (Beneficiário)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade (SmartAdmin/Bootstrap 3) · Docker / nginx
>
> **Onda de implementação:** A · Grupo: Core operacional

> Stub **rico** (Onda A): inventário completo de `.ctp` e gaps do piloto para o implementador.

## Contexto

Portar o módulo legado `beneficiario` (`BeneficiarioController`) para Laravel admin, preservando comportamento, ACL (`Modulo.controller` = `beneficiario`) e layouts SmartAdmin/Bootstrap 3.

Módulo piloto estendido: listagem, detalhe, cadastro/edição, all, view2/timeline.

## Objetivo

Disponibilizar no `/admin` as mesmas telas e regras do Cake para **Beneficiário**, com middleware auth + tenant + permission alinhados ao AppController.

## Escopo

### Inclui

- Portar views admin listadas em **Escopo / telas**
- Rotas `/admin/beneficiario/…` próximas ao legado
- ACL por action (níveis 1/2/3) com chave `beneficiario` (e chaves finas se houver)
- Tenant conforme tabela abaixo

### Não inclui

- Redesign visual / Bootstrap 4+
- Alterar `legacy/`

### Escopo / telas (legado `admin_*.ctp`)

Inventário em `legacy/app/View/Beneficiario/`:

- `legacy/app/View/Beneficiario/admin_index.ctp`
- `legacy/app/View/Beneficiario/admin_view.ctp`
- `legacy/app/View/Beneficiario/admin_view2.ctp`
- `legacy/app/View/Beneficiario/admin_add.ctp`
- `legacy/app/View/Beneficiario/admin_all.ctp`
- `legacy/app/View/Beneficiario/admin_timeline_example.ctp`

### Ações extras / ACL fina

- all — chave ACL fina `beneficiario/all`
- view2 / timeline


## Gaps do piloto Laravel (estado atual)

- Laravel hoje: `BeneficiarioController` com `index` + `show` apenas; rotas **plurais** `/admin/beneficiarios` e `/admin/beneficiarios/{id}`.
- Views Blade: `admin/beneficiarios/index.blade.php` e `show.blade.php` (piloto, sem parity visual completa).
- Falta: `add`/`edit`, `all`, `view2`, timeline; alinhar paths ao legado (`/admin/beneficiario`, `/admin/beneficiario/view/{id}`, `/admin/beneficiario/add`).
- Tenant: via `cliente_id` → `cliente.grupo_empresarial_id` (scope `forTenant()` já iniciado).
- Preservar filtros, IDs/classes JS do `.ctp` e níveis ACL 1/2/3 por action.

## Atores / perfis

| Perfil | ID | Escopo típico |
|--------|----|---------------|
| Root | 1 | Tudo (todas contas) |
| Administrador | 2 | Cadastro e atualizações no GE |
| TI | 3 | Cadastro e atualizações no GE |
| Operador | 4 | Visualização (cliente selecionado) |
| Auditoria | 5 | Relatórios (todos clientes) |
| Backoffice | 6 | Relatórios (todos clientes) |
| Cliente | 7 | Gerencial da própria empresa |

- Perfis com acesso: conforme `perfil_modulo` para o módulo
- **Módulo / menu (`Modulo.controller`):** `beneficiario` ← chave ACL principal

## Regras de negócio

1. Gate ACL: `index/view` ≥1; `add/edit` ≥2; `delete` ≥3 (espelho AppController).
2. Root (`usuario.id == 1`) bypass com perm=3 em módulos ativos.
3. Respeitar tenant de sessão (`grupo_empresarial_id` / `cliente_id`) quando aplicável.
4. Preservar IDs/classes JS usados pelos `.ctp` ao portar Blade.

## Fluxo principal

1. Usuário autenticado acessa `/admin/beneficiario`
2. Middleware reconstrói permissões e valida nível da action
3. Controller consulta Eloquent (schema legado) e renderiza Blade

## Fluxos alternativos / erros

- Sem permissão (0/ausente) → 403 / redirect
- Sessão expirada → redirect login
- Sem cliente selecionado (quando exigido) → fluxo `grupo_empresarial/selecione`

## Dados

### Models / tabelas envolvidas

| Model | Tabela | Uso |
|-------|--------|-----|
| `Beneficiario` | `beneficiario` | CRUD / consultas do módulo |

### Campos novos ou alterados

- Preferir schema legado; migrations só para gaps

### Escopo multi-tenant

- Filtrar por `grupo_empresarial_id`? Sim
- Filtrar por `cliente_id`? Sim
- Usar scopes Eloquent de tenant? Sim

### Integrações

- [ ] Importação de planilha (Excel/CSV)
- [ ] API REST (`routes/api.php`)
- [ ] Job / Schedule (`app/Console`)
- [ ] E-mail (Laravel Mail)
- [ ] Geração PDF / download

## Interface (admin)

| Action | URL | Descrição |
|--------|-----|-----------|
| `index` | `/admin/beneficiario` | Listagem |
| `show/view` | `/admin/beneficiario/view/{id}` | Detalhe |
| `view2` | `/admin/beneficiario/view2/{id}` | Detalhe alternativo |
| `add/edit` | `/admin/beneficiario/add[/id]` | Cadastro / edição |
| `all` | `/admin/beneficiario/all` | Listagem ampla (ACL fina) |
| `timeline` | `/admin/beneficiario/timeline…` | Exemplo timeline |

Layout: `resources/views/layouts/admin.blade.php`

## Critérios de aceite

- [ ] Cada `.ctp` do inventário (exceto backups) tem Blade/rota equivalente ou justificativa de exclusão
- [ ] ACL com chave `beneficiario` e actions extras documentadas
- [ ] Tenant aplicado conforme spec
- [ ] Parity visual SmartAdmin/Bootstrap 3 suficiente para uso diário
- [ ] Smoke root + perfil restrito no `/admin`

## Referências

- Controller legado: `legacy/app/Controller/BeneficiarioController.php`
- Views legado: `legacy/app/View/Beneficiario/`
- Plano-mestre: módulos por ondas (feature `mod-beneficiario`)


