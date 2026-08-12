<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class BeneficiarioController extends AppController {
    
    #SUCESSO DEVE SER MÍNUSCULO
    public $components = array('Paginator','Funcoes');
    private $name_search;
    private $table;
    public $msg_nao_existe = 'Beneficiario Inexistente';
    public $msg_salvo = 'A Beneficiario foi SALVA com sucesso!';
    public $msg_salvo_erro = 'Não foi possível SALVAR a Beneficiario, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public $msg_salvo_erro_login = 'Não foi possível efetuar o FILTRO ou SALVAR porque foi deslogado, tente novamente!';
    public $msg_excluido = 'A Beneficiario foi EXCLUÍDA com sucesso!';
    public $msg_excluido_erro = 'Não foi possível EXCLUIR a Beneficiario, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    
    
    
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
        
        $telTipoArr     = $this->Funcoes->select_merge(array('Residencial'=>'Residencial','Comercial'=>'Comercial','Fax'=>'Fax','Celular'=>'Celular'));

        $sexoArr        = $this->Funcoes->select_merge(array('M' => 'Masculino', 'F' => 'Feminino'));
        
        if($this->action == 'admin_view'){
            $estadoCivilArr = $this->Funcoes->parametros('Estado Civil','list',null,true,'');
        }else if($this->action == 'admin_index'){
            $estadoCivilArr = $this->Funcoes->parametros('Estado Civil','list',null,true,'Estado Civil...');
        }else{
            $estadoCivilArr = $this->Funcoes->parametros('Estado Civil');
        }
        $this->set(compact('sexoArr','telTipoArr','estadoCivilArr'));
        
        
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
            if ($search['cpf']!= ''):
                $cpf = $search['cpf'];
                $cpf = str_replace('.','',$cpf);
                $cpf = str_replace('-','',$cpf);
                
                $condition[] = $TABLE.'.cpf = "'.$cpf.'"' ;
            endif;
            
            if ($search['status']!= ''):
                $condition[] = $TABLE.'.status = '. $search['status'];
            endif;
        endif;
        
        
        
        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if($this->Session->read('Auth.Usuario.id') != $this->uRoot){
            $condition[] = $TABLE.$this->status_default;
        }
        $condition[] = $TABLE.'.cliente_id = '.$this->cliente_id;
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
        
        $this->$TABLE->recursive = 2;
        $row = $this->$TABLE->find('first', $options);

        $row['Beneficiario']['first_name'] = '';
        $row['Beneficiario']['last_name'] = '';
        if(isset($row['Beneficiario']['nome'])){
            $nomeArr = explode(' ',$row['Beneficiario']['nome']);
            $row['Beneficiario']['first_name'] = $nomeArr[0];
            unset($nomeArr[0]);
            $row['Beneficiario']['last_name'] = implode(' ',$nomeArr);
        }

        $altura = '-';
        $peso = '-';
        $valor = '-';

        if(isset($row['Beneficiario']['altura']) && $row['Beneficiario']['altura'] != '' && $row['Beneficiario']['altura'] > 0){
            if($row['Beneficiario']['altura'] > 100){
                $altura = $row['Beneficiario']['altura'] / 100;
                $altura =  str_pad($altura,4,0,STR_PAD_RIGHT);
            }
        }
        if(isset($row['Beneficiario']['peso']) && $row['Beneficiario']['peso'] != '' && $row['Beneficiario']['peso'] > 0){
            $peso = str_replace('.',',', $row['Beneficiario']['peso']);
        }
        if(isset($row['Beneficiario']['valor']) && $row['Beneficiario']['valor'] != '' && $row['Beneficiario']['valor'] > 0){
            $valor = str_replace('.',',',$row['Beneficiario']['valor']);
        }

        $row['Beneficiario']['altura'] = $altura;
        $row['Beneficiario']['peso'] = $peso;
        $row['Beneficiario']['valor'] = $valor;



        $listAfa = $listAfaAll = array();#afastado
        $listBP = $listBPAll = array(); #beneficio previdenciario
        $listAb = $listAbAll = array(); #absenteísmo
        $listAt = $listAtAll = array();#timeline

        






        #afastado
        if(isset($row['Afastado']) && count($row['Afastado']) > 0){
            foreach($row['Afastado'] as $afastArr){
                $listAfa = array();
                $listAfa['id']                       = $afastArr['id'];
                $listAfa['importacao_id']            = $afastArr['importacao_id'];
                $listAfa['data_inicio_afastamento']  = $afastArr['data_inicio_afastamento'];
                $listAfa['data_fim_afastamento']     = $afastArr['data_fim_afastamento'];
                $listAfa['cid']                      = $afastArr['cid'];
                $listAfa['tipo_afastamento']         = $afastArr['tipo_afastamento'];
                $listAfa['assistencia_medica']       = $afastArr['assistencia_medica'];
                $listAfa['plano_assistencia_medica'] = $afastArr['plano_assistencia_medica'];
                $listAfa['data_cadastro']            = $afastArr['data_cadastro'];
                $btnLink = Router::url(array('controller'=>'afastado','action'=>'add',$row['Beneficiario']['id'],$afastArr['id']),true);
                $listAfa['btn'] = '<a class="btn btn-primary btn-xs" href="'.$btnLink.'">Editar</a>';
                $listAfaAll[] = $listAfa;
            }
        }
        unset($row['Afastado']);


        #beneficio previdenciario
        if(isset($row['BeneficioPrevidenciario']) && count($row['BeneficioPrevidenciario']) > 0){
            foreach($row['BeneficioPrevidenciario'] as $benefPrevArr){
                $listBP = array();
                $listBP['id']                       = $benefPrevArr['id'];
                $listBP['importacao_id']            = $benefPrevArr['importacao_id'];
                $listBP['especie']                  = $benefPrevArr['especie'];
                $listBP['especie_bp_id']            = $benefPrevArr['especie_bp_id'];
                $listBP['situacao']                 = $benefPrevArr['situacao'];
                $listBP['data_entrada_requerimento']= $benefPrevArr['data_entrada_requerimento'];
                $listBP['data_inicio']              = $benefPrevArr['data_inicio'];
                $listBP['data_despacho']            = $benefPrevArr['data_despacho'];
                $listBP['data_realizacao_pericia']  = $benefPrevArr['data_realizacao_pericia'];
                $listBP['conclusao_pericia_medica'] = $benefPrevArr['conclusao_pericia_medica'];
                $listBP['data_limite']              = $benefPrevArr['data_limite'];
                $listBP['data_indeferimento']       = $benefPrevArr['data_indeferimento'];
                $listBP['data_cessacao']            = $benefPrevArr['data_cessacao'];
                $listBP['nexo_tecnico']             = $benefPrevArr['nexo_tecnico'];
                $listBP['data_cadastro']            = $benefPrevArr['data_cadastro'];
                $btnLink = Router::url(array('controller'=>'beneficio_previdenciario','action'=>'add',$row['Beneficiario']['id'],$benefPrevArr['id']),true);
                $listBP['btn'] = '<a class="btn btn-primary btn-xs" href="'.$btnLink.'">Editar</a>';
                $listBPAll[] = $listBP;
            }
        }
        unset($row['BeneficioPrevidenciario']);

        #absenteísmo
        if(isset($row['Absenteismo']) && count($row['Absenteismo']) > 0){
            foreach($row['Absenteismo'] as $absenteismoArr){

                
                $listAb = array();
                $listAb['id']                   = $absenteismoArr['id'];
                $listAb['importacao_id']        = $absenteismoArr['importacao_id'];
                $listAb['matricula']			= $absenteismoArr['matricula'];
                $listAb['documento_id']			= $absenteismoArr['documento_id'];
                $listAb['motivo_id']		    = $absenteismoArr['motivo_id'];
                $listAb['hospital_clinica']		= $absenteismoArr['hospital_clinica'];
                $listAb['nome_colaborador']		= $absenteismoArr['nome_colaborador'];
                $listAb['data_saida']			= $absenteismoArr['data_saida'];
                $listAb['qtde_dias_atestado']	= $absenteismoArr['qtde_dias_atestado'];
                $listAb['hora_saida']		    = $absenteismoArr['hora_saida'];
                $listAb['hora_retorno']		    = $absenteismoArr['hora_retorno'];
                $listAb['cid']					= $absenteismoArr['cid'];
                $listAb['especialidade_id']		= $absenteismoArr['especialidade_id'];
                $listAb['emissor_id']			= $absenteismoArr['emissor_id'];
                $listAb['profissional']			= $absenteismoArr['profissional'];
                $listAb['num_crm']				= $absenteismoArr['num_crm'];
                $listAb['tipo_absenteismo_id']	= $absenteismoArr['tipo_absenteismo_id'];
                $listAb['situacao']				= $absenteismoArr['situacao'];
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
                $btnLink = Router::url(array('controller'=>'absenteismo','action'=>'add',$row['Beneficiario']['id'],$absenteismoArr['id']),true);
                
                $listAb['btn'] = '<a class="btn btn-primary btn-xs" href="'.$btnLink.'">Editar</a>';
                $listAbAll[] = $listAb;
            }
        }
        unset($row['Absenteismo']);


        #beneficio previdenciario
        if(isset($row['Atendimento']) && count($row['Atendimento']) > 0){
            foreach($row['Atendimento'] as $atendArr){
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
                $listAT['data_conclusao']           = $atendArr['data_conclusao'];
                $listAT['data_cadastro']            = $atendArr['data_cadastro'];
                $btnEditLink = Router::url(array('controller'=>'atendimento','action'=>'add',$row['Beneficiario']['id'],$atendArr['id']),true);
                $btnExcLink = Router::url(array('controller'=>'atendimento','action'=>'delete',$row['Beneficiario']['id'],$atendArr['id']),true);
                $listAT['btn'] = '<a class="btn btn-primary btn-xs" href="'.$btnEditLink.'">Editar</a> <a class="btn btn-danger btn-xs" href="'.$btnExcLink.'">Excluir</a>';
                
                $listAtAll[] = $listAT;
            }
        }
        unset($row['Atendimento']);

       
        
        $row['ListAfastado'] = $listAfaAll;
        $row['listBeneficioPrevidenciario'] = $listBPAll;
        $row['listAbsenteismo'] = $listAbAll;
        $row['listTimeline'] = $listAtAll;



        #krumo($row); exit;



        
		$this->set('simNaoArr', array('' => '', 0 => 'Não', 1 => 'Sim'));

		$tipoAtendimentoArr = array('' => 'Selecione...', 1 => 'Acolhimento Social', 2 => 'Acolhimento Psicológico', 3 => 'Atendimento Médico',4 => 'Atendimento de Enfermagem','5'=>'Atendimento de Fisioterapia');
		$this->set('tipoAtendimentoArr', $tipoAtendimentoArr);
		
		$formaAtendimentoArr = array('' => 'Selecione...', 0 => 'Telefone', 1 => 'Presencial', 2 => 'E-mail');
		$this->set('formaAtendimentoArr', $formaAtendimentoArr);
		
        $statusAtendimentoArr = array('' => '', 2 => 'Concluído', 0 => 'Sem Contato', 1 => 'Deixou Recado');
		$this->set('statusAtendimentoArr', $statusAtendimentoArr);

		

        

        $this->set('row',$row); 
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
                }else{
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
                
                
                if (!$this->$TABLE->save($this->request->data[$TABLE])) {
                    throw new Exception();
                }
                $id = $this->$TABLE->id;
                
                
                
                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
                                  'log'                 => 'Gravação - Beneficiario',
                                  'description'         => json_encode($this->data),
                                  'server_description'  => '',
                                  'data_cadastro'       => $dateTimeNow,
                                  'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                    );
                $this->Log->save($data_log);
                
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
                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array('id' =>'',
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
            $options = array('conditions' => array($TABLE.'.' . $this->$TABLE->primaryKey => $id));
            $this->$TABLE->recursive = 2;
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
