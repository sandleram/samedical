# Plan — [módulo]

> ACL: `{controller}` · Fluxo: Cake → spec → testes → Domain → Infra → Controller → Blade → validar Cake

```
.ctp / Controller Cake
  → Interfaces\{X}Controller → Application\{X}\*UseCase
    → Domain\{X} → Eloquent{X}Repository
  → Blade admin/{x}/*  (skeleton SmartAdmin do .ctp)
```

## Camadas

| Camada | Path |
|--------|------|
| Domain | `app/Domain/{X}/` |
| Application | `app/Application/{X}/` |
| Infrastructure | `Eloquent{X}Repository` |
| Interfaces | `{X}Controller` + FormRequest |
| Views | `resources/views/admin/{x}/*` |

## Etapas

- [ ] Spec (regras **deste** módulo + inventário `.ctp`)
- [ ] Testes (fake de repository)
- [ ] Domain + Infrastructure + binding
- [ ] Controller fino + rotas legado
- [ ] Blade = markup Cake (wid-id, smart-form, classes JS)
- [ ] Validar contra Cake (ACL, tenant, HTML)
