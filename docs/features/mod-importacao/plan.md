# Plan — mod-importacao (Importação)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** E · **ACL key:** `importacao`

## Abordagem

Portar para camadas Clean/Hexagonal (referência Beneficiário / Absenteismo):

```
routes/web.php → Interfaces\ImportacaoController → Application UseCase
  → Domain (Importacao + RepositoryInterface) → EloquentImportacaoRepository → Blade
```

Entry points mantidos; `carga_*` permanece deferido.## Arquivos a criar ou alterar

| Arquivo | Ação | Descrição |
|---------|------|-----------|
| `app/Http/Controllers/Admin/ImportacaoController.php` | criar/alterar | Actions espelhando legado |
| `app/Models/Importacao.php` | criar/alterar | Eloquent `$table = 'importacao'` |
| `resources/views/admin/importacao/*` | criar | Blades portados dos `.ctp` |
| `routes/web.php` | alterar | Prefixo `/admin/importacao` |
| Form Requests (se CRUD) | criar | Validação add/edit |

### Mapa legado → Blade

| Legado | Ação | Laravel |
|-------|------|---------|
| `legacy/app/View/Importacao/admin_index.ctp` | portar | → `resources/views/admin/importacao/index.blade.php` |
| `legacy/app/View/Importacao/admin_add.ctp` | portar | → `resources/views/admin/importacao/add.blade.php` |
| `legacy/app/View/Importacao/admin_import.ctp` | portar | → `resources/views/admin/importacao/import.blade.php` |
| `legacy/app/View/Importacao/admin_validacao.ctp` | portar | → `resources/views/admin/importacao/validacao.blade.php` |

## Etapas

### 1. Preparação

- [ ] Confirmar `spec.md` e inventário de `.ctp`
- [ ] Ler `legacy/app/Controller/ImportacaoController.php`
- [ ] Verificar registro em `modulo` com `controller = importacao`

### 2. Banco de dados

- [ ] Preferir tabelas legadas existentes
- [ ] Migration só se gap além do dump
- [ ] Model Eloquent (`$table`, relations, scopes tenant)

### 3. Controller

- [ ] Middleware auth + tenant + `modulo:importacao,{nivel}`
- [ ] Actions cobrindo cada tela do inventário
- [ ] Eager load; flash messages

### 4. Views

- [ ] Portar HTML/JS preservando IDs/classes
- [ ] Layout `admin`; Bootstrap 3 / SmartAdmin apenas

### 5. Rotas

- [ ] Paths próximos ao legado (`/admin/importacao`, `/view/{id}`, `/add`, extras)
- [ ] Nomear rotas explicitamente para actions extras

### 6. Permissões

- [ ] Chave ACL `importacao`; chaves finas se documentadas no spec
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


