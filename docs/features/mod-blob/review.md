# Review — mod-blob (Blob (arquivos))

> **Onda:** F · **ACL key:** `blob` + always-allowed `blob/download`

## Code review — camadas

- [x] `Interfaces\Http\Controllers\Admin\BlobController` (thin)
- [x] UseCase `DownloadBlob`
- [x] Domain `BlobFile` + `BlobRepositoryInterface`
- [x] Eloquent só em Infrastructure
- [x] Lookup `md5(id)` legado
- [x] `blob/download` em `always_allowed_actions`
- [x] Shim Http deprecated

## QA

- [x] Guest → login
- [x] Always-allowed helper
- [ ] Download com blob real — smoke manual

## Testes

- [x] `OndaFIntegrationRoutesTest`
- [x] `DownloadBlobTest`
- [ ] Suite Docker usuário

## Deferred

- Upload / index / delete / `blob_action`

## Resultado

- [x] Aprovado com ressalvas (Onda F layered)
