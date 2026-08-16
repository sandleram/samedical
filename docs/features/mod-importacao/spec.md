# Spec — mod-importacao (Importação)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade (SmartAdmin/Bootstrap 3) · Docker / nginx
>
> **Onda de implementação:** E · Grupo: Import / BI

## Contexto

Portar o módulo legado `importacao` (`ImportacaoController`) para Laravel admin, preservando comportamento, ACL (`Modulo.controller` = `importacao`) e layouts SmartAdmin/Bootstrap 3.

Fluxo de importação (upload → validação → import). Pode exigir Job/Schedule.

## Objetivo

Disponibilizar no `/admin` as mesmas telas e regras do Cake para **Importação**, com middleware auth + tenant + permission alinhados ao AppController.

## Escopo

### Inclui

- Portar views admin listadas em **Escopo / telas**
- Rotas `/admin/importacao/…` próximas ao legado
- ACL por action (níveis 1/2/3) com chave `importacao` (e chaves finas se houver)
- Tenant conforme tabela abaixo

### Não inclui

- Redesign visual / Bootstrap 4+
- Alterar `legacy/`

### Escopo / telas (legado `admin_*.ctp`)

Inventário em `legacy/app/View/Importacao/`:

- `legacy/app/View/Importacao/admin_index.ctp`
- `legacy/app/View/Importacao/admin_add.ctp`
- `legacy/app/View/Importacao/admin_import.ctp`
- `legacy/app/View/Importacao/admin_validacao.ctp`

### Ações extras / ACL fina

- import
- validacao

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
- **Módulo / menu (`Modulo.controller`):** `importacao` ← chave ACL principal

## Regras de negócio

1. Gate ACL: `index/view` ≥1; `add/edit` ≥2; `delete` ≥3 (espelho AppController).
2. Root (`usuario.id == 1`) bypass com perm=3 em módulos ativos.
3. Respeitar tenant de sessão (`grupo_empresarial_id` / `cliente_id`) quando aplicável.
4. Preservar IDs/classes JS usados pelos `.ctp` ao portar Blade.

## Fluxo principal

1. Usuário autenticado acessa `/admin/importacao`
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
| `Importacao` | `importacao` | CRUD / consultas do módulo |

### Campos novos ou alterados

- Preferir schema legado; migrations só para gaps

### Escopo multi-tenant

- Filtrar por `grupo_empresarial_id`? Sim
- Filtrar por `cliente_id`? Sim
- Usar scopes Eloquent de tenant? Sim

### Integrações

- [x] Importação de planilha (Excel/CSV)
- [ ] API REST (`routes/api.php`)
- [x] Job / Schedule (`app/Console`)
- [ ] E-mail (Laravel Mail)
- [ ] Geração PDF / download

## Interface (admin)

| Action | URL | Descrição |
|--------|-----|-----------|
| `index` | `/admin/importacao` | Listagem |
| `add/edit` | `/admin/importacao/add[/id]` | Cadastro / edição |
| `import` | `/admin/importacao/import` | Importação |
| `validacao` | `/admin/importacao/validacao` | Validação de importação |

Layout: `resources/views/layouts/admin.blade.php`

## Critérios de aceite

- [ ] Cada `.ctp` do inventário (exceto backups) tem Blade/rota equivalente ou justificativa de exclusão
- [ ] ACL com chave `importacao` e actions extras documentadas
- [ ] Tenant aplicado conforme spec
- [ ] Parity visual SmartAdmin/Bootstrap 3 suficiente para uso diário
- [ ] Smoke root + perfil restrito no `/admin`

## Referências

- Controller legado: `legacy/app/Controller/ImportacaoController.php`
- Views legado: `legacy/app/View/Importacao/`
- Plano-mestre: módulos por ondas (feature `mod-importacao`)


