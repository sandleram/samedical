# Review — mod-mh_critico (MH Crítico)

> **Onda:** D · **ACL key:** `mh_critico`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\MhCriticoController` (thin)
- [x] UseCases em `App\Application\MhCritico`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentMhCriticoRepository`
- [x] Binding registrado
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware("modulo:mh_critico")`

## Resultado

- [x] Aprovado (Onda D layered)

### Feito
- Port completo Domain → Application → Infrastructure → Interfaces
- Shim Http deprecated
- Index com principais + opções (rowsSub); form com prestadores usados/livres
- Rotas legado `/admin/mh_critico`, `/view/{id}`, `/add/{id?}`

### Diferido
- Soft-delete / bulk delete / log / busca sessão
- Tenant GE/cliente (colunas ausentes)
