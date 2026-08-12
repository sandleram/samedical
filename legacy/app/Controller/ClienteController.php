<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('ClienteDesligamento', 'Model');

class ClienteController extends AppController
{

    #SUCESSO DEVE SER MÍNUSCULO
    public $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public $msg_nao_existe = 'Cliente Inexistente';
    public $msg_salvo = 'A Cliente foi SALVA com sucesso!';
    public $msg_salvo_erro = 'Não foi possível SALVAR a Cliente, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public $msg_salvo_erro_login = 'Não foi possível efetuar o FILTRO ou SALVAR porque foi deslogado, tente novamente!';
    public $msg_excluido = 'A Cliente foi EXCLUÍDA com sucesso!';
    public $msg_excluido_erro = 'Não foi possível EXCLUIR a Cliente, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';



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

            if ($search['status'] != ''):
                $condition[] = $TABLE . '.status = ' . $search['status'];
            endif;
        endif;



        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if ($this->Session->read('Auth.Usuario.id') != $this->uRoot) {
            $condition[] = $TABLE . $this->status_default;
        }
        $condition[] = $TABLE . '.grupo_empresarial_id = ' . $this->grupo_empresarial_id;
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
    public function admin_view($id = null)
    {
        $TABLE = $this->table;

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
    public function admin_add($id = null)
    {
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
                    $this->request->data[$TABLE]['grupo_empresarial_id'] = $this->grupo_empresarial_id;
                    $this->request->data[$TABLE]['data_atualizacao'] = null;
                } else {
                    $this->request->data[$TABLE]['data_atualizacao'] = $dateTimeNow;
                }


                #BEGIN - UPLOAD IMAGEM
                $imagemArr = array();
                $nome_imagem_old = '';

                if (isset($this->data[$TABLE]['arquivo_logo']) && is_array($this->data[$TABLE]['arquivo_logo'])) {
                    $upload = $this->Funcoes->uploadImage($this->data[$TABLE]['arquivo_logo'], $this->params['controller'], $this->params['action']); #true força o tamanho da imagem
                    if ($upload != false) {
                        $this->request->data[$TABLE]['img_logo'] = $upload;
                    }
                }
                #END - UPLOAD IMAGEM


                if (!$this->$TABLE->save($this->request->data[$TABLE])) {
                    throw new Exception();
                }
                $id = $this->$TABLE->id;



                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Gravação - Cliente',
                    'description'         => json_encode($this->data),
                    'server_description'  => '',
                    'data_cadastro'       => $dateTimeNow,
                    'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);

                $this->Session->setFlash(__($this->msg_salvo));
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



                if ($flash == '') {
                    $flash = 'Erro: ' . $this->msg_salvo_erro;
                }
                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Erro Gravação - Cliente',
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
    public function admin_delete($id = null)
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


    public function admin_desligamento($cliente_id = null)
    {

        if ($cliente_id === null) {
            $cliente_id =  $this->Session->read('Auth.Usuario.cliente_id');
        }

        set_time_limit(1800);
        ini_set('max_execution_time', 1800);

        $this->autoRender = false;
        $this->loadModel('Cliente');
        if (!$this->Cliente->exists($cliente_id)) {
            $this->Session->setFlash($this->msg_nao_existe);
            return $this->redirect(array('action' => 'index'));
        }

        try {
            $cliente = $this->Cliente->find('first', array(
                'conditions' => array('Cliente.id' => $cliente_id),
                'fields' => array('Cliente.nome'),
                'recursive' => -1
            ));

            $clienteNome = isset($cliente['Cliente']['nome']) ? $cliente['Cliente']['nome'] : 'cliente';
            $fileCliente = preg_replace('/[^A-Za-z0-9 _\-]/', '_', $clienteNome);
            $fileCliente = trim($fileCliente);
            $clienteFolder = $fileCliente;

            $this->loadModel('Beneficiario');
            $this->Beneficiario->recursive = 2;
            $beneficiarios = $this->Beneficiario->find('all', array(
                'conditions' => array('Beneficiario.cliente_id' => $cliente_id),
                'order' => array('Beneficiario.nome' => 'ASC')
            ));

            $listBeneficiarios = array();
            $listAtendimentos = array();
            $listAfastados = array();
            $listAbsenteismo = array();
            $listBeneficioPrev = array();
            $anexosAfastado = array();
            $anexosAtendimento = array();

            foreach ($beneficiarios as $benef) {
                $nomeBenef = $this->Funcoes->utf8ToIso($benef['Beneficiario']['nome']);
                $cpfBenef = isset($benef['Beneficiario']['cpf']) ? str_replace(array('.', '-', ' '), '', $benef['Beneficiario']['cpf']) : '';
                $cnpjEmpresa = isset($benef['Empresa']['cnpj']) ? $benef['Empresa']['cnpj'] : '';

                $listBeneficiarios[] = array(
                    'nome' => $nomeBenef,
                    'cpf' => $cpfBenef,
                    'cnpj_empresa' => $cnpjEmpresa
                );

                if (isset($benef['Atendimento']) && is_array($benef['Atendimento'])) {
                    foreach ($benef['Atendimento'] as $atendimento) {
                        $anexoAtendimento = isset($atendimento['anexo']) ? $atendimento['anexo'] : '';
                        if (!empty($anexoAtendimento)) {
                            $anexosAtendimento[] = $anexoAtendimento;
                        }

                        $listAtendimentos[] = array(
                            'nome' => $nomeBenef,
                            'cpf' => $cpfBenef,
                            'data_cadastro' => $this->Funcoes->dateToView(@$atendimento['data_cadastro'], true),
                            'anexo' => $anexoAtendimento
                        );
                    }
                }

                if (isset($benef['Afastado']) && is_array($benef['Afastado'])) {
                    foreach ($benef['Afastado'] as $afastado) {
                        $anexoAfastado = isset($afastado['anexo']) ? $afastado['anexo'] : '';
                        if (!empty($anexoAfastado)) {
                            $anexosAfastado[] = $anexoAfastado;
                        }

                        $listAfastados[] = array(
                            'nome' => $nomeBenef,
                            'cpf' => $cpfBenef,
                            'data_cadastro' => $this->Funcoes->dateToView(@$afastado['data_cadastro'], true),
                            'anexo' => $anexoAfastado
                        );
                    }
                }

                if (isset($benef['Absenteismo']) && is_array($benef['Absenteismo'])) {
                    foreach ($benef['Absenteismo'] as $absenteismo) {
                        $listAbsenteismo[] = array(
                            'nome' => $nomeBenef,
                            'cpf' => $cpfBenef,
                            'data_cadastro' => $this->Funcoes->dateToView(@$absenteismo['data_cadastro'], true),
                            'anexo' => isset($absenteismo['arquivo']) ? $absenteismo['arquivo'] : ''
                        );
                    }
                }

                if (isset($benef['BeneficioPrevidenciario']) && is_array($benef['BeneficioPrevidenciario'])) {
                    foreach ($benef['BeneficioPrevidenciario'] as $beneficioPrev) {
                        $listBeneficioPrev[] = array(
                            'nome' => $nomeBenef,
                            'cpf' => $cpfBenef,
                            'data_cadastro' => $this->Funcoes->dateToView(@$beneficioPrev['data_cadastro'], true)
                        );
                    }
                }
            }

            $anexos = array(
                'Afastado' => array_values(array_unique($anexosAfastado)),
                'Atendimento' => array_values(array_unique($anexosAtendimento))
            );

            $this->Session->write('Desligamento.anexos', $anexos);
            $this->Session->write('Desligamento.cliente_nome', $clienteNome);
            $this->Session->write('Desligamento.datahora', date('Y-m-d H:i:s'));

            App::import('Vendor', 'PHPExcel');
            $oPlanilha = new \Vendor\PHPExcel();

            $oPlanilha->addPlanilha('Beneficiarios');
            $oPlanilha->setColunas(array(
                'nome' => 'Nome',
                'cpf' => 'CPF',
                'cnpj_empresa' => 'CNPJ da Empresa'
            ));
            $oPlanilha->addLinhaTitulo(array('negrito', 'borda' => 'tlrb', 'cor-fundo' => 'azulado-claro', 'texto'));
            foreach ($listBeneficiarios as $row) {
                $oPlanilha->addLinha($row, array('nome', 'cpf', 'cnpj_empresa'), array('borda' => 'lrb', 'texto'));
            }
            $oPlanilha->setLarguraAutomaticaColunas();
            $oPlanilha->setAlturaAutomaticaLinhas();

            $oPlanilha->addPlanilha('Atendimentos');
            $oPlanilha->setColunas(array(
                'nome' => 'Nome Beneficiario',
                'cpf' => 'CPF Beneficiario',
                'data_cadastro' => 'Data Cadastro',
                'anexo' => 'Anexo'
            ));
            $oPlanilha->addLinhaTitulo(array('negrito', 'borda' => 'tlrb', 'cor-fundo' => 'azulado-claro', 'texto'));
            foreach ($listAtendimentos as $row) {
                $oPlanilha->addLinha($row, array('nome', 'cpf', 'data_cadastro', 'anexo'), array('borda' => 'lrb', 'texto'));
            }
            $oPlanilha->setLarguraAutomaticaColunas();
            $oPlanilha->setAlturaAutomaticaLinhas();

            $oPlanilha->addPlanilha('Afastado');
            $oPlanilha->setColunas(array(
                'nome' => 'Nome Beneficiario',
                'cpf' => 'CPF Beneficiario',
                'data_cadastro' => 'Data Cadastro',
                'anexo' => 'Anexo'
            ));
            $oPlanilha->addLinhaTitulo(array('negrito', 'borda' => 'tlrb', 'cor-fundo' => 'azulado-claro', 'texto'));
            foreach ($listAfastados as $row) {
                $oPlanilha->addLinha($row, array('nome', 'cpf', 'data_cadastro', 'anexo'), array('borda' => 'lrb', 'texto'));
            }
            $oPlanilha->setLarguraAutomaticaColunas();
            $oPlanilha->setAlturaAutomaticaLinhas();

            $oPlanilha->addPlanilha('Absenteismo');
            $oPlanilha->setColunas(array(
                'nome' => 'Nome Beneficiario',
                'cpf' => 'CPF Beneficiario',
                'data_cadastro' => 'Data Cadastro',
                'anexo' => 'Anexo'
            ));
            $oPlanilha->addLinhaTitulo(array('negrito', 'borda' => 'tlrb', 'cor-fundo' => 'azulado-claro', 'texto'));
            foreach ($listAbsenteismo as $row) {
                $oPlanilha->addLinha($row, array('nome', 'cpf', 'data_cadastro', 'anexo'), array('borda' => 'lrb', 'texto'));
            }
            $oPlanilha->setLarguraAutomaticaColunas();
            $oPlanilha->setAlturaAutomaticaLinhas();

            $oPlanilha->addPlanilha('beneficiario_previdenciario');
            $oPlanilha->setColunas(array(
                'nome' => 'Nome Beneficiario',
                'cpf' => 'CPF Beneficiario',
                'data_cadastro' => 'Data Cadastro'
            ));
            $oPlanilha->addLinhaTitulo(array('negrito', 'borda' => 'tlrb', 'cor-fundo' => 'azulado-claro', 'texto'));
            foreach ($listBeneficioPrev as $row) {
                $oPlanilha->addLinha($row, array('nome', 'cpf', 'data_cadastro'), array('borda' => 'lrb', 'texto'));
            }
            $oPlanilha->setLarguraAutomaticaColunas();
            $oPlanilha->setAlturaAutomaticaLinhas();

            $oPlanilha->removerPlanilha(0);

            $dateTimeNow = date('Y-m-d H:i:s');
            $timestamp = date('d-m-Y H_i_s');
            $excelFileName = $fileCliente . ' e ' . $timestamp . '.xlsx';
            $zipFileName = $fileCliente . ' e ' . $timestamp . '.zip';
            $baseDest = WWW_ROOT . 'files' . DS . 'uploads' . DS . 'desligamento' . DS . $clienteFolder . DS;

            if (!file_exists($baseDest)) {
                mkdir($baseDest, 0777, true);
            }

            $excelPath = $baseDest . $excelFileName;
            $oPlanilha->salvarArquivo($excelPath, true);

            $this->loadModel('ClienteDesligamento');
            $this->ClienteDesligamento->setSource('cliente_desligamento');
            $zipResult = $this->copyDesligamentoAnexos($clienteNome, $anexos, $excelFileName, $zipFileName, $baseDest);

            $grupoEmpresarialId = $this->Session->read('Auth.Usuario.grupo_empresarial_id');
            $usuarioId = $this->Session->read('Auth.Usuario.id');

            $this->ClienteDesligamento->create();
            $this->ClienteDesligamento->save(array(
                'grupo_empresarial_id' => $grupoEmpresarialId,
                'cliente_id' => $cliente_id,
                'usuario_id' => $usuarioId,
                'data_cadastro' => $dateTimeNow,
                'file_info' => $excelFileName,
                'files' => $zipFileName
            ));

            if (!empty($zipResult['zip_path']) && file_exists($zipResult['zip_path'])) {
                $this->downloadZipFile($zipResult['zip_path'], $zipFileName);
            }

            $this->Session->setFlash('Arquivos de desligamento gerados com sucesso!');
            $this->redirect(array('action' => 'index'));
        } catch (Exception $e) {
            $this->loadModel('Log');
            $this->Log->create();
            $this->Log->save(array(
                'log' => 'Erro no desligamento do cliente',
                'description' => json_encode(array(
                    'cliente_id' => $cliente_id,
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'error_trace' => $e->getTraceAsString()
                )),
                'server_description' => json_encode($this->request->params),
                'data_cadastro' => date('Y-m-d H:i:s'),
                'usuario_id' => $this->Session->read('Auth.Usuario.id')
            ));

            $this->Session->setFlash('Erro ao gerar os arquivos de desligamento. O administrador foi notificado.');
            $this->redirect(array('action' => 'index'));
        }
    }

    private function copyDesligamentoAnexos($clienteNome, $anexos, $excelFileName, $zipFileName, $baseDest)
    {
        $clienteFolder = preg_replace('/[^A-Za-z0-9 _\-]/', '_', $clienteNome);
        $clienteFolder = trim($clienteFolder);
        if ($clienteFolder === '') {
            $clienteFolder = 'cliente';
        }

        $baseDest = WWW_ROOT . 'files' . DS . 'uploads' . DS . 'desligamento' . DS . $clienteFolder . DS;
        $result = array();

        foreach (array('Afastado' => 'afastado', 'Atendimento' => 'atendimento') as $sessionKey => $subFolder) {
            $files = isset($anexos[$sessionKey]) && is_array($anexos[$sessionKey]) ? $anexos[$sessionKey] : array();
            $destFolder = $baseDest . $subFolder . DS;
            if (!file_exists($destFolder)) {
                mkdir($destFolder, 0777, true);
            }
            $result[$subFolder] = array();
            $srcFolder = WWW_ROOT . 'files' . DS . 'uploads' . DS . $subFolder . DS;

            foreach ($files as $filename) {
                if (empty($filename)) {
                    continue;
                }

                $srcPath = $srcFolder . $filename;
                $destPath = $destFolder . $filename;

                if (!file_exists($srcPath)) {
                    $result[$subFolder][] = array('file' => $filename, 'status' => 'missing');
                    continue;
                }

                if (@copy($srcPath, $destPath)) {
                    $result[$subFolder][] = array('file' => $filename, 'status' => 'copied');
                } else {
                    $result[$subFolder][] = array('file' => $filename, 'status' => 'error');
                }
            }
        }

        $zipPath = WWW_ROOT . 'files' . DS . 'uploads' . DS . 'desligamento' . DS . $zipFileName;
        $zipSuccess = $this->zipDirectory($baseDest, $zipPath);

        $this->loadModel('Log');
        $this->Log->create();
        $this->Log->save(array(
            'log' => 'Desligamento: cópia de anexos para cliente ' . $clienteNome,
            'description' => json_encode(array('files' => $result, 'excel' => $excelFileName, 'zip' => basename($zipPath), 'zip_success' => $zipSuccess)),
            'server_description' => json_encode(array('cliente_nome' => $clienteNome, 'base_dest' => $baseDest)),
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario_id' => $this->Session->read('Auth.Usuario.id')
        ));

        return array(
            'zip_path' => $zipPath,
            'zip_filename' => $zipFileName,
            'excel_filename' => $excelFileName,
            'zip_success' => $zipSuccess,
            'details' => $result
        );
    }

    private function zipDirectory($sourcePath, $zipPath)
    {
        if (!class_exists('ZipArchive')) {
            return false;
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        $sourcePath = realpath($sourcePath);
        if ($sourcePath === false) {
            $zip->close();
            return false;
        }

        $baseLength = strlen($sourcePath) + 1;
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourcePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $filePath = $item->getPathname();
            $localName = substr($filePath, $baseLength);
            if ($item->isDir()) {
                $zip->addEmptyDir($localName);
            } else {
                $zip->addFile($filePath, $localName);
            }
        }

        $zip->close();
        return true;
    }

    private function downloadZipFile($zipPath, $zipFileName)
    {
        if (!file_exists($zipPath)) {
            return false;
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
        header('Content-Length: ' . filesize($zipPath));
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile($zipPath);
        exit;
    }
}
