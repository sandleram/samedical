# Plan — mod-db (DB (utilitário))

> **Onda:** F · **ACL key:** `db`

## Abordagem

```
routes/web.php → Interfaces\Admin\DbController → GetDbIndex
  → DbSettingsInterface → ConfigDbSettings (SAMED_PHPMYADMIN_URL)
```

**Sanitização:** não portar host/usuário/senha do legado `admin_index.ctp`.

## Endpoints shipped

- `GET /admin/db` (Blade sanitizado)

## Deferred

- lista / gerencial / medico / rh / CRUD (cópia incompleta de BI no legado)

## Etapas

- [x] Camadas + binding
- [x] Blade sem secrets
- [x] Feature test
- [x] Docs
