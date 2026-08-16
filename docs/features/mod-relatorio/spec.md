# Spec — mod-relatorio (Relatório)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade (SmartAdmin/Bootstrap 3) · Docker / nginx
>
> **Onda de implementação:** E · Grupo: Import / BI / Relatório

## Contexto

Portar o módulo legado `relatorio` (`RelatorioController`) para Laravel admin, preservando comportamento, ACL (`Modulo.controller` = `relatorio`) e layouts SmartAdmin/Bootstrap 3.

Relatórios operacionais e downloads; views report_* no MySQL podem ser usadas.

## Objetivo

Disponibilizar no `/admin` as mesmas telas e regras do Cake para **Relatório**, com middleware auth + tenant + permission alinhados ao AppController.

## Escopo

### Inclui

- Portar views admin listadas em **Escopo / telas**
- Rotas `/admin/relatorio/…` próximas ao legado
- ACL por action (níveis 1/2/3) com chave `relatorio` (e chaves finas se houver)
- Tenant conforme tabela abaixo

### Não inclui

- Redesign visual / Bootstrap 4+
- Alterar `legacy/`

### Escopo / telas (legado `admin_*.ctp`)

Inventário em `legacy/app/View/Relatorio/`:

- `legacy/app/View/Relatorio/admin_index.ctp`
- `legacy/app/View/Relatorio/admin_afastados.ctp`
- `legacy/app/View/Relatorio/admin_atendimentos_pendentes.ctp`
- `legacy/app/View/Relatorio/admin_beneficiarios.ctp`
- `legacy/app/View/Relatorio/admin_exportacao.ctp`
- `legacy/app/View/Relatorio/admin_fatura.ctp`
- `legacy/app/View/Relatorio/admin_fatura_down.ctp`
- `legacy/app/View/Relatorio/admin_gerencial.ctp`
- `legacy/app/View/Relatorio/admin_movimentacao_beneficiario.ctp`
- `legacy/app/View/Relatorio/admin_movimentacao_beneficiario_down.ctp`
- `legacy/app/View/Relatorio/admin_movimentacao_fatura.ctp`
- `legacy/app/View/Relatorio/admin_movimentacao_fatura_down.ctp`
- `legacy/app/View/Relatorio/admin_movimentacao_sinistro.ctp`
- `legacy/app/View/Relatorio/admin_movimentacao_sinistro_down.ctp`
- `legacy/app/View/Relatorio/admin_sinistro.ctp`
- `legacy/app/View/Relatorio/admin_sinistro_down.ctp`

### Ações extras / ACL fina

- várias telas + downloads `*_down`

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
- **Módulo / menu (`Modulo.controller`):** `relatorio` ← chave ACL principal

## Regras de negócio

1. Gate ACL: `index/view` ≥1; `add/edit` ≥2; `delete` ≥3 (espelho AppController).
2. Root (`usuario.id == 1`) bypass com perm=3 em módulos ativos.
3. Respeitar tenant de sessão (`grupo_empresarial_id` / `cliente_id`) quando aplicável.
4. Preservar IDs/classes JS usados pelos `.ctp` ao portar Blade.

## Fluxo principal

1. Usuário autenticado acessa `/admin/relatorio`
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
| — | — | Sem model principal (UI agregada / API / utilitário) |

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
- [x] Geração PDF / download

## Interface (admin)

| Action | URL | Descrição |
|--------|-----|-----------|
| `index` | `/admin/relatorio` | Listagem |
| `afastados` | `/admin/relatorio/afastados` | Tela/relatório legado `admin_afastados.ctp` |
| `atendimentos_pendentes` | `/admin/relatorio/atendimentos_pendentes` | Tela/relatório legado `admin_atendimentos_pendentes.ctp` |
| `beneficiarios` | `/admin/relatorio/beneficiarios` | Tela/relatório legado `admin_beneficiarios.ctp` |
| `exportacao` | `/admin/relatorio/exportacao` | Tela/relatório legado `admin_exportacao.ctp` |
| `fatura` | `/admin/relatorio/fatura` | Tela/relatório legado `admin_fatura.ctp` |
| `fatura_down` | `/admin/relatorio/fatura_down` | Tela/relatório legado `admin_fatura_down.ctp` (download) |
| `gerencial` | `/admin/relatorio/gerencial` | Painel gerencial |
| `movimentacao_beneficiario` | `/admin/relatorio/movimentacao_beneficiario` | Tela/relatório legado `admin_movimentacao_beneficiario.ctp` |
| `movimentacao_beneficiario_down` | `/admin/relatorio/movimentacao_beneficiario_down` | Tela/relatório legado `admin_movimentacao_beneficiario_down.ctp` (download) |
| `movimentacao_fatura` | `/admin/relatorio/movimentacao_fatura` | Tela/relatório legado `admin_movimentacao_fatura.ctp` |
| `movimentacao_fatura_down` | `/admin/relatorio/movimentacao_fatura_down` | Tela/relatório legado `admin_movimentacao_fatura_down.ctp` (download) |
| `movimentacao_sinistro` | `/admin/relatorio/movimentacao_sinistro` | Tela/relatório legado `admin_movimentacao_sinistro.ctp` |
| `movimentacao_sinistro_down` | `/admin/relatorio/movimentacao_sinistro_down` | Tela/relatório legado `admin_movimentacao_sinistro_down.ctp` (download) |
| `sinistro` | `/admin/relatorio/sinistro` | Tela/relatório legado `admin_sinistro.ctp` |
| `sinistro_down` | `/admin/relatorio/sinistro_down` | Tela/relatório legado `admin_sinistro_down.ctp` (download) |

Layout: `resources/views/layouts/admin.blade.php`

## Critérios de aceite

- [ ] Cada `.ctp` do inventário (exceto backups) tem Blade/rota equivalente ou justificativa de exclusão
- [ ] ACL com chave `relatorio` e actions extras documentadas
- [ ] Tenant aplicado conforme spec
- [ ] Parity visual SmartAdmin/Bootstrap 3 suficiente para uso diário
- [ ] Smoke root + perfil restrito no `/admin`

## Referências

- Controller legado: `legacy/app/Controller/RelatorioController.php`
- Views legado: `legacy/app/View/Relatorio/`
- Plano-mestre: módulos por ondas (feature `mod-relatorio`)


