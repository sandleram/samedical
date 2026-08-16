# Review — mod-absenteismo (Absenteísmo)

> **Onda:** C · **ACL key:** `absenteismo`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\AbsenteismoController` (thin)
- [x] UseCases em `App\Application\Absenteismo`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentAbsenteismoRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware("modulo:absenteismo")`

## Resultado

- [x] Aprovado (Onda C layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Rotas legado `/admin/absenteismo`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete em massa / parity JS legado completa
- Unit tests com fake de repository (opcional)