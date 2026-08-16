# Review — mod-importacao_nova (Importação Nova)

> **Onda:** E · **ACL:** `importacao_nova`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\ImportacaoNovaController` (thin)
- [x] UseCases em `App\Application\ImportacaoNova`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentImportacaoNovaRepository`
- [x] Binding registrado
- [x] ACL `middleware("modulo:importacao_nova")`
- [x] Shim Http deprecated

## Resultado

- [x] Aprovado com ressalvas
- Entry points (index/add/view/import/validacao/status/processar_arquivo) layered
- Processamento assíncrono completo (`processar_arquivo` worker / carga_*) **deferido** — reprocessar só reabre pending

### Diferido

- Job/worker de carga linha-a-linha
- Unit tests com fake de repository (opcional)
