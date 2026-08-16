# Review — mod-empresa (Empresa)

> **Onda:** B · **ACL key:** `empresa`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\EmpresaController` (thin)
- [x] UseCases em `App\Application\Empresa`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentEmpresaRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware(\"modulo:empresa\")`

## Resultado

- [x] Aprovado (Onda B layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Rotas legado `/admin/empresa`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete em massa / parity JS legado completa
- Unit tests com fake de repository (opcional)