# Plan — mod-blob (Blob (arquivos))

> **Onda:** F · **ACL key:** `blob` + always-allowed `blob/download`

## Abordagem

```
routes/web.php → Interfaces\Admin\BlobController → DownloadBlob
  → BlobRepositoryInterface → EloquentBlobRepository → stream
```

Respeitar `config/samed.php` → `always_allowed_actions` → `blob/download`.

## Endpoints shipped

- `GET /admin/blob/download/{id}` (md5 do id numérico)

## Deferred

- Upload / `blob_action` / index / delete

## Etapas

- [x] Camadas + binding
- [x] Rota + always-allowed
- [x] Feature + unit (`DownloadBlobTest`)
- [x] Docs
