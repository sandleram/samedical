<?php

App::uses('AppController', 'Controller');

class TipoBeneficioController  extends AppController {

    public  $components = array('Paginator','Funcoes');
    private $name_search;
    private $table;
    public  $msg_nao_existe = 'Tipo Beneficio Inexistente';
    public  $msg_salvo = 'O Tipo Beneficio foi SALVO com sucesso!';
    public  $msg_salvo_erro = 'Não foi possível SALVAR o Tipo Beneficio, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public  $msg_excluido = 'O Tipo Beneficio foi EXCLUÍDO com sucesso ';
    public  $msg_excluido_erro = 'Não foi possível EXCLUIR o Beneficio, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    
    
    /**
     * AUTOMÁTICO
     * MÉTODO INICIA ANTES DE TUDO QUANDO A CONTROLLER É CHAMADA
     */
    public function beforeFilter() {
        parent::beforeFilter();
        
        #CONVERSÃO PARA ARQUIVOS COM UNDERLINE
        $control_verify = $this->params['controller'];
        $control_verify = str_replace('_', ' ', $control_verify);
        $control_verify = ucwords($control_verify);
        $control_verify = str_replace(' ', '', $control_verify);
        $this->table = $control_verify;
        $this->set('TABLE', $control_verify);
        
        $this->name_search = 'pesquisa_'.$this->params['controller'];
        $this->set('name_search', $this->name_search);
        
        
        
        
        
        
    }
    
    
    /**
     * AUTOMÁTICO
     * Retira da sessão a busca feita pelo usuário Element/admin/search_filter.ctp
     * @param type $search
     */
    public function admin_busca_unset($search) {
        $this->autoRender = false;
        parent::all_busca_unset($search,$this->name_search);
        $this->redirect(array('action' => 'index'));
    }

    
    /**
     * AUTOMÁTICO (TROCAR SOMENTE NOMES DOS FILTROS DA BUSCA)
     * LISTAGEM E FILTRO 
     */
    public function admin_index() {
        
        $TABLE = $this->table;
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'].'_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'].'_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        $search = $this->Session->read($this->name_search);
        $condition = array();
        
        if (is_array($search)):
            if (!empty($search['id_']) && is_numeric($search['id_'])):
                $condition[] = array($TABLE.'.id = "' . $search['id_'] . '"');
            endif;
            if (!empty($search['descricao'])):
                $buscaArr = explode(' ',$search['descricao']);
                if(count($buscaArr)> 0){
                   foreach($buscaArr as $vBusca){
                       $condition[] = $TABLE.'.descricao like "%' . $vBusca . '%"';
                   }
                }
            endif;
    
            if ($search['status']!= ''):
                $condition[] = $TABLE.'.status = '. $search['status'];
            endif;
        endif;
        
        
        
        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if($this->Session->read('Auth.Usuario.id') != $this->uRoot){
            $condition[] = $TABLE.$this->status_default;
        }
        #END- USUÁRIO AUTORIZADO DEFAULT
        
   
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 10,
            'order' => array('id' => 'DESC')
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
    public function admin_view($id = null) {
        $TABLE = $this->table;
        
        if (!$this->$TABLE->exists($id)) {
//            throw new NotFoundException(__($this->msg_nao_existe));
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('action'=>'index'));

        }
        
        $options = array('conditions' => array($TABLE.'.' . $this->$TABLE->primaryKey => $id));
        
        $row = $this->$TABLE->find('first', $options);
        $this->set('row',$row); ;
    }

    
    /**
     * SALVAR NOVO E EDITAR!
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function admin_add($id = null) {
        $TABLE = $this->table;
        
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
                    $this->request->data[$TABLE]['cliente_id'] = $this->cliente_id;
                }else{
                    $this->request->data[$TABLE]['data_atualizacao'] = $dateTimeNow;
                }
                
                
                
                
                
                
                if (!$this->$TABLE->save($this->request->data[$TABLE])) {
                    throw new Exception();
                }
                $id = $this->$TABLE->id;
                
                
                
                $this->Session->setFlash(__($this->msg_salvo));
                $dataSource->commit();
                return $this->redirect(array('action'=>'add',$id));
                
            } catch (Exception $ex) {
               
                $flash = '';
                if(count($this->$TABLE->validationErrors) > 0){
                    $errorDB = $this->$TABLE->validationErrors;
                    foreach($errorDB as $kerror => $error){
                        if($error[0] == 'notEmpty'){
                            $flash .= 'Erro: O campo '.$kerror.' não pode ser vazio!<br />';
                        }
                    }
                }
                
                if($flash == ''){
                    $flash = 'Erro: '.$this->msg_salvo_erro;
                }
                
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
            $options = array('conditions' => array($TABLE.'.' . $this->$TABLE->primaryKey => $id));
            $this->request->data = $this->$TABLE->find('first', $options);
           
        }
        
       
        
        
        #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
        if (!$this->request->is(array('post', 'put'))){
            $error_form = $this->Session->read('error_form');
            $this->Session->delete('error_form');
            if(is_array($error_form)){
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
    public function admin_delete($id = null) {
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
        } else {#EXCLUSÃO MULTIPLA
           
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

    
    
    
}
