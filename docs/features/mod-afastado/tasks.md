# Tasks — mod-afastado (Afastado)

> **Onda:** C · **ACL:** `afastado`

## Camadas

- [x] Domain entity + SearchCriteria + RepositoryInterface
- [x] Application UseCases (List/Get/Save/Prepare conforme módulo)
- [x] Eloquent*Repository em Infrastructure
- [x] Interfaces Controller + FormRequest
- [x] Http Admin shim deprecated
- [x] Rotas `Interfaces\Http\Controllers\Admin\AfastadoController`
- [x] Binding AppServiceProvider
- [x] Blade SmartAdmin BS3 preservadas / criadas para parity
- [x] `config/samed.php` route_module_map + Funcoes::adminModuleUrl

## Qualidade

- [x] Feature smoke em `tests/Feature/OndaCRoutesTest.php`
- [ ] Smoke manual `/admin/afastado`
- [ ] PR aberto