<?php
App::uses('AppController', 'Controller');
/**
 * Afastado Controller
 *
 * @property Afastado $Afastado
 * @property PaginatorComponent $Paginator
 */
class AfastadoController extends AppController
{
    public  $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public  $msg_nao_existe = 'Afastamento Inexistente';
    public  $msg_salvo = 'O Afastamento foi SALVO com sucesso!';
    public  $msg_salvo_erro = 'Não foi possível SALVAR o Afastamento, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public  $msg_excluido = 'O Afastamento foi EXCLUÍDO com sucesso ';
    public  $msg_excluido_erro = 'Não foi possível EXCLUIR o Afastamento, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
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

        $this->simNaoArr = array(0 => 'Não', 1 => 'Sim');
        $this->simNaoAcaoInssArr = array(0 => 'Não', 1 => 'Sim, ação judicial', 2 => 'Sim, recurso administrativo');

        $this->set('simNaoArr', array_merge(['' => 'Selecione...'], $this->simNaoArr));
        $this->set('simNaoAcaoInssArr', array_merge(['' => 'Selecione...'], $this->simNaoAcaoInssArr));
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
    public function admin_index($download = 0)
    {

        $TABLE = $this->table;
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'] . '_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'] . '_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        $search = $this->Session->read($this->name_search);
        $condition = array();
        if ($download == 0) {
            $condition[] = $TABLE . '.situacao = "A"';
        }

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
        $condition[] = 'Afastado.status = 1 ';
        #$condition[] = 'Afastado.status = 1 ';
        #$condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        #$condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        # $condition[] = 'Afastado.empresa_id = 346';


        #$this->$TABLE->recursive = 2;


        if ($download == 1) {
            $this->index_download($this->chave, $condition);
            exit;
        }


        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 15,
            'order' => array('id' => 'DESC'),
            'group' => array('Afastado.beneficiario_id'),
            'recursive' => 2
        );

        #krumo($condition);

        $rows = $this->Paginator->paginate();
        #krumo($rows);
        #exit;
        $this->set('rows', $rows);
        $this->set('search', $search);
    }

    public function index_download($chave, $condition)
    {
        if ($chave != $this->chave) {
            die;
        }
        $TABLE = $this->table;


        #BUSCA DOWNLOAD
        #$this->$TABLE->unBindModel(array('belongsTo' => array('Beneficiario'=>array('belongsTo' => array('Beneficiario')))));
        $this->loadModel('Beneficiario');
        $this->Beneficiario->unBindModel(array(
            'belongsTo' => array('Cliente', 'UsuarioCriador', 'UsuarioAtualizacao', 'Empresa'),
            'hasMany' => array('Afastado', 'BeneficioPrevidenciario', 'Absenteismo')
        ));
        $this->Beneficiario->BindModel(array('hasMany' => array('Atendimento' => array('conditions' => ['status' => 1], 'limit' => 1, 'order' => ['data_cadastro' => 'desc']))));

        $rows = $this->$TABLE->find('all', [
            'conditions' => $condition,
            'group' => ['Afastado.beneficiario_id'],
            'order' => ['Afastado.id' => 'DESC'],
            'recursive' => 2
        ]);


        #TRATA DOWNLOAD
        $list = [];
        foreach ($rows as $row) {

            $cpf = $row['Beneficiario']['cpf'];
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);

            $data_interacao = '';
            $desc_interacao = $this->Funcoes->utf8ToIso('Sem interação');
            if (count($row['Beneficiario']['Atendimento']) > 0) {
                $data_interacao = $this->Funcoes->dateToView(@$row['Beneficiario']['Atendimento'][0]['data_conclusao']);
                $desc_interacao = $this->Funcoes->limpa_textarea(@$row['Beneficiario']['Atendimento'][0]['descricao']);
                $desc_interacao = $this->Funcoes->utf8ToIso($desc_interacao);
            }

            $acao_trabalhista = '';
            if ($row[$TABLE]['acao_trabalhista'] != '' && isset($this->simNaoArr[$row[$TABLE]['acao_trabalhista']])) {
                $acao_trabalhista = $this->simNaoArr[$row[$TABLE]['acao_trabalhista']];
            }

            $acao_inss = '';
            if ($row[$TABLE]['acao_inss'] != '' && isset($this->simNaoAcaoInssArr[$row[$TABLE]['acao_inss']])) {
                $acao_inss = $this->simNaoAcaoInssArr[$row[$TABLE]['acao_inss']];
            }

            $limbo_previdenciario = '';
            if ($row[$TABLE]['limbo_previdenciario'] != '' && isset($this->simNaoArr[$row[$TABLE]['limbo_previdenciario']])) {
                $limbo_previdenciario = $this->simNaoArr[$row[$TABLE]['limbo_previdenciario']];
            }

            $list[] = [
                'id' => $row[$TABLE]['id'],
                'beneficiario' => $this->Funcoes->utf8ToIso($row['Beneficiario']['nome']),
                'cpf' => $cpf,
                'data_inicio' => $this->Funcoes->dateToView(@$row[$TABLE]['data_inicio_afastamento']),
                'data_fim' => $this->Funcoes->dateToView(@$row[$TABLE]['data_fim_afastamento']),
                'cid' => $row[$TABLE]['cid'],
                'tipo_afastamento' => $this->Funcoes->utf8ToIso($row[$TABLE]['tipo_afastamento']),
                'assistencia_medica' => $this->Funcoes->utf8ToIso($row[$TABLE]['assistencia_medica']),
                'plano_assistencia_medica' => $this->Funcoes->utf8ToIso($row[$TABLE]['plano_assistencia_medica']),
                'acao_trabalhista' => $this->Funcoes->utf8ToIso($acao_trabalhista),
                'acao_inss' => $this->Funcoes->utf8ToIso($acao_inss),
                'limbo_previdenciario' => $this->Funcoes->utf8ToIso($limbo_previdenciario),
                'situacao' => ($row[$TABLE]['situacao'] == 'A') ? 'Afastado' : 'Retorno ao Trabalho',
                'data_interacao' => $data_interacao,
                'desc_interacao' => $this->Funcoes->utf8ToIso($desc_interacao),
                'data_cadastro' => $this->Funcoes->dateToView($row[$TABLE]['data_cadastro'], true),
                'via' => $this->Funcoes->utf8ToIso(($row[$TABLE]['importacao_id'] == '') ? 'Entrada Manual' : 'Importação')
            ];
        }


        //Gerando planilha .xlsx para download
        App::import('Vendor', 'PHPExcel');
        $oPlanilha = new \Vendor\PHPExcel();
        $oPlanilha->addPlanilha("Afastados");
        $oPlanilha->setColunas([
            'id' => 'ID',
            'beneficiario' => 'Beneficiario',
            'cpf' => 'CPF',
            'data_inicio' => 'Data Inicio Afastamento',
            'data_fim' => 'Data Fim Afastamento',
            'cid' => 'CID',
            'tipo_afastamento' => 'Tipo de Afastamento',
            'assistencia_medica' => $this->Funcoes->utf8ToIso('Assistência Médica'),
            'plano_assistencia_medica' => $this->Funcoes->utf8ToIso('Plano de Assistência Médica'),
            'acao_trabalhista' => $this->Funcoes->utf8ToIso('Possui ação Trabalhista?'),
            'acao_inss' => $this->Funcoes->utf8ToIso('Possui ação contra o INSS?'),
            'limbo_previdenciario' => $this->Funcoes->utf8ToIso('Limbo previdenciário?'),
            'situacao' => $this->Funcoes->utf8ToIso('Situação'),
            'data_interacao' => $this->Funcoes->utf8ToIso('Data de Interação'),
            'desc_interacao' => $this->Funcoes->utf8ToIso('Descritivo Interação'),
            'data_cadastro' => 'Data de Cadastro',
            'via' => 'Via'
        ]);

        $oPlanilha->addLinhaTitulo([
            'negrito',
            'borda'     => 'tlrb',
            'cor-fundo' => 'azulado-claro',
            'texto'
        ]);

        foreach ($list as $row) {
            // foreach($row[0] AS $k => $v)
            //     $row['CopartExtrato'][$k] = $v;

            $oPlanilha->addLinha($row, [], [
                'borda' => 'lrb',
                'texto',
            ]);
        }

        #$oPlanilha->aplicarEstilo('monetario', 'G2:H' . $oPlanilha->getUltimaLinha());
        $oPlanilha->setLarguraAutomaticaColunas();
        $oPlanilha->setAlturaAutomaticaLinhas();

        $oPlanilha->downloadArquivo("afastados " . date('d-m-Y H_m_s') . ".xlsx");


        exit;
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


    /**
     * SALVAR NOVO E EDITAR!
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function admin_add($beneficiario_id = null, $id = null)
    {
        $TABLE = $this->table;

        $this->loadModel('Empresa');
        $empresaArr = $this->Empresa->find('list', array('conditions' => array('cliente_id' => $this->cliente_id), 'order' => array('razao_social' => 'ASC'), 'fields' => ['id', 'razao_cnpj']));
        $empresaArr = $this->Funcoes->select_merge($empresaArr);
        $this->set('empresaArr', $empresaArr);


        if ($beneficiario_id == null || !is_numeric($beneficiario_id)) {
            $this->Session->setFlash('Beneficiário inváido!');
            $this->redirect(array('controller' => 'beneficiario'));
            exit;
        }


        $this->loadModel('Beneficiario');
        $benef = $this->Beneficiario->find('first', array('conditions' => array('id' => $beneficiario_id, 'cliente_id' => $this->cliente_id), 'fields' => 'id,nome', 'recursive' => -1));
        if (count($benef) == 0) {
            $this->Session->setFlash('Beneficiário inexistente ou de outro cliente!');
            $this->redirect(array('controller' => 'beneficiario'));
            exit;
        }
        $this->set('benef', $benef);

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
                } else {
                    $this->request->data[$TABLE]['data_atualizacao'] = $dateTimeNow;
                    $this->request->data[$TABLE]['usuario_id'] = $user_create;
                }


                $FILE = $this->data['Afastado']['arquivo'];
                $uploadFolder = WWW_ROOT . 'files' . DS . 'uploads' . DS . 'afastado' . DS;

                unset($this->request->data[$TABLE]['anexo']);



                //BEGIN - BLOB
                // if (isset($FILE['name']) && $FILE['name'] != '') {

                //     if(!parent::blob_action($FILE, ['action'=> 0,'table'=> 'Afastado',])){
                //         throw new Exception();
                //     }

                //     $this->request->data[$TABLE]['blob_id'] = $this->Blob->id;
                // }
                //FIM - BLOB


                #OLD MODEL FILE
                if (isset($FILE['name']) && $FILE['name'] != '') {
                    #CRIA AS PASTAS 
                    if (!file_exists($uploadFolder)) {
                        mkdir($uploadFolder, 0777, true);
                    }

                    #DEFINE NOVO NOME COM TIME
                    $fileNameArr = explode('.', $FILE['name']);
                    $extensao = $fileNameArr[count($fileNameArr) - 1];
                    unset($fileNameArr[count($fileNameArr) - 1]);
                    $file_name = implode('.', $fileNameArr);
                    $file_name = $this->Funcoes->normalizaeUrl($file_name) . '_' . time() . '.' . $extensao;

                    #MONTA CAMINHO E NOME DO ARQUIVO E SALVA NO SERVIDOR
                    $file_full = $uploadFolder . $file_name;
                    $successMove = move_uploaded_file($FILE['tmp_name'], $file_full);

                    $this->request->data[$TABLE]['anexo'] = $file_name;
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
                $data_log = array(
                    'id' => '',
                    'log' => 'Afastado - Cadastro',
                    'description'         =>  '',
                    'server_description'  =>  '',
                    'data_cadastro'       =>  date('Y/m/d H:i:s'),
                    'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                #END - CRIANDO LOG
                return $this->redirect(array('action' => 'add', $beneficiario_id, $id));
            } catch (Exception $ex) {
                debug($this->Afastado->getDataSource()->getLog(false, false));
                exit;
                $flash = '';
                $dataSource->rollback();
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

                $this->Session->setFlash($flash);
                #BEGIN - CRIANDO LOG ERRO
                $this->loadModel('LogErro');
                $this->LogErro->create();
                $data_log = array(
                    'id' => '',
                    'log' => 'Afastado - Erro - Cadastro',
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
                $this->redirect(array('controller' => 'beneficiario', 'action' => 'view', $beneficiario_id));
                exit();
            }
            $options = array('conditions' => array($TABLE . '.' . $this->$TABLE->primaryKey => $id));
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
                        'log' => 'Afastado - Exclusão ',
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
                    'log' => 'Afastado - Exclusão Múltipla',
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






    public function admin_index_download_pdf($id)
    {
        $this->autoRender = false;
        #REFERENCE DOMPDF
        ini_set('memory_limit', '512M');
        $this->layout = 'blank';
        $rows = $this->admin_index_download_get_dados($id);
        $usuario_id = $this->Session->read('Auth.Usuario.id');

        $view = new View($this, false);
        $view->set(compact('rows'));
        $html = $view->render('mapa_pessoal_pdf');

        $nomeDoArquivo = "mapeamento_{$id}.pdf";
        $endDoArquivo = './files/uploads/pdf/mapeamento_individual/' . $nomeDoArquivo;
        $endDoArquivoLink = Router::url('', true) . 'v4/files/uploads/pdf/mapeamento_individual/' . $nomeDoArquivo;
        if ($this->geraPDF($html, $nomeDoArquivo)) {
            if ($mail == true && $rows['email'] != '') {
                if (SENDMAIL == true) {
                    #ENVIO E-MAIL
                    #$toEmail = 'sandler.matos@victorysaude.com.br';
                    $toEmail = $rows['email'];
                    $subject = 'Mapeamento Individual';
                    $msg = 'Olá ' . $rows['nome'] . ', parabéns por ter concluído seu mapeamento! <br><br>
                            Acompanhe seu mapa pessoal de saúde.<br><br>

                            Atenciosamente, <br>
                            <b>Equipe de Gestão Avatar da Saúde</b>
                            <br><br>
                            <span style="font-size:10px; color:#bbbbbb">Avatar da Saúde © ' . date('Y') . ' | Todos os direitos reservados</span>
                            ';

                    $attachments = array($nomeDoArquivo => $endDoArquivo);



                    if (!parent::envio_email($toEmail, $subject, $msg, $attachments)) {
                        #GRAVA LOG
                        $dataLog = array();
                        $dataLog['email'] = $toEmail;
                        $dataLog['assunto'] = $subject;
                        $dataLog['mensagem'] = $msg;
                        $dataLog['anexo'] = $attachments;
                        $dataLog['mensagem'] = 'Erro ao Enviar o E-mail! ';
                        $return_log = parent::grava_log('Mapeamento - Erro Envio Mapa Pessoal', $dataLog, 'Erro - E-mail Mapa Pessoal enviado com sucesso.');

                        $link_pdf = '<a href="' . $endDoArquivoLink . '" target="_blank">Mapa Pessoal</a>';
                        if ($redirect) {
                            $this->Session->setFlash('Erro ao Enviar o E-mail! Arquivo Gerado: ' . $link_pdf);
                            $this->redirect($this->referer());
                        } else {
                            $this->Session->setFlash('Você acabou de salvar com sucesso seu mapeamento! &nbsp; &nbsp; Segue o link do seu Mapa Pessoal: ' . $link_pdf . ' ');
                            return false;
                        }
                    } else {

                        if ($redirect) {
                            $this->loadModel('QvPreenchido');
                            $dataQv = array();
                            $dataQv['cod_preenchido'] = $rows['cod_preenchido'];
                            $dataQv['mapa_pessoal_enviado'] = date('Y-m-d H:i:s');
                            $this->QvPreenchido->save($dataQv);

                            $this->Session->setFlash('E-mail Enviado com Sucesso!!');
                            $this->redirect($this->referer());
                        } else {
                            $this->Session->setFlash('Você acabou de salvar com sucesso seu mapeamento! &nbsp; &nbsp; <b>Foi enviado um E-mail com o seu Mapa Pessoal!!<b>');
                            return true;
                        }
                    }
                } else {
                    if ($redirect) {
                        $link_pdf = '<a href="' . $endDoArquivoLink . '" target="_blank">Mapa Pessoal</a>';
                        $this->Session->setFlash('Envio de e-mail está desabilitado, mas o arquivo foi gerado: ' . $link_pdf);
                        $this->redirect($this->referer());
                    } else {
                        $link_pdf = '<a href="' . $endDoArquivoLink . '" target="_blank">Mapa Pessoal</a>';
                        $this->Session->setFlash('Envio de e-mail está desabilitado, mas o arquivo foi gerado: ' . $link_pdf);
                        return false;
                    }
                }
                exit();
            } else {
                if ($redirect) {
                    #$this->Session->setFlash('Gerado com Sucesso!!');                    
                    $this->redirect('/files/uploads/pdf/mapeamento_individual/' . $nomeDoArquivo);
                } else {
                    return false;
                }
            }
        } else {
            if ($redirect) {
                $this->Session->setFlash('Erro ao gerar o arquivo!');
                $this->redirect($this->referer());
            } else {
                return false;
            }
        }
    }

    public function admin_index_download_get_dados($id)
    {

        return false;
    }


    public function admin_index_download_excel()
    {
        $excel = new ExportacaoExcelDAO();
        $dadoExcel = $excel->buscarMovimentacao($post, false);
        $trArr = explode('</tr>', $dadoExcel);
        if (count($trArr) > 1) {
            $nome_arquivo = "movimentacao_{$data_movimentacao}_" . $excel->gera_nome(true, "xls");
            $excel->setCaminho("../../_tmp/arquivo/gst_doc/");
            $excel->setCaminhoVer("../../_tmp/arquivo/gst_doc/");
            $excel->setNomeOriginal($nome_arquivo);
            $excel->setArquivo($nome_arquivo);
            $excel->criarArquivo($dadoExcel);
            $caminho_arquivo = ROOT . '/_tmp/arquivo/gst_doc/' . $nome_arquivo;
            $tamanho_arquivo = filesize($caminho_arquivo);
        }
    }
}
