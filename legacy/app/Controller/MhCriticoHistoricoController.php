<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class MhCriticoHistoricoController  extends AppController
{

    #SUCESSO DEVE SER MÍNUSCULO
    public $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public $msg_nao_existe = 'Crítico Histórico  Inexistente';
    public $msg_salvo = 'O Crítico Histórico  foi SALVA com sucesso!';
    public $msg_salvo_erro = 'Não foi possível SALVAR o Crítico Histórico , verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public $msg_salvo_erro_login = 'Não foi possível efetuar o FILTRO ou SALVAR porque foi deslogado, tente novamente!';
    public $msg_excluido = 'O Crítico Histórico  foi EXCLUÍDA com sucesso!';
    public $msg_excluido_erro = 'Não foi possível EXCLUIR o Crítico Histórico , tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';



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


        $ArrStatusCiclo = ['' => 'Selecione...', 0 => 'Não Iniciada', 1 => 'Em Andamento', 2 => 'Concluída'];
        $ArrStatusCiclo = $this->Funcoes->select_merge($ArrStatusCiclo, ($this->params['action'] == 'admin_index' ? 'Status Ciclo...' : 'Selecione...'));
        $this->set('ArrStatusCiclo', $ArrStatusCiclo);

        $ArrCiclo = ['' => 'Selecione...', 0 => 'Prospecção', 1 => 'Contato', 2 => 'Mapeameno', 3 => 'Negociação', 4 => 'Insucesso'];
        $ArrCiclo = $this->Funcoes->select_merge($ArrCiclo, ($this->params['action'] == 'admin_index' ? 'Ciclo...' : 'Selecione...'));
        $this->set('ArrCiclo', $ArrCiclo);

        $ArrOpcao = ['' => 'Selecione...', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8'];
        #$ArrOpcao = ['' => 'Selecione...', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8'];
        $ArrOpcao = $this->Funcoes->select_merge($ArrOpcao, ($this->params['action'] == 'admin_index' ? 'Opção...' : 'Selecione...'));
        $this->set('ArrOpcao', $ArrOpcao);
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
    public function admin_index($mh_critico_id = null)
    {
        $TABLE = $this->table;
        $this->loadModel('MhCritico');

        if(!$this->MhCritico->exists($mh_critico_id)){
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('controller'=>'mh_critico'));
        }



        if ($this->request->is('post')) :
            if (isset($this->data[$this->params['controller'] . '_form_busca'])) :
                $this->Session->write($this->name_search, $this->data[$this->params['controller'] . '_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        $search = $this->Session->read($this->name_search);
        $condition = array();
        $condition[] = $TABLE . '.mh_critico_id = '.$mh_critico_id;

        if (is_array($search)) :
            if (!empty($search['id_']) && is_numeric($search['id_'])) :
                $condition[] = array($TABLE . '.id = "' . $search['id_'] . '"');
            endif;
            if (!empty($search['nome'])) :
                $buscaArr = explode(' ', $search['nome']);
                if (count($buscaArr) > 0) {
                    foreach ($buscaArr as $vBusca) {
                        $condition[] = $TABLE . '.nome like "%' . $vBusca . '%"';
                    }
                }
            endif;

            if ($search['status'] != '') :
                $condition[] = $TABLE . '.status = ' . $search['status'];
            endif;
        endif;



        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
   
        if ($this->Session->read('Auth.Usuario.id') != $this->uRoot) {
            $condition[] = $TABLE . $this->status_default;
        }
        

        #$condition[] = $TABLE.'.usuario_id = '.$this->usuario_id;
        #END- USUÁRIO AUTORIZADO DEFAULT


        

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 15,
            'order' => array('id' => 'DESC')
        );

        #ADICIONAR
        $this->$TABLE->recursive = 2;


        
        $rows = $this->Paginator->paginate();
       

        $this->set('rows', $rows);
        $this->set('search', $search);
    }




    /**
     * VISUALIZAÇÃO DAS INFORMAÇÕES
     * @param type $id
     * @throws NotFoundException
     */
    public function admin_view($mh_critico_id = null , $id = null)
    {
        $TABLE = $this->table;

        $this->loadModel('MhCritico');

        if(!$this->MhCritico->exists($mh_critico_id)){
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('controller'=>'mh_critico'));
        }
        
        if (!$this->$TABLE->exists($id)) {
            //            throw new NotFoundException(__($this->msg_nao_existe));
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('action' => 'index'));
        }

        $options = array('conditions' => array($TABLE . '.' . $this->$TABLE->primaryKey => $id));

        $this->$TABLE->recursive = 2;
        $row = $this->$TABLE->find('first', $options);
        $this->set('row', $row);;
    }


    /**
     * SALVAR NOVO E EDITAR!
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function admin_add($mh_critico_id = null , $id = null)
    {
        $TABLE = $this->table;

        $this->loadModel('MhCritico');

        if(!$this->MhCritico->exists($mh_critico_id)){
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('controller'=>'mh_critico'));
        }


        if ($this->request->is(array('post', 'put'))) {

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
                    $this->request->data[$TABLE]['mh_critico_id'] = $mh_critico_id;
                    $this->request->data[$TABLE]['data_atualizacao'] = null;
                } else {
                    $this->request->data[$TABLE]['data_atualizacao'] = $dateTimeNow;
                }



                if (!$this->$TABLE->save($this->request->data[$TABLE])) {
                    throw new Exception();
                }
                $id = $this->$TABLE->id;


                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Gravação - Crítico Histórico',
                    'description'         => json_encode($this->data),
                    'server_description'  => '',
                    'data_cadastro'       => $dateTimeNow,
                    'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);

                $this->Session->setFlash(__($this->msg_salvo));
                $dataSource->commit();

                return $this->redirect(array('action' => 'add', $mh_critico_id, $id));
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
                    'log'                 => 'Erro Gravação - Crítico Histórico',
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
                $this->redirect(array('action' => 'index',$mh_critico_id));
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
    public function admin_delete($mh_critico_id,$id = null)
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
                foreach ($idsArr as $id) :
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
        $this->redirect(array('action' => 'index',$mh_critico_id));
    }



    
}
