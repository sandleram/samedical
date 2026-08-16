# Plan — mod-beneficiario (Beneficiário)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** A · **ACL key:** `beneficiario`

## Abordagem

Portar `BeneficiarioController` + views `Beneficiario/admin_*.ctp` para Laravel Admin, sem redesenhar regras.

```
routes/web.php → Admin\BeneficiarioController → Eloquent → Blade admin/beneficiario/*
                      ↓
            Middleware (auth, tenant, permission ≥ nível da action)
```

## Gaps do piloto a fechar

- Laravel hoje: `BeneficiarioController` com `index` + `show` apenas; rotas **plurais** `/admin/beneficiarios` e `/admin/beneficiarios/{id}`.
- Views Blade: `admin/beneficiarios/index.blade.php` e `show.blade.php` (piloto, sem parity visual completa).
- Falta: `add`/`edit`, `all`, `view2`, timeline; alinhar paths ao legado (`/admin/beneficiario`, `/admin/beneficiario/view/{id}`, `/admin/beneficiario/add`).
- Tenant: via `cliente_id` → `cliente.grupo_empresarial_id` (scope `forTenant()` já iniciado).
- Preservar filtros, IDs/classes JS do `.ctp` e níveis ACL 1/2/3 por action.

## Arquivos a criar ou alterar

| Arquivo | Ação | Descrição |
|---------|------|-----------|
| `app/Http/Controllers/Admin/BeneficiarioController.php` | criar/alterar | Actions espelhando legado |
| `app/Models/Beneficiario.php` | criar/alterar | Eloquent `$table = 'beneficiario'` |
| `resources/views/admin/beneficiario/*` | criar | Blades portados dos `.ctp` |
| `routes/web.php` | alterar | Prefixo `/admin/beneficiario` |
| Form Requests (se CRUD) | criar | Validação add/edit |

### Mapa legado → Blade

| Legado | Ação | Laravel |
|-------|------|---------|
| `legacy/app/View/Beneficiario/admin_index.ctp` | portar | → `resources/views/admin/beneficiario/index.blade.php` |
| `legacy/app/View/Beneficiario/admin_view.ctp` | portar | → `resources/views/admin/beneficiario/view.blade.php` |
| `legacy/app/View/Beneficiario/admin_view2.ctp` | portar | → `resources/views/admin/beneficiario/view2.blade.php` |
| `legacy/app/View/Beneficiario/admin_add.ctp` | portar | → `resources/views/admin/beneficiario/add.blade.php` |
| `legacy/app/View/Beneficiario/admin_all.ctp` | portar | → `resources/views/admin/beneficiario/all.blade.php` |
| `legacy/app/View/Beneficiario/admin_timeline_example.ctp` | portar | → `resources/views/admin/beneficiario/timeline_example.blade.php` |

## Etapas

### 1. Preparação

- [ ] Confirmar `spec.md` e inventário de `.ctp`
- [ ] Ler `legacy/app/Controller/BeneficiarioController.php`
- [ ] Verificar registro em `modulo` com `controller = beneficiario`

### 2. Banco de dados

- [ ] Preferir tabelas legadas existentes
- [ ] Migration só se gap além do dump
- [ ] Model Eloquent (`$table`, relations, scopes tenant)

### 3. Controller

- [ ] Middleware auth + tenant + `modulo:beneficiario,{nivel}`
- [ ] Actions cobrindo cada tela do inventário
- [ ] Eager load; flash messages

### 4. Views

- [ ] Portar HTML/JS preservando IDs/classes
- [ ] Layout `admin`; Bootstrap 3 / SmartAdmin apenas

### 5. Rotas

- [ ] Paths próximos ao legado (`/admin/beneficiario`, `/view/{id}`, `/add`, extras)
- [ ] Nomear rotas explicitamente para actions extras

### 6. Permissões

- [ ] Chave ACL `beneficiario`; chaves finas se documentadas no spec
- [ ] Testar root e perfil restrito

### 7. Validação

- [ ] Smoke `/admin`
- [ ] Preencher `review.md`

## Riscos e dependências

- Depende Parte 0 (`config/samed.php`) e Parte 1 (ACL action-level) — outros agentes
- Tenant / seleção de cliente pode bloquear telas se sessão incompleta
- Onda A: só implementar após ondas anteriores quando houver dependência de cadastros/core

## Ordem sugerida de PRs

1. Model + rotas + index
2. show/view + add/edit
3. Actions extras + parity JS + permissões


