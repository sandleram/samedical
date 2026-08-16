# Review — mod-cliente (Cliente)

> **Onda:** B · **ACL key:** `cliente`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\ClienteController` (thin)
- [x] UseCases em `App\Application\Cliente`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentClienteRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware(\"modulo:cliente\")`

## Resultado

- [x] Aprovado (Onda B layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Rotas legado `/admin/cliente`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete em massa / parity JS legado completa
- Unit tests com fake de repository (opcional)