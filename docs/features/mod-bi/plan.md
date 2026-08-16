# Plan — mod-bi (BI)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** E · **ACL key:** `bi`

## Abordagem

Camadas Clean/Hexagonal. lista/gerencial/medico/rh + CRUD index; conexão `proativa` não usada neste módulo (legado iframe).## Arquivos a criar ou alterar

| Arquivo | Ação | Descrição |
|---------|------|-----------|
| `app/Http/Controllers/Admin/BiController.php` | criar/alterar | Actions espelhando legado |
| `app/Models/Bi.php` | criar/alterar | Eloquent `$table = 'bi'` |
| `resources/views/admin/bi/*` | criar | Blades portados dos `.ctp` |
| `routes/web.php` | alterar | Prefixo `/admin/bi` |
| Form Requests (se CRUD) | criar | Validação add/edit |

### Mapa legado → Blade

| Legado | Ação | Laravel |
|-------|------|---------|
| `legacy/app/View/Bi/admin_index.ctp` | portar | → `resources/views/admin/bi/index.blade.php` |
| `legacy/app/View/Bi/admin_lista.ctp` | portar | → `resources/views/admin/bi/lista.blade.php` |
| `legacy/app/View/Bi/admin_gerencial.ctp` | portar | → `resources/views/admin/bi/gerencial.blade.php` |
| `legacy/app/View/Bi/admin_rh.ctp` | portar | → `resources/views/admin/bi/rh.blade.php` |
| `legacy/app/View/Bi/admin_medico.ctp` | portar | → `resources/views/admin/bi/medico.blade.php` |
| `legacy/app/View/Bi/admin_view.ctp` | portar | → `resources/views/admin/bi/view.blade.php` |
| `legacy/app/View/Bi/admin_add.ctp` | portar | → `resources/views/admin/bi/add.blade.php` |

## Etapas

### 1. Preparação

- [ ] Confirmar `spec.md` e inventário de `.ctp`
- [ ] Ler `legacy/app/Controller/BiController.php`
- [ ] Verificar registro em `modulo` com `controller = bi`

### 2. Banco de dados

- [ ] Preferir tabelas legadas existentes
- [ ] Migration só se gap além do dump
- [ ] Model Eloquent (`$table`, relations, scopes tenant)

### 3. Controller

- [ ] Middleware auth + tenant + `modulo:bi,{nivel}`
- [ ] Actions cobrindo cada tela do inventário
- [ ] Eager load; flash messages

### 4. Views

- [ ] Portar HTML/JS preservando IDs/classes
- [ ] Layout `admin`; Bootstrap 3 / SmartAdmin apenas

### 5. Rotas

- [ ] Paths próximos ao legado (`/admin/bi`, `/view/{id}`, `/add`, extras)
- [ ] Nomear rotas explicitamente para actions extras

### 6. Permissões

- [ ] Chave ACL `bi`; chaves finas se documentadas no spec
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


