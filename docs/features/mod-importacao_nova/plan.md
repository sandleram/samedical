# Plan — mod-importacao_nova (Importação Nova)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** E · **ACL key:** `importacao_nova`

## Abordagem

Camadas Clean/Hexagonal (Interfaces → Application → Domain → Infrastructure). Entry points mantidos; worker/carga_* deferido.## Arquivos a criar ou alterar

| Arquivo | Ação | Descrição |
|---------|------|-----------|
| `app/Http/Controllers/Admin/ImportacaoNovaController.php` | criar/alterar | Actions espelhando legado |
| `app/Models/ImportacaoNova.php` | criar/alterar | Eloquent `$table = 'importacao_nova'` |
| `resources/views/admin/importacao_nova/*` | criar | Blades portados dos `.ctp` |
| `routes/web.php` | alterar | Prefixo `/admin/importacao_nova` |
| Form Requests (se CRUD) | criar | Validação add/edit |

### Mapa legado → Blade

| Legado | Ação | Laravel |
|-------|------|---------|
| `legacy/app/View/ImportacaoNova/admin_index.ctp` | portar | → `resources/views/admin/importacao_nova/index.blade.php` |
| `legacy/app/View/ImportacaoNova/admin_view.ctp` | portar | → `resources/views/admin/importacao_nova/view.blade.php` |
| `legacy/app/View/ImportacaoNova/admin_add.ctp` | portar | → `resources/views/admin/importacao_nova/add.blade.php` |
| `legacy/app/View/ImportacaoNova/admin_import.ctp` | portar | → `resources/views/admin/importacao_nova/import.blade.php` |
| `legacy/app/View/ImportacaoNova/admin_validacao.ctp` | portar | → `resources/views/admin/importacao_nova/validacao.blade.php` |

## Etapas

### 1. Preparação

- [ ] Confirmar `spec.md` e inventário de `.ctp`
- [ ] Ler `legacy/app/Controller/ImportacaoNovaController.php`
- [ ] Verificar registro em `modulo` com `controller = importacao_nova`

### 2. Banco de dados

- [ ] Preferir tabelas legadas existentes
- [ ] Migration só se gap além do dump
- [ ] Model Eloquent (`$table`, relations, scopes tenant)

### 3. Controller

- [ ] Middleware auth + tenant + `modulo:importacao_nova,{nivel}`
- [ ] Actions cobrindo cada tela do inventário
- [ ] Eager load; flash messages

### 4. Views

- [ ] Portar HTML/JS preservando IDs/classes
- [ ] Layout `admin`; Bootstrap 3 / SmartAdmin apenas

### 5. Rotas

- [ ] Paths próximos ao legado (`/admin/importacao_nova`, `/view/{id}`, `/add`, extras)
- [ ] Nomear rotas explicitamente para actions extras

### 6. Permissões

- [ ] Chave ACL `importacao_nova`; chaves finas se documentadas no spec
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


