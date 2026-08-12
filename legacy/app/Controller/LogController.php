<?php

App::uses('AppController', 'Controller');

class LogController extends AppController {

    public  $components = array('Paginator','Funcoes');
    private $name_search;
    private $table;
    public  $msg_nao_existe = 'Log Inexistente';
    public  $msg_salvo = 'A Log foi SALVA com sucesso!';
    public  $msg_salvo_erro = 'Não foi possível SALVAR a Log, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public  $msg_excluido = 'A Log foi EXCLUÍDA com sucesso ';
    public  $msg_excluido_erro = 'Não foi possível EXCLUIR a Log, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    
    
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
        #ID	Log	Tabela	Nova Informação	Antiga Informação	Campo	Data de Cadastro	Usuário	Ações
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
            if (!empty($search['log'])):
                $buscaArr = explode(' ',$search['log']);
                if(count($buscaArr)> 0):
                   foreach($buscaArr as $vBusca):
                       $condition[] = $TABLE.'.log like "%' . $vBusca . '%"';
                   endforeach;
                endif;
            endif;
            if (!empty($search['description'])):
                $buscaArr = explode(' ',$search['description']);
                if(count($buscaArr)> 0):
                   foreach($buscaArr as $vBusca):
                       $condition[] = $TABLE.'.description like "%' . $vBusca . '%"';
                   endforeach;
                endif;
            endif;
            
            if (!empty($search['data_inicio']) && empty($search['data_fim'])):
                $condition[] = $TABLE.'.data_cadastro > "'.$search['data_inicio'].'"';
            elseif (empty($search['data_inicio']) && !empty($search['data_fim'])):
                $condition[] = $TABLE.'.data_cadastro < "'.$search['data_fim'].'"';
            elseif (!empty($search['data_inicio']) && !empty($search['data_fim'])):
                $condition[] = $TABLE.'.data_cadastro between "'.$search['data_inicio'].'" and "'.$search['data_fim'].'"';
            endif;
           
        endif;

        
       
          
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 30,
            'order'=>'Log.id desc'
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
    public function admin_add($data = array()) {
        $TABLE = $this->table;
        $dateTimeNow = date('Y-m-d H:i:s');
        $user_create = $this->Session->read('Auth.Usuario.id');
        
        $this->$TABLE->create();
        $data['data_cadastro'] = $dateTimeNow;
        $data['usuario_id'] = $user_create;
        

        if (!$this->$TABLE->save($data)) {
           return false;
        }
       return true;
    }
 
    
  
    
    
    
    
    
}
