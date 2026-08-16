# Tasks — mod-grupo_empresarial (Grupo Empresarial)

> **Onda:** B · **ACL:** `grupo_empresarial`

## Camadas

- [x] Domain entity + SearchCriteria + RepositoryInterface
- [x] Application UseCases (List/Get/Save/Prepare conforme módulo)
- [x] Eloquent*Repository em Infrastructure
- [x] Interfaces Controller + FormRequest
- [x] Http Admin shim deprecated
- [x] Rotas `Interfaces\Http\Controllers\Admin\GrupoEmpresarialController`
- [x] Binding AppServiceProvider
- [x] Blade SmartAdmin BS3 preservadas
- [x] `config/samed.php` route_module_map + Funcoes::adminModuleUrl

## Qualidade

- [x] Feature smoke em `tests/Feature/OndaBRoutesTest.php`
- [x] Smoke Docker `php artisan test` (`OndaBRoutesTest` `/admin/grupo_empresarial`)
- [ ] PR aberto