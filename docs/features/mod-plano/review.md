# Review — mod-plano (Plano)

> **Onda:** B · **ACL key:** `plano`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\PlanoController` (thin)
- [x] UseCases em `App\Application\Plano`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentPlanoRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware(\"modulo:plano\")`

## Resultado

- [x] Aprovado (Onda B layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Rotas legado `/admin/plano`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete em massa / parity JS legado completa
- Unit tests com fake de repository (opcional)