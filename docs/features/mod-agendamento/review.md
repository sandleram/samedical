# Review — mod-agendamento (Agendamento)

> **Onda:** C · **ACL key:** `agendamento`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\AgendamentoController` (thin)
- [x] UseCases em `App\Application\Agendamento`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentAgendamentoRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware("modulo:agendamento")`

## Resultado

- [x] Aprovado (Onda C layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Rotas legado `/admin/agendamento`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete em massa / parity JS legado completa
- Unit tests com fake de repository (opcional)