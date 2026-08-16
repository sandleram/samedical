# Infrastructure

Adapters concretos: Eloquent, filesystem, clientes HTTP externos.

- `Persistence\Eloquent\Eloquent*Repository` implementa interfaces do Domain
- Único lugar autorizado a usar `App\Models\*` e Query Builder
- Bindings de interface → implementação em `AppServiceProvider`
