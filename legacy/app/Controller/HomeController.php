<?php
App::uses('AppController', 'Controller');


class HomeController   extends AppController
{
    var $name = 'Home';

   

    function index() {}

    /**
     * Gerdau KPI
     * - Quando clicar no cliente gerdau, aparecer um kpi com a qtd de agendamentos pedentes de todas as filiais que o usuário tiver acesso.
     * - Monstar a lista identica a afastados
     * - Regras
     *    - Aparecer KPI somente com cliente
     *    - Agendamentos pendentes das filiais que o usuário tema acesso
     */

    function admin_index()
    {
        $this->set('title_for_layout', 'Dashboard');

        $this->loadModel('Afastado');
        $this->loadModel('BeneficioPrevidenciario');
        $this->loadModel('Absenteismo');
        $this->loadModel('Cliente');

        #BEGIN - NOTIFICAÇÃO DE MANUTENÇÃO DO SERVIDOR
        $idUsuario = $this->Session->read('Auth.Usuario.id');
        $chave = 'Notificacao.20260718.' . $idUsuario;

        $this->set('exibeNotificacao', false);

        if (
            date('Y-m-d H:i:s') <= '2026-07-12 04:00:00' &&
            !$this->Session->check($chave)
        ) {
            $this->Session->write($chave, true);
            $this->set('exibeNotificacao', true);
        }
        #END - NOTIFICAÇÃO DE MANUTENÇÃO DO SERVIDOR

        $listCliente = $this->Cliente->find('list', array('conditions' => array('grupo_empresarial_id in (' . $this->grupo_empresarial_id . ')')));

        $conditionCliente = implode(',', $listCliente);

        $conditions = array(
            'conditions' => array('Importacao.cliente_id in (' . $conditionCliente . ')'),
            'fields' => 'Importacao.cliente_id, DATE_FORMAT(Importacao.data_cadastro, "%m/%Y") as data, count(Importacao.cliente_id) as contador',
            'group' => array('Importacao.cliente_id', 'DATE_FORMAT(Importacao.data_cadastro, "%m/%Y")'),
            'recursive' => 1
        );

        $listAfastado = $this->Afastado->find('all', $conditions);
        $listBeneficioPrevidenciario = $this->BeneficioPrevidenciario->find('all', $conditions);
        $listAbsenteismo = $this->Absenteismo->find('all', $conditions);

        $notificacoes = array('afastado' => array(), 'beneficio_previdenciario' => array(), 'absenteismo' => array());

        #krumo($listAfastado);
        #krumo($listBeneficioPrevidenciario);

        if (count($listAfastado) > 0) {
            $lista = array();
            foreach ($listAfastado as $afastados) {
                $lista[$afastados['Importacao']['cliente_id']][] = $afastados[0]['contador'];
            }
            $notificacoes['afastado'] = $lista;
        }

        if (count($listBeneficioPrevidenciario) > 0) {
            $lista = array();
            foreach ($listBeneficioPrevidenciario as $beneficios) {
                $lista[$beneficios['Importacao']['cliente_id']][] = $beneficios[0]['contador'];
            }
            $notificacoes['beneficio_previdenciario'] = $lista;
        }

        if (count($listAbsenteismo) > 0) {
            $lista = array();
            foreach ($listAbsenteismo as $absenteismo) {
                $lista[$absenteismo['Importacao']['cliente_id']][] = $absenteismo[0]['contador'];
            }
            $notificacoes['absenteismo'] = $lista;
        }


        #krumo($notificacoes);


        #$this->Afastado->find('list',array('conditions'=>))

        #$this->grupo_empresarial_id
        $this->set('notificacoes', $notificacoes);
        #exit;


        $row['charts']['kpi']['total_beneficiarios'] = ['titulo' => 'Total de Beneficiários', 'valor' => 0, 'url' => ''];
        $row['charts']['kpi']['beneficiarios_ativos'] = ['titulo' => '', 'valor' => 0, 'url' => ''];
        $row['charts']['kpi']['afastados'] = ['titulo' => '', 'valor' => 0, 'url' => ''];
        $row['charts']['kpi']['importacoes'] = ['titulo' => 'Importações', 'valor' => 0, 'url' => ''];

        $allowed_users_kpi_gerdau = [1, 3, 261, 268, 290, 331]; //USUARIOS PARA ACESSAR O KPI DE GERDAU
        if ($this->Session->read('Auth.Usuario.grupo_empresarial_id') == 10 && in_array($this->Session->read('Auth.Usuario.id'), $allowed_users_kpi_gerdau)) {
            $row['charts']['kpi']['agendamentos_pendentes_gerdau'] = ['titulo' => 'Gerdau - Agendamentos Pendentes', 'valor' => 0, 'url' => ''];
        }
        if (in_array($this->Session->read('Auth.Usuario.perfil_id'), [1, 2, 3])) {
            $row['charts']['kpi']['agendametos_pendentes_atribuidos'] = ['titulo' => 'Agendamentos Pendentes Atribuidos', 'valor' => 0, 'url' => ''];
        }
        // $row['charts']['kpi']['beneficios_cessados'] = ['titulo'=>'Benefícios Cessados','valor'=>0,'url'=>''];
        // $row['charts']['kpi_gerencial']['beneficios_cessados_1'] = ['titulo'=>'Benefícios Cessados em 1 dia','valor'=>0,'url'=>''];
        // $row['charts']['kpi_gerencial']['beneficios_cessados_5'] = ['titulo'=>'Benefícios Cessados em 5 dias','valor'=>0,'url'=>''];
        // $row['charts']['kpi_gerencial']['beneficios_cessados_10'] = ['titulo'=>'Benefícios Cessados em 10 dias','valor'=>0,'url'=>''];




        $this->loadModel('Beneficiario');
        $count_benefciiario = $this->Beneficiario->find('count', array('conditions' => array('Beneficiario.cliente_id' => $this->cliente_id, 'Beneficiario.status' => 1)));
        $row['charts']['kpi']['total_beneficiarios'] = ['titulo' => 'Total de Beneficiários', 'valor' => $count_benefciiario, 'url' => Router::url('/admin/beneficiario', true)];

        $count_benefciiario_ativos = $this->Beneficiario->find('count', array('conditions' => array('Beneficiario.cliente_id' => $this->cliente_id, 'Beneficiario.situacao' => 'Ativo', 'Beneficiario.status' => 1)));
        $row['charts']['kpi']['beneficiarios_ativos'] = ['titulo' => 'Beneficiarios Ativos', 'valor' => $count_benefciiario_ativos, 'url' => Router::url('/admin/beneficiario/index/situacao:Ativo', true)];


        if (in_array($this->Session->read('Auth.Usuario.perfil_id'), [1, 2, 3])) {

            $this->loadModel('Agendamento');
            $count_agendametos_pendentes_atribuidos = $this->Agendamento->find('count', array(
                'conditions' => array(
                    'Agendamento.usuario_id' => $this->Session->read('Auth.Usuario.id'),
                    'Agendamento.status' => 0
                )
            ));
            #"MD5(CONCAT(Agendamento.usuario_id, '" . $this->hash_token . "'))" => $this->user_hash_token(),
            $row['charts']['kpi']['agendametos_pendentes_atribuidos'] = ['titulo' => 'Agendamentos Pendentes Atribuidos', 'valor' => $count_agendametos_pendentes_atribuidos, 'url' => Router::url('/admin/agendamento/index/search:atribuidos', true)];
        }







        $this->loadModel('Importacao');
        $count_importacao = $this->Importacao->find('count', array('conditions' => array('cliente_id' => $this->cliente_id)));
        $row['charts']['kpi']['importacoes'] = ['titulo' => 'Importações', 'valor' => $count_importacao, 'url' => Router::url('/admin/importacao', true)];

        $this->loadModel('Afastado');
        #$count_afastado = $this->Afastado->find('count',array('conditions'=>array('Beneficiario.cliente_id' =>$this->cliente_id),'group'=>array('Afastado.beneficiario_id')));
        $count_afastado = $this->Afastado->find('count', array('conditions' => array('Beneficiario.cliente_id' => $this->cliente_id, 'Afastado.situacao' => 'A'), 'group' => array('Afastado.beneficiario_id')));
        if ($count_afastado == '') {
            $count_afastado = 0;
        }
        $row['charts']['kpi']['afastados'] = ['titulo' => 'Beneficiários Afastados', 'valor' => $count_afastado, 'url' => Router::url('/admin/afastado', true)];

        if ($this->Session->read('Auth.Usuario.grupo_empresarial_id') == 10 && in_array($this->Session->read('Auth.Usuario.id'), $allowed_users_kpi_gerdau)) {
            $this->loadModel('Cliente');
            $list_cliente = $this->Cliente->find('list', array('conditions' => array('grupo_empresarial_id' => $this->Session->read('Auth.Usuario.grupo_empresarial_id'))));
            $conditionCliente = implode(',', $list_cliente);

            $sql = "SELECT COUNT(*) AS `count` 
                        FROM`agendamento` AS `Agendamento` 
                        LEFT JOIN `atendimento` AS `Atendimento` ON (`Agendamento`.`atendimento_id` = `Atendimento`.`id`) 
                        LEFT JOIN `usuario` AS `Usuario` ON (`Agendamento`.`usuario_id` = `Usuario`.`id`) 
                        LEFT JOIN `usuario` AS `UsuarioAgendamento` ON (`Agendamento`.`usuario_agendamento_id` = `UsuarioAgendamento`.`id`) 
                        left join `beneficiario` AS `Beneficiario` ON (`Atendimento`.`beneficiario_id` = `Beneficiario`.`id`)
                    WHERE `Beneficiario`.`cliente_id` in ({$conditionCliente}) AND `Agendamento`.`status` = 0;";

            $count_ag_pendente_gerdau = $this->Cliente->query($sql);
            $count_ag_pendente_gerdau = $count_ag_pendente_gerdau[0][0]['count'];

            $row['charts']['kpi']['agendamentos_pendentes_gerdau'] = ['titulo' => 'Gerdau - Agendamentos Pendentes', 'valor' => $count_ag_pendente_gerdau, 'url' => Router::url('/admin/agendamento/index/gerdau', true)];
        }


        $this->set('row', $row);
    }


    function dashboard()
    {
        $this->layout = 'grafico';

        //http://www.sacademico.com/dashboard/token:a58dfas86we
        //http://localhost/cinovacao/sacademico/dashboard/token:a58dfas86we
        //    krumo($this->params['named']['token']);
        //    exit();

        // if(!isset($this->params['named']['token'])){
        //     $this->redirect(array('controller'=>'usuario','action'=>'login'));
        // }else{
        //     $token = $this->params['named']['token'];
        //     $this->loadModel('Empresa');
        //     $rsEmpresa = $this->Empresa->find('first',array('conditions'=>array('token_tv'=>$token), 'fields'=>array('id'),'recursive'=>-1));

        //     if(count($rsEmpresa) > 0){
        //         $this->empresa_id = $rsEmpresa['Empresa']['id'];
        //     }else{
        //         $this->redirect(array('controller'=>'usuario','action'=>'login'));
        //     }        

        // }
        exit();
        // $this->admin_index();


    }
}
