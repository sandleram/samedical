<?php
App::uses('AppController', 'Controller');
/**
 * Bi Controller
 *
 * @property BeneficioPrevidenciario $BeneficioPrevidenciario
 * @property PaginatorComponent $Paginator
 */
class BiController extends AppController {
    public  $components = array('Paginator','Funcoes');
    private $name_search;
    private $table;
    public  $msg_nao_existe = 'Bi Inexistente';
    public  $msg_salvo = 'O Bi foi SALVO com sucesso!';
    public  $msg_salvo_erro = 'Não foi possível SALVAR o Bi, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public  $msg_excluido = 'O Bi foi EXCLUÍDO com sucesso ';
    public  $msg_excluido_erro = 'Não foi possível EXCLUIR o Bi, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    
    
    /**
     * AUTOMÁTICO
     * MÉTODO INICIA ANTES DE TUDO QUANDO A CONTROLLER � CHAMADA
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



    public function admin_lista(){
        $this->loadModel('GrupoEmpresarial');
        $this->loadModel('Cliente');
        $this->loadModel('UsuarioBi');

        $list = [];
        // $row = $this->GrupoEmpresarial->find('first',['conditions'=>['id'=>$this->grupo_empresarial_id],'fields'=>'bi','recursive'=> -1]);
        // if($row['GrupoEmpresarial']['bi'] != ''){
        //   $list['gerencial'] = 'Gerencial' ;   
        // }
        
        // $row = $this->Cliente->find('first',['conditions'=>['id'=>$this->cliente_id],'fields'=>'bi_medico,bi_rh','recursive'=> -1]);
        // if($row['Cliente']['bi_medico'] != ''){
        //     $list['medico'] = 'M�dico' ;    
        // }

        // $row = $this->Cliente->find('first',['conditions'=>['id'=>$this->cliente_id],'fields'=>'bi_rh','recursive'=> -1]);
        // if($row['Cliente']['bi_rh'] != ''){
        //     $list['rh'] = 'RH' ;   
        // }


        #GRUPO EMPRESARIAL
        $rows = $this->UsuarioBi->find('all',['conditions'=>['UsuarioBi.usuario_id'=>$this->Session->read('Auth.Usuario.id'),
                                                             'Bi.grupo_empresarial_id'=>$this->Session->read('Auth.Usuario.grupo_empresarial_id'),
                                                             'Bi.status'=>1,
                                                             'Bi.cliente_id'=>null],
                                              'order'=>['ordem'=>'ASC'],
                                              'recursive'=> 1]);
        if(count($rows)>0){
            foreach($rows as $row){
            
                $list[] = [ 'titulo'=>$row['Bi']['titulo'],
                            'subtitulo'=>$row['Bi']['subtitulo'],
                            'link'=>$row['Bi']['link']];
            }
        }

        #CLIENTES ESPECIFICO
        $rows = $this->UsuarioBi->find('all',['conditions'=>['UsuarioBi.usuario_id'=>$this->Session->read('Auth.Usuario.id'),
                                                             'Bi.grupo_empresarial_id'=>$this->Session->read('Auth.Usuario.grupo_empresarial_id'),
                                                             'Bi.status'=>1,
                                                             'Bi.cliente_id'=>$this->Session->read('Auth.Usuario.cliente_id')],
                                              'order'=>['ordem'=>'ASC'],
                                              'recursive'=> 1]);
        if(count($rows)>0){
            foreach($rows as $row){
                $list[] = [ 'titulo'=>$row['Bi']['titulo'],
                            'subtitulo'=>$row['Bi']['subtitulo'],
                            'link'=>$row['Bi']['link']];
            }
        }
        
        
       
        #$this->Session->read('Auth.Usuario.cliente_id');
        
        // $row = $this->Bi->find('first',['conditions'=>['id'=>$this->grupo_empresarial_id],'fields'=>'bi','recursive'=> -1]);
        // $this->set('url',$row['GrupoEmpresarial']['bi']);







         
        $this->set('list',$list);
    }


    public function admin_gerencial(){
        $this->loadModel('GrupoEmpresarial');
        $row = $this->GrupoEmpresarial->find('first',['conditions'=>['id'=>$this->grupo_empresarial_id],'fields'=>'bi','recursive'=> -1]);
        $this->set('url',$row['GrupoEmpresarial']['bi']);
    }
    public function admin_medico(){
        $this->loadModel('Cliente');
        $row = $this->Cliente->find('first',['conditions'=>['id'=>$this->cliente_id],'fields'=>'bi_medico','recursive'=> -1]);
        $this->set('url',$row['Cliente']['bi_medico']);
    }
    public function admin_rh(){
        $this->loadModel('Cliente');
        $row = $this->Cliente->find('first',['conditions'=>['id'=>$this->cliente_id],'fields'=>'bi_rh','recursive'=> -1]);
        $this->set('url',$row['Cliente']['bi_rh']);
        
    }



    
    /**
     * AUTOMÁTICO (TROCAR SOMENTE NOMES DOS FILTROS DA BUSCA)
     * LISTAGEM E FILTRO 
     */
    public function admin_index() {
        
        
        $TABLE = $this->table;
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'].'_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'].'_form_busca']); //USADO PARA PAGINA??O
            endif;
        endif;
        $search = $this->Session->read($this->name_search);
        $condition = array();
        
        if (is_array($search)):
            if (!empty($search['id_']) && is_numeric($search['id_'])):
                $condition[] = array($TABLE.'.id = "' . $search['id_'] . '"');
            endif;
            if ($search['modulo_id']!= ''):
                $condition[] = $TABLE.'.modulo_id = '. $search['modulo_id'];
            endif;
            if (!empty($search['nome'])):
                $buscaArr = explode(' ',$search['nome']);
                if(count($buscaArr)> 0){
                   foreach($buscaArr as $vBusca){
                       $condition[] = $TABLE.'.nome like "%' . $vBusca . '%"';
                   }
                }
            endif;
            if (!empty($search['controller'])):
                $buscaArr = explode(' ',$search['controller']);
                if(count($buscaArr)> 0){
                   foreach($buscaArr as $vBusca){
                       $condition[] = $TABLE.'.controller like "%' . $vBusca . '%"';
                   }
                }
            endif;
            
            if ($search['status']!= ''):
                $condition[] = $TABLE.'.status = '. $search['status'];
            endif;
        endif;
        
        
        
        #BEGIN- USU?RIO AUTORIZADO DEFAULT
        if($this->Session->read('Auth.Usuario.id') != $this->uRoot){
            $condition[] = $TABLE.$this->status_default;
        }
        #END- USU?RIO AUTORIZADO DEFAULT
        
        
        
            

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 15,
            'order' => array('modulo_id' => 'ASC', 'order'=> 'ASC')
        );
        

        
        #ADICIONAR
//        $this->$TABLE->recursive = 1;
        $this->set('rows', $this->Paginator->paginate());
        $this->set('search', $search);
        
    }

    
    
    /**
     * VISUALIZA??O DAS INFORMA??ES
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
                }else{
                    $this->request->data[$TABLE]['data_atualizacao'] = $dateTimeNow;
                }
                
                
                
                if (!$this->$TABLE->save($this->request->data[$TABLE])) {
                    throw new Exception();
                }
                $id = $this->$TABLE->id;
                
                                
                $this->Session->setFlash(__($this->msg_salvo));
                $dataSource->commit();
                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                  'log'=>'Módulo - Cadastro',
                                  'description'         =>  '',
                                  'server_description'  =>  '',
                                  'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                  'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
                return $this->redirect(array('action'=>'add',$id));
                
            } catch (Exception $ex) {
               
                $flash = '';
                $dataSource->rollback();
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
                #BEGIN - CRIANDO LOG ERRO
                $this->loadModel('LogErro');
                $this->LogErro->create();
                $data_log = array('id' =>'',
                                                  'log'=>'Módulo - Erro - Cadastro',
                                                  'description'         =>  json_encode($this->data),
                                                  'mensagem'            =>  $flash,
                                                  'server_description'  =>  json_encode($this->params),
                                                  'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                                  'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                        );
                $this->LogErro->save($data_log);	
                #END - CRIANDO LOG ERRO
                
                #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSER??O
                $this->Session->write('error_form', $this->data);
                $this->redirect($this->referer());
                #END - RETORNO DADOS EM CASO DE ERRO NA INSER??O
 
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
        
       
        
        
        #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSER??O
        if (!$this->request->is(array('post', 'put'))){
            $error_form = $this->Session->read('error_form');
            $this->Session->delete('error_form');
            if(is_array($error_form)){
                $data_new = array_merge($this->data, $error_form);
                $this->request->data = $data_new;
            }
        }
        #END - RETORNO DADOS EM CASO DE ERRO NA INSER??O

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
                    #BEGIN - CRIANDO LOG    
                        $this->loadModel('Log');
                        $this->Log->create();
                        $data_log = array(  'id' =>'',
                                            'log'=>'Módulo - Exclusão ',
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
        } else {#EXCLUS?O MULTIPLA
           
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
                    $data_log = array(  'id' =>'',
                                        'log'=>'Módulo - Exclusão Múltipla',
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
        $this->redirect(array('action' => 'index'));
    }




    
}
