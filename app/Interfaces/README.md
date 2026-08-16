# Interfaces

Adaptadores de entrada (HTTP, CLI futuro).

- `Http\Controllers\Admin\*` — controllers finos (validar → UseCase → view/redirect)
- `Http\Requests\*` — FormRequests
- Sem regras de negócio; formatação de display e flash/session OK aqui
