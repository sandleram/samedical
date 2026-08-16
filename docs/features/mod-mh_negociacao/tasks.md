# Tasks — mod-mh_negociacao (MH Negociação)

> **Onda:** D · **ACL:** `mh_negociacao`

## Camadas

- [x] Domain entity + SearchCriteria + RepositoryInterface
- [x] Application UseCases (List/Get/Save/Prepare)
- [x] Eloquent*Repository em Infrastructure
- [x] Interfaces Controller + FormRequest
- [x] Http Admin shim deprecated
- [x] Rotas `Interfaces\Http\Controllers\Admin\MhNegociacaoController`
- [x] Binding AppServiceProvider
- [x] Blade SmartAdmin BS3 preservadas

## Qualidade

- [x] Feature smoke em `tests/Feature/MhModulesRoutesTest.php` (Onda D)
- [ ] Smoke manual `/admin/mh_negociacao`
- [ ] PR aberto

## Deferred

- Soft-delete / log / busca sessão
- Campos fantasma do `.ctp` (`nome`, `data_cancelamento`)
- Tenant por GE (coluna ausente)
