# Tasks — mod-mh_critico (MH Crítico)

> **Onda:** D · **ACL:** `mh_critico`

## Camadas

- [x] Domain entity + SearchCriteria + RepositoryInterface
- [x] Application UseCases (List/Get/Save/Prepare)
- [x] Eloquent*Repository em Infrastructure (principals + subsidiaries)
- [x] Interfaces Controller + FormRequest
- [x] Http Admin shim deprecated
- [x] Rotas `Interfaces\Http\Controllers\Admin\MhCriticoController`
- [x] Binding AppServiceProvider
- [x] Blade SmartAdmin BS3 preservadas

## Qualidade

- [x] Feature smoke em `tests/Feature/MhModulesRoutesTest.php` (Onda D)
- [ ] Smoke manual `/admin/mh_critico`
- [ ] PR aberto

## Deferred

- Soft-delete (`status=2`) / bulk delete
- Gravação em `log`
- Busca em sessão (`admin_busca_unset`)
- Tenant por GE/cliente (colunas ausentes no dump)
