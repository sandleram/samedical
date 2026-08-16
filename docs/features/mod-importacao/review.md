# Review — mod-importacao (Importação)

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade · Docker / nginx
>
> **Onda:** E · **ACL key:** `importacao`

## Code review — camadas

- [x] Controller em `App\Interfaces\Http\Controllers\Admin\ImportacaoController` (thin)
- [x] UseCases em `App\Application\Importacao`
- [x] Domain sem Laravel
- [x] Eloquent só em `EloquentImportacaoRepository`
- [x] Binding registrado (`AppServiceProvider`)
- [x] Blade BS3 / SmartAdmin
- [x] ACL `middleware("modulo:importacao")`
- [x] Shim Http deprecated

## QA / aceite

- [x] Entry points `/admin/importacao`, add, import, validacao
- [x] Upload grava arquivo + registro (carga_* deferida)
- [x] `tests/Feature/OndaERoutesTest.php`
- [ ] Smoke manual no Docker

## Resultado

- [x] Aprovado com ressalvas — **carga linha-a-linha (`carga_*`) deferida**

### Diferido

- Processamento síncrono completo (`admin_read_file_import`, `admin_carga_*`, validação linha-a-linha)
- Unit tests com fake de repository (opcional)
