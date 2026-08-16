# Review — mod-rest (REST API)

> **Onda:** F · **ACL key:** token `SAMED_REST_TOKEN`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Api\RestController` (thin)
- [x] UseCases em `App\Application\Rest`
- [x] Domain sem Laravel (`RestApiResult`, repos, `TokenMatcher`)
- [x] Eloquent só em Infrastructure
- [x] Binding registrado
- [x] Shim Http deprecated
- [x] Token só via config/env (sem secret hardcoded)

## QA / aceite

- [x] `/api/rest` → failed JSON
- [x] Sem token → failed
- [ ] Carga completa com dump real — smoke manual
- [x] MVP endpoints Proativa

## Testes

- [x] `OndaFIntegrationRoutesTest`
- [x] `TokenMatcherTest`
- [ ] Suite completa no Docker do usuário

## Deferred

- Paginação / rate limit / Bearer
- Jobs/commands

## Resultado

- [x] Aprovado com ressalvas (Onda F layered)
