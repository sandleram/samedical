<?php

App::uses('AppController', 'Controller');

class PlanoController extends AppController {

    public  $components = array('Paginator','Funcoes');
    private $name_search;
    private $table;
    public  $msg_nao_existe = 'Plano Inexistente';
    public  $msg_salvo = 'O Plano foi SALVO com sucesso!';
    public  $msg_salvo_erro = 'Não foi possível SALVAR o Plano, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public  $msg_excluido = 'O Plano foi EXCLUÍDO com sucesso ';
    public  $msg_excluido_erro = 'Não foi possível EXCLUIR o Plano, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    
    
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
        
        $this->loadModel('Operadora');
        $operadoraArr = $this->Operadora->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,nome'));
        if(in_array($this->action,array('admin_view','admin_index'))){
            $operadoraArr = $this->Funcoes->select_merge($operadoraArr,'Operadora...');
        }else{
            $operadoraArr = $this->Funcoes->select_merge($operadoraArr);
        }
        $this->set('operadoraArr',$operadoraArr);
        
        
        $this->loadModel('TipoBeneficio');
        $tipoBeneficioArr = $this->TipoBeneficio->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,descricao'));
        if(in_array($this->action,array('admin_view','admin_index'))){
            $tipoBeneficioArr = $this->Funcoes->select_merge($tipoBeneficioArr,'Tipo de Beneficio...');
        }else{
            $tipoBeneficioArr = $this->Funcoes->select_merge($tipoBeneficioArr);
        }
        $this->set('tipoBeneficioArr',$tipoBeneficioArr);
        
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
            if (!empty($search['nome'])):
                $buscaArr = explode(' ',$search['nome']);
                if(count($buscaArr)> 0){
                   foreach($buscaArr as $vBusca){
                       $condition[] = $TABLE.'.nome like "%' . $vBusca . '%"';
                   }
                }
            endif;
            
            if ($search['tipo_beneficio_id']!= ''):
                $condition[] = $TABLE.'.tipo_beneficio_id = '. $search['tipo_beneficio_id'];
            endif;
            if ($search['operadora_id']!= ''):
                $condition[] = $TABLE.'.operadora_id = '. $search['operadora_id'];
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
          
        
        if($this->grupo_empresarial_id == 1){
            $condition['OR'] = array($TABLE.'.cliente_id = '.$this->cliente_id, $TABLE.'.cliente_id IS NULL');
        }else{
            $condition[] = $TABLE.'.cliente_id = '.$this->cliente_id;
        }
        
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 10,
            'order' => array('Operadora.nome'=>'ASC','Plano.ordem' => 'ASC')
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
                    $operadora_id       = $this->request->data[$TABLE]['operadora_id'];
                    $tipo_beneficio_id  = $this->request->data[$TABLE]['tipo_beneficio_id'];

                    $rs2 = $this->$TABLE->find('first', array('conditions' => array('cliente_id' => $this->cliente_id, 'operadora_id' => $operadora_id, 'tipo_beneficio_id' => $tipo_beneficio_id, 'status < 2'), 'fields' => array('MAX(ordem) as ordem'), 'recursive'=>'-1'));
                    $ordem = 1;
                    if(isset($rs2[0]['ordem']) && !empty($rs2[0]['ordem'])){
                        $ordem = $rs2[0]['ordem'] + 1;
                    }
                    $this->request->data[$TABLE]['ordem'] = $ordem;
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

    
  
    
    public function admin_busca_planos(){
        $data = array();
        $beneficio_id = $this->data['beneficio_id'];
        $beneficio_id = implode(',',$beneficio_id);

        
        $this->loadModel('Beneficio');
        $data = $this->Beneficio->find('all',array('conditions'=>array('status'=>1,'id in ('.$beneficio_id.')'),'fields'=>'tipo_beneficio_id,operadora_id','recursive'=>-1));
        $conditions = array();
        
        if(count($data) == 0){
            return false;
        }
        $tipo_beneficio_idArr = array();
        $operadora_idArr = array();
        foreach($data as $benefArr){
            $tipo_beneficio_idArr[] = $benefArr['Beneficio']['tipo_beneficio_id'];
            $operadora_idArr[] = $benefArr['Beneficio']['operadora_id'];
        }
        // foreach($data as $tipo_beneficio_id => $operadora_id){
        //     $tipo_beneficio_idArr[] = $tipo_beneficio_id;
        //     $operadora_idArr[] = $operadora_id;
        //     #$filtroArr[] = "(tipo_beneficio_id = {$tipo_beneficio_id} AND operadora_id = {$operadora_id})";
        // }

        
        
        #$filtro = implode(' OR ', $filtroArr);

        #$filtro = "(tipo_beneficio_id in (".implode(',',$tipo_beneficio_idArr).") AND operadora_id in (".implode(',',$operadora_id).")) ";
        $conditions[] = "status=1";
        #$conditions[] = "tipo_beneficio_id in (".implode(',',$tipo_beneficio_idArr).")";
        #$conditions[] = "operadora_id in (".implode(',',$operadora_idArr).")";
        $filtros = "tipo_beneficio_id in (".implode(',',$tipo_beneficio_idArr).") AND operadora_id in (".implode(',',$operadora_idArr).") AND cliente_id =".$this->cliente_id;
        #$conditions[] = array('OR'=>array('cliente_id is null', 'cliente_id'=>$this->cliente_id ));
        $conditions[] = array('OR'=>array('cliente_id is null', $filtros ));
        
        $data = $this->Plano->find('list',array('conditions'=>$conditions,'fields'=>'id,nome'));
        //var_dump($this->Plano->getDataSource()->getLog(false, false));
        echo json_encode($data);
        exit;
    }
    
    
    
    
    /**
     * 
     * @param type $id
     * @param type $action (0 =>  , 1)
     */
    public function admin_ordenacao($id, $action) {
        $TABLE = $this->table;
        $data1 = $data2 = array();

        if (!$this->$TABLE->exists($id)) {
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect($this->referer());
        } else {

            $rs = $this->$TABLE->find('first', array('conditions' => array('id' => $id),'recursive'=>'-1'));
            $operadora_id       = $rs[$TABLE]['operadora_id'];
            $tipo_beneficio_id  = $rs[$TABLE]['tipo_beneficio_id'];


            $rs2 = $this->$TABLE->find('first', array('conditions' => array('cliente_id' => $this->cliente_id, 'operadora_id' => $operadora_id, 'tipo_beneficio_id' => $tipo_beneficio_id, 'status < 2'), 'fields' => array('MAX(ordem) as ordem'), 'recursive'=>'-1'));
            $ordem_max = $rs2[0]['ordem'];
            $new_ordem = ($action == 0) ? $rs[$TABLE]['ordem'] - 1 : $rs[$TABLE]['ordem'] + 1;


            if ($new_ordem < 1):
                $this->Session->setFlash('Aviso: Não é possivel diminuir a ordenação para 0.');
                $this->redirect($this->referer());
            elseif ($new_ordem > $ordem_max):
                $this->Session->setFlash('Aviso: Não é possivel aumetar a ordenação para ' . $new_ordem . '.');
                $this->redirect($this->referer());
            endif;
            $old_ordem = $rs[$TABLE]['ordem'];
            $date_now = date('Y-m-d H:i:s');
            $dataSource = $this->$TABLE->getDataSource();

            try {
                $data1['id'] = $id;
                $data1['ordem'] = $new_ordem;

                unset($rs);
                $rs = $this->$TABLE->find('first', array('conditions' => array('cliente_id' => $this->cliente_id, 'operadora_id' => $operadora_id, 'tipo_beneficio_id' => $tipo_beneficio_id, 'ordem' => $new_ordem),'recursive'=>'-1'));
                if (count($rs) > 0) {
                    $data2['id'] = $rs[$TABLE]['id'];
                    $data2['ordem'] = $old_ordem;

                    if (!$this->$TABLE->save($data1)) {
                        throw new Exception();
                    } else {
                        if (!$this->$TABLE->save($data2)) {
                            throw new Exception();
                        }
                    }
                } else {
                    $this->Session->setFlash('Aviso: A ordenação já atingiu seu nível máximo!');
                    $this->redirect($this->referer());
                }
                $this->Session->setFlash(__($this->msg_salvo));
                $dataSource->commit();
            } catch (Exception $ex) {
                if ($flash == '') {
                    $flash = 'Erro: Não foi possível efetuar a mudança de ordenação. Tente novamente mais tarde!';
                }
                $dataSource->rollback();
                $this->Session->setFlash($flash);
                $this->redirect($this->referer());
            }
        }

        $this->redirect(array('action' => 'index'));
    }
    
}
