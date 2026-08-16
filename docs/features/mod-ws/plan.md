# Plan — mod-ws (Web Service (WS))

> **Onda:** F · **ACL key:** token `SAMED_WS_TOKEN` / fallback REST

## Abordagem

```
routes/api.php → Interfaces\Api\WsController → CallBiBeneficiarios / GetWsIndex
  → Domain (WsCallResult + WsBiRepositoryInterface) → EloquentWsBiRepository
```

Token compartilhado via `IntegrationTokenSettingsInterface`.

## Endpoints shipped

- `GET /api/ws` → 204
- `GET /api/ws/call_bi_beneficiarios`
- `GET /api/ws/call_bi_beneficiarios2`

## Deferred

- Robôs Proativa / e-mail rotina
- Conexão DB `proativa` (MVP lê `dw_beneficiario` no app)

## Etapas

- [x] Camadas + bindings
- [x] Rotas API
- [x] Feature test token gate
- [x] Docs
