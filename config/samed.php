<?php

/**
 * Configuração de domínio SAMED V2 (ACL, liberados, mapas de rota).
 * Espelha regras do legado AppController + bootstrap variaveis.project.
 */
return [

    'project' => env('SAMED_PROJECT', 'SAMed'),

    /*
    | Token REST Proativa (legado RestController::$token_proativa).
    | Sem valor no .env os endpoints falham fechados (não usa o secret legado no código).
    */
    'rest' => [
        'token' => env('SAMED_REST_TOKEN', ''),
    ],

    /*
    | Token WS (opcional). Se vazio, reutiliza rest.token.
    */
    'ws' => [
        'token' => env('SAMED_WS_TOKEN') ?: env('SAMED_REST_TOKEN', ''),
    ],

    /*
    | Link externo phpMyAdmin / utilitário DB (sem credenciais na UI).
    */
    'db' => [
        'phpmyadmin_url' => env('SAMED_PHPMYADMIN_URL', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Níveis perfil_modulo.permissao (0–3) — constantes + significado
    |--------------------------------------------------------------------------
    */
    'permission_levels' => [
        'none' => 0,
        'view' => 1,
        'manage' => 2,
        'delete' => 3,
    ],

    'permission_level_labels' => [
        0 => 'Negado — sem acesso ao módulo',
        1 => 'Visualizar — index / view (leitura)',
        2 => 'Gerenciar — add / edit / create / update',
        3 => 'Full — inclui delete / destroy',
    ],

    /*
    | Controllers sempre liberados no admin (AppController $control_allowed).
    */
    'always_allowed_controllers' => [
        'home',
        'kcfinder',
    ],

    /*
    | Actions finas sempre liberadas ($control_action_allowed).
    */
    'always_allowed_actions' => [
        'grupo_empresarial/selecione',
        'usuario/atualiza_session_cliente',
        'blob/download',
    ],

    /*
    | Nível mínimo por action Laravel (e aliases admin_* do legado).
    | index/view ≥1; add/edit/create/update ≥2; delete ≥3
    */
    'action_min_levels' => [
        'index' => 1,
        'show' => 1,
        'view' => 1,
        'create' => 2,
        'store' => 2,
        'add' => 2,
        'edit' => 2,
        'update' => 2,
        'destroy' => 3,
        'delete' => 3,
    ],

    /*
    | Mapa rota nomeada → chave de módulo (Modulo.controller).
    */
    'route_module_map' => [
        'admin.home' => 'home',
        'admin.beneficiario.index' => 'beneficiario',
        'admin.beneficiario.view' => 'beneficiario',
        'admin.beneficiario.add' => 'beneficiario',
        'admin.beneficiarios.index' => 'beneficiario',
        'admin.beneficiarios.show' => 'beneficiario',
        'admin.grupo_empresarial.index' => 'grupo_empresarial',
        'admin.grupo_empresarial.view' => 'grupo_empresarial',
        'admin.grupo_empresarial.add' => 'grupo_empresarial',
        'admin.grupo_empresarial.selecione' => 'grupo_empresarial',
        'admin.cliente.index' => 'cliente',
        'admin.cliente.view' => 'cliente',
        'admin.cliente.add' => 'cliente',
        'admin.empresa.index' => 'empresa',
        'admin.empresa.view' => 'empresa',
        'admin.empresa.add' => 'empresa',
        'admin.usuario.index' => 'usuario',
        'admin.usuario.view' => 'usuario',
        'admin.usuario.add' => 'usuario',
        'admin.usuario.atualiza_session_cliente' => 'usuario',
        'admin.perfil.index' => 'perfil',
        'admin.perfil.view' => 'perfil',
        'admin.perfil.add' => 'perfil',
        'admin.modulo.index' => 'modulo',
        'admin.modulo.view' => 'modulo',
        'admin.modulo.add' => 'modulo',
        'admin.blob.download' => 'blob',
        'admin.db.index' => 'db',
        'admin.mh_critico.index' => 'mh_critico',
        'admin.mh_critico.view' => 'mh_critico',
        'admin.mh_critico.add' => 'mh_critico',
        'admin.mh_critico_historico.index' => 'mh_critico_historico',
        'admin.mh_critico_historico.view' => 'mh_critico_historico',
        'admin.mh_critico_historico.add' => 'mh_critico_historico',
        'admin.mh_negociacao.index' => 'mh_negociacao',
        'admin.mh_negociacao.view' => 'mh_negociacao',
        'admin.mh_negociacao.add' => 'mh_negociacao',
        'admin.mh_prestador.index' => 'mh_prestador',
        'admin.mh_prestador.view' => 'mh_prestador',
        'admin.mh_prestador.add' => 'mh_prestador',
        'admin.operadora.index' => 'operadora',
        'admin.operadora.view' => 'operadora',
        'admin.operadora.add' => 'operadora',
        'admin.plano.index' => 'plano',
        'admin.plano.view' => 'plano',
        'admin.plano.add' => 'plano',
        'admin.parametro.index' => 'parametro',
        'admin.parametro.view' => 'parametro',
        'admin.parametro.add' => 'parametro',
        'admin.log.index' => 'log',
        // Onda C
        'admin.tipo_beneficio.index' => 'tipo_beneficio',
        'admin.tipo_beneficio.view' => 'tipo_beneficio',
        'admin.tipo_beneficio.add' => 'tipo_beneficio',
        'admin.procedimento.index' => 'procedimento',
        'admin.procedimento.view' => 'procedimento',
        'admin.procedimento.add' => 'procedimento',
        'admin.beneficio.index' => 'beneficio',
        'admin.beneficio.view' => 'beneficio',
        'admin.beneficio.add' => 'beneficio',
        'admin.subfatura.index' => 'subfatura',
        'admin.subfatura.view' => 'subfatura',
        'admin.subfatura.add' => 'subfatura',
        'admin.afastado.index' => 'afastado',
        'admin.afastado.view' => 'afastado',
        'admin.afastado.add' => 'afastado',
        'admin.absenteismo.index' => 'absenteismo',
        'admin.absenteismo.view' => 'absenteismo',
        'admin.absenteismo.add' => 'absenteismo',
        'admin.atendimento.index' => 'atendimento',
        'admin.atendimento.view' => 'atendimento',
        'admin.atendimento.add' => 'atendimento',
        'admin.beneficio_previdenciario.index' => 'beneficio_previdenciario',
        'admin.beneficio_previdenciario.view' => 'beneficio_previdenciario',
        'admin.beneficio_previdenciario.add' => 'beneficio_previdenciario',
        'admin.agendamento.index' => 'agendamento',
        'admin.agendamento.view' => 'agendamento',
        'admin.agendamento.add' => 'agendamento',
        // Onda E
        'admin.importacao.index' => 'importacao',
        'admin.importacao.add' => 'importacao',
        'admin.importacao.import' => 'importacao',
        'admin.importacao.validacao' => 'importacao',
        'admin.importacao_nova.index' => 'importacao_nova',
        'admin.importacao_nova.view' => 'importacao_nova',
        'admin.importacao_nova.add' => 'importacao_nova',
        'admin.importacao_nova.import' => 'importacao_nova',
        'admin.importacao_nova.validacao' => 'importacao_nova',
        'admin.importacao_nova.status' => 'importacao_nova',
        'admin.importacao_nova.processar_arquivo' => 'importacao_nova',
        'admin.bi.index' => 'bi',
        'admin.bi.lista' => 'bi',
        'admin.bi.gerencial' => 'bi',
        'admin.bi.medico' => 'bi',
        'admin.bi.rh' => 'bi',
        'admin.bi.view' => 'bi',
        'admin.bi.add' => 'bi',
        'admin.relatorio.index' => 'relatorio',
        'admin.relatorio.afastados' => 'relatorio',
        'admin.relatorio.beneficiarios' => 'relatorio',
        'admin.relatorio.atendimentos_pendentes' => 'relatorio',
        'admin.relatorio.gerencial' => 'relatorio',
        'admin.relatorio.exportacao' => 'relatorio',
        'admin.relatorio.fatura' => 'relatorio',
        'admin.relatorio.sinistro' => 'relatorio',
        'admin.relatorio.movimentacao_beneficiario' => 'relatorio',
        'admin.relatorio.movimentacao_sinistro' => 'relatorio',
        'admin.relatorio.movimentacao_fatura' => 'relatorio',
        'admin.relatorio.down' => 'relatorio',
    ],

];
