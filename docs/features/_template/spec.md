# Spec — [Nome da feature]

> Stack: PHP 8.4+ · Laravel · MySQL 8 · Blade (SmartAdmin/Bootstrap 3) · Docker / nginx

## Contexto

Por que essa feature existe? Qual problema de negócio resolve?

## Objetivo

Resultado esperado em 1–3 frases.

## Escopo

### Inclui

- …

### Não inclui

- …

## Atores / perfis

Quem usa? Mapear para os perfis do SAMED:

| Perfil | ID | Escopo típico |
|--------|----|---------------|
| Root | 1 | Tudo (todas contas) |
| Administrador | 2 | Cadastro e atualizações no GE |
| TI | 3 | Cadastro e atualizações no GE |
| Operador | 4 | Visualização (cliente selecionado) |
| Auditoria | 5 | Relatórios (todos clientes) |
| Backoffice | 6 | Relatórios (todos clientes) |
| Cliente | 7 | Gerencial da própria empresa |

- Perfis com acesso: …
- Módulo / menu afetado (`Modulo.controller`): …

## Regras de negócio

1. …
2. …

## Fluxo principal

1. Usuário acessa `/admin/{resource}`
2. …
3. …

## Fluxos alternativos / erros

- Se X acontecer → mensagem flash Y / redirect para …
- Sessão expirada → redirect login

## Dados

### Models / tabelas envolvidas

| Model | Tabela | Uso |
|-------|--------|-----|
| … | … | … |

### Campos novos ou alterados

- Migration `…`: coluna `…` (tipo, nullable, default)
- Preferir schema legado; migrations só para gaps

### Escopo multi-tenant

- Filtrar por `grupo_empresarial_id`? Sim / Não
- Filtrar por `cliente_id`? Sim / Não
- Usar scopes Eloquent de tenant? Sim / Não

### Integrações

- [ ] Importação de planilha (Excel/CSV)
- [ ] API REST (`routes/api.php`)
- [ ] Job / Schedule (`app/Console`)
- [ ] E-mail (Laravel Mail)
- [ ] Geração PDF

## Interface (admin)

| Action | URL | Descrição |
|--------|-----|-----------|
| `index` | `/admin/{resource}` | Listagem |
| `create`/`store` | `/admin/{resource}/create` | Cadastro |
| `show` | `/admin/{resource}/{id}` | Detalhe |
| … | … | … |

Layout: `resources/views/layouts/admin.blade.php`

## Critérios de aceite

- [ ] …
- [ ] …
- [ ] …

## Referências

- Issue / ticket:
- Controller/model de referência: …
- Legado (se portar): `legacy/app/Controller/…`
- Docs relacionadas:
