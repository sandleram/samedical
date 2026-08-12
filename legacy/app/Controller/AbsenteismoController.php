<?php
App::uses('AppController', 'Controller');
/**
 * Absenteismo Controller
 *
 * @property BeneficioPrevidenciario $BeneficioPrevidenciario
 * @property PaginatorComponent $Paginator
 */
class AbsenteismoController extends AppController {
    public  $components = array('Paginator','Funcoes');
    private $name_search;
    private $table;
    public  $msg_nao_existe = 'Absenteísmo Inexistente';
    public  $msg_salvo = 'O Absenteísmo foi SALVO com sucesso!';
    public  $msg_salvo_erro = 'Não foi possível SALVAR o Absenteísmo, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public  $msg_excluido = 'O Absenteísmo foi EXCLUÍDO com sucesso ';
    public  $msg_excluido_erro = 'Não foi possível EXCLUIR o Absenteísmo, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    
    
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
    public function admin_index($beneficiario_id = null) {
        $TABLE = $this->table;

        $this->redirect($this->referer());

        if($beneficiario_id == null || !is_numeric($beneficiario_id)){
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect($this->referer());
            exit;
        } 

        // krumo($this->Session);  
        // exit;
        $this->loadModel('Beneficiario');
        $benef = $this->Beneficiario->find('first',array('conditions'=>array('id' => $beneficiario_id )));
        if(count($benef) == 0){
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect($this->referer());
            exit;
        }
     
        
        
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'].'_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'].'_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        $search = $this->Session->read($this->name_search);
        $condition = array();
        
        if (is_array($search)):
            if (!empty($search['beneficiario_id_']) && is_numeric($search['id_'])):
                $condition[] = array($TABLE.'.id = 
                "' . $search['id_'] . '"');
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
        
        
        
        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if($this->Session->read('Auth.Usuario.id') != $this->uRoot){
            $condition[] = $TABLE.$this->status_default;
        }
        #END- USUÁRIO AUTORIZADO DEFAULT
        
        
        
            

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 10,
            'order' => array('order'=> 'ASC')
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
    public function admin_view($beneficiario_id = null, $id = null) {
        $TABLE = $this->table;

        $this->redirect($this->referer());
        
        
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
    public function admin_add($beneficiario_id = null, $id = null) {
        $TABLE = $this->table;
       
        $this->loadModel('Empresa');
        $empresaArr = $this->Empresa->find('list',array('conditions'=>array('cliente_id'=>$this->cliente_id),'order'=>array('razao_social'=>'ASC'),'fields'=>'id,razao_cnpj'));
        $empresaArr = $this->Funcoes->select_merge($empresaArr);
        $this->set('empresaArr',$empresaArr);
        
     
        

        if($beneficiario_id == null || !is_numeric($beneficiario_id)){
            $this->Session->setFlash('Beneficiário inválido!');
            $this->redirect(array('controller'=>'beneficiario'));
            exit;
        } 

        
        $this->loadModel('Beneficiario');
        $benef = $this->Beneficiario->find('first',array('conditions'=>array('id' => $beneficiario_id, 'cliente_id'=>$this->cliente_id),'fields'=>'id,nome','recursive'=>-1));
        if(count($benef) == 0){
            $this->Session->setFlash('Beneficiário inexistente ou não permitido acesso para seu perfil!');#de outro cliente
            $this->redirect(array('controller'=>'beneficiario'));
            exit;
        }
        $this->set('benef',$benef);
        
        if ($this->request->is(array('post', 'put'))) {
            
            // krumo($this->data);
            // exit;


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
                    $this->request->data[$TABLE]['usuario_atualizacao_id'] = $user_create;
                }
                
                

                


                #DATA_SAIDA
                #DATA_RETORNO 
                #calculo dias

                $data_saida = $this->data[$TABLE]['data_saida'];
                $data_retorno = $this->data[$TABLE]['data_retorno'];
                $dias = trim($this->data[$TABLE]['qtde_dias_atestado']);

                if($data_saida != '' && $data_retorno != '' && $dias == ''){
                    $dias = $this->Funcoes->dias_entre_datas($data_saida,$data_retorno);
                    $this->request->data[$TABLE]['qtde_dias_atestado'] = $dias;
                }
                
                if($data_saida != '' && $dias != ''){
                    $this->request->data[$TABLE]['data_retorno'] =  $this->Funcoes->calculaData('Y-m-d', $data_saida.' 00:00:01', ' + '.$dias.' DAY');
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
                                  'log'=>'Absenteísmo - Cadastro',
                                  'description'         =>  '',
                                  'server_description'  =>  '',
                                  'data_cadastro'       =>  date('Y/m/d H:i:s'),
                                  'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                        );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
                return $this->redirect(array('action'=>'add',$beneficiario_id,$id));
                
            } catch (Exception $ex) {
                  debug($this->Absenteismo->getDataSource()->getLog(false, false));
                  exit;
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
                                                  'log'=>'Absenteísmo - Erro - Cadastro',
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
                $this->redirect(array('controller'=>'beneficiario','action' => 'view',$beneficiario_id));
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
    public function admin_delete($beneficiario_id = null, $id = null) {
        
        $TABLE = $this->table;

        $this->redirect($this->referer());
        
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
                                            'log'=>'Absenteísmo - Exclusão ',
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
                #BEGIN - CRIANDO LOG    
                    $this->loadModel('Log');
                    $this->Log->create();
                    $data_log = array(  'id' =>'',
                                        'log'=>'Absenteísmo - Exclusão Múltipla',
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
        $this->redirect(array('controller'=>'beneficiario','action' => 'view',$beneficiario_id));
    }

    
}
