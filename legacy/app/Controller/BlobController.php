<?php
App::uses('AppController', 'Controller');
/**
 * Blob Controller
 *
 * @property Blob $Blob
 * @property PaginatorComponent $Paginator
 */




class BlobController extends AppController
{
    public  $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public  $msg_nao_existe = 'Arquivo Inexistente';
    public  $msg_salvo = 'O Arquivo foi SALVO com sucesso!';
    public  $msg_salvo_erro = 'Não foi possível SALVAR o Arquivo, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public  $msg_excluido = 'O Arquivo foi EXCLUÍDO com sucesso ';
    public  $msg_excluido_erro = 'Não foi possível EXCLUIR o Arquivo, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    private $chave = 'dsfe51ewfwe1t65h1yjzn51a515145165233asdf51vbgasd';
    public $simNaoArr = [];
    public $simNaoAcaoInssArr = [];


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
    public function admin_index()
    {

        $TABLE = $this->table;
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'] . '_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'] . '_form_busca']); //USADO PARA PAGINAÇÃO
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



        #$condition[] = 'Importacao.cliente_id = '.$this->cliente_id;
        $condition[] = 'Beneficiario.cliente_id = ' . $this->cliente_id;
        $condition[] = 'Blob.status = 1 ';
        #$condition[] = 'Blob.status = 1 ';
        #$condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        #$condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        # $condition[] = 'Blob.empresa_id = 346';


        #$this->$TABLE->recursive = 2;



        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 15,
            'order' => array('id' => 'DESC'),
            'group' => array('Blob.beneficiario_id'),
            'recursive' => 2
        );

        #krumo($condition);

        $rows = $this->Paginator->paginate();
        #krumo($rows);
        #exit;
        $this->set('rows', $rows);
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


    public function admin_download($id_md5 = null)
    {
        $TABLE = $this->table;
        if ($id_md5 == null || strlen($id_md5) != 32) {
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect($this->referer());
        }


        $row = $this->$TABLE->find('first', array(
            'conditions' => array('md5(id) = "' . $id_md5 . '"', 'Blob.status' => 1),
            'recursive' => -1
        ));


        if (count($row) == 0) {
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect($this->referer());
        }

        if ($row[$TABLE]['blob'] == null || $row[$TABLE]['blob'] == '') {
            $this->Session->setFlash('Arquivo não existe no banco de dados!');
            $this->redirect($this->referer());
        }




        header('Content-Type: ' . $row['Blob']['tipo']);
        header('Content-Disposition: attachment; filename="' . $row['Blob']['nome'] . '"');
        header("Content-Length: " . $row['Blob']['tamanho']);
        echo $row['Blob']['blob'];
        exit;
    }



    #usado na parent::blob_action
    public function blob_action($FILE = null, $attr = [])
    {
        $this->loadModel('Blob');
        $dbo_blob = $this->Blob->getDatasource();

        if (!in_array($attr['action'], array(0, 1, 2))) {
            return false;
        }
        $file_exist = false;
        if (isset($FILE['tmp_name']) && $FILE['tmp_name'] != '' && file_exists($FILE['tmp_name']) && in_array($attr['action'], array(0, 1))) {

            $fileContent = file_get_contents($FILE['tmp_name']);
            $fileNameArr = explode('.', $FILE['name']);
            $extensao = $fileNameArr[count($fileNameArr) - 1];
            unset($fileNameArr[count($fileNameArr) - 1]);
            $file_name = implode('.', $fileNameArr);
            $file_name = $this->Funcoes->normalizaeUrl($file_name) . '_' . time() . '.' . $extensao;
            $file_exist = true;
        }

        // krumo($fileContent);
        // krumo($FILE);
        // krumo($attr);
        // krumo($file_exist);
        // exit;

        if ($attr['action'] == 0) {
            if ($file_exist == false) {
                return false;
            }

            #AJUSTA MYSQL PARA ACEITAR BLOB MAIORES
            $this->mysql_blob_allowed($dbo_blob);


            $dataSaveBlob = [];
            $dataSaveBlob['Blob']['id'] = '';
            $dataSaveBlob['Blob']['table'] = $attr['table'];
            $dataSaveBlob['Blob']['nome'] = $file_name;
            $dataSaveBlob['Blob']['tipo'] = $FILE['type'];
            $dataSaveBlob['Blob']['tamanho'] = $FILE['size'];
            $dataSaveBlob['Blob']['extensao'] = $extensao;
            $dataSaveBlob['Blob']['blob'] =  $fileContent;
            $dataSaveBlob['Blob']['data_cadastro'] =  date('Y-m-d H:i:s');
            $dataSaveBlob['Blob']['usuario_id'] =  $this->Session->read('Auth.Usuario.id');

            $this->Blob->create();
            if (!$this->Blob->save($dataSaveBlob)) {
                return false;
            }

            return $this->Blob->id;
        } else if ($attr['action'] == 1) {
            if ($file_exist == false) {
                return false;
            }
            $dataSaveBlob['Blob']['id'] = $attr['id'];
            $dataSaveBlob['Blob']['table'] = $attr['table'];
            $dataSaveBlob['Blob']['nome'] = $file_name;
            $dataSaveBlob['Blob']['tipo'] = $FILE['type'];
            $dataSaveBlob['Blob']['tamanho'] = $FILE['size'];
            $dataSaveBlob['Blob']['extensao'] = $extensao;
            $dataSaveBlob['Blob']['blob'] =  $fileContent;
            $dataSaveBlob['Blob']['data_atualizacao'] =  date('Y-m-d H:i:s');
            $dataSaveBlob['Blob']['usuario_id_atualizacao'] =  $this->Session->read('Auth.Usuario.id');

            if (!$this->Blob->save($dataSaveBlob)) {
                return false;
            }

            return $this->Blob->id;
        } else if ($attr['action'] == 2) {
            if (!isset($attr['id']) || $attr['id'] == '') {
                return false;
            }
            $dataSaveBlob['Blob']['id'] = $attr['id'];
            $dataSaveBlob['Blob']['status'] = 2;
            $dataSaveBlob['Blob']['data_atualizacao'] =  date('Y-m-d H:i:s');
            $dataSaveBlob['Blob']['usuario_id_atualizacao'] =  $this->Session->read('Auth.Usuario.id');

            if (!$this->Blob->save($dataSaveBlob)) {
                return false;
            }
        }

        return false;
    }

    #usado na parent::mysql_blob_allowed
    public function mysql_blob_allowed($dbo)
    {
        #AJUSTA MYSQL PARA ACEITAR BLOB MAIORES
        $result = $dbo->fetchAll("SHOW VARIABLES LIKE 'max_allowed_packet'");
        debug($result);
        $dbo->execute("SET GLOBAL max_allowed_packet = 67108864");
        return true;
    }


  


    /**
     * DELETAR 
     * @param type $id
     */
    public function admin_delete($beneficiario_id = null, $id = null)
    {

        $TABLE = $this->table;
        $this->Session->setFlash('Erro - Módulo em Desenvolvimento! ');
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
                    $data_log = array(
                        'id' => '',
                        'log' => 'Blob - Exclusão ',
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
                    'log' => 'Blob - Exclusão Múltipla',
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
