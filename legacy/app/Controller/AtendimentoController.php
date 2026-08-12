<?php
App::uses('AppController', 'Controller');
/**
 * Atendimento Controller
 *
 * @property Atendimento $Atendimento
 * @property PaginatorComponent $Paginator
 */
class AtendimentoController extends AppController
{
    public  $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public  $msg_nao_existe = 'Atendimento Inexistente';
    public  $msg_salvo = 'O Atendimento foi SALVO com sucesso!';
    public  $msg_salvo_erro = 'Não foi possível SALVAR o Atendimento, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public  $msg_excluido = 'O Atendimento foi EXCLUÍDO com sucesso ';
    public  $msg_excluido_erro = 'Não foi possível EXCLUIR o Atendimento, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    public  $msgFlashError = '';

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

        $this->name_search = 'pesquisa_' . $this->params['controller'];
        $this->set('name_search', $this->name_search);
    }


    /**
     * AUTOMÁTICO
     * Retira da sessão a busca feita pelo usuário Element/admin/search_filter.ctp
     * @param type $search
     */
    public function admin_busca_unset($search)
    {
        $this->autoRender = false;
        parent::all_busca_unset($search, $this->name_search);
        $this->redirect(array('action' => 'index'));
    }


    /**
     * AUTOMÁTICO (TROCAR SOMENTE NOMES DOS FILTROS DA BUSCA)
     * LISTAGEM E FILTRO 
     */
    public function admin_index($beneficiario_id = null)
    {
        $TABLE = $this->table;

        $this->redirect($this->referer());

        if ($beneficiario_id == null || !is_numeric($beneficiario_id)) {
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect($this->referer());
            exit;
        }

        #krumo($this->Session);  
        exit;
        $this->loadModel('Beneficiario');
        $benef = $this->Beneficiario->find('first', array('conditions' => array('id' => $beneficiario_id)));
        if (count($benef) == 0) {
        }
        exit;


        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'] . '_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'] . '_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        $search = $this->Session->read($this->name_search);
        $condition = array();

        if (is_array($search)):
            if (!empty($search['beneficiario_id_']) && is_numeric($search['id_'])):
                $condition[] = array($TABLE . '.id = 
                "' . $search['id_'] . '"');
            endif;
            if (!empty($search['nome'])):
                $buscaArr = explode(' ', $search['nome']);
                if (count($buscaArr) > 0) {
                    foreach ($buscaArr as $vBusca) {
                        $condition[] = $TABLE . '.nome like "%' . $vBusca . '%"';
                    }
                }
            endif;
            if (!empty($search['controller'])):
                $buscaArr = explode(' ', $search['controller']);
                if (count($buscaArr) > 0) {
                    foreach ($buscaArr as $vBusca) {
                        $condition[] = $TABLE . '.controller like "%' . $vBusca . '%"';
                    }
                }
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





        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 10,
            'order' => array('order' => 'ASC')
        );

        #ADICIONAR
        //        $this->$TABLE->recursive = 1;
        $this->set('rows', $this->Paginator->paginate());
        $this->set('search', $search);
    }



    /**
     * VISUALIZAÇÃO DAS INFORMAÇÕES
     * @param type $id
     * @throws NotFoundException
     */
    public function admin_view($beneficiario_id = null, $id = null)
    {
        $TABLE = $this->table;

        $this->redirect($this->referer());


        if (!$this->$TABLE->exists($id)) {
            //            throw new NotFoundException(__($this->msg_nao_existe));
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('action' => 'index'));
        }

        $options = array('conditions' => array($TABLE . '.' . $this->$TABLE->primaryKey => $id));

        $row = $this->$TABLE->find('first', $options);
        $this->set('row', $row);;
    }


    /**
     * SALVAR NOVO E EDITAR!
     * @param type $id
     * @return type
     * @throws Exception 
     * , $atendimento_id = null
     */
    public function admin_add($beneficiario_id = null, $id = null)
    {
        $TABLE = $this->table;

        $this->loadModel('Empresa');
        $empresaArr = $this->Empresa->find('list', array('conditions' => array('cliente_id' => $this->cliente_id), 'order' => array('razao_social' => 'ASC'), 'fields' => 'id,razao_cnpj'));
        $empresaArr = $this->Funcoes->select_merge($empresaArr);
        $this->set('empresaArr', $empresaArr);

        $this->loadModel('EspecieBp');
        $especieBpArr = $this->EspecieBp->find('list', array('conditions' => array(), 'order' => array('id' => 'ASC'), 'fields' => 'id,nome_importacao'));
        $especieBpArr = $this->Funcoes->select_merge($especieBpArr);
        $especieBpArr['x'] = '..::: Novo Item :::.. ';
        $this->set('especieBpArr', $especieBpArr);


        $this->set('simNaoArr', array('' => 'Selecione', 0 => 'Não', 1 => 'Sim'));

        $tipoAtendimentoArr = array('' => 'Selecione...', 1 => 'Acolhimento Social', 2 => 'Acolhimento Psicológico', 3 => 'Atendimento Médico', 4 => 'Atendimento de Enfermagem', '5' => 'Atendimento de Fisioterapia', '6' => 'Administrativo', '7' => 'Atendimento Concierge');
        $this->set('tipoAtendimentoArr', $tipoAtendimentoArr);

        $formaAtendimentoArr = array('' => 'Selecione...', 0 => 'Telefone', 1 => 'Presencial', 2 => 'E-mail', 3 => 'Por Mensagem (whatsapp, sms, outros)');
        $this->set('formaAtendimentoArr', $formaAtendimentoArr);

        $statusAtendimentoArr = array('' => 'Selecione...', 2 => 'Concluído', 0 => 'Sem Contato', 1 => 'Deixou Recado'); #,3 => 'Aguardando Execução'
        $this->set('statusAtendimentoArr', $statusAtendimentoArr);


        // if($atendimento_id != null){
        //     $this->

        // }




        if ($beneficiario_id == null || !is_numeric($beneficiario_id)) {
            $this->Session->setFlash('Beneficiário inváido!');
            $this->redirect(array('controller' => 'beneficiario'));
            exit;
        }








        $this->loadModel('Beneficiario');
        // $benef = $this->Beneficiario->find('first',array('conditions'=>array('id' => $beneficiario_id, 'cliente_id'=>$this->cliente_id),'fields'=>'id,nome','recursive'=>-1));
        // if(count($benef) == 0){
        //     $this->Session->setFlash('Beneficiário inexistente ou de outro cliente!');
        //     $this->redirect(array('controller'=>'beneficiario'));
        //     exit;
        // }


        
        $benef = $this->Beneficiario->find('first', array('conditions' => array('id' => $beneficiario_id), 'fields' => 'id,nome,cliente_id', 'recursive' => -1));
        #alteração da session cliente_id para acesso do beneficiário
        if ($benef['Beneficiario']['cliente_id'] != $this->Session->read('Auth.Usuario.cliente_id')) {

            if ($this->Session->read('Auth.Usuario.perfil_id') != $this->perfil_root) {

                $this->loadModel('UsuarioCliente');
                $countUC = $this->UsuarioCliente->find('count', ['conditions' => ['usuario_id' => $this->Session->read('Auth.Usuario.id'), 'usuario_id' => $benef['Beneficiario']['cliente_id']]]);
                if ($countUC == 0) {
                    $this->Session->setFlash('Você não tem permissão para o beneficiário com este cliente. Por favor, verifique novamente.');
                    $this->redirect($this->referer());
                }
            }

            $this->Session->write('Auth.Usuario.cliente_id', $benef['Beneficiario']['cliente_id']);
        }

        $this->set('benef', $benef);
      



        if ($this->request->is(array('post', 'put'))) {

            $this->msgFlashError = '';

          

            #BEGIN - TRANSACTION
            $dataSource = $this->$TABLE->getDataSource();

            try {
                $dataSource->begin();
                $dateTimeNow = date('Y-m-d H:i:s');
                $user_create = $this->Session->read('Auth.Usuario.id');


                if (empty($this->data[$TABLE]['id'])) {
                    $this->$TABLE->create();
                    $this->request->data[$TABLE]['data_cadastro'] = $dateTimeNow;
                    $this->request->data[$TABLE]['usuario_id'] = $user_create;
                } else {
                    $this->request->data[$TABLE]['data_atualizacao'] = $dateTimeNow;
                    $this->request->data[$TABLE]['usuario_id'] = $user_create;
                }


                if ($this->data[$TABLE]['forma_atendimento'] != '0') {
                    $this->request->data[$TABLE]['status_atendimento'] = 2;
                }

                if ($this->request->data[$TABLE]['status_atendimento'] == 2) {
                    $this->request->data[$TABLE]['data_conclusao'] = $dateTimeNow;
                }

                if (!isset($this->request->data[$TABLE]['at_horas']) || (isset($this->request->data[$TABLE]['at_horas']) && $this->request->data[$TABLE]['at_horas'] == '')) {
                    $this->request->data[$TABLE]['at_horas'] = 0;
                }



                $FILE = $this->data['Atendimento']['arquivo'];
                $uploadFolder = WWW_ROOT . 'files' . DS . 'uploads' . DS . 'atendimento' . DS;

                unset($this->request->data[$TABLE]['anexo']);

                if (isset($FILE['name']) && $FILE['name'] != '') {
                    #CRIA AS PASTAS 
                    if (!file_exists($uploadFolder)) {
                        mkdir($uploadFolder, 0777, true);
                    }


                    if ($FILE['error'] == 1) {
                        throw new Exception();
                    }

                    //BEGIN - BLOB
                    // if(!parent::blob_action($FILE, ['action'=> 0,'table'=> 'Atendimento'])){
                    //     throw new Exception();
                    // }
                    // $this->request->data[$TABLE]['blob_id'] = $this->Blob->id;
                    //FIM - BLOB


                    #DEFINE NOVO NOME COM TIME
                    $fileNameArr = explode('.', $FILE['name']);
                    $extensao = $fileNameArr[count($fileNameArr) - 1];
                    unset($fileNameArr[count($fileNameArr) - 1]);
                    $file_name = implode('.', $fileNameArr);
                    $file_name = $this->Funcoes->normalizaeUrl($file_name) . '_' . time() . '.' . $extensao;

                    #MONTA CAMINHO E NOME DO ARQUIVO E SALVA NO SERVIDOR
                    $file_full = $uploadFolder . $file_name;
                    $successMove = move_uploaded_file($FILE['tmp_name'], $file_full);

                    $this->request->data[$TABLE]['anexo'] = $file_name;
                }

                if ($this->request->data[$TABLE]['descricao'] != '') {
                    $desc_org = $this->request->data[$TABLE]['descricao'];
                    $this->request->data[$TABLE]['descricao_origin'] = $desc_org;
                    $allowedTags = "<br><p><b><strong><i><u><a><ul><ol><li><em><sub><sup><s>";
                    $this->request->data[$TABLE]['descricao'] = strip_tags($desc_org, $allowedTags);
                }

                // $this->request->data[$TABLE]['descricao_agendamento']  = '' ;
                // if (isset($this->request->data['Agendamento'][0]['descricao']) && $this->request->data['Agendamento'][0]['descricao'] != '') {
                //     $desc_org = $this->request->data['Agendamento'][0]['descricao'];
                //     $this->request->data[$TABLE]['descricao_agendamento_origin'] = $desc_org;
                //     $allowedTags = "<br><p><b><strong><i><u><a><ul><ol><li><em><sub><sup><s>";
                //     $this->request->data[$TABLE]['descricao_agendamento'] = strip_tags($desc_org, $allowedTags);
                // }

               


                if (!$this->$TABLE->save($this->request->data[$TABLE])) {
                    throw new Exception();
                }
                $id = $this->$TABLE->id;

                $this->loadModel('Agendamento');
                $rowAg = $this->Agendamento->find('first', ['conditions' => ['atendimento_id' => $id], 'fields' => 'id', 'recursive' => -1]);
                if (count($rowAg) > 0 && isset($rowAg['Agendamento']['id'])) {
                    #ATUALIZA AGENDAMENTO
                    $dataSave = [];
                    $dataSave['id'] = $rowAg['Agendamento']['id'];
                    $dataSave['usuario_agendamento_id'] = $this->Session->read('Auth.Usuario.id');
                    $dataSave['data_atualizacao'] = date('Y-m-d H:i:s');
                    $dataSave['usuario_atualizacao_id'] = $this->Session->read('Auth.Usuario.id');
                    $dataSave['status'] = 1;

                    
                    $this->loadModel('Agendamento');
                    if (!$this->Agendamento->save($dataSave)) {
                        $setFlash = 'Erro: Agendamento não encontrado! Por favor, refaça ou contato o adminsitrador do sistema.';
                        $this->msgFlashError = $setFlash;
                        throw new Exception();
                    }
                }

       
            
                #SAVE AFASTADO
                if($this->data['Atendimento']['esta_afastado'] == 1 && 
                    isset($this->data['Atendimento']['afastado_id']) && $this->data['Atendimento']['afastado_id'] != '' &&
                    isset($this->data['Atendimento']['beneficiario_retorno']) && $this->data['Atendimento']['beneficiario_retorno'] == 1 &&
                    isset($this->data['Atendimento']['data_retorno_afastamento']) && $this->data['Atendimento']['data_retorno_afastamento'] != ''){
                    $this->loadModel('Afastado');

                    #BUSCAR AFASTAMENTO PARA MONSTAR AS OUTRAS INFOS
                    $rs_afastado = $this->Afastado->find('first', ['conditions' => ['id' => $this->data['Atendimento']['afastado_id']], 
                                                                    'fields' => 'beneficiario_id,empresa_id,data_inicio_afastamento,situacao,cid,tipo_afastamento,assistencia_medica,acao_trabalhista,acao_inss,limbo_previdenciario', 'recursive' => -1]);
                    
                    if(count($rs_afastado) == 0){
                        $setFlash = 'Erro: Afastamento não encontrado! Por favor, refaça ou contato o adminsitrador do sistema.';
                        $this->msgFlashError = $setFlash;
                        throw new Exception();
                    }

                    $this->Afastado->create();
                    $dataSave = [];
                    $dataSave['id'] = '';
                    $dataSave['beneficiario_id'] = $rs_afastado['Afastado']['beneficiario_id'];
                    $dataSave['empresa_id'] = $rs_afastado['Afastado']['empresa_id'];
                    $dataSave['cid'] = $rs_afastado['Afastado']['cid'];
                    $dataSave['tipo_afastamento'] = $rs_afastado['Afastado']['tipo_afastamento'];
                    $dataSave['assistencia_medica'] = $rs_afastado['Afastado']['assistencia_medica'];
                    $dataSave['data_inicio_afastamento'] = $rs_afastado['Afastado']['data_inicio_afastamento'];
                    $dataSave['data_fim_afastamento'] = $this->Funcoes->dateToDb($this->data['Atendimento']['data_retorno_afastamento']);
                    $dataSave['acao_trabalhista'] = $rs_afastado['Afastado']['acao_trabalhista'];
                    $dataSave['acao_inss'] = $rs_afastado['Afastado']['acao_inss'];
                    $dataSave['limbo_previdenciario'] = $rs_afastado['Afastado']['limbo_previdenciario'];
                    $dataSave['usuario_id'] = $this->Session->read('Auth.Usuario.id');
                    $dataSave['data_cadastro'] = date('Y/m/d H:i:s');
                    $dataSave['situacao'] = 'RT';
                    $dataSave['status'] = 1;
                    
                    if (!$this->Afastado->save($dataSave)) {
                        $setFlash = 'Erro: Afastamento não encontrado! Por favor, refaça ou contato o adminsitrador do sistema.';
                        $this->msgFlashError = $setFlash;
                        throw new Exception();
                    }
                    
                
                }    
               

                $this->Session->setFlash(__($this->msg_salvo));
                $dataSource->commit();
                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log' => 'Atendimento - Cadastro',
                    'description'         =>  json_encode($this->data),
                    'mensagem'            =>  $this->msg_salvo,
                    'server_description'  =>  json_encode($this->params),
                    'data_cadastro'       =>  date('Y/m/d H:i:s'),
                    'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
                #return $this->redirect(array('action'=>'add',$beneficiario_id,$id));

                $redirect = array('controller' => 'beneficiario', 'action' => 'view', $beneficiario_id);
                if (isset($this->request->data[$TABLE]['novo_agendamento']) && $this->request->data[$TABLE]['novo_agendamento'] == 1) {
                    $redirect = array('controller' => 'agendamento', 'action' => 'add', $beneficiario_id);
                }

                return $this->redirect($redirect);
            } catch (Exception $ex) {
                //  debug($this->Atendimento->getDataSource()->getLog(false, false));
                //  exit;
                $flash = '';
                if ($this->msgFlashError != '') {
                    $flash = $this->msgFlashError;
                }
                $dataSource->rollback();


                if ($FILE['error'] == 1) {
                    $flash .= 'Erro: O campo de anexo excedeu o limite de 5mb!<br />';
                }


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
                    'log' => 'Atendimento - Erro - Cadastro',
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

        #BUSCA INFORMAÇÕES 
        if ($id !== null) {
            if (!$this->$TABLE->exists($id)) {
                $this->Session->setFlash(__($this->msg_nao_existe));
                $this->redirect(array('controller' => 'beneficiario', 'action' => 'view', $beneficiario_id));
                exit();
            }

            #ATUALIZAÇÃO  
            $this->$TABLE->bindModel([
                'belongsTo' => [
                    'Beneficiario' => [
                        'className' => 'Beneficiario',
                        'foreignKey' => 'beneficiario_id'
                    ]
                ]
            ], false);
            $this->Atendimento->Beneficiario->unbindModel(array(
                'hasMany' => array(
                    'BeneficioPrevidenciario',
                    'Absenteismo',
                    'Atendimento'
                ),
                'belongsTo' => array(
                    'Cliente',
                    'UsuarioCriador',
                    'UsuarioAtualizacao',
                    'Empresa'
                )
            ));
            $options = array('conditions' => array($TABLE . '.' . $this->$TABLE->primaryKey => $id),'recursive' => 2);
            
            $this->request->data = $this->$TABLE->find('first', $options);
            $this->request->data['esta_afastado'] = false;
            $this->request->data['afastado_id'] = '';
            if(isset($this->request->data['Beneficiario']['Afastado'][0]['id']) && $this->request->data['Beneficiario']['Afastado'][0]['situacao'] != ''){
                if($this->request->data['Beneficiario']['Afastado'][0]['situacao'] == 'A'){
                    $this->request->data['esta_afastado'] = true;
                    $this->request->data['afastado_id'] = $this->request->data['Beneficiario']['Afastado'][0]['id'];
                }
            }
            

            #ATUALIZAÇÃO DE QUEM ESTÁ ASSUMINDO O ATENDMENTO
            $tipo_atendimento = $this->request->data['Atendimento']['tipo_atendimento'];
           
            if ($tipo_atendimento == '') {
                if (isset($this->request->data['Agendamento'][0]['id']) && $this->request->data['Agendamento'][0]['id'] != '') {
                    $dataSave = [];
                    $dataSave['id'] = $id;
                    $dataSave['usuario_id'] = $this->Session->read('Auth.Usuario.id');
                    $dataSave['data_atualizacao'] = date('Y-m-d H:i:s');
                    $dataSave['usuario_atualizacao_id'] = $this->Session->read('Auth.Usuario.id');
                    if (!$this->$TABLE->save($dataSave)) {
                        $setFlash = 'Erro: Atendimento não encontrado! Por favor, refaça ou contato o adminsitrador do sistema.';
                    }


                    $dataSave = [];
                    $dataSave['id'] = $this->request->data['Agendamento'][0]['id'];
                    $dataSave['usuario_agendamento_id'] = $this->Session->read('Auth.Usuario.id');
                    $dataSave['data_atualizacao'] = date('Y-m-d H:i:s');
                    $dataSave['usuario_atualizacao_id'] = $this->Session->read('Auth.Usuario.id');
                    $this->loadModel('Agendamento');
                    if (!$this->Agendamento->save($dataSave)) {
                        $setFlash = 'Erro: Agendamento não encontrado! Por favor, refaça ou contato o adminsitrador do sistema.';
                    }
                }
            }




            // if (isset($this->request->data['Agendamento'][0]['descricao']) && $this->request->data['Agendamento'][0]['descricao'] != '') {
            //     $desc_org = $this->request->data['Agendamento'][0]['descricao'];
            //     $this->request->data[$TABLE]['descricao_agenadmento'] = $desc_org;
            // }
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
    public function admin_delete($beneficiario_id = null, $id = null)
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
                    #BEGIN - CRIANDO LOG    
                    $this->loadModel('Log');
                    $this->Log->create();
                    $data_log = array(
                        'id' => '',
                        'log' => 'Atendimento - Exclusão ',
                        'description'         =>  json_encode($data),
                        'server_description'  =>  '',
                        'data_cadastro'       =>  date('Y/m/d H:i:s'),
                        'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                    );
                    $this->Log->save($data_log);
                    #END - CRIANDO LOG
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
                #BEGIN - CRIANDO LOG    
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log' => 'Atendimento - Exclusão Múltipla',
                    'description'         =>  $setFlash,
                    'server_description'  =>  '',
                    'data_cadastro'       =>  date('Y/m/d H:i:s'),
                    'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
                $this->Session->setFlash($setFlash);
            } else {
                $this->Session->setFlash(__('Nenhum registro foi selecionado'));
            }
        }
        $this->redirect(array('controller' => 'beneficiario', 'action' => 'view', $beneficiario_id));
    }
}
