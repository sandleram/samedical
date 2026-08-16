# Tasks — mod-mh_prestador (MH Prestador)

> **Onda:** D · **ACL:** `mh_prestador`

## Camadas

- [x] Domain entity + SearchCriteria + RepositoryInterface
- [x] Application UseCases (List/Get/Save)
- [x] Eloquent*Repository em Infrastructure
- [x] Interfaces Controller + FormRequest
- [x] Http Admin shim deprecated
- [x] Rotas `Interfaces\Http\Controllers\Admin\MhPrestadorController`
- [x] Binding AppServiceProvider
- [x] Blade SmartAdmin BS3 preservadas
- [x] `config/samed.php` route_module_map + Funcoes::adminModuleUrl

## Qualidade

- [x] Feature smoke em `tests/Feature/MhModulesRoutesTest.php` (Onda D)
- [ ] Smoke manual `/admin/mh_prestador`
- [ ] PR aberto

## Deferred

- Soft-delete / log / busca sessão
- Campos fantasma do `.ctp` (`cnpj`, `tipo_negocio` multi)
