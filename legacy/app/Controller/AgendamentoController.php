<?php
App::uses('AppController', 'Controller');
/**
 * Agendamento Controller
 *
 * @property BeneficioPrevidenciario $BeneficioPrevidenciario
 * @property PaginatorComponent $Paginator
 */
class AgendamentoController extends AppController
{
    public  $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public  $msg_nao_existe = 'Agendamento Inexistente';
    public  $msg_salvo = 'O Agendamento foi SALVO com sucesso!';
    public  $msg_salvo_erro = 'Não foi possível SALVAR o Agendamento, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public  $msg_excluido = 'O Agendamento foi EXCLUÍDO com sucesso ';
    public  $msg_excluido_erro = 'Não foi possível EXCLUIR o Agendamento, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    public  $setFlash = '';


    /**
     * AUTOMÁTICO
     * Retira da sessão a busca feita pelo usuário Element/admin/search_filter.ctp
     * @param type $search
     */
    public function admin_busca_unset($search)
    {
        $this->autoRender = false;
        parent::all_busca_unset($search, $this->name_search);
        $this->redirect(array('action' => 'admin_index'));
    }

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->table = 'Agendamento';
        // $this->loadModel('HmPrograma');
        // $dataArr = $this->HmPrograma->find('list', array('conditions' => array('status' => 1, 'list' => 1), 'fields' => 'cod_hm_programa,nome', 'recursive' => -1));
        // $dataDef = ($this->params['action'] == 'admin_index') ? 'Programa...' : 'Selecione...';
        // $programaArr = $this->Funcoes->select_merge($dataArr, $dataDef);
        // $this->set('programaArr', $programaArr);
        $this->name_search = 'pesquisa_' . $this->params['controller'];
        $this->set('name_search', $this->name_search);

        #CONVERSÃO PARA ARQUIVOS COM UNDERLINE
        $control_verify = $this->params['controller'];
        $control_verify = str_replace('_', ' ', $control_verify);
        $control_verify = ucwords($control_verify);
        $control_verify = str_replace(' ', '', $control_verify);
        $this->table = $control_verify;
        $this->set('TABLE', $control_verify);
    }

    /**
     * AUTOMÁTICO
     * Retira da sessão a busca feita pelo usuário Element/admin/search_filter.ctp
     * @param type $search
     */
    public function busca_unset($search)
    {
        $this->autoRender = false;
        parent::all_busca_unset($search, $this->name_search);
        $this->redirect(array('action' => 'admin_index'));
    }

    // public function admin_index($cod_hm_prontuario = null) {

    public function admin_index($filtro_grupo_empresarial = null)
    {
        $allowed_filter_grupo_empresarial = array(10); //para o grupo empresarial Gerdau
        // if($filtro_grupo_empresarial != null && in_array($this->Session->read('Auth.Usuario.grupo_empresarial_id'), $allowed_filter_grupo_empresarial)){

        // $this->loadModel('Cliente');
        // $list_cliente = $this->Cliente->find('list',array('conditions'=>array('grupo_empresarial_id'=>$this->Session->read('Auth.Usuario.grupo_empresarial_id'))));
        // $conditionCliente = implode(',',$list_cliente);

        // $sql = "SELECT COUNT(*) AS `count` 
        //             FROM`agendamento` AS `Agendamento` 
        //             LEFT JOIN `atendimento` AS `Atendimento` ON (`Agendamento`.`atendimento_id` = `Atendimento`.`id`) 
        //             LEFT JOIN `usuario` AS `Usuario` ON (`Agendamento`.`usuario_id` = `Usuario`.`id`) 
        //             LEFT JOIN `usuario` AS `UsuarioAgendamento` ON (`Agendamento`.`usuario_agendamento_id` = `UsuarioAgendamento`.`id`) 
        //             left join `beneficiario` AS `Beneficiario` ON (`Atendimento`.`beneficiario_id` = `Beneficiario`.`id`)
        //         WHERE `Beneficiario`.`cliente_id` in ({$conditionCliente}) AND `Agendamento`.`status` = 1;";

        // $count_ag_pendente_gerdau = $this->Cliente->query($sql);
        // $count_ag_pendente_gerdau = $count_ag_pendente_gerdau[0][0]['count'];
        // }   




        #BEGIN - BUSCA
        if ($this->request->is('post')):

            if (isset($this->data[$this->params['controller'] . '_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'] . '_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;

        $search = $this->Session->read($this->name_search);
        // krumo($search);
        // exit;
        $condition = array();
        $condition[] = $this->table . ".status < 2"; #2 É EXCLUÍDO
        if (!in_array($this->Session->read('Auth.Usuario.perfil_id'), [1, 2, 3, 13])) {
            $condition[] = $this->table . '.usuario_agendamento_id = ' . $this->Session->read('Auth.Usuario.id');
        }

        if (is_array($search) || isset($this->params['named']['situacao'])):
            if (!empty($search['cod_']) && is_numeric($search['cod_'])):
                $condition[] = array($this->table . '.id = "' . $search['cod_'] . '"');
            endif;
            // if ($search['usuario_agendamento_id'] != ''):
            //     $condition[] = 'HmHistorico.cod_hm_programa = ' . $search['cod_hm_programa'];
            // endif;
            if (in_array($this->Session->read('Auth.Usuario.perfil_id'), [1, 2, 3, 13])) {
                if (isset($search['usuario_agendamento_id']) && $search['usuario_agendamento_id'] != ''):
                    $condition[] = $this->table . '.usuario_agendamento_id = ' . $search['usuario_agendamento_id'];
                endif;
            }
            // if (in_array($this->Session->read('Auth.Usuario.perfil_id'), [1, 2, 3, 13])) {
            //     if (isset($search['usuario_id']) && $search['usuario_id'] != ''):
            //         $condition[] = $this->table . '.usuario_id = ' . $search['usuario_id'];
            //     endif;
            // }
            if (isset($search['status']) && $search['status']  != ''):
                $condition[] = $this->table . '.status = ' . $search['status'];
            endif;
        elseif (isset($_GET['status'])):
        // $condition[] = $this->table . '.status = 0';
        // $search = array();
        // $search['cod_'] = '';
        // $search['usuario_agendamento_id'] = '';
        // $search['status'] = 0;
        endif;


        #BUSCA POR GET
        if ((isset($this->params['named']['search']) && $this->params['named']['search'] == 'atribuidos') || (isset($search['search']) && $search['search'] == 'atribuidos')):
            #$condition[] = $this->table .'Agendamento.md5(concat(usuario_id, '.$this->hash_token.')) = "' . $this->user_hash_token() . '"' ;
            #s$condition[] = "MD5(CONCAT(Agendamento.usuario_id, '" . $this->hash_token . "')) = '" . $this->user_hash_token() . "'";
            $condition[] = "Agendamento.usuario_id = '" . $this->Session->read('Auth.Usuario.id') . "'";
            $condition[] = "Agendamento.status = 0";
            $search['search'] = 'atribuidos';
        endif;

        $this->set('search', $search);
        #END - BUSCA
        #PARA NATURA
        #if (in_array($_SESSION['cod_usuario'], array(360430, 360429, 337705, 358487, 361774, 363733, 366616, 368812))) {
        #    $condition['OR'] = array($this->table . '.cod_usuario = ' . $_SESSION['cod_usuario'], $this->table . '.cod_usuario_agendamento = ' . $_SESSION['cod_usuario']);
        #} else {
        // if ($_SESSION['cod_conta'] != 44) {
        //     $condition['OR'] = array($this->table . '.cod_usuario = ' . $_SESSION['cod_usuario'], $this->table . '.cod_usuario_agendamento = ' . $_SESSION['cod_usuario']);
        // }
        #}



        if ($filtro_grupo_empresarial != null && in_array($this->Session->read('Auth.Usuario.grupo_empresarial_id'), $allowed_filter_grupo_empresarial)) {
            $this->loadModel('Cliente');
            $list_cliente = $this->Cliente->find('list', array('conditions' => array('grupo_empresarial_id' => $this->Session->read('Auth.Usuario.grupo_empresarial_id'))));
            $conditionCliente = implode(',', $list_cliente);

            $condition = array(
                'Beneficiario.cliente_id IN (' . $conditionCliente . ')',
                'Agendamento.status' => 0
            );

            #alteração feita através do model Agendamento
            // $this->paginate = array(
            //     'Agendamento' => array(
            //         'joins' => array(
            //             array(
            //                 'table' => 'atendimento',
            //                 'alias' => 'Atendimento',
            //                 'type' => 'LEFT',
            //                 'conditions' => array('Agendamento.atendimento_id = Atendimento.id')
            //             ),
            //             array(
            //                 'table' => 'usuario',
            //                 'alias' => 'Usuario',
            //                 'type' => 'LEFT',
            //                 'conditions' => array('Agendamento.usuario_id = Usuario.id')
            //             ),
            //             array(
            //                 'table' => 'usuario',
            //                 'alias' => 'UsuarioAgendamento',
            //                 'type' => 'LEFT',
            //                 'conditions' => array('Agendamento.usuario_agendamento_id = UsuarioAgendamento.id')
            //             ),
            //             array(
            //                 'table' => 'beneficiario',
            //                 'alias' => 'Beneficiario',
            //                 'type' => 'LEFT',
            //                 'conditions' => array('Atendimento.beneficiario_id = Beneficiario.id')
            //             )
            //         ),
            //         'conditions' => $condition,
            //         'fields' => array(
            //             'Agendamento.*',
            //             'Atendimento.*',
            //             'Usuario.*',
            //             'UsuarioAgendamento.*',
            //             'Beneficiario.*'
            //         ),
            //         'limit' => 10,
            //         'order' => array(
            //             'Agendamento.status' => 'asc',
            //             'Agendamento.data_hora' => 'asc'
            //         ),
            //         'recursive' => -1 // Usa recursive baixo porque as joins já trazem os dados
            //     )
            // );


        } else {
            // $this->paginate = array(
            //     'Agendamento' =>
            //     array(
            //         'conditions' => $condition,
            //         'limit' => 10,
            //         'order' => array('Agendamento.status' => 'asc', 'Agendamento.data_hora' => 'asc'),
            //         'recursive' => 3
            //     )
            // );


        }




        $this->Paginator->settings = array(
            'Agendamento' => array(
                'limit' => 20,
                'order' => array(
                    'Agendamento.status' => 'asc',
                    'Agendamento.data_hora' => 'asc'
                ),
                // Essas conditions serão convertidas em SQL dentro do paginate() do Model
                'conditions' => $condition,
                'paramType' => 'querystring'
            )
        );

        // Use o Paginator normalmente
        $rows = $this->Paginator->paginate('Agendamento');




        // krumo($condition);
        // krumo($rows);
        // debug($this->Agendamento->getDataSource()->getLog(false, false));
        // exit;
        //        exit;
        $this->set('rows', $rows);

        #busca usuários
        #$usrList = array(''=>'Responsável...');
        // if ($_SESSION['cod_conta'] == 44) {
        //     $agListArr = $this->HmAgendamento->find('list',array('conditions'=>array('HmAgendamento.status < 2'),'fields'=>'cod_usuario_agendamento','recursive'=>-1,'group'=>'cod_usuario_agendamento'));
        //     if(count($agListArr) > 0){
        //         $this->loadModel('DadoPessoal');
        //         foreach($agListArr as $cod_usuario_agendamento){

        //             $dpN = $this->DadoPessoal->find('first',array('conditions'=>array('cod_usuario'=>$cod_usuario_agendamento),'fields'=>'nome','recursive'=>-1));
        //             if($dpN > 0){
        //                 $usrList[$cod_usuario_agendamento] = ucwords(strtolower($this->Funcoes->tirar_acentos($dpN['DadoPessoal']['nome'])));
        //             }else{
        //                 $usrList[$cod_usuario_agendamento] = $cod_usuario_agendamento;
        //             }
        //         }
        //         asort($usrList);
        //     }
        //     #$usrList = array_unshift($usrList,'Responsável');
        // }





        $this->loadModel('Usuario');

        $perfis_qv = '1,2,3,6,7,8,12,13';
        $dataArr = $this->Usuario->find('all', array(
            #'conditions' => array('Usuario.id <> '.$this->Session->read('Auth.Usuario.id') ,'Usuario.perfil_id IN (' . $perfis_qv . ')', 'Usuario.status' => 1), 
            #'conditions' => array('Usuario.id <> '.$this->Session->read('Auth.Usuario.id') ,'Usuario.perfil_id IN (' . $perfis_qv . ')', 'Usuario.status' => 1) , 
            'conditions' => array('Usuario.perfil_id IN (' . $perfis_qv . ')', 'Usuario.status' => 1),
            'field' => 'Usuario.id,Usuario.nome',
            'order' => array('Usuario.usuario'),
            'recursive' => -1
        ));
        #$dataArr = $this->Usuario->find('all', array('conditions' => array('Usuario.id <> '.$this->Session->read('Auth.Usuario.id') ,'Usuario.perfil_id IN (' . $perfis_qv . ')', 'Usuario.status' => 1), 'field' => 'Usuario.id,Usuario.nome', 'order' => array('Usuario.usuario')));
        #,'UsuarioCliente.cliente_id'=>$this->Session->read('Auth.Usuario.cliente_id')
        #,'recursive'=>3

        $usrListAll = array();
        if (count($dataArr) > 0) {
            $cod_user_not_allowed = array(276076, 345841, 313678, 275725, 8, 9);
            foreach ($dataArr as $usersArr) {
                if (!in_array($usersArr['Usuario']['id'], $cod_user_not_allowed)) {
                    $usrListAll[$usersArr['Usuario']['id']] = ucwords(strtolower($usersArr['Usuario']['nome']));
                }
            }
        }

        $usrList = $this->Funcoes->select_merge($usrListAll, 'Responsável...');
        $usrListQuemCriou = $this->Funcoes->select_merge($usrListAll, 'Quem Criou...');


        // krumo($usrList);
        // exit;
        $this->set('usrList', $usrList);
        $this->set('usrListQuemCriou', $usrListQuemCriou);
    }



    #public function admin_add($cod_hm_prontuario = null, $id = null) {
    public function admin_add($beneficiario_id = null, $id = null)
    {
        $TABLE = $this->table;


        /**
         * CRIA UM AGENDAMENTO A PARTIR DE UM PRONTUÁRIO
         * Quando gerar um agendamento, criar um atendimento com status de pendência
         * verificar se tem um programa já existente para a pessoa assumir ou se realmente vai agendar um.
         */
        $this->loadModel('Beneficiario');

        if (!$this->Beneficiario->exists($beneficiario_id)) {
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect($this->referer());
        }


        #BUSCA DADO PESSOAL BREADCRUMB
        #$this->HmProntuario->unBindModel(array('hasMany' => array('HmHistorico')));
        $Beneficiario = $this->Beneficiario->find('first', array('conditions' => array('id' => $beneficiario_id), 'recursive' => -1));
        $this->set('nome', $Beneficiario['Beneficiario']['nome']);
        $this->set('beneficiario_id', $beneficiario_id);


        if ($this->request->is(array('post', 'put'))) {
            $data = $this->data['Agendamento'];
            $dataSource = $this->$TABLE->getDataSource();
            try {
                $dataSource->begin();
                $dateTimeNow = date('Y-m-d H:i:s');
                $usuario_id = $this->Session->read('Auth.Usuario.id');




                #GRAVAR NOVO EMPRESA_ATENDIMENTO
                #GERAR HISTÓRICO NO ATENDIMENTO
                #GRAVAR CÓDIGO DO ATENDIMENTO NA TABELA HMHISTORICO
                #BEGIN - CRIA ATENDIMENTO *********************
                #ETAPAS
                #   - GRAVAR NOVO ATENDIMENTO
                #   - GRAVAR NOVO EMPRESA_ATENDIMENTO
                #   - GERAR HISTÓRICO NO ATENDIMENTO
                #   - GRAVAR NOVA TAREFA (AGENDA)
                /**
                 * STATUS ATENDIMENTO 
                 *  0 - Sem contato
                 *  1 - Deixou recado
                 *  2 - Concluído
                 *  4 - Aguardando execução
                 */

                $usuario_agendamento_id = $usuario_id;
                if ($data['usuario_agendamento_proprio_id'] == 0) {
                    $usuario_agendamento_id = $data['usuario_agendamento_id'];
                }

                if ($data['descricao'] != '') {
                    $allowedTags = "<br><p><b><strong><i><u><a><ul><ol><li><em><sub><sup><s>";
                    $data['descricao'] = strip_tags($data['descricao'], $allowedTags);
                }

                $this->loadModel('Atendimento');
                $this->Atendimento->create();
                $dataAt = array();
                $dataAt['id'] = '';
                $dataAt['descricao'] = '';
                $dataAt['beneficiario_id'] = $beneficiario_id;
                // $dataAt['descricao_agendamento'] = $data['descricao'];
                $dataAt['status_atendimento'] = 3; // 0 - sem contato | 1 - Deixou recado | 2 - Concluído | 3 - Aguardando Execução
                $dataAt['usuario_id'] = $usuario_agendamento_id;
                $dataAt['status'] = 1; //0 - EM ANDAMENTO | 1 - CONCLUÍDO | 2 - CANCELADO | 3 - EM ANDAMENTO
                $dataAt['data_cadastro'] = $dateTimeNow;

                if (!$this->Atendimento->save($dataAt)) {
                    throw new Exception();
                }
                $atendimento_id = $this->Atendimento->id;





                $data_agendamento = $data['data_hora']['year'] . '-' . $data['data_hora']['month'] . '-' . $data['data_hora']['day'] . ' ' . $data['data_hora']['hour'] . ':' . $data['data_hora']['min'] . ':00';




                #BEGIN - CRIA TAREFA VS (AGENDAMENTO)
                if ($data['data_hora']['hour'] > 15) {
                    $data['data_hora']['hour'] = 8;
                }

                $tarefa_id = '';


                #GRAVAR AGENDAMENTO
                $this->$TABLE->create();
                $dataAg = array();
                $dataAg['id'] = '';
                $dataAg['descricao'] = $data['descricao'];
                $dataAg['data_hora'] = $data_agendamento;
                $dataAg['data_cadastro'] = $dateTimeNow;
                $dataAg['status'] = 0; //0 - PENDENTE | 1 - CONCLUÍDO | 2 - CANCELADO
                $dataAg['usuario_id'] = $usuario_id;
                $dataAg['usuario_agendamento_id'] = $usuario_agendamento_id;
                $dataAg['atendimento_id'] = $atendimento_id;
                $dataAg['tarefa_id'] = $tarefa_id;

                if (!$this->$TABLE->save($dataAg)) {
                    throw new Exception();
                }
                $agendamento_id = $this->$TABLE->id;



                $this->Session->setFlash($this->msg_salvo);
                $dataSource->commit();

                $this->loadModel('Log');
                $this->Log->create();
                $vLog = [
                    'Atendimento' => $dataAt,
                    'Agendamento' => $dataAg,
                    'agendamento_id' => $agendamento_id
                ];
                $data_log = array(
                    'id' => '',
                    'log' => 'Agendamento - Cadastro',
                    'description'         =>  json_encode($vLog),
                    'mensagem'            =>  $this->msg_salvo,
                    'server_description'  =>  json_encode($this->params),
                    'data_cadastro'       =>  date('Y/m/d H:i:s'),
                    'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);

                $this->redirect(array('controller' => 'beneficiario', 'action' => 'admin_view', $beneficiario_id));
                #$this->redirect($this->referer());

            } catch (Exception $ex) {

                # debug($this->Atendimento->getDataSource()->getLog(false, false));
                # exit;
                $flash = '';
                $dataSource->rollback();
                if (count($this->$TABLE->validationErrors) > 0) {
                    $errorDB = $this->$TABLE->validationErrors;
                    foreach ($errorDB as $kerror => $error) {
                        if ($error[0] == 'notEmpty') {
                            $flash .= 'Erro: O campo ' . $kerror . ' não pode ser vazio!<br />';
                        }
                    }
                }

                if ($flash == '') {
                    $flash = 'Erro: ' . $this->msg_salvo_erro;
                }

                $this->Session->setFlash($flash);
                #BEGIN - CRIANDO LOG ERRO
                $this->loadModel('LogErro');
                $this->LogErro->create();
                $data_log = array(
                    'id' => '',
                    'log' => 'Agendamento - Erro - Cadastro',
                    'description'         =>  json_encode($this->data),
                    'mensagem'            =>  $flash,
                    'server_description'  =>  json_encode($this->params),
                    'data_cadastro'       =>  date('Y/m/d H:i:s'),
                    'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                );
                $this->LogErro->save($data_log);
                #END - CRIANDO LOG ERRO

                #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
                $this->Session->write('error_form', $this->data);
                $this->redirect($this->referer());
                #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
            }
            #END - TRANSACTION
        }


        #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
        if (!$this->request->is(array('post', 'put'))) {
            $error_form = $this->Session->read('error_form');
            $this->Session->delete('error_form');
            if (is_array($error_form)) {
                $data_new = array_merge($this->data, $error_form);
                $this->request->data = $data_new;
            }
        }
        #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO


        #BUSCA EMPRESAS
        $this->loadModel('Usuario');
        $this->loadModel('UsuarioCliente');

        $perfis_qv = '1,2,3,6,7,8,12,13';

        $dataArr = $this->UsuarioCliente->find('all', array(
            'conditions' => [
                'UsuarioCliente.cliente_id' => $this->Session->read('Auth.Usuario.cliente_id'),
                'Usuario.perfil_id IN (' . $perfis_qv . ')',
                'Usuario.id <> ' . $this->Session->read('Auth.Usuario.id'),
                'Usuario.status' => 1
            ],
            'fields' => 'Usuario.id, Usuario.nome, Usuario.perfil_id, UsuarioCliente.cliente_id,UsuarioCliente.usuario_id',
            'order' => ['Usuario.usuario'],
            'recursive' => 1
        ));

        // $dataArr = $this->Usuario->find('all', array(
        //     #'conditions' => array('Usuario.id <> '.$this->Session->read('Auth.Usuario.id') ,'Usuario.perfil_id IN (' . $perfis_qv . ')', 'Usuario.status' => 1), 
        //     'conditions' => array('Usuario.id <> '.$this->Session->read('Auth.Usuario.id') ,'Usuario.perfil_id IN (' . $perfis_qv . ')', 'Usuario.status' => 1,'Usuario.grupo_empresarial_id in ('.$this->Session->read('Auth.Usuario.grupo_empresarial_id').',1,4,5)') , 
        //     'field' => 'Usuario.id,Usuario.nome', 
        //     'order' => array('Usuario.usuario'),
        //     'recursive'=>3  
        // ));
        #debug($this->$TABLE->getDataSource()->getLog(false, false));
        #,'UsuarioCliente.cliente_id'=>$this->Session->read('Auth.Usuario.cliente_id')
        #,'recursive'=>3
        // krumo(array('Usuario.id <> '.$this->Session->read('Auth.Usuario.id') ,'Usuario.perfil_id IN (' . $perfis_qv . ')', 'Usuario.status' => 1,'Usuario.grupo_empresarial_id in ('.$this->Session->read('Auth.Usuario.grupo_empresarial_id').',1,4)'));


        $usuariosAgendaArr = array();
        if (count($dataArr) > 0) {
            $cod_user_not_allowed = array(276076, 345841, 313678, 275725, 8, 9);
            foreach ($dataArr as $usersArr) {
                if (!in_array($usersArr['Usuario']['id'], $cod_user_not_allowed)) {
                    $usuariosAgendaArr[$usersArr['Usuario']['id']] =  ucwords(strtolower($usersArr['Usuario']['nome']));
                }
            }
        }


        $usuariosAgendaArr = $this->Funcoes->select_merge($usuariosAgendaArr, 'Selecione...');
        $this->set('usuariosAgendaArr', $usuariosAgendaArr);
        // krumo($usuariosAgendaArr);
        // exit;

    }

    // public function view($id = null) {
    //     $TABLE = $this->table;  

    //     if (!$this->$TABLE->exists($id)) {
    //         $this->Session->setFlash($this->msg_nao_existe);
    //         $this->redirect(array('action' => 'admin_index'));
    //     }

    //     $options = array('conditions' => array($TABLE . '.' . $this->$TABLE->primaryKey => $id));

    //     $row = $this->$TABLE->find('first', $options);
    //     $this->set('row', $row);
    // }

    public function delete($id = null)
    {
        $TABLE = $this->table;



        /**
         * CANCELAMENTO CANCELA
         * - AGENDA
         * - TAREFA
         * - E MUDA STATUS DO HISTÓRICO PARA CANCELADO.
         *
         */
        if ($id !== null) { #EXCLUSÃO UNITÁRIA
            $this->$TABLE->id = $id;

            if (!$this->$TABLE->exists($id)) {
                $this->Session->setFlash($this->msg_nao_existe);
            } else {
                $data[$TABLE][$this->$TABLE->primaryKey] = $id;
                $data[$TABLE]['status'] = 2;
                if ($this->$TABLE->save($data)) {
                    $setFlash = $this->msg_excluido;
                    $this->Session->setFlash($setFlash);
                } else {
                    $setFlash = $this->msg_excluido_erro;
                    $this->Session->setFlash($setFlash);
                }
                #GRAVA LOG
                $return_log = parent::grava_log($TABLE . ' - Exclusão Individual', $this->data, $setFlash);
            }
        } else { #EXCLUSÃO MULTIPLA
            if (isset($this->params['named']['ids']) && $this->params['named']['ids'] != '') {
                $idsArr = explode('_', $this->params['named']['ids']);
                $setFlash = '';
                $data = array();
                foreach ($idsArr as $id) {
                    if ($id != '') {
                        $this->$TABLE->id = $id;
                        if (!$this->$TABLE->exists($id)) {
                            $setFlash .= 'COD:' . $id . ' - Não Existe <br />';
                        } else {
                            $data[$TABLE][$this->$TABLE->primaryKey] = $id;
                            $data[$TABLE]['status'] = 2;
                            if ($this->$TABLE->save($data)) {
                                $setFlash .= 'O COD: ' . $id . ' foi EXCLUÍDO com sucesso! <br />';
                            } else {
                                $setFlash .= 'O COD: ' . $id . ' não pode ser EXCLUÍDO! Por favor, tente novamente.  <br /> ';
                            }
                        }
                        #GRAVA LOG
                        $return_log = parent::grava_log($TABLE . ' - Exclusão Multipla', $this->data, $setFlash);
                    }
                }
                $this->Session->setFlash($setFlash);
            } else {
                $this->Session->setFlash(__('Nenhum registro foi selecionado'));
            }
        }
        //        krumo($data);
        //        debug($this->$TABLE->getDataSource()->getLog(false, false));
        //        exit();
        $this->redirect(array('action' => 'admin_index'));
    }



    /**
     * CANCELAR AGENDAMENTO 
     * CANCELAR ATENDIMENTO
     */
    public function admin_cancelar_agendamento($beneficiario_id, $atendimento_id)
    {
        $TABLE = $this->table;


        $erro = false;
        $this->setFlash = '';
        $dataSource = $this->$TABLE->getDataSource();


        try {
            $row = $this->$TABLE->find('first', ['conditions' => ['beneficiario_id' => $beneficiario_id, 'atendimento_id' => $atendimento_id]]);

            if (count($row) > 0) {
                $this->loadModel('Atendimento');

                #CANCELAMENTO DO ATENDIMENTO 
                $dataSave = [];
                $dataSave['id'] = $row[$TABLE]['atendimento_id'];
                $dataSave['status'] = 2;
                $dataSave['data_atualizacao'] = date('Y-m-d H:i:s');
                $dataSave['usuario_atualizacao_id'] = $this->Session->read('Auth.Usuario.id');


                if (!$this->Atendimento->save($dataSave)) {
                    $this->setFlash = 'Erro: Agendamento não encontrado! Por favor, refaça ou contato o adminsitrador do sistema.';
                    $erro = true;
                } else {
                    #CANCELAMENTO DO AGENDAMENTO
                    $dataSave = [];
                    $dataSave['id'] = $row[$TABLE]['id'];
                    $dataSave['status'] = 2;
                    $dataSave['data_atualizacao'] = date('Y-m-d H:i:s');
                    $dataSave['usuario_atualizacao_id'] = $this->Session->read('Auth.Usuario.id');

                    if (!$this->$TABLE->save($dataSave)) {
                        $setFlash = 'Erro: Agendamento não encontrado! Por favor, refaça ou contato o adminsitrador do sistema.';
                        $erro = true;
                    }
                }
            } else {
                $this->setFlash = 'Erro: Agendamento não encontrado! Por favor, refaça ou contato o adminsitrador do sistema.';
                $erro = true;
            }



            if ($erro == true) {
                throw new Exception();
            }

            $this->Session->setFlash('O cancelamento do agendamento foi feito com sucesso!');
            $dataSource->commit();
        } catch (Exception $ex) {
            if ($this->setFlash == '') {
                $this->setFlash = 'Erro ao tentar excluir o agendamento! Por favor, refaça ou contato o adminsitrador do sistema.';
            }
            $this->Session->setFlash($this->setFlash);
            $dataSource->rollback();


            #GRAVA LOG
            #$return_log = parent::grava_log('Cancelamento Agendamento  - Cancelamento individual', $this->data, $setFlash);
        }




        $this->redirect($this->referer());
    }
}
