# Plan — [Nome da feature]

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx

## Abordagem

Resumo da estratégia técnica. Camadas afetadas:

```
routes/web.php → Controller → Eloquent Model → Blade
                      ↓
            Middleware (auth, tenant, permission)
```

## Arquivos a criar ou alterar

| Arquivo | Ação | Descrição |
|---------|------|-----------|
| `app/Http/Controllers/Admin/XController.php` | criar/alterar | Actions CRUD |
| `app/Models/X.php` | criar/alterar | Eloquent + `$table` |
| `resources/views/admin/x/index.blade.php` | criar/alterar | Listagem |
| `resources/views/admin/x/form.blade.php` | criar/alterar | Formulário |
| `routes/web.php` | alterar | Rotas `admin` |
| `database/migrations/…` | criar | Só se gap no schema |
| … | … | … |

## Etapas

### 1. Preparação

- [ ] Confirmar `spec.md` e critérios de aceite
- [ ] Identificar equivalente em `legacy/` (se portar)
- [ ] Verificar módulo em `modulo` / `perfil_modulo`

### 2. Banco de dados

- [ ] Preferir tabelas legadas existentes
- [ ] Se necessário: migration Laravel + documentar rollback
- [ ] Atualizar Model Eloquent (`$table`, `$fillable`, relations)

### 3. Model (`app/Models/`)

- [ ] `$table` singular português quando legado
- [ ] Relations (`belongsTo`, `hasMany`)
- [ ] Scopes de tenant quando aplicável
- [ ] Casts / accessors se necessário

### 4. Controller (`app/Http/Controllers/Admin/`)

- [ ] Middleware `auth` + tenant + permission
- [ ] Actions: `index`, `create`/`store`, `show`, `edit`/`update`, `destroy`
- [ ] Eager load para evitar N+1
- [ ] Flash messages (`session()->flash`)

### 5. Views (`resources/views/`)

- [ ] Blade sob `admin/{resource}/`
- [ ] Reutilizar layout `admin` e partials
- [ ] Classes Bootstrap 3 / SmartAdmin existentes
- [ ] Assets novos só se necessário em `public/`

### 6. Rotas e API (se aplicável)

- [ ] Grupo `Route::prefix('admin')->middleware(...)`
- [ ] API em `routes/api.php` + token/sanctum se necessário
- [ ] Job/command para batch/cron

### 7. Permissões

- [ ] Registro em `modulo` (se novo menu)
- [ ] `perfil_modulo` para perfis com acesso
- [ ] Testar root (id=1) e perfil restrito

### 8. Validação

- [ ] Smoke manual no `/admin`
- [ ] Revisar `review.md`
- [ ] `docker compose exec app php artisan test` (se houver testes)

## Riscos e dependências

- …
- …

## Ordem sugerida de PRs (opcional)

1. Migration + Model
2. Controller + Views
3. Permissões + ajustes finais
