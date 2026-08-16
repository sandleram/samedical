# Review — mod-afastado (Afastado)

> **Onda:** C · **ACL key:** `afastado`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\AfastadoController` (thin)
- [x] UseCases em `App\Application\Afastado`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentAfastadoRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware("modulo:afastado")`

## Resultado

- [x] Aprovado (Onda C layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Rotas legado `/admin/afastado`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete em massa / parity JS legado completa
- Unit tests com fake de repository (opcional)