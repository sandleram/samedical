# Review — mod-beneficiario (Beneficiário)

> **Onda:** A · **ACL:** `beneficiario`

## Camadas

- [x] Controller thin em `Interfaces\Http\Controllers\Admin`
- [x] Domain sem Laravel; Eloquent só no repo
- [x] Rotas Cake `/admin/beneficiario`, `/view/{id}`, `/add/{id?}`
- [x] ACL `modulo:beneficiario`
- [x] Tenant no repo (`cliente_id` → GE)

## Visual vs Cake

- [x] Index: `wid-id-12`, `smart-form form_ajax`, filtros, Timeline/Acessar, badges situação/status, Ações dropdown, paginação Cake
- [x] View: card resumido (avatar, CPF, cliente/empresa) + abas Timeline/Afastado/BP/Absenteísmo/Cadastro + dropdown Ações
- [x] Add: `well no-padding` + `smart-form` + Ações (espelho Cake)
- [x] Layout SmartAdmin / BS3

## Resultado

- [x] Aprovado com ressalvas

### Diferido

- `admin_all`, `admin_view2`, `admin_timeline_example`
- Checkbox / exclusão em massa (sem rota `delete`)
- Prefill `add/{beneficiario_id}` nos módulos filhos (Cake passava o id do beneficiário)
