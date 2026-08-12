<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');

class UsuarioController extends AppController
{

    #SUCESSO DEVE SER MÍNUSCULO
    public $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public $msg_nao_existe = 'Usuário Inexistente';
    public $msg_salvo = 'O Usuário foi SALVO com sucesso!';
    public $msg_salvo_erro = 'Não foi possível SALVAR o Usuário, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public $msg_salvo_erro_login = 'Não foi possível efetuar o FILTRO ou SALVAR porque foi deslogado, tente novamente!';
    public $msg_excluido = 'O Usuário foi EXCLUÍDO com sucesso!';
    public $msg_excluido_erro = 'Não foi possível EXCLUIR o Usuário, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';



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

        $telTipoArr     = $this->Funcoes->select_merge(array('Residencial' => 'Residencial', 'Comercial' => 'Comercial', 'Fax' => 'Fax', 'Celular' => 'Celular'));
        $sexoArr        = $this->Funcoes->select_merge(array('Masculino' => 'Masculino', 'Feminino' => 'Feminino'));
        $this->set(compact('sexoArr', 'telTipoArr'));
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
     * LISTAGEM E FILSTRO 
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
        if (isset($this->params['pass'][0]) && is_numeric($this->params['pass'][0]) && $this->perfil_id == $this->perfil_root):
            $this->Session->write($this->name_search, array('cliente' => $this->params['pass'][0], 'perfil_id <> 12')); #cliente e perfil usuario para aluno vai ser == 6
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
            //                $condition[] = $TABLE.'.nome like "%' . $search['nome'] . '%"';
            endif;
            if (!empty($search['usuario'])):
                $buscaArr = explode(' ', $search['usuario']);
                if (count($buscaArr) > 0) {
                    foreach ($buscaArr as $vBusca) {
                        $condition[] = $TABLE . '.usuario like "%' . $vBusca . '%"';
                    }
                }
            endif;
            if (!empty($search['email_'])):
                $buscaArr = explode(' ', $search['email_']);
                if (count($buscaArr) > 0) {
                    foreach ($buscaArr as $vBusca) {
                        $condition[] = $TABLE . '.email like "%' . $vBusca . '%"';
                    }
                }
            endif;

            if (!empty($search['perfil'])):
                $condition[] = $TABLE . '.perfil_id = ' . $search['perfil'];
            endif;
            if (!empty($search['cliente'])):
                $condition[] = $TABLE . '.cliente_id = ' . $search['cliente'];
            endif;
            if (!empty($search['status'])):
                $condition[] = $TABLE . '.status = ' . $search['status'];
            endif;
        endif;


        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if ($this->Session->read('Auth.Usuario.id') != $this->uRoot) {
            $condition[] = $TABLE . '.id <> ' . $this->uRoot;
        }





        #END- USUÁRIO AUTORIZADO DEFAULT
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 10,
            'order' => array('id' => 'DESC')
        );


        $this->loadModel('Cliente');
        $this->loadModel('Perfil');

        $conditionsCliente = $conditionsPerfil = array('status' => 1);


        // if($this->perfil_id != $this->perfil_root ){
        //     $conditionsCliente = array('OR'=>array('Cliente.id = '.$this->cliente_id,'Cliente.cliente_id = '.$this->cliente_id,'status'=>1));
        //     $conditionsPerfil  = array( 'Perfil.id NOT IN ('.$this->perfil_fac_professor.','.$this->perfil_fac_resposavel.','.$this->perfil_root.','.$this->perfil_aluno.','.$this->perfil_aluno_consultor.')' ,'status'=>1);
        // }

        $clienteArr = $this->Cliente->find('list', array('conditions' => $conditionsCliente, 'fields' => 'id,nome'));
        $clienteArr  = $this->Funcoes->select_merge($clienteArr, 'Cliente...');
        $this->set('clienteArr', $clienteArr);

        $perfilArr = $this->Perfil->find('list', array('conditions' => $conditionsPerfil, 'fields' => 'id,nome'));
        $perfilArr = $this->Funcoes->select_merge($perfilArr, 'Perfil...');
        $this->set('perfilArr', $perfilArr);




        #BUSCA STATUS
        $statusArr = $this->Funcoes->parametros('Status');
        $this->set('statusArr', $statusArr);


        $this->$TABLE->recursive = 1;
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

        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if ($id == 1 && $this->Session->read('Auth.Usuario.id') != $this->uRoot) {
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('action' => 'index'));
        }
        #END- USUÁRIO AUTORIZADO DEFAULT

        if (!$this->$TABLE->exists($id)) {
            //            throw new NotFoundException(__($this->msg_nao_existe));
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('action' => 'index'));
        }
        $options = array('conditions' => array($TABLE . '.' . $this->$TABLE->primaryKey => $id));

        $row = $this->$TABLE->find('first', $options);
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

        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if ($id == 1 && $this->Session->read('Auth.Usuario.id') != $this->uRoot) {
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('action' => 'index'));
        }
        #END- USUÁRIO AUTORIZADO DEFAULT


        #BEGIN - LISTA BI

        #DEPOIS PODEMOS MELHORAR AS CONDITIONS SOMENTE PARA PERMISSÃO QUE A PESSOA TIVER DO BI DELA
        $cond = ['Bi.status < 2'];

        $this->loadModel('Bi');
        $UBArr = $this->Bi->find('all', array('conditions' => $cond, 'fields' => 'id,grupo_empresarial_id,cliente_id,titulo,subtitulo,status', 'recursive' => 2, 'order' => ['Bi.titulo' => 'ASC']));

        $selectBi = [];
        if (count($UBArr) > 0) {

            foreach ($UBArr as $uc) {
                $ge_ = '';
                if (isset($uc['GrupoEmpresarial']['id'])) {
                    $ge_ = $uc['GrupoEmpresarial']['id'];
                }
                // $cliente_ = '';
                // if(isset($uc['Cliente']['id'])){
                //     $cliente_ = $uc['Cliente']['id'];
                // }


                $selectBi[$ge_][] = [
                    'ge_id' => @$uc['GrupoEmpresarial']['id'],
                    'ge_nome' => @$uc['GrupoEmpresarial']['nome'],
                    'cliente_id' => @$uc['Cliente']['id'],
                    'cliente_nome' => @$uc['Cliente']['nome'],
                    'bi_id' => $uc['Bi']['id'],
                    'titulo' => $uc['Bi']['titulo'],
                    'subtitulo' => $uc['Bi']['subtitulo'],
                    'status' => $uc['Bi']['status']
                ];
            }
        }


        $this->set('selectBi', $selectBi);

        #END - LISTA BI


        if ($this->request->is(array('post', 'put'))) {


            #BEGIN - BUSCA SE USUARIO OU EMAIL JÁ EXISTE NO SISTEMA
            $usuarioC = array();
            $emailC = array();

            if (!empty($this->data[$TABLE]['id'])) {
                if (!$this->$TABLE->exists($this->data[$TABLE]['id'])) {
                    $this->Session->setFlash(__($this->msg_nao_existe));
                    $this->redirect(array('action' => 'index'));
                    exit();
                }
                $conditions = array('conditions' => array('id' => $this->data[$TABLE]['id']), 'fields' => array('usuario', 'email'), 'recursive' => -1);
                $userUser = $this->$TABLE->find('first', $conditions);
                $usuarioC = array('usuario != ' => $userUser[$TABLE]['usuario']);
                $emailC = array('email != ' => $userUser[$TABLE]['email']);
            }

            #VALIDA ESPAÇOS NO USUÁRIO E SENHA
            if (isset($this->data[$TABLE]['senha']) && preg_match('/ /', $this->data[$TABLE]['senha'])) {
                $this->Session->setFlash('A SENHA não pode conter espaços, favor corrigir!!');
                $this->Session->write('error_form', $this->data);
                $this->redirect($this->referer());
            }

            if (preg_match('/ /', $this->data[$TABLE]['usuario'])) {
                $this->Session->setFlash('Nome de USUÁRIO não pode conter espaços, favor corrigir!!');
                $this->Session->write('error_form', $this->data);
                $this->redirect($this->referer());
            }


            #BUSCA SE JÁ EXISTIR USUARIO
            $conditions = array_merge(array('usuario' => $this->data[$TABLE]['usuario']), $usuarioC);
            $valUsuario = $this->$TABLE->find('all', array('conditions' => $conditions, 'fields' => array('id'), 'recursive' => -1));

            #BUSCA SE JÁ EXISTIR EMAIL
            $conditions = array_merge(array('email' => $this->data[$TABLE]['email']), $emailC);
            $valEmail = $this->$TABLE->find('all', array('conditions' => $conditions, 'fields' => array('id'), 'recursive' => -1));

            #VALIDA EXISTÊNCIA DE EMAIL OU USUÁRIO IGUAL
            if (count($valUsuario) > 0 || count($valEmail) > 0) {
                if (count($valEmail) > 0) {
                    $this->Session->setFlash('Este EMAIL já está cadastrado no sistema !!');
                }
                if (count($valUsuario) > 0) {
                    $this->Session->setFlash('Este USUÁRIO já existe no sistema !!');
                }

                $this->Session->write('error_form', $this->data);
                $this->redirect($this->referer());
            }
            #END - BUSCA SE USUARIO OU EMAIL JÁ EXISTE NO SISTEMA


            #excluindo imagem antiga
            $image_old = array();
            if (isset($this->data[$TABLE]['id']) && $this->data[$TABLE]['id'] != '') {
                $image_old = $this->$TABLE->find('first', array('fields' => 'imagem', 'recursive' => -1, 'conditions' => array('id' => $this->data[$TABLE]['id'])));
            }


            #BEGIN - TRANSACTION
            $dataSource = $this->$TABLE->getDataSource();
            $type_error = '';

            try {
                $dataSource->begin();
                $dateTimeNow = date('Y-m-d H:i:s');
                $user_create = $this->Session->read('Auth.Usuario.id');
                if (empty($this->data[$TABLE]['id'])) {
                    if ($this->data[$TABLE]['senha'] != $this->data[$TABLE]['retry_senha']) {
                        $this->Session->setFlash(__('Confirmação diferente da senha. Por favor, faça a correção e tente novamente.'));
                        $this->Session->write('error_form', $this->data);
                        $this->redirect($this->referer());
                    } else {
                        $this->$TABLE->create();
                        $this->request->data[$TABLE]['senha'] = $this->Auth->password($this->request->data[$TABLE]['senha']);
                        $this->request->data[$TABLE]['data_cadastro'] = $dateTimeNow;
                        $this->request->data[$TABLE]['usuario_criador_id'] = $user_create;
                        $this->request->data[$TABLE]['grupo_empresarial_id'] = $this->grupo_empresarial_id;
                    }
                } else {

                    if ($this->data[$TABLE]['senha'] != $this->data[$TABLE]['retry_senha']) {
                        $this->Session->setFlash(__('Confirmação diferente da senha. Por favor, faça a correção e tente novamente.'));
                        $this->Session->write('error_form', $this->data);
                        $this->redirect($this->referer());
                    } else {
                        if (isset($this->data[$TABLE]['senha']) && $this->data[$TABLE]['senha'] != '') {
                            $this->request->data[$TABLE]['senha'] = $this->Auth->password($this->request->data[$TABLE]['senha']);
                            $this->request->data[$TABLE]['data_atualizacao'] = $dateTimeNow;
                        } else {
                            unset($this->request->data[$TABLE]['senha']);
                        }
                    }
                }


                #BEGIN - UPLOAD IMAGEM
                #validação de imagem
                //                if($this->data[$TABLE]['arquivo_imagem']['name'] == '' && $this->data[$TABLE]['imagem'] == ''){
                //                    throw new Exception();
                //                }        
                //                krumo($this->data);




                if (isset($this->data[$TABLE]['arquivo_imagem']) &&  is_array($this->data[$TABLE]['arquivo_imagem'])) {

                    $upload_imagem = $this->Funcoes->uploadImage($this->request->data[$TABLE]['arquivo_imagem'], $this->params['controller'], $this->params['action'], true); #true força o tamanho da imagem
                    unset($this->request->data[$TABLE]['arquivo_imagem']);
                    if ($upload_imagem != false) {
                        $this->request->data[$TABLE]['imagem'] = $upload_imagem;
                    }
                }
                #END - UPLOAD IMAGEM

                if ($this->perfil_id != $this->perfil_root && $this->data[$TABLE]['perfil_id'] == 1) {
                    throw new Exception();
                }


                if (!$this->$TABLE->save($this->data[$TABLE])) {
                    $type_error = 'Erro ao salvar o usuário!';
                    throw new Exception();
                }
                $id = $this->$TABLE->id;







                #CRIAÇÃO USUARIO CLIENTE
                $this->loadModel('UsuarioCliente');

                if (isset($this->data[$TABLE]['cliente_id']) && $this->data[$TABLE] != '' && count($this->data[$TABLE]['cliente_id']) > 0) {
                    $sql = 'delete  from usuario_cliente where usuario_id = ' . $id;
                    $this->UsuarioCliente->query($sql);
                    #$this->UsuarioCliente->deleteAll(array('usuario_id' => $id), false);
                }

                if (isset($this->data[$TABLE]['cliente_id']) && is_array($this->data[$TABLE]['cliente_id']) && count($this->data[$TABLE]['cliente_id']) > 0) {
                    foreach ($this->data[$TABLE]['cliente_id'] as $cliente_id) {
                        $this->UsuarioCliente->create();
                        $dataUC = array();
                        $dataUC['cliente_id'] = $cliente_id;
                        $dataUC['usuario_id'] = $id;
                        if (!$this->UsuarioCliente->save($dataUC)) {
                            $type_error = 'Erro ao salvar o cliente usuário!';
                            throw new Exception();
                        }
                    }
                }


                #CRIAÇÃO USUARIO BI
                $this->loadModel('UsuarioBi');

                if (isset($this->data[$TABLE]['bi_id']) && $this->data[$TABLE] != '' && count($this->data[$TABLE]['bi_id']) > 0) {
                    $sql = 'delete  from usuario_bi where usuario_id = ' . $id;
                    $this->UsuarioBi->query($sql);
                    #$this->UsuarioBi->deleteAll(array('usuario_id' => $id), false);
                }

                if (isset($this->data[$TABLE]['bi_id']) && is_array($this->data[$TABLE]['bi_id']) && count($this->data[$TABLE]['bi_id']) > 0) {
                    foreach ($this->data[$TABLE]['bi_id'] as $bi_id) {
                        $this->UsuarioBi->create();
                        $dataUC = array();
                        $dataUC['bi_id'] = $bi_id;
                        $dataUC['usuario_id'] = $id;
                        if (!$this->UsuarioBi->save($dataUC)) {
                            $type_error = 'Erro ao salvar o cliente usuário!';
                            throw new Exception();
                        }
                    }
                }




                #excluindo imagem antiga
                if (isset($image_old[$TABLE]['imagem']) && $image_old[$TABLE]['imagem'] != '' && $upload_imagem != false) {
                    $image_old_delete  = $this->Funcoes->deleteImage($image_old[$TABLE]['imagem'], $this->params['controller']);
                }


                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Gravação - Usuário',
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
                #ENDEREÇO
                if (count($this->Endereco->validationErrors) > 0) {
                    $errorDB = $this->Endereco->validationErrors;
                    foreach ($errorDB as $kerror => $error) {
                        if ($error[0] == 'notEmpty') {
                            $flash .= 'Erro: O campo ' . $kerror . ' não pode ser vazio!<br />';
                        }
                    }
                }

                if ($this->perfil_id != $this->perfil_root && $this->data[$TABLE]['perfil_id'] == 1) {
                    $flash .= 'Erro: Perfil selecionado não existe!';
                }



                if ($flash == '') {
                    $flash = 'Erro: ' . $this->msg_salvo_erro;
                }

                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Erro Gravação - Usuário',
                    'mensagem'            => $flash,
                    'description'         => json_encode($this->data),
                    'server_description'  => json_encode($this->params),
                    'data_cadastro'       => $dateTimeNow,
                    'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                $this->Session->setFlash($flash);
                $dataSource->rollback();
                #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
                $this->Session->write('error_form', $this->data);
                $this->redirect($this->referer());
                #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO

            }
            #END - TRANSACTION

        }


        #BUSCA INFORMAÇÕES - USUARIO X CLIENTE
        if ($id !== null) {
            if (!$this->$TABLE->exists($id)) {
                $this->Session->setFlash(__($this->msg_nao_existe));
                $this->redirect(array('action' => 'index'));
                exit();
            }

            $options = array('conditions' => array('Usuario.' . $this->$TABLE->primaryKey => $id));
            $this->request->data = $this->$TABLE->find('first', $options);
            unset($this->request->data[$TABLE]['senha']);

            $this->request->data['Usuario']['cliente_id'] = array();
            if (isset($this->request->data['UsuarioCliente']) && count($this->request->data['UsuarioCliente']) > 0) {
                foreach ($this->request->data['UsuarioCliente'] as $usuario_cliente) {
                    $this->request->data['Usuario']['cliente_id'][] = $usuario_cliente['cliente_id'];
                }
            }
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


        $this->loadModel('Cliente');
        $this->loadModel('Perfil');
        $this->loadModel('Estado');

        $conditionsCliente = $conditionsPerfil = array('status' => 1);

        if ($this->perfil_id != $this->perfil_root) {
            $conditionsCliente = array('OR' => array('Cliente.id = ' . $this->cliente_id, 'Cliente.id = ' . $this->cliente_id, 'status' => 1));
            $conditionsPerfil = array('id > 1', 'status' => 1);
        }

        $clienteArr = $this->Cliente->find('list', array('conditions' => $conditionsCliente, 'fields' => 'id,nome'));
        $clienteArr = $this->Funcoes->select_merge($clienteArr);
        $this->set('clienteArr', $clienteArr);




        $perfilArr = $this->Perfil->find('list', array('conditions' => $conditionsPerfil, 'fields' => 'id,nome'));
        $perfilArr = $this->Funcoes->select_merge($perfilArr);
        $this->set('perfilArr', $perfilArr);

        $estadoArr = $this->Estado->find('list', array('fields' => 'id,nome'));
        $estadoArr = $this->Funcoes->select_merge($estadoArr);
        $this->set('estadoArr', $estadoArr);

        //       debug($this->Usuario->getDataSource()->getLog(false, true));
        //       exit();
    }




    /**
     * BUSCA CIDADES
     */
    public function busca_cidades()
    {
        $this->autoRender = false;
        $this->loadModel('Cidade');
        $busca = trim($this->data['busca']);
        $result = $this->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $busca), 'fields' => ('id,nome')));
        //        debug($this->Usuario->getDataSource()->getLog(false, true));
        echo json_encode($result);
        exit();
    }





    /**
     * DELETAR 
     * @param type $id
     */
    public function admin_delete($id = null)
    {
        $TABLE = $this->table;

        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if ($id == 1 && $this->Session->read('Auth.Usuario.id') != $this->uRoot) {
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('action' => 'index'));
        }
        #END- USUÁRIO AUTORIZADO DEFAULT

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
                    #BEGIN- USUÁRIO AUTORIZADO DEFAULT
                    if ($id == 1 && $this->Session->read('Auth.Usuario.id') != $this->uRoot) {
                        $this->Session->setFlash($this->msg_nao_existe);
                        $setFlash .= 'ID: ' . $id . '  - Não Existe  <br /> ';
                    } else {
                        #END- USUÁRIO AUTORIZADO DEFAULT

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
                    }
                endforeach;
                $this->Session->setFlash($setFlash);
            } else {
                $this->Session->setFlash(__('Nenhum registro foi selecionado'));
            }
        }
        $this->redirect(array('action' => 'index'));
    }

    // public function enviarRecaptchaRequest($token)
    // {
    //     // Substitua esses valores pelos seus dados dinâmicos
    //     #$token = 'TOKEN'; // O token retornado da chamada grecaptcha.enterprise.execute()
    //     $userAction = ''; // A ação iniciada pelo usuário (opcional)
    //     $siteKey = '6LdU-FUqAAAAAH7IKnMcvnBnCyGq6b1ZOB7MLPQt'; // Seu site key

    //     // Criando o conteúdo do arquivo JSON
    //     $data = array(
    //         'event' => array(
    //             'token' => $token,
    //             'expectedAction' => $userAction,
    //             'siteKey' => $siteKey
    //         )
    //     );
    //     //https://recaptchaenterprise.googleapis.com/v1/projects/dev-samed/assessments?key=API_KEY
    //     // Convertendo o array para JSON
    //     $jsonData = json_encode($data);
    //     krumo($jsonData);
    //     exit;
    //     // Salvando o conteúdo no arquivo request.json
    //     $filePath = WWW_ROOT . 'files' . DS . 'request.json';
    //     file_put_contents($filePath, $jsonData);

    //     $this->set('message', 'Arquivo request.json criado com sucesso!');
    // }

    public function enviarRecaptchaRequest($token)
    {
        // Substitua esses valores pelos seus dados dinâmicos
        // $token = 'TOKEN'; // O token retornado da chamada grecaptcha.enterprise.execute()
        $userAction = ''; // A ação iniciada pelo usuário (opcional)
        $siteKey = '6LeuUFgqAAAAAPW5-fbBjwERz2W2fcYDXj1gLIDX'; // Seu site key
        $apiKey = 'AIzaSyCowEx_w4nTLYVE4in5ApAEyGmJ-sUuaLs'; // Sua chave da API do Google Cloud

        // Criando o conteúdo do arquivo JSON
        $data = array(
            'event' => array(
                'token' => $token,
                'expectedAction' => $userAction,
                'siteKey' => $siteKey
            )
        );
        krumo($data);
        // Convertendo o array para JSON
        $jsonData = json_encode($data);

        // Configuração do URL da API
        $url = "https://recaptchaenterprise.googleapis.com/v1/projects/samed-1728101950644/assessments?key=" . $apiKey;

        #$url = "https://recaptchaenterprise.googleapis.com/v1/projects/dev-samed/assessments?key={$apiKey}";

        // Enviando a solicitação POST usando HttpSocket
        $http = new HttpSocket();
        $response = $http->post($url, $jsonData, array(
            'header' => array(
                'Content-Type' => 'application/json'
            )
        ));

        krumo($response);
        exit;

        // Verificando a resposta
        if ($response->isOk()) {
            $this->set('message', 'Solicitação enviada com sucesso!');
            $this->set('response', $response->body());
        } else {
            $this->set('message', 'Erro ao enviar a solicitação.');
            $this->set('response', $response->body());
        }
    }


    /**
     * LOGAR
     * @return type
     */
    public function login()
    {

        #public: 6Lf56XIaAAAAAJWTrRwzNERfz_dnM61x0P4h4jOc
        #secret: 6Lf56XIaAAAAAAxi1x7uWTGib-rMRnut955iBZuA

        $this->layout = 'login';
        $this->set('title_for_layout', 'Login');





        if ($this->request->is('post')) {

            $this->loadModel('Usuario');
            // krumo($this->Usuario->find('first',array('conditions'=>array('id'=>4),'recursive'=>-1)));
            // krumo($this->data);


            if ($this->Auth->login()) {

                $localhost = explode( ':', $_SERVER['HTTP_HOST'] );
                if(in_array($localhost[0],array('localhost'))){
                    $this->request->data['g-recaptcha-response'] = 'asdfasfd';//LOCALHOST
                }
                if ($this->data['g-recaptcha-response'] == '') {

                    #BEGIN - CRIANDO LOG
                    $this->loadModel('Log');
                    $this->Log->create();
                    $data_log = array(
                        'id' => '',
                        'log' => 'Erro - Login',
                        'description'         =>  'Erro Acesso Login (recaptcha) ',
                        'server_description'  =>  '',
                        'data_cadastro'       =>  date('Y/m/d H:i:s'),
                        'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                    );
                    $this->Log->save($data_log);
                    #END - CRIANDO LOG


                    $this->Session->destroy();
                    $this->Session->delete('Auth.redirect');
                    $this->Session->renew();

                    $this->Session->setFlash('Por favor, verifique o reCaptcha!');

                    //return $this->redirect($this->Auth->redirectUrl());
                    $this->redirect('/');
                    exit;
                } else {

                    #$this->enviarRecaptchaRequest($this->data['g-recaptcha-response']);


                    #validação recaptcha
                    // $returnRecaptcha = $this->enviarRecaptchaRequest($this->data['g-recaptcha-response']);
                    // krumo($returnRecaptcha);
                    // krumo('passou');
                    // exit;
                }




                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log' => 'Acesso - Login',
                    'description'         =>  'Acesso - Login',
                    'server_description'  =>  '',
                    'data_cadastro'       =>  date('Y/m/d H:i:s'),
                    'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log' => 'Erro - Login',
                    'description'         =>  'Erro Acesso Login',
                    'server_description'  =>  '',
                    'data_cadastro'       =>  date('Y/m/d H:i:s'),
                    'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                #END - CRIANDO LOG


                $this->Session->setFlash(
                    __($this->Auth->loginError),
                    'default',
                    array(),
                    'auth'
                );
            }
        } else {

            #REDIRECIONA SE ESTIVER LOGADO
            if ($this->Auth->login()) {
                #VALIDA CAPTCHA


                

                #BEGIN - CRIANDO LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log' => 'Acesso - Login',
                    'description'         =>  'Acesso - Login',
                    'server_description'  =>  '',
                    'data_cadastro'       =>  date('Y/m/d H:i:s'),
                    'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                #END - CRIANDO LOG


                return $this->redirect($this->Auth->redirectUrl());
            }
        }
    }


    /**
     * DESLOCAGAR
     */
    public function logout()
    {
        $this->Session->destroy();
        $this->Session->setFlash(__('Sua sessão foi encerrada.', true));

        #BEGIN - CRIANDO LOG
        $this->loadModel('Log');
        $this->Log->create();
        $data_log = array(
            'id' => '',
            'log' => 'Acesso - Logout',
            'description'         =>  'Acesso - Logout',
            'server_description'  =>  '',
            'data_cadastro'       =>  date('Y/m/d H:i:s'),
            'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
        );
        $this->Log->save($data_log);
        #END - CRIANDO LOG


        $this->redirect($this->Auth->loginAction);
    }


    /**
     * RECUPERAR SENHA
     */
    public function forgot()
    {
        $this->layout = 'login';
        $this->set('title_for_layout', 'Recuperar Senha');

        if ($this->request->is('post')) {
            $usuario_verify = $this->Usuario->find('first', array('conditions' => array('Usuario.email' => $this->data['Usuario']['email']), 'recursive' => -1));
            if (isset($usuario_verify['Usuario']['id'])) {
                $newData = array();
                $newData['id'] = (int) $usuario_verify['Usuario']['id'];
                $qtd = $usuario_verify['Usuario']['qtd_forgot'];
                $newData['qtd_forgot'] =  $qtd + 1;
                $newData['token_forgot'] = $this->Auth->password(rand());
                $link = Router::url(array('controller' => 'usuario', 'action' => 'renew', 'token' => $newData['token_forgot']), true);
                $nome = $usuario_verify['Usuario']['nome'];
                $logo = Router::url('/img/logo-lv.png', true);
                $toEmail = $this->data['Usuario']['email'];

                if ($this->Usuario->save($newData, false)) {
                    $subject = 'SAMed - Requisição Nova Senha';
                    $msg = "Olá <b> {$nome}</b><br /><br />Segue o link com seu Token de acesso para restaurar sua senha: <a href='{$link}'> Clique Aqui</a><br><br><br>Enviado:" . date('d/m/Y') . " às " . date('H:i');
                    parent::envio_email($toEmail, $subject, $msg);

                    #BEGIN - CRIANDO LOG
                    $this->loadModel('Log');
                    $this->Log->create();
                    $data_log = array(
                        'id' => '',
                        'log' => 'Acesso - Restauração de Senha',
                        'description'         =>  'Acesso - Restauração de Senha',
                        'server_description'  =>  '',
                        'data_cadastro'       =>  date('Y/m/d H:i:s'),
                        'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                    );
                    $this->Log->save($data_log);
                    #END - CRIANDO LOG


                    $this->Session->setFlash(__('Sua sessão foi encerrada.', true));
                    $this->Session->destroy();

                    $this->Session->setFlash('Email de recuperação de senha enviado com sucesso para: ' . $this->data['Usuario']['email']);
                    $this->redirect(array('action' => 'forgot'));
                } else {
                    $this->Session->setFlash('Não foi possível efetuar a requisição de nova senha, tente novamente mais tarde!');
                }
            } else {
                $this->Session->setFlash('Email não cadastrado no sistema, verifique se o email está correto!');
            }
        }

        if (isset($this->data['Usuario']['email'])) {
            $this->set('email', $this->data['Usuario']['email']);
        } else {
            $this->set('email', '');
        }
    }


    /**
     * VALIDAR TOKEN
     * @param type $token
     * @return type
     */
    private function token_valida($token)
    {
        if (!isset($token) || trim($token) == '') {
            $this->token_invalido();
        }

        $token = stripcslashes($token);
        if (
            preg_match('/"/', $token) ||
            preg_match("/'/", $token) ||
            preg_match("/-/", $token) ||
            preg_match("/,/", $token)
        ) {
            $this->token_invalido();
        }

        $usuario_verify = $this->Usuario->find('first', array('conditions' => array('Usuario.token_forgot' => $token), 'recursive' => -1));
        if (!isset($usuario_verify['Usuario']['id'])) {
            $this->token_invalido();
        }

        return $usuario_verify;
    }


    /**
     * REDIRECIONAMENTO DE TOKEN INVÁLIDO
     */
    private function token_invalido()
    {
        $this->Session->setFlash('Token Inválido, favor recupere a senha novamente e em poucos segundos receberá um novo email!');
        $this->redirect(array('action' => 'forgot'));
    }


    /**
     * CRIAÇÃO DE UMA NOVA SENHA
     */
    function renew()
    {
        $this->layout = 'login';
        $this->set('title_for_layout', 'Nova Senha');

        if ($this->request->is('post')) {
            $data = $this->data['Usuario'];

            $usuario_verify =  $this->token_valida($data['token']);
            if (isset($usuario_verify['Usuario']['id'])) {
                $newData = array();
                $newData['id'] = (int) $usuario_verify['Usuario']['id'];
                $newData['token_forgot'] = '';
                if ($data['senha'] != $data['retry_senha']) {
                    $this->Session->setFlash('Senha Diferente, tente novamente!');
                    $this->redirect(array('action' => 'renew', 'token' => $token));
                }
                $newData['senha'] = $this->Auth->password($data['senha']);

                if ($this->Usuario->save($newData, false)) {
                    $this->Session->setFlash('Sua senha foi salva com sucesso!');
                    $this->redirect(array('action' => 'login'));
                } else {
                    $this->token_invalido();
                }
            } else {
                $this->token_invalido();
            }
        } else {
            if (!isset($this->params['named']['token'])) {
                $this->token_invalido();
            }
            $this->token_valida($this->params['named']['token']);
        }
    }


    /**
     * CRIAÇÃO DE UM USUÁRIO TESTE
     */
    public function addteste()
    {
        //        $this->render = false;
        //        krumo($this->Auth->password('46852654'));
        //        exit();

        //        $this->Usuario->create();
        //        $this->Usuario->save(array(
        //            'perfil_id' => 1,
        //            'cliente_id' => 1,
        //            'nome' => 'Sândler',
        //            'usuario' => 'sandleram@gmail.com',
        //            'email' => 'sandleram@gmail.com',
        //            'senha' => $this->Auth->password('123'),
        ////            'username' => 'teste' . rand(1, 100),
        ////            'password' => $this->Auth->password(rand()),
        //            'data_cadastro' => date('Y-m-d H:i:s'),
        //            'status' => 1
        //        ));
        //        echo 'cadastro com sucesso!';
        //        exit();
    }




    /**
     * SALVAR NOVO 
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function cadastrese($id_cliente = NULL)
    {
        $TABLE = $this->table;
        $this->loadModel('Cliente');
        $this->layout = 'formulario';
        $this->set('title_for_layout', 'Cadastro de Aluno');



        if ($id_cliente == NULL) {
            $this->redirect($this->Auth->loginRedirect);
        } else {
            if (!$this->Cliente->exists($id_cliente)) {
                $this->redirect($this->Auth->loginRedirect);
            }
        }


        if ($this->request->is(array('post', 'put'))) {

            #BEGIN - BUSCA SE CPF OU EMAIL JÁ EXISTE NO SISTEMA
            #BUSCA SE JÁ EXISTIR EMAIL
            $conditions = array('email' => $this->data[$TABLE]['email']);
            $valEmail = $this->$TABLE->find('all', array('conditions' => $conditions, 'fields' => array('id'), 'recursive' => -1));

            #BUSCA SE JÁ EXISTIR CPF
            //                $conditions = array("REPLACE(REPLACE(cpf, '-', ''), '.', '') "=>trim($this->data[$TABLE]['cpf']));
            $conditions = array("TRIM(cpf)" => trim($this->data[$TABLE]['cpf']));
            $valCpf = $this->$TABLE->find('all', array('conditions' => $conditions, 'fields' => array('id'), 'recursive' => -1));


            #VALIDA EXISTÊNCIA DE EMAIL OU USUÁRIO IGUAL
            if (count($valEmail) > 0 || count($valCpf) > 0) {
                $this->Session->write('error_form', $this->data);
                if (count($valEmail) > 0) {
                    $msgErro = 'Este EMAIL já está cadastrado no sistema !!';
                } else {
                    $msgErro = 'Este CPF já está cadastrado no sistema !!';
                }
                //                    $this->Session->setFlash('Este CPF já está cadastrado no sistema !!','default', array(), 'front');
                $this->Session->setFlash($msgErro);
                $this->Session->write('error_form', $this->data);
                $this->redirect($this->referer());
            }

            #END - BUSCA SE USUARIO OU EMAIL JÁ EXISTE NO SISTEMA



            #BEGIN - TRANSACTION
            $dataSource = $this->$TABLE->getDataSource();
            $type_error = '';






            try {
                $dataSource->begin();
                $dateTimeNow = date('Y-m-d H:i:s');
                $user_create = $this->Session->read('Auth.Usuario.id');


                #CRIAR USUÁRIO
                $this->$TABLE->create();
                $this->request->data[$TABLE]['perfil_id'] = 12;
                $this->request->data[$TABLE]['data_cadastro'] = $dateTimeNow;
                $this->request->data[$TABLE]['usuario_create_id'] = $user_create;

                if (!$this->$TABLE->save($this->data[$TABLE])) {
                    $type_error = 'Erro ao salvar o usuário!';
                    throw new Exception();
                }
                $usuario_id = $this->$TABLE->id;


                #CRIAR ALUNO
                $this->loadModel('Aluno');
                $this->Aluno->create();
                $aluno = array();
                $aluno['usuario_id'] = $usuario_id;
                $aluno['origem'] = $this->data[$TABLE]['origem'];
                $aluno['data_cadastro'] = $dateTimeNow;

                if (!$this->Aluno->save($aluno)) {
                    $type_error = 'Erro ao salvar o usuário!';
                    throw new Exception();
                }
                $aluno_id = $this->Aluno->id;


                #CRIAR ALUNO_CURSO
                $this->loadModel('AlunoClienteCurso');
                $this->AlunoClienteCurso->create();
                $aluno_curso = array();
                $aluno_curso['aluno_id'] = $aluno_id;
                $aluno_curso['cliente_curso_id'] = $this->data[$TABLE]['curso_id'];
                $aluno_curso['data_cadastro'] = $dateTimeNow;

                if (!$this->AlunoClienteCurso->save($aluno_curso)) {
                    $type_error = 'Erro ao salvar o Curso do Aluno!';
                    throw new Exception();
                }
                $aluno_curso_id = $this->AlunoClienteCurso->id;


                //                $this->loadModel('AlunoCurso');
                //                $this->AlunoCurso->create();
                //                $aluno_curso = array();
                //                $aluno_curso['aluno_id'] = $aluno_id;
                //                $aluno_curso['curso_id'] = $this->data[$TABLE]['curso_id'];
                //                $aluno_curso['cliente_id'] = $this->data[$TABLE]['cliente_id'];
                //                $aluno_curso['data_cadastro'] = $dateTimeNow;
                //                
                //                if (!$this->AlunoCurso->save($aluno_curso)) {
                //                    $type_error = 'Erro ao salvar o Curso do Aluno!';
                //                    throw new Exception();
                //                }
                //                $aluno_curso_id = $this->AlunoCurso->id;


                #BEGIN - ENVIAR EMAIL DESENVOLVER (FACULDADE PROGRESSO)
                if ($this->data['Usuario']['cliente_id'] == '53') { //Sr Roberto roberto@faculdadeprogresso.edu.br
                    $toEmail = 'roberto@faculdadeprogresso.edu.br';
                    $subject = 'Cadastro de Aluno';
                    $msg = "Olá ";

                    $msg .= "Sr. Roberto<br><br>";
                    $msg .= "Foi cadastrado um novo aluno agora " . date('d/m/Y') . " às " . date('H:i') . " <br><br>";
                    $msg .= "<b>Aluno Nome:</b> " . $this->data['Usuario']['nome'] . " <br>";
                    $msg .= "<b>E-mail:</b> " . $this->data['Usuario']['email'] . " <br>";
                    $msg .= "<b>Telefone " . $this->data['Usuario']['tel1_tipo'] . " : </b>" . $this->data['Usuario']['tel1'] . "  <br>";

                    #EFETUAR TESTE
                    //                    parent::envio_email($toEmail,$subject,$msg);
                    #END - ENVIAR EMAIL DESENVOLVER (FACULDADE PROGRESSO)
                }




                //                krumo('teste');

                //                $aviso_msg = $this->Funcoes->envia_mensagem($this->data['Usuario']);
                //                krumo($aviso_msg);
                //                exit();

                $this->Session->setFlash('O Aluno foi Salvo com sucesso!');
                $dataSource->commit();
                return $this->redirect($this->referer());
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
                #ENDEREÇO
                if (count($this->Aluno->validationErrors) > 0) {
                    $errorDB = $this->Aluno->validationErrors;
                    foreach ($errorDB as $kerror => $error) {
                        if ($error[0] == 'notEmpty') {
                            $flash .= 'Erro: O campo ' . $kerror . ' não pode ser vazio!<br />';
                        }
                    }
                }
                //                if(count($this->AlunoCurso->validationErrors) > 0){
                //                    $errorDB = $this->AlunoCurso->validationErrors;
                //                    foreach($errorDB as $kerror => $error){
                //                        if($error[0] == 'notEmpty'){
                //                            $flash .= 'Erro: O campo '.$kerror.' não pode ser vazio!<br />';
                //                        }
                //                    }
                //                }

                if ($flash == '') {
                    $flash = 'Erro: ' . $this->msg_salvo_erro;
                }

                $dataSource->rollback();
                #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
                $this->Session->setFlash($flash);
                $this->Session->write('error_form', $this->data);
                $this->redirect($this->referer());
                #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO

            }
            #END - TRANSACTION  

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

        #BUSCA PUBLICIDADE - COMO CHEGOU ATÉ NÓS?
        $origemArr = $this->Funcoes->parametros('Origem');
        $this->set('origemArr', $origemArr);



        #BUSCA CURSO
        $this->loadModel('ClienteCurso');
        //        $cursosArrId = $this->ClienteCurso->find('list',array('conditions'=>array('cliente_id'=>$id_cliente), 'fields'=>array('curso_id')));

        #BUSCA EMPRESA DA MESMA FILIAL
        #BUSCA IDS DE UNIDADES E MATRIZES DA MESMA REDE
        $sql = 'SELECT id,cliente_id, nome FROM cliente WHERE id = ' . $this->params['pass'][0] . ' OR cliente_id = ' . $this->params['pass'][0];
        $result = $this->Cliente->query($sql);
        //        $list = array();
        //        krumo($sql);

        #COMENTADO (VER ERRO)
        #caso busque somente um, porque é uma unidade, então desta maneira vai buscar a matriz e outras unidades da mesma rede!
        ##COMENTADO ERRO 1
        //        if(count($result) == 1){
        //            $cliente_matriz_id = $result[0]['cliente']['cliente_id'];
        //            if($cliente_matriz_id != ''){
        //                $sql = 'SELECT id,cliente_id, nome FROM cliente WHERE id = '.$cliente_matriz_id .' OR cliente_id = '.$cliente_matriz_id ;
        //                krumo($sql);
        //                $result = $this->Cliente->query($sql);
        //            }
        //        }


        #monta lista de clientes e nomes
        $idsCliente = array();
        foreach ($result as $vResult) {
            $idsCliente[] = $vResult['cliente']['id'];
        }
        $clienteIds = implode(',', $idsCliente);

        //        krumo($clienteIds);

        $cursosArrId = $this->ClienteCurso->find('all', array('conditions' => array('ClienteCurso.cliente_id IN (' . $clienteIds . ')', 'ClienteCurso.status' => 1), 'fields' => array('Curso.nome', 'ClienteCurso.id', 'ClienteCurso.periodo', 'ClienteCurso.tipo_curso')));


        $periodo = $this->Funcoes->parametros('Curso_Periodo', 'list', NULL, false);
        $tipo_curso = $this->Funcoes->parametros('Tipo_Curso', 'list', NULL, false);


        if (count($cursosArrId) > 0) {
            $cursoArr = array('' => '');
            foreach ($cursosArrId as $vCurso) {
                if (isset($vCurso['ClienteCurso']['periodo']) && $vCurso['ClienteCurso']['periodo'] == 4) {
                    $cursoArr[$vCurso['ClienteCurso']['id']] = $vCurso['Curso']['nome'] . ' - ' . $tipo_curso[$vCurso['ClienteCurso']['tipo_curso']];
                } else {
                    $cursoArr[$vCurso['ClienteCurso']['id']] = $vCurso['Curso']['nome'] . ' (' . @$periodo[$vCurso['ClienteCurso']['periodo']] . ') - ' . $tipo_curso[$vCurso['ClienteCurso']['tipo_curso']];
                }
            }
        } else {
            $cursoArr = array('' => 'Nenhum Curso Disponível.');
        }

        asort($cursoArr);
        $this->set('cursoArr', $cursoArr);




        //        if(count($cursosArrId)> 0){
        //            $this->loadModel('Curso');
        //            $cursos = implode(',',$cursosArrId);
        //            $cursoArr = $this->Curso->find('list',array('conditions'=>array('id IN ('.$cursos.')') ,'fields'=>'id,nome','recursive'=>-1));
        //            $cursoArr  = $this->Funcoes->select_merge($cursoArr,'Cursos...');
        //        }else{
        //            $cursoArr = array(''=>'Nenhum Curso Disponível.');
        //        }

        //       debug($this->Usuario->getDataSource()->getLog(false, true));
        //       exit();
    }





    /**
     * BUSCA FACULDADE
     */
    public function busca_faculdades()
    {
        $this->autoRender = false;
        $this->loadModel('Cliente');
        $busca = trim($this->data['busca']);
        //        $busca = 59;
        //        $busca_faculdade = trim($this->data['busca_faculdade']);

        $sql = 'SELECT cliente_id, curso_id, periodo, tipo_curso  FROM cliente_curso WHERE id = ' . $busca;
        $result = $this->Cliente->query($sql);


        if (count($result) > 0) {
            $cliente_id = $result[0]['cliente_curso']['cliente_id'];
            $curso_id = $result[0]['cliente_curso']['curso_id'];
            $periodo = $result[0]['cliente_curso']['periodo'];
            $tipo_curso = $result[0]['cliente_curso']['tipo_curso'];
        } else {
            $list = array();
            echo json_encode($list);
            exit();
        }



        #BUSCA IDS DE UNIDADES E MATRIZES DA MESMA REDE
        $sql = 'SELECT id,cliente_id, nome FROM cliente WHERE id = ' . $cliente_id . ' OR cliente_id = ' . $cliente_id;
        $result = $this->Cliente->query($sql);
        $list = array();

        #COMENTADO (VER ERRO)
        #caso busque somente um, porque é uma unidade, então desta maneira vai buscar a matriz e outras unidades da mesma rede!
        //        if(count($result) == 1){
        //            $cliente_matriz_id = $result[0]['cliente']['cliente_id'];
        //            if($cliente_matriz_id != ''){
        //                $sql = 'SELECT id,cliente_id, nome FROM cliente WHERE id = '.$cliente_matriz_id .' OR cliente_id = '.$cliente_matriz_id ;
        //                $result = $this->Cliente->query($sql);
        //            }
        //        }

        #monta lista de clientes e nomes
        foreach ($result as $vResult) {
            $list[$vResult['cliente']['id']] = $vResult['cliente']['nome'];
            $idsCliente[] = $vResult['cliente']['id'];
        }
        $ids = implode(',', $idsCliente);



        #adiciona mais clientes da mesma rede caso tenha o curso exato
        $sql = 'SELECT cliente.id,cliente.nome 
            FROM cliente_curso 
            INNER JOIN cliente on cliente_curso.cliente_id = cliente.id 
            WHERE cliente_curso.cliente_id in (' . $ids . ') and 
                  cliente_curso.curso_id = ' . $curso_id . ' and 
                  cliente_curso.tipo_curso = ' . $tipo_curso . ' and 
                  cliente_curso.periodo = ' . $periodo . ' and 
                  cliente_curso.status = 1';
        $result = $this->Cliente->query($sql);

        unset($list);
        $list = array();

        foreach ($result as $vResult) {
            $list[$vResult['cliente']['id']] = $vResult['cliente']['nome'];
        }

        echo json_encode($list);
        //        exit();
        //
        ////        $result = $this->Cidade->find('list',array('conditions'=>array('Cliente.estado_id'=>$busca), 'fields'=>('id,nome')));
        ////        $sql = 'SELECT e.id, CONCAT(e.nome," - ",e.cnpj) AS nome_completo FROM cliente_curso ec INNER JOIN cliente e ON e.id = ec.cliente_id WHERE ec.curso_id = '.$busca;
        //        $sql = 'SELECT cliente.id, cliente.nome FROM cliente_curso INNER JOIN cliente ON cliente.id = cliente_curso.cliente_id WHERE cliente_curso.curso_id = '.$busca.' AND cliente.id = '.$busca_faculdade.' order by cliente.nome';
        //        
        //        $result = $this->Cliente->query($sql);
        //        
        //        $list = array();
        //        if(count($result) > 0){
        //            foreach($result as $row) {
        //                $id = $row['cliente']['id'];
        //                $name = $row['cliente']['nome'];
        //                $list[$id] = $name;
        //            }
        //        }
        ////        debug($this->Usuario->getDataSource()->getLog(false, true));
        //        echo json_encode($list);
        //        exit();
    }



    /**
     * DESENVOLVER
     * CHAMAR VIA AJAX PARA GRAVAR AS AÇÕES DO USUÁRIO NA SESSION
     * @param type $key
     * @param type $value
     * @return boolean
     */
    function admin_atualiza_session_menu()
    {
        $this->autoRender = false;

        #desktop-detected pace-done minified
        if (isset($this->data['key']) && isset($this->data['value'])) {
            $_SESSION[$this->data['key']] = $this->data['value'];
        }
        echo json_encode(true);
    }


    /**
     * session ajax para alteração de grupo_empresarial_id e cliente
     */
    function admin_atualiza_session_cliente()
    {

        $this->autoRender = false;
        if (isset($this->data['valor']) && isset($this->data['tipo'])) {

            #ESCREVE NA SESSION O CLIENTE
            if (!$this->Session->check("Auth.Usuario.old_cliente_id")) {
                $this->Session->write("Auth.Usuario.old_cliente_id", $this->Session->read("Auth.Usuario.cliente_id"));
            }
            $this->Session->write("Auth.Usuario.cliente_id", $this->data['valor']);

            #ESCREVE NA SESSION O GRUPO EMPRESARIAL
            if (!$this->Session->check("Auth.Usuario.old_cliente_id")) {
                $this->Session->write("Auth.Usuario.old_cliente_id", $this->Session->read("Auth.Usuario.cliente_id"));
            }
            $GEArr = $this->Session->read('selectClienteGENew');
            if (isset($GEArr[$this->data['valor']])) {
                $this->Session->write("Auth.Usuario.grupo_empresarial_id", $GEArr[$this->data['valor']]);
            }
        }

        #OLD MODEL
        // if(isset($this->data['valor']) && isset($this->data['tipo'])){
        //     if(!$this->Session->check("Auth.Usuario.old_{$this->data['tipo']}")){
        //         $this->Session->write("Auth.Usuario.old_{$this->data['tipo']}",$this->Session->read("Auth.Usuario.{$this->data['tipo']}"));
        //     }
        //     if($this->data['tipo'] == 'grupo_empresarial_id'){ #IRÁ SETAR O ID 
        //         $returnCI = $this->Session->read("Auth.Usuario.grupo_empresarial_id");
        //         if($returnCI != $this->data['valor']){
        //             $this->Session->write("Auth.Usuario.cliente_id",NULL);
        //         }
        //     }
        //     $this->Session->write("Auth.Usuario.{$this->data['tipo']}",$this->data['valor']);
        // }
        echo json_encode(true);
    }
}
