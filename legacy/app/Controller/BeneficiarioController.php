<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class BeneficiarioController extends AppController
{

    #SUCESSO DEVE SER MÍNUSCULO
    public $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public $msg_nao_existe = 'Beneficiario Inexistente';
    public $msg_salvo = 'A Beneficiario foi SALVO com sucesso!';
    public $msg_salvo_erro = 'Não foi possível SALVAR o(a) Beneficiario(a), verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public $msg_salvo_erro_login = 'Não foi possível efetuar o FILTRO ou SALVAR porque foi deslogado, tente novamente!';
    public $msg_excluido = 'A Beneficiario foi EXCLUÍDA com sucesso!';
    public $msg_excluido_erro = 'Não foi possível EXCLUIR a Beneficiario, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    public $tipoPerfil = 1;


    /**
     * AUTOMÁTICO
     * MÉTODO INICIA ANTES DE TUDO QUANDO A CONTROLLER É CHAMADA
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        #CONVERSÃO PARA ARQUIVOS COM UNDERLINE
        $control_verify = $this->params['controller'];
        $control_verify = str_replace('_', ' ', $control_verify);
        $control_verify = ucwords($control_verify);
        $control_verify = str_replace(' ', '', $control_verify);
        $this->table = $control_verify;
        $this->set('TABLE', $control_verify);


        if ($this->params['action'] == 'admin_all') {
            $this->name_search = 'pesquisa_' . $this->params['controller'] . 'all';
        } else {
            $this->name_search = 'pesquisa_' . $this->params['controller'];
        }
        $this->set('name_search', $this->name_search);

        $telTipoArr     = $this->Funcoes->select_merge(array('Residencial' => 'Residencial', 'Comercial' => 'Comercial', 'Fax' => 'Fax', 'Celular' => 'Celular'));

        $sexoArr        = $this->Funcoes->select_merge(array('M' => 'Masculino', 'F' => 'Feminino'));

        if ($this->action == 'admin_view') {
            $estadoCivilArr = $this->Funcoes->parametros('Estado Civil', 'list', null, true, '');
        } else if ($this->action == 'admin_index') {
            $estadoCivilArr = $this->Funcoes->parametros('Estado Civil', 'list', null, true, 'Estado Civil...');
        } else {
            $estadoCivilArr = $this->Funcoes->parametros('Estado Civil');
        }

        $tipoPerfil = '1';
        if ($this->Session->check('Auth.Usuario.Perfil.tipo')) {
            $tipoPerfil =  $this->Session->read('Auth.Usuario.Perfil.tipo');
        }

        $this->tipoPerfil = $tipoPerfil;

        $this->set(compact('sexoArr', 'telTipoArr', 'estadoCivilArr', 'tipoPerfil'));
    }


    /**
     * AUTOMÁTICO
     * Retira da sessão a busca feita pelo usuário Element/admin/search_filter.ctp
     * @param type $search
     */
    public function admin_busca_unset($search, $action = 'index')
    {
        $this->autoRender = false;
        if ($action == 'all') {
            parent::all_busca_unset($search, $this->name_search . 'all');
        } else {
            parent::all_busca_unset($search, $this->name_search);
        }
        $this->redirect(array('action' => $action));
    }


    /**
     * AUTOMÁTICO (TROCAR SOMENTE NOMES DOS FILTROS DA BUSCA)
     * LISTAGEM E FILTRO 
     */
    public function admin_index()
    {

        $TABLE = $this->table;
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'] . '_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'] . '_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        #BUSCA VIA LINK





        $search = $this->Session->read($this->name_search);
        $condition = array();

        if (is_array($search) || isset($this->params['named']['situacao'])):
            if (!empty($search['id_']) && is_numeric($search['id_'])):
                $condition[] = array($TABLE . '.id = "' . $search['id_'] . '"');
            endif;
            if (!empty($search['nome'])):
                $buscaArr = explode(' ', $search['nome']);
                if (count($buscaArr) > 0) {
                    foreach ($buscaArr as $vBusca) {
                        $condition[] = $TABLE . '.nome like "%' . $vBusca . '%"';
                    }
                }
            endif;
            if (!empty($search['nome_social'])):
                $buscaArr = explode(' ', $search['nome_social']);
                if (count($buscaArr) > 0) {
                    foreach ($buscaArr as $vBusca) {
                        $condition[] = $TABLE . '.nome_social like "%' . $vBusca . '%"';
                    }
                }
            endif;
            if (isset($search['cpf']) && $search['cpf'] != ''):
                $cpf = $search['cpf'];
                $cpf = str_replace('.', '', $cpf);
                $cpf = str_replace('-', '', $cpf);

                $condition[] = $TABLE . '.cpf = "' . $cpf . '"';
            endif;

            if (isset($search['status']) && $search['status'] != ''):
                $condition[] = $TABLE . '.status = ' . $search['status'];
            endif;




            #BUSCA POR GET
            if (isset($this->params['named']['situacao'])):
                $condition[] = $TABLE . '.situacao = "' . $this->params['named']['situacao'] . '"';
            else:
                #CASO NÃO EXITA O GET, UTILIZA O POST
                if (isset($search['situacao']) && $search['situacao'] != ''):
                    $condition[] = $TABLE . '.situacao = "' . $search['situacao'] . '"';
                endif;
            endif;


        endif;



        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if ($this->Session->read('Auth.Usuario.id') != $this->uRoot) {
            $condition[] = $TABLE . $this->status_default;
        }
        $condition[] = $TABLE . '.cliente_id = ' . $this->cliente_id;
        #END- USUÁRIO AUTORIZADO DEFAULT



        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 15,
            'order' => array('id' => 'DESC')
        );

        #ADICIONAR
        $this->$TABLE->recursive = 2;
        $this->set('rows', $this->Paginator->paginate());
        $this->set('search', $search);
    }


    /**
     * AUTOMÁTICO (TROCAR SOMENTE NOMES DOS FILTROS DA BUSCA)
     * LISTAGEM E FILTRO 
     */
    public function admin_all()
    {

        $TABLE = $this->table;
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'] . '_form_busca_all'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'] . '_form_busca_all']); //USADO PARA PAGINAÇÃO
            endif;
        endif;

        $search = $this->Session->read($this->name_search);
        $condition = array();

        if (is_array($search)):
            if (!empty($search['id_']) && is_numeric($search['id_'])):
                $condition[] = array($TABLE . '.id = "' . $search['id_'] . '"');
            endif;
            if (!empty($search['nome'])):
                $buscaArr = explode(' ', $search['nome']);
                if (count($buscaArr) > 0) {
                    foreach ($buscaArr as $vBusca) {
                        $condition[] = $TABLE . '.nome like "%' . $vBusca . '%"';
                    }
                }
            endif;
            if ($search['cpf'] != ''):
                $cpf = $search['cpf'];
                $cpf = str_replace('.', '', $cpf);
                $cpf = str_replace('-', '', $cpf);

                $condition[] = $TABLE . '.cpf = "' . $cpf . '"';
            endif;

            if ($search['status'] != ''):
                $condition[] = $TABLE . '.status = ' . $search['status'];
            endif;
        endif;



        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if ($this->Session->read('Auth.Usuario.id') != $this->uRoot) {
            $condition[] = $TABLE . $this->status_default;
        }

        #END- USUÁRIO AUTORIZADO DEFAULT




        $this->loadModel('UsuarioCliente');
        $this->loadModel('Cliente');



        #BUSCA SOMENTE CLIENTES DO GRUPO EMPRESARIAL AUTORIZADOS
        $selectCliente = $this->Cliente->find('list', array('conditions' => array("grupo_empresarial_id" => $this->Session->read('Auth.Usuario.grupo_empresarial_id')), 'fields' => 'id'));



        if ($this->Session->read('Auth.Usuario.perfil_id') != $this->perfil_root) {
            $newSelect = [];
            $selectClienteUsuario = $this->UsuarioCliente->find('list', array('conditions' => array("usuario_id" => $this->Session->read('Auth.Usuario.id')), 'fields' => 'cliente_id'));

            foreach ($selectCliente as $cliente_id) {
                if (in_array($cliente_id, $selectClienteUsuario)) {
                    $newSelect[] = $cliente_id;
                }
            }
            $selectCliente = $newSelect;
        }
        if (count($selectCliente) > 0) {
            $condition[] = $TABLE . '.cliente_id in (' . implode(',', $selectCliente) . ')';
        } else {
            $condition[] = '1 != 1';
        }



        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 15,
            'order' => array('id' => 'DESC')
        );

        #ADICIONAR
        $this->$TABLE->recursive = 2;
        $this->set('rows', $this->Paginator->paginate());

        $this->set('search', $search);
    }


    /**
     * VISUALIZAÇÃO DAS INFORMAÇÕES
     * @param type $id
     * @throws NotFoundException
     */
    public function admin_view($id = null)
    {
        $TABLE = $this->table;

        if (!$this->$TABLE->exists($id)) {
            //    throw new NotFoundException(__($this->msg_nao_existe));
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('action' => 'index'));
        }

        $options = array('conditions' => array($TABLE . '.' . $this->$TABLE->primaryKey => $id), 'recursive' => 3);




        $this->$TABLE->recursive = 2;
        $row = $this->$TABLE->find('first', $options);




        #alteração da session cliente_id para acesso do beneficiário
        if ($row['Beneficiario']['cliente_id'] != $this->Session->read('Auth.Usuario.cliente_id')) {

            if ($this->Session->read('Auth.Usuario.perfil_id') != $this->perfil_root) {

                $this->loadModel('UsuarioCliente');
                $countUC = $this->UsuarioCliente->find('count', ['conditions' => ['usuario_id' => $this->Session->read('Auth.Usuario.id'), 'cliente_id' => $row['Beneficiario']['cliente_id']]]);


                if ($countUC == 0) {
                    $this->Session->setFlash('Você não tem permissão para o beneficiário com este cliente. Por favor, verifique novamente.');
                    $this->redirect('/');
                }
            }

            $this->Session->write('Auth.Usuario.cliente_id', $row['Beneficiario']['cliente_id']);
        }





        $row['Beneficiario']['first_name'] = '';
        $row['Beneficiario']['last_name'] = '';
        if (isset($row['Beneficiario']['nome'])) {
            $nomeArr = explode(' ', $row['Beneficiario']['nome']);
            $row['Beneficiario']['first_name'] = $nomeArr[0];
            unset($nomeArr[0]);
            $row['Beneficiario']['last_name'] = implode(' ', $nomeArr);
        }

        #BEGIN - CRIANDO LOG
        $logList['nome'] = $row['Beneficiario']['first_name'];
        $logList['cpf'] = $row['Beneficiario']['cpf'];
        $logList['cliente'] = $row['Cliente']['nome'];

        $this->loadModel('Log');
        $this->Log->create();
        $data_log = array(
            'id' => '',
            'log' => 'Acesso - Beneficiário',
            'description'         =>  'Acesso no Beneficiário: ' . $logList['nome'] . ' CPF: ' . $logList['cpf'] . ' do cliente: ' . $logList['cliente'],
            'server_description'  =>  '',
            'data_cadastro'       =>  date('Y/m/d H:i:s'),
            'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
        );
        $this->Log->save($data_log);
        #END - CRIANDO LOG



        $altura = '-';
        $peso = '-';
        $valor = '-';

        if (isset($row['Beneficiario']['altura']) && $row['Beneficiario']['altura'] != '' && $row['Beneficiario']['altura'] > 0) {
            if ($row['Beneficiario']['altura'] > 100) {
                $altura = $row['Beneficiario']['altura'] / 100;
                $altura =  str_pad($altura, 4, 0, STR_PAD_RIGHT);
            }
        }
        if (isset($row['Beneficiario']['peso']) && $row['Beneficiario']['peso'] != '' && $row['Beneficiario']['peso'] > 0) {
            $peso = str_replace('.', ',', $row['Beneficiario']['peso']);
        }
        if (isset($row['Beneficiario']['valor']) && $row['Beneficiario']['valor'] != '' && $row['Beneficiario']['valor'] > 0) {
            $valor = str_replace('.', ',', $row['Beneficiario']['valor']);
        }

        $row['Beneficiario']['altura'] = $altura;
        $row['Beneficiario']['peso'] = $peso;
        $row['Beneficiario']['valor'] = $valor;
        #krumo($row);exit;



        $listAfa = $listAfaAll = array(); #afastado
        $listBP = $listBPAll = array(); #beneficio previdenciario
        $listAb = $listAbAll = array(); #absenteísmo
        $listAt = $listAtAll = array(); #timeline


        $permissoes = $this->Session->read('Auth.permissoes');
        $this->set('permissoes', $permissoes);




        #afastado
        if (isset($row['Afastado']) && count($row['Afastado']) > 0) {
            foreach ($row['Afastado'] as $afastArr) {
                $listAfa = array();
                $listAfa['id']                       = $afastArr['id'];
                $listAfa['importacao_id']            = $afastArr['importacao_id'];
                $listAfa['situacao']                 = $afastArr['situacao'];
                $listAfa['data_inicio_afastamento']  = $afastArr['data_inicio_afastamento'];
                $listAfa['data_fim_afastamento']     = $afastArr['data_fim_afastamento'];
                $listAfa['cid']                      = $afastArr['cid'];
                $listAfa['tipo_afastamento']         = $afastArr['tipo_afastamento'];
                $listAfa['assistencia_medica']       = $afastArr['assistencia_medica'];
                $listAfa['plano_assistencia_medica'] = $afastArr['plano_assistencia_medica'];
                $listAfa['data_cadastro']            = $afastArr['data_cadastro'];

                if ($row['Beneficiario']['pis'] == '' && (isset($listAfa['pis']) && $listAfa['pis'] != '')) {
                    $row['Beneficiario']['pis'] = $listAfa['pis'];
                }


                $btnLink = Router::url(array('controller' => 'afastado', 'action' => 'add', $row['Beneficiario']['id'], $afastArr['id']), true);
                $listAfa['btn'] = '';
                if (isset($permissoes['Afastado']['permissao']) && $permissoes['Afastado']['permissao'] > 1) {
                    $listAfa['btn'] = '<a class="btn btn-primary btn-xs" href="' . $btnLink . '">Editar</a>';
                }

                $listAfaAll[] = $listAfa;
            }
        }
        unset($row['Afastado']);


        #beneficio previdenciario
        if (isset($row['BeneficioPrevidenciario']) && count($row['BeneficioPrevidenciario']) > 0) {
            foreach ($row['BeneficioPrevidenciario'] as $benefPrevArr) {
                $listBP = array();
                $listBP['id']                       = $benefPrevArr['id'];
                $listBP['importacao_id']            = $benefPrevArr['importacao_id'];
                $listBP['data_proxima_pericia']     = $benefPrevArr['data_proxima_pericia'];
                $listBP['num_requerimento']         = $benefPrevArr['num_requerimento'];
                $listBP['nb']                       = $benefPrevArr['nb'];
                $listBP['especie']                  = $benefPrevArr['especie'];
                $listBP['especie_bp_id']            = $benefPrevArr['especie_bp_id'];
                $listBP['situacao']                 = $benefPrevArr['situacao'];
                $listBP['data_entrada_requerimento'] = $benefPrevArr['data_entrada_requerimento'];
                $listBP['data_inicio']              = $benefPrevArr['data_inicio'];
                $listBP['data_despacho']            = $benefPrevArr['data_despacho'];
                $listBP['data_realizacao_pericia']  = $benefPrevArr['data_realizacao_pericia'];
                $listBP['conclusao_pericia_medica'] = $benefPrevArr['conclusao_pericia_medica'];
                $listBP['data_limite']              = $benefPrevArr['data_limite'];
                $listBP['data_indeferimento']       = $benefPrevArr['data_indeferimento'];
                $listBP['data_cessacao']            = $benefPrevArr['data_cessacao'];
                $listBP['nexo_tecnico']             = $benefPrevArr['nexo_tecnico'];
                $listBP['data_cadastro']            = $benefPrevArr['data_cadastro'];
                $btnLink = Router::url(array('controller' => 'beneficio_previdenciario', 'action' => 'add', $row['Beneficiario']['id'], $benefPrevArr['id']), true);
                if ($row['Beneficiario']['pis'] == '' && (isset($benefPrevArr['nit']) && $benefPrevArr['nit'] != '')) {
                    $row['Beneficiario']['pis'] = $benefPrevArr['nit'];
                }


                $listBP['btn'] = '';
                if (isset($permissoes['Beneficio_previdenciario']['permissao']) && $permissoes['Beneficio_previdenciario']['permissao'] > 1) {
                    $listBP['btn'] = '<a class="btn btn-primary btn-xs" href="' . $btnLink . '">Editar</a>';
                }

                $listBPAll[] = $listBP;
            }
        }
        unset($row['BeneficioPrevidenciario']);

        #absenteísmo
        if (isset($row['Absenteismo']) && count($row['Absenteismo']) > 0) {
            foreach ($row['Absenteismo'] as $absenteismoArr) {


                $listAb = array();
                $listAb['id']                   = $absenteismoArr['id'];
                $listAb['importacao_id']        = $absenteismoArr['importacao_id'];
                $listAb['matricula']            = $absenteismoArr['matricula'];
                $listAb['documento_id']            = $absenteismoArr['documento_id'];
                $listAb['motivo_id']            = $absenteismoArr['motivo_id'];
                $listAb['hospital_clinica']        = $absenteismoArr['hospital_clinica'];
                $listAb['nome_colaborador']        = $absenteismoArr['nome_colaborador'];
                $listAb['data_saida']            = $absenteismoArr['data_saida'];
                $listAb['qtde_dias_atestado']    = $absenteismoArr['qtde_dias_atestado'];
                $listAb['hora_saida']            = $absenteismoArr['hora_saida'];
                $listAb['hora_retorno']            = $absenteismoArr['hora_retorno'];
                $listAb['cid']                    = $absenteismoArr['cid'];
                $listAb['especialidade_id']        = $absenteismoArr['especialidade_id'];
                $listAb['emissor_id']            = $absenteismoArr['emissor_id'];
                $listAb['profissional']            = $absenteismoArr['profissional'];
                $listAb['num_crm']                = $absenteismoArr['num_crm'];
                $listAb['tipo_absenteismo_id']    = $absenteismoArr['tipo_absenteismo_id'];
                $listAb['situacao']                = $absenteismoArr['situacao'];
                $listAb['data_cadastro']        = $absenteismoArr['data_cadastro'];


                // $listAb['data_retorno']			= $absenteismoArr['data_retorno'];
                // $listAb['dias_calculados']		= $absenteismoArr['dias_calculados'];
                // $listAb['horas_calculadas']		= $absenteismoArr['horas_calculadas'];
                // $listAb['cid_id']				= $absenteismoArr['cid_id'];
                // $listAb['departamento_id']		= $absenteismoArr['departamento_id'];
                // $listAb['cargo_id']				= $absenteismoArr['cargo_id'];
                // $listAb['setor_id']				= $absenteismoArr['setor_id'];
                // $listAb['parte_corpo_id']		= $absenteismoArr['parte_corpo_id'];
                // $listAb['observacao']			= $absenteismoArr['observacao'];
                // $listAb['arquivo']				= $absenteismoArr['arquivo'];
                $btnLink = Router::url(array('controller' => 'absenteismo', 'action' => 'add', $row['Beneficiario']['id'], $absenteismoArr['id']), true);
                $listAb['btn'] = '';
                if (isset($permissoes['Absenteismo']['permissao']) && $permissoes['Absenteismo']['permissao'] > 1) {
                    $listAb['btn'] = '<a class="btn btn-primary btn-xs" href="' . $btnLink . '">Editar</a>';
                }

                $listAbAll[] = $listAb;
            }
        }
        unset($row['Absenteismo']);

        #beneficio previdenciario
        if (isset($row['Atendimento']) && count($row['Atendimento']) > 0) {
            foreach ($row['Atendimento'] as $atendArr) {

                $listAT = array();
                $listAT['id']                       = $atendArr['id'];
                $listAT['descricao']                = $atendArr['descricao'];
                $listAT['tipo_atendimento']         = $atendArr['tipo_atendimento'];
                $listAT['hora_inicio']              = $atendArr['hora_inicio'];
                $listAT['hora_fim']                 = $atendArr['hora_fim'];
                $listAT['tempo_trabalho']           = $atendArr['tempo_trabalho'];
                $listAT['cid']                      = $atendArr['cid'];
                $listAT['forma_atendimento']        = $atendArr['forma_atendimento'];
                $listAT['at_horas']                 = $atendArr['at_horas'];
                $listAT['at_minutos']               = $atendArr['at_minutos'];
                $listAT['status_atendimento']       = $atendArr['status_atendimento'];
                $listAT['status']                   = $atendArr['status'];
                $listAT['usuario_id']               = $atendArr['usuario_id'];
                $listAT['usuario_nome']             = $atendArr['UsuarioResponsavel']['nome'];

                $usuario_agendamento_nome = '';
                if (isset($atendArr['Agendamento'][0]['Usuario']['nome'])) {
                    if ($atendArr['Agendamento'][0]['Usuario']['id'] != $listAT['usuario_id']) {
                        $usuario_agendamento_nome = $atendArr['Agendamento'][0]['Usuario']['nome'];
                    }
                }
                $datahora_agendamento = '';
                if (isset($atendArr['Agendamento'][0]['data_hora']) && $atendArr['Agendamento'][0]['data_hora'] != '') {
                    $datahora_agendamento = $atendArr['Agendamento'][0]['data_hora'];
                }

                $descricao_agendamento = '';
                if (isset($atendArr['Agendamento'][0]['descricao']) && $atendArr['Agendamento'][0]['descricao'] != '') {
                    $descricao_agendamento = $atendArr['Agendamento'][0]['descricao'];
                }


                $listAT['descricao_agendamento']    = $descricao_agendamento;
                $listAT['datahora_agendamento']     = $datahora_agendamento;
                $listAT['usuario_agendamento_nome'] = $usuario_agendamento_nome;
                $listAT['data_conclusao']           = $atendArr['data_conclusao'];
                $listAT['data_cadastro']            = $atendArr['data_cadastro'];
                $listAT['anexo']                    = $atendArr['anexo'];
                $listAT['blob_id']                  = $atendArr['blob_id'];

                $btnEditLink = Router::url(array('controller' => 'atendimento', 'action' => 'add', $row['Beneficiario']['id'], $atendArr['id']), true);
                $btnExcLink = Router::url(array('controller' => 'atendimento', 'action' => 'delete', $row['Beneficiario']['id'], $atendArr['id']), true);

                $btnIniciarLink = Router::url(array('controller' => 'atendimento', 'action' => 'add', $row['Beneficiario']['id'], $atendArr['id']), true);
                $btnCancelarLink = Router::url(array('controller' => 'agendamento', 'action' => 'cancelar_agendamento', $row['Beneficiario']['id'], $atendArr['id']), true);

                $listAT['btn'] = '';
                if ($atendArr['status_atendimento'] == 3) {

                    $listAT['btn'] = '  <a href="' . $btnIniciarLink . '" class="ajaxMsg btn btn-success btn-xs abrir_cria_programa " style="margin: 2px;" ajaxmsg="Tem certeza que deseja Assumir o atendimento do Beneficiario `' . $row['Beneficiario']['nome'] . '´?">
                                            <i class="fa fa-user-md"></i> Iniciar Atendimento
                                        </a>';


                    if ($atendArr['usuario_id'] == $this->usuario_id ||  in_array($this->perfil_id, $this->perfil_adm)  || $this->usuario_id == 3) {
                        $listAT['btn'] .= ' <a href="' . $btnCancelarLink . '" class="ajaxMsg btn btn-danger btn-xs " style="margin: 2px;" ajaxmsg="Tem certeza que deseja CANCELAR o atendimento do Beneficiario `' . $row['Beneficiario']['nome'] . '´?">
                                                <i class="fa fa-remove"></i> Cancelar
                                            </a>';
                    }
                } else {
                    if ($atendArr['usuario_id'] == $this->usuario_id ||  in_array($this->perfil_id, $this->perfil_adm) || $this->usuario_id == 3) {
                        $listAT['btn'] = ' <a class="btn btn-primary btn-xs" href="' . $btnEditLink . '">Editar</a> ';
                    }
                    #if(in_array($this->perfil_id, $this->perfil_adm) ){
                    if (isset($permissoes['Atendimento']['permissao']) && $permissoes['Atendimento']['permissao'] == 3) {
                        $listAT['btn'] .= ' <a class="ajaxMsg btn btn-danger btn-xs" href="' . $btnExcLink . '" ajaxmsg="Tem certeza que deseja EXCLUIR o atendimento do Beneficiario `' . $row['Beneficiario']['nome'] . '´?">Excluir</a>';
                    }
                }





                $listAtAll[] = $listAT;
            }
        }
        unset($row['Atendimento']);



        $row['listAfastado'] = $listAfaAll;
        $row['listBeneficioPrevidenciario'] = $listBPAll;
        $row['listAbsenteismo'] = $listAbAll;
        $row['listTimeline'] = $listAtAll;



        #krumo($row); exit;




        $this->set('simNaoArr', array('' => '', 0 => 'Não', 1 => 'Sim'));

        $tipoAtendimentoArr = array('' => 'Selecione...', 1 => 'Acolhimento Social', 2 => 'Acolhimento Psicológico', 3 => 'Atendimento Médico', 4 => 'Atendimento de Enfermagem', '5' => 'Atendimento de Fisioterapia', '6' => 'Administrativo', '7' => 'Atendimento Concierge');
        $this->set('tipoAtendimentoArr', $tipoAtendimentoArr);

        $formaAtendimentoArr = array('' => 'Selecione...', 0 => 'Telefone', 1 => 'Presencial', 2 => 'E-mail', 3 => 'Por Mensagem (whatsapp, sms, outros)');
        $this->set('formaAtendimentoArr', $formaAtendimentoArr);

        $statusAtendimentoArr = array('' => '', 2 => 'Concluído', 0 => 'Sem Contato', 1 => 'Deixou Recado', 3 => 'Aguardando Execução');
        $this->set('statusAtendimentoArr', $statusAtendimentoArr);






        $this->set('row', $row);
    }



    /**
     * SALVAR NOVO E EDITAR!
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function admin_add($id = null)
    {
        $TABLE = $this->table;

        $this->loadModel('Empresa');
        $empresaArr = $this->Empresa->find('list', array('conditions' => array('Empresa.cliente_id' => $this->cliente_id), 'order' => array('razao_social' => 'ASC'), 'fields' => 'id,razao_cnpj'));
        $empresaArr = $this->Funcoes->select_merge($empresaArr);
        $this->set('empresaArr', $empresaArr);

        if ($this->request->is(array('post', 'put'))) {

            #BEGIN - TRANSACTION
            $dataSource = $this->$TABLE->getDataSource();
            // krumo($_SERVER);
            // krumo("verificar quando vem do operador");
            // exit;
            try {

                $dataSource->begin();
                $dateTimeNow = date('Y-m-d H:i:s');
                $user_create = $this->Session->read('Auth.Usuario.id');
                if (empty($this->data[$TABLE]['id'])) {
                    $this->$TABLE->create();
                    $this->request->data[$TABLE]['data_cadastro'] = $dateTimeNow;
                    $this->request->data[$TABLE]['usuario_criador_id'] = $user_create;
                    $this->request->data[$TABLE]['cliente_id'] = $this->cliente_id;
                    $this->request->data[$TABLE]['data_atualizacao'] = null;
                } else {
                    $this->request->data[$TABLE]['usuario_atualizacao_id'] = $user_create;
                    $this->request->data[$TABLE]['data_atualizacao'] = $dateTimeNow;
                }


                $peso = str_replace(',', '.', $this->data[$TABLE]['peso']);
                $altura = str_replace(',', '.', $this->data[$TABLE]['altura']);
                $this->request->data[$TABLE]['peso'] = $peso;
                $this->request->data[$TABLE]['altura'] = ($altura) * 100;

                $imc = '';
                if ($peso != '' && $altura != '') {
                    $imc = $this->Funcoes->imc($peso, $altura);
                }

                $this->request->data[$TABLE]['imc'] = $imc;


                $cpf = trim($this->request->data[$TABLE]['cpf']);
                $cpf = str_replace('.', '', $cpf);
                $cpf = str_replace('-', '', $cpf);
                $this->request->data[$TABLE]['cpf'] = $cpf;


                #VALIDA CPF
                #$this->$TABLE->find('count', array('conditions' => array('cpf' => $this->request->data[$TABLE]['cpf'])));,

                #$countB = $this->Beneficiario->find('first',['conditions'=>['cpf'=>$cpf],'fields'=>'id, situacao','recursive'=>-1]);

                // $countB = $this->Beneficiario->find('first',['conditions'=>['cpf'=>$cpf,'cliente_id'=>$this->cliente_id],'fields'=>'id, situacao','recursive'=>-1]);
                // if(count($countB) > 0 ){
                //     if($countB['Beneficiario']['situacao'] != $data_v['situacao']){
                //         $dataS = array();
                //         $dataS['id'] = $countB['Beneficiario']['id'];
                //         $dataS['situacao']= $data_v['situacao'];


                //         if(!$this->Beneficiario->save($dataS)){
                //             #$this->erro[] = 'ERRO CADASTRO DE BENEFICIARIO '.$data_k; #GERAR LOG ERRO
                //             $this->erro[] = array('linha'=>$data_k,'descricao'=>'ERRO CADASTRO DE BENEFICIARIO '.$data_k);
                //             $this->erro_exec = true;
                //         }
                //     }else{
                //         $this->erro[] = array('linha'=>$data_k,'descricao'=>'BENEFICIÁRIO EXISTENTE '.$data_k);
                //         $this->erro_exec = true;
                //     }


                // }else{
                //     $rsB = $this->Beneficiario->find('all',array('conditions'=>array('cpf'=>$cpf),'recursive'=>-1));
                //     if(count($rsB)>0){
                //         $this->erro[] = array('linha'=>$data_k,'descricao'=>'BENEFICIÁRIO EXISTENTE EM OUTRO CLIENTE'.$data_k);
                //         $this->erro_exec = true;
                //     }
                // }



                if (!$this->$TABLE->save($this->request->data[$TABLE])) {
                    throw new Exception();
                }
                $id = $this->$TABLE->id;



                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Gravação - Beneficiario',
                    'description'         => json_encode($this->data),
                    'server_description'  => '',
                    'data_cadastro'       => $dateTimeNow,
                    'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);


                $this->Session->setFlash($this->msg_salvo);

                $dataSource->commit();

                return $this->redirect(array('action' => 'add', $id));
            } catch (Exception $ex) {

                $flash = '';
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
                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Erro Gravação - Beneficiario',
                    'mensagem'            => $flash,
                    'description'         => json_encode($this->data),
                    'server_description'  => json_encode($this->params),
                    'data_cadastro'       => $dateTimeNow,
                    'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);

                $this->Session->setFlash($flash);


                #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
                $this->Session->write('error_form', $this->data);
                $dataSource->rollback();
                $this->redirect($this->referer());
                #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO

            }
            #END - TRANSACTION

        }

        #BUSCA INFORMAÇÕES 
        if ($id !== null) {
            if (!$this->$TABLE->exists($id)) {
                $this->Session->setFlash(__($this->msg_nao_existe));
                $this->redirect(array('action' => 'index'));
                exit();
            }
            $options = array('conditions' => array($TABLE . '.' . $this->$TABLE->primaryKey => $id));
            $this->$TABLE->recursive = 2;
            $this->request->data = $this->$TABLE->find('first', $options);
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

    }


    /**
     * DELETAR 
     * @param type $id
     */
    public function admin_delete($id = null)
    {
        $TABLE = $this->table;

        if ($id !== null) { #EXCLUSÃO UNITÁRIA
            $this->$TABLE->id = $id;
            if (!$this->$TABLE->exists($id)) {
                $this->Session->setFlash($this->msg_nao_existe);
            } else {
                $data[$TABLE]['id'] = $id;
                $data[$TABLE]['data_atualizacao'] = date('Y-m-d H:i:s');
                $data[$TABLE]['status'] = 2;
                if ($this->$TABLE->save($data)) {
                    $this->Session->setFlash($this->msg_excluido);
                } else {
                    $this->Session->setFlash($this->msg_excluido_erro);
                }
            }
        } else { #EXCLUSÃO MULTIPLA

            if (isset($this->params['named']['ids']) && $this->params['named']['ids'] != '') {
                $idsArr = explode('_', $this->params['named']['ids']);
                $setFlash = '';
                $data = array();
                foreach ($idsArr as $id):
                    if ($id != '') {
                        $this->$TABLE->id = $id;
                        if (!$this->$TABLE->exists($id)) {
                            $setFlash .= 'ID:' . $id . ' - Não Existe <br />';
                        } else {
                            $data[$TABLE]['id'] = $id;
                            $data[$TABLE]['data_atualizacao'] = date('Y-m-d H:i:s');
                            $data[$TABLE]['status'] = 2;
                            if ($this->$TABLE->save($data)) {
                                $setFlash .= 'O ID: ' . $id . ' foi EXCLUÍDO com sucesso! <br />';
                            } else {
                                $setFlash .= 'O ID: ' . $id . ' não pode ser EXCLUÍDO! Por favor, tente novamente.  <br /> ';
                            }
                        }
                    }
                endforeach;
                $this->Session->setFlash($setFlash);
            } else {
                $this->Session->setFlash(__('Nenhum registro foi selecionado'));
            }
        }
        $this->redirect(array('action' => 'index'));
    }


    /**
     * status = 0 - Não existe
     * status = 1 - Existe (mesmo cliente)
     * status = 2 - existe (outro cliente)
     */
    public function admin_busca_beneficiario($cpf = null, $json = true)
    {
        $this->autoRender = false;
        $this->loadModel('Beneficiario');
        $cliente_id = $this->cliente_id;
        if ($json == true && isset($this->data['cpf'])) {
            $cpf = $this->data['cpf'];
        }

        if ($cpf == '') return false;
        $this->Beneficiario->recursive = -1;
        $benef = $this->Beneficiario->find('first', array('conditions' => array('cpf' => $cpf, 'status' => 1), 'fields' => 'id,cpf,cliente_id', 'recursive' => -1));
        if (count($benef) > 0 && $benef['Beneficiario']['cpf'] != '') {
            $benef2 = $this->Beneficiario->find('first', array('conditions' => array('cpf' => $cpf, 'cliente_id' => $cliente_id, 'status' => 1), 'fields' => 'id,cpf', 'recursive' => -1));
            if (count($benef2) > 0 && $benef2['Beneficiario']['cpf'] != '') {
                $return = ['status' => 1, 'message' => 'Beneficiário Existente!', 'id' => $benef2['Beneficiario']['id']];
            } else {
                $return = ['status' => 2, 'message' => 'Beneficiário Existente Outro Cliente!', 'id' => $benef['Beneficiario']['id']];
            }
        } else {
            $return = ['status' => 0, 'message' => 'Não existe Beneficiário!', 'id' => null];
        }

        if ($json) {
            echo json_encode($return);
        } else {
            return $return;
        }
    }
}
