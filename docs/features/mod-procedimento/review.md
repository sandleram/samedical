# Review — mod-procedimento (Procedimento)

> **Onda:** C · **ACL key:** `procedimento`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\ProcedimentoController` (thin)
- [x] UseCases em `App\Application\Procedimento`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentProcedimentoRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware("modulo:procedimento")`

## Resultado

- [x] Aprovado (Onda C layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Rotas legado `/admin/procedimento`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete em massa / parity JS legado completa
- Unit tests com fake de repository (opcional)