# Spec — mod-mh_negociacao (MH Negociação)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade (SmartAdmin/Bootstrap 3) · Docker / nginx
>
> **Onda de implementação:** D · Grupo: MH

## Contexto

Portar o módulo legado `mh_negociacao` (`MhNegociacaoController`) para Laravel admin, preservando comportamento, ACL (`Modulo.controller` = `mh_negociacao`) e layouts SmartAdmin/Bootstrap 3.

Negociações relacionadas a MH.

## Objetivo

Disponibilizar no `/admin` as mesmas telas e regras do Cake para **MH Negociação**, com middleware auth + tenant + permission alinhados ao AppController.

## Escopo

### Inclui

- Portar views admin listadas em **Escopo / telas**
- Rotas `/admin/mh_negociacao/…` próximas ao legado
- ACL por action (níveis 1/2/3) com chave `mh_negociacao` (e chaves finas se houver)
- Tenant conforme tabela abaixo

### Não inclui

- Redesign visual / Bootstrap 4+
- Alterar `legacy/`

### Escopo / telas (legado `admin_*.ctp`)

Inventário em `legacy/app/View/MhNegociacao/`:

- `legacy/app/View/MhNegociacao/admin_index.ctp`
- `legacy/app/View/MhNegociacao/admin_view.ctp`
- `legacy/app/View/MhNegociacao/admin_add.ctp`
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
- **Módulo / menu (`Modulo.controller`):** `mh_negociacao` ← chave ACL principal

## Regras de negócio

1. Gate ACL: `index/view` ≥1; `add/edit` ≥2; `delete` ≥3 (espelho AppController).
2. Root (`usuario.id == 1`) bypass com perm=3 em módulos ativos.
3. Respeitar tenant de sessão (`grupo_empresarial_id` / `cliente_id`) quando aplicável.
4. Preservar IDs/classes JS usados pelos `.ctp` ao portar Blade.

## Fluxo principal

1. Usuário autenticado acessa `/admin/mh_negociacao`
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
| `MhNegociacao` | `mh_negociacao` | CRUD / consultas do módulo |

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
| `index` | `/admin/mh_negociacao` | Listagem |
| `show/view` | `/admin/mh_negociacao/view/{id}` | Detalhe |
| `add/edit` | `/admin/mh_negociacao/add[/id]` | Cadastro / edição |

Layout: `resources/views/layouts/admin.blade.php`

## Critérios de aceite

- [ ] Cada `.ctp` do inventário (exceto backups) tem Blade/rota equivalente ou justificativa de exclusão
- [ ] ACL com chave `mh_negociacao` e actions extras documentadas
- [ ] Tenant aplicado conforme spec
- [ ] Parity visual SmartAdmin/Bootstrap 3 suficiente para uso diário
- [ ] Smoke root + perfil restrito no `/admin`

## Referências

- Controller legado: `legacy/app/Controller/MhNegociacaoController.php`
- Views legado: `legacy/app/View/MhNegociacao/`
- Plano-mestre: módulos por ondas (feature `mod-mh_negociacao`)


