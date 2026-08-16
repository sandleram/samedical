# Plan — mod-relatorio (Relatório)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** E · **ACL key:** `relatorio`

## Abordagem

Camadas Clean/Hexagonal com `RelatorioRepositoryInterface` para listagens. Hub + listagens shipped; `*_down`/Excel/PDF deferidos.## Arquivos a criar ou alterar

| Arquivo | Ação | Descrição |
|---------|------|-----------|
| `app/Http/Controllers/Admin/RelatorioController.php` | criar/alterar | Actions espelhando legado |
| — | — | Sem model principal nesta feature |
| `resources/views/admin/relatorio/*` | criar | Blades portados dos `.ctp` |
| `routes/web.php` | alterar | Prefixo `/admin/relatorio` |
| Form Requests (se CRUD) | criar | Validação add/edit |

### Mapa legado → Blade

| Legado | Ação | Laravel |
|-------|------|---------|
| `legacy/app/View/Relatorio/admin_index.ctp` | portar | → `resources/views/admin/relatorio/index.blade.php` |
| `legacy/app/View/Relatorio/admin_afastados.ctp` | portar | → `resources/views/admin/relatorio/afastados.blade.php` |
| `legacy/app/View/Relatorio/admin_atendimentos_pendentes.ctp` | portar | → `resources/views/admin/relatorio/atendimentos_pendentes.blade.php` |
| `legacy/app/View/Relatorio/admin_beneficiarios.ctp` | portar | → `resources/views/admin/relatorio/beneficiarios.blade.php` |
| `legacy/app/View/Relatorio/admin_exportacao.ctp` | portar | → `resources/views/admin/relatorio/exportacao.blade.php` |
| `legacy/app/View/Relatorio/admin_fatura.ctp` | portar | → `resources/views/admin/relatorio/fatura.blade.php` |
| `legacy/app/View/Relatorio/admin_fatura_down.ctp` | portar | → `resources/views/admin/relatorio/fatura_down.blade.php` |
| `legacy/app/View/Relatorio/admin_gerencial.ctp` | portar | → `resources/views/admin/relatorio/gerencial.blade.php` |
| `legacy/app/View/Relatorio/admin_movimentacao_beneficiario.ctp` | portar | → `resources/views/admin/relatorio/movimentacao_beneficiario.blade.php` |
| `legacy/app/View/Relatorio/admin_movimentacao_beneficiario_down.ctp` | portar | → `resources/views/admin/relatorio/movimentacao_beneficiario_down.blade.php` |
| `legacy/app/View/Relatorio/admin_movimentacao_fatura.ctp` | portar | → `resources/views/admin/relatorio/movimentacao_fatura.blade.php` |
| `legacy/app/View/Relatorio/admin_movimentacao_fatura_down.ctp` | portar | → `resources/views/admin/relatorio/movimentacao_fatura_down.blade.php` |
| `legacy/app/View/Relatorio/admin_movimentacao_sinistro.ctp` | portar | → `resources/views/admin/relatorio/movimentacao_sinistro.blade.php` |
| `legacy/app/View/Relatorio/admin_movimentacao_sinistro_down.ctp` | portar | → `resources/views/admin/relatorio/movimentacao_sinistro_down.blade.php` |
| `legacy/app/View/Relatorio/admin_sinistro.ctp` | portar | → `resources/views/admin/relatorio/sinistro.blade.php` |
| `legacy/app/View/Relatorio/admin_sinistro_down.ctp` | portar | → `resources/views/admin/relatorio/sinistro_down.blade.php` |

## Etapas

### 1. Preparação

- [ ] Confirmar `spec.md` e inventário de `.ctp`
- [ ] Ler `legacy/app/Controller/RelatorioController.php`
- [ ] Verificar registro em `modulo` com `controller = relatorio`

### 2. Banco de dados

- [ ] Preferir tabelas legadas existentes
- [ ] Migration só se gap além do dump
- [ ] Model Eloquent (`$table`, relations, scopes tenant)

### 3. Controller

- [ ] Middleware auth + tenant + `modulo:relatorio,{nivel}`
- [ ] Actions cobrindo cada tela do inventário
- [ ] Eager load; flash messages

### 4. Views

- [ ] Portar HTML/JS preservando IDs/classes
- [ ] Layout `admin`; Bootstrap 3 / SmartAdmin apenas

### 5. Rotas

- [ ] Paths próximos ao legado (`/admin/relatorio`, `/view/{id}`, `/add`, extras)
- [ ] Nomear rotas explicitamente para actions extras

### 6. Permissões

- [ ] Chave ACL `relatorio`; chaves finas se documentadas no spec
- [ ] Testar root e perfil restrito

### 7. Validação

- [ ] Smoke `/admin`
- [ ] Preencher `review.md`

## Riscos e dependências

- Depende Parte 0 (`config/samed.php`) e Parte 1 (ACL action-level) — outros agentes
- Tenant / seleção de cliente pode bloquear telas se sessão incompleta
- Onda E: só implementar após ondas anteriores quando houver dependência de cadastros/core

## Ordem sugerida de PRs

1. Model + rotas + index
2. show/view + add/edit
3. Actions extras + parity JS + permissões


