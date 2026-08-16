# Review — mod-beneficio_previdenciario (Benefício Previdenciário)

> **Onda:** C · **ACL key:** `beneficio_previdenciario`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\BeneficioPrevidenciarioController` (thin)
- [x] UseCases em `App\Application\BeneficioPrevidenciario`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentBeneficioPrevidenciarioRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware("modulo:beneficio_previdenciario")`

## Resultado

- [x] Aprovado (Onda C layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Rotas legado `/admin/beneficio_previdenciario`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete em massa / parity JS legado completa
- Unit tests com fake de repository (opcional)