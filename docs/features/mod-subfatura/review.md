# Review — mod-subfatura (Subfatura)

> **Onda:** C · **ACL key:** `subfatura`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\SubfaturaController` (thin)
- [x] UseCases em `App\Application\Subfatura`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentSubfaturaRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware("modulo:subfatura")`

## Resultado

- [x] Aprovado (Onda C layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Rotas legado `/admin/subfatura`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete em massa / parity JS legado completa
- Unit tests com fake de repository (opcional)