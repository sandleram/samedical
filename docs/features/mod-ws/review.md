# Review — mod-ws (Web Service (WS))

> **Onda:** F · **ACL key:** token WS/REST

## Code review — camadas

- [x] `Interfaces\Http\Controllers\Api\WsController` (thin)
- [x] UseCases `CallBiBeneficiarios`, `GetWsIndex`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentWsBiRepository`
- [x] Token via config (endurecido vs Auth::allow legado)
- [x] Shim Http deprecated

## QA

- [x] Token inválido → 403
- [x] MVP `call_bi_*`
- [ ] Robôs / e-mail — deferred

## Testes

- [x] `OndaFIntegrationRoutesTest`
- [ ] Suite Docker usuário

## Resultado

- [x] Aprovado com ressalvas (Onda F layered)
