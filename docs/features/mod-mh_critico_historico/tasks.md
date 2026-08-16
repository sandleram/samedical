# Tasks — mod-mh_critico_historico (MH Crítico Histórico)

> **Onda:** D · **ACL:** `mh_critico_historico`

## Camadas

- [x] Domain entity + SearchCriteria + RepositoryInterface
- [x] Application UseCases (List/Get/Save/Prepare)
- [x] Eloquent*Repository em Infrastructure
- [x] Interfaces Controller + FormRequest
- [x] Http Admin shim deprecated
- [x] Rotas `Interfaces\Http\Controllers\Admin\MhCriticoHistoricoController`
- [x] Binding AppServiceProvider
- [x] Blade SmartAdmin BS3 preservadas

## Qualidade

- [x] Feature smoke auth gate em `tests/Feature/MhModulesRoutesTest.php` (Onda D)
- [ ] Smoke manual root + restrito
- [ ] PR aberto

## Deferred

- Soft-delete / bulk delete / log
- Busca sessão
- Campos `nome`/`opcao` do `.ctp` view — view usa `descricao`
