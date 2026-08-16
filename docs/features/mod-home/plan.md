# Plan — mod-home (Home / Dashboard)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** A · **ACL key:** `home`

## Abordagem

Portar `HomeController` + views `Home/admin_*.ctp` para Laravel Admin, sem redesenhar regras.

```
routes/web.php → Admin\HomeController → Eloquent → Blade admin/home/*
                      ↓
            Middleware (auth, tenant, permission ≥ nível da action)
```

## Gaps do piloto a fechar

- Laravel: `HomeController@index` + `resources/views/admin/home/index.blade.php` existem como stub (só título).
- Falta portar widgets/HTML/JS de `legacy/app/View/Home/admin_index.ctp`.
- Rota atual: `GET /admin/home` (middleware `modulo:home,1`) — manter path.
- Seleção de cliente/GE (parcial de `grupo_empresarial/selecione`) pode ser pré-requisito do dashboard útil.

## Arquivos a criar ou alterar

| Arquivo | Ação | Descrição |
|---------|------|-----------|
| `app/Http/Controllers/Admin/HomeController.php` | criar/alterar | Actions espelhando legado |
| — | — | Sem model principal nesta feature |
| `resources/views/admin/home/*` | criar | Blades portados dos `.ctp` |
| `routes/web.php` | alterar | Prefixo `/admin/home` |
| Form Requests (se CRUD) | criar | Validação add/edit |

### Mapa legado → Blade

| Legado | Ação | Laravel |
|-------|------|---------|
| `legacy/app/View/Home/admin_index.ctp` | portar | → `resources/views/admin/home/index.blade.php` |

## Etapas

### 1. Preparação

- [ ] Confirmar `spec.md` e inventário de `.ctp`
- [ ] Ler `legacy/app/Controller/HomeController.php`
- [ ] Verificar registro em `modulo` com `controller = home`

### 2. Banco de dados

- [ ] Preferir tabelas legadas existentes
- [ ] Migration só se gap além do dump
- [ ] Model Eloquent (`$table`, relations, scopes tenant)

### 3. Controller

- [ ] Middleware auth + tenant + `modulo:home,{nivel}`
- [ ] Actions cobrindo cada tela do inventário
- [ ] Eager load; flash messages

### 4. Views

- [ ] Portar HTML/JS preservando IDs/classes
- [ ] Layout `admin`; Bootstrap 3 / SmartAdmin apenas

### 5. Rotas

- [ ] Paths próximos ao legado (`/admin/home`, `/view/{id}`, `/add`, extras)
- [ ] Nomear rotas explicitamente para actions extras

### 6. Permissões

- [ ] Chave ACL `home`; chaves finas se documentadas no spec
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


