# Plan — [Nome da feature]

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> Arquitetura: **Controller → UseCase → Domain → Repository Interface → Eloquent (Infrastructure)**  
> Ver `docs/architecture/layered.md` e o vertical de referência **Beneficiário**.

## Abordagem

Resumo da estratégia técnica. Camadas afetadas:

```
routes/web.php
  → Interfaces\Http\Controllers\Admin\XController (+ FormRequest)
  → Application\X\{List|Get|Save}X
  → Domain\X\{Entity, XRepositoryInterface}
  → Infrastructure\Persistence\Eloquent\EloquentXRepository
  → Blade resources/views/admin/x/*
                      ↓
            Middleware (auth, tenant, permission)
```

## Arquivos a criar ou alterar

| Arquivo | Ação | Descrição |
|---------|------|-----------|
| `app/Domain/X/…` | criar | Entidade/DTO + `XRepositoryInterface` (+ VOs/critérios) |
| `app/Application/X/…` | criar | UseCases (`ListX`, `GetX`, `SaveX`, …) |
| `app/Infrastructure/Persistence/Eloquent/EloquentXRepository.php` | criar | Implementação Eloquent |
| `app/Interfaces/Http/Controllers/Admin/XController.php` | criar | Controller fino |
| `app/Interfaces/Http/Requests/Admin/…Request.php` | criar | Validação HTTP |
| `app/Models/X.php` | criar/alterar | Eloquent `$table` — **só** usado pela Infrastructure |
| `resources/views/admin/x/index.blade.php` | criar/alterar | Listagem |
| `resources/views/admin/x/form.blade.php` | criar/alterar | Formulário |
| `routes/web.php` | alterar | Rotas `admin` → controller Interfaces |
| `app/Providers/AppServiceProvider.php` | alterar | `bind(XRepositoryInterface, EloquentXRepository)` |
| `database/migrations/…` | criar | Só se gap no schema |
| … | … | … |

## Etapas

### 1. Preparação

- [ ] Confirmar `spec.md` e critérios de aceite
- [ ] Identificar equivalente em `legacy/` (se portar)
- [ ] Verificar módulo em `modulo` / `perfil_modulo`
- [ ] Listar UseCases necessários (index / show / add / …)

### 2. Banco de dados

- [ ] Preferir tabelas legadas existentes
- [ ] Se necessário: migration Laravel + documentar rollback
- [ ] Model Eloquent em `app/Models` (`$table`, `$fillable`, relations) — sem lógica de negócio

### 3. Domain (`app/Domain/`)

- [ ] Entidade / DTO de domínio (sem `Illuminate\*`)
- [ ] `XRepositoryInterface` + critérios de busca / `TenantScope` quando aplicável
- [ ] Regras puras (cálculos, invariantes) no Domain — **não** no Controller

### 4. Application (`app/Application/`)

- [ ] UseCases: um por intenção (`ListX`, `GetX`, `SaveX`, …)
- [ ] Receber `TenantScope` / input tipado; não ler `session()` diretamente
- [ ] Orquestrar repositórios; sem Eloquent / Blade

### 5. Infrastructure (`app/Infrastructure/`)

- [ ] `EloquentXRepository` implementa a interface do Domain
- [ ] Filtros de tenant aplicados aqui
- [ ] Mapear Model ↔ entidade de domínio
- [ ] Registrar binding no `AppServiceProvider`

### 6. Interfaces HTTP (`app/Interfaces/Http/`)

- [ ] Controller fino: FormRequest → UseCase → view/redirect
- [ ] Sem regras de negócio; formatação de display / flash OK
- [ ] Middleware `auth` + tenant + permission nas rotas

### 7. Views (`resources/views/`)

- [ ] Blade sob `admin/{resource}/`
- [ ] Reutilizar layout `admin` e partials
- [ ] Classes Bootstrap 3 / SmartAdmin existentes
- [ ] Assets novos só se necessário em `public/`

### 8. Rotas e API (se aplicável)

- [ ] Grupo `Route::prefix('admin')->middleware(...)`
- [ ] Apontar para `App\Interfaces\Http\Controllers\Admin\…`
- [ ] API em `routes/api.php` + token/sanctum se necessário
- [ ] Job/command para batch/cron

### 9. Permissões

- [ ] Registro em `modulo` (se novo menu)
- [ ] `perfil_modulo` para perfis com acesso
- [ ] Testar root (id=1) e perfil restrito

### 10. Validação

- [ ] Smoke manual no `/admin`
- [ ] Revisar `review.md`
- [ ] `docker compose exec app php artisan test` (se houver testes)

## Riscos e dependências

- …
- …

## Ordem sugerida de PRs (opcional)

1. Domain + Model Eloquent + binding
2. Application UseCases + Infrastructure repo
3. Interfaces Controller + Views + rotas
4. Permissões + ajustes finais
