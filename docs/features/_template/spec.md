# Spec — [módulo]

> Onda: _ · ACL: `{controller}` · Legado: `legacy/app/Controller/{X}Controller.php`

## Objetivo

Portar `{controller}` para `/admin/{controller}` com as mesmas telas e regras do Cake.

## Telas (`.ctp` → Blade)

| Cake | Blade | Rota |
|------|-------|------|
| `admin_index.ctp` | `admin/{x}/index.blade.php` | `/admin/{x}` |
| `admin_view.ctp` | `show.blade.php` | `/view/{id}` |
| `admin_add.ctp` | `add.blade.php` | `/add/{id?}` |

Não inclui: redesign / BS4+ · alterar `legacy/`.

## Regras deste módulo

(Só o que o Cake faz **neste** controller/model — ACL genérico já está nas rules.)

1. …

## Dados

| Tabela | Tenant |
|--------|--------|
| `{tabela}` | `grupo_empresarial_id` / `cliente_id` / via relação |

## Aceite

- [ ] Cada `.ctp` do inventário tem Blade equivalente
- [ ] Rotas e ACL iguais ao Cake
- [ ] Skeleton SmartAdmin (jarviswidget, smart-form, IDs/JS) preservado
- [ ] Tenant aplicado no repo
