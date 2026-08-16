# Plan — mod-rest (REST API)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** F · **ACL key:** token `SAMED_REST_TOKEN`

## Abordagem

Camadas Clean/Hexagonal (referência Beneficiário):

```
routes/api.php → Interfaces\Api\RestController → Application UseCase
  → Domain (RestApiResult + RestProativaRepositoryInterface) → EloquentRestProativaRepository
```

Token via `IntegrationTokenSettingsInterface` (config); audit via `RestAuditLoggerInterface`.

## Arquivos

| Camada | Path |
|--------|------|
| Interfaces | `app/Interfaces/Http/Controllers/Api/RestController.php` |
| Application | `app/Application/Rest/*` |
| Domain | `app/Domain/Rest/*`, `app/Domain/Integration/*` |
| Infrastructure | `EloquentRestProativaRepository`, `EloquentRestAuditLogger`, `ConfigIntegrationTokenSettings` |
| Shim | `app/Http/Controllers/Api/RestController.php` (deprecated) |

## Endpoints shipped

- `GET /api/rest` (failed payload legado)
- `bi_proativa_beneficiario(s)`, `faturamento(s)`, `sinistro(s)`
- dumps: beneficio, cliente, grupo_estatistico, cronicos, subfaturas, procedimento

## Deferred

- Paginação / rate limit / Bearer header
- Jobs/commands robô

## Etapas

- [x] Spec / inventário legado
- [x] Camadas + bindings
- [x] Rotas `routes/api.php`
- [x] Feature + unit smoke
- [x] Docs review
