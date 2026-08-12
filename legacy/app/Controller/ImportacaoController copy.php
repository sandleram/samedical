<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class ImportacaoController extends AppController
{

    #SUCESSO DEVE SER MÍNUSCULO
    public $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public $msg_nao_existe = 'Importação Inexistente';
    public $msg_salvo = 'A Importação foi SALVA com sucesso!';
    public $msg_salvo_erro = 'Não foi possível SALVAR a Importação, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public $msg_salvo_erro_login = 'Não foi possível efetuar o FILTRO ou SALVAR porque foi deslogado, tente novamente!';
    public $msg_excluido = 'A Importação foi EXCLUÍDA com sucesso!';
    public $msg_excluido_erro = 'Não foi possível EXCLUIR a Importação, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    private $erro = array();
    private $erro_exec = false;
    private $erro_linha = array();
    private $estrutura_banco = array();
    private $estrutura_banco_db = array();
    private $warning_error = array();
    private $warning_linha = array();
    private $tipo_importacao = '';


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

        $tipoImportacaoArr = array(
            'beneficiario' => 'Beneficiário',
            'afastado' => 'Afastado',
            'beneficio_previdenciario' => 'Benefício Previdenciário',
            'absenteismo' => 'Absenteísmo'
            // ,'sinistro'=>'Sinistro'
            // ,'faturamento'=>'Faturamento'
        );
        $tipoImportacaoArr = $this->Funcoes->select_merge($tipoImportacaoArr, ($this->params['action'] == 'admin_index' ? 'Tipo de Importação...' : 'Selecione...'));

        $this->set('tipoImportacaoArr', $tipoImportacaoArr);
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
    public function admin_index($id = null)
    {
        $TABLE = $this->table;
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'] . '_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'] . '_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;


        $search = $this->Session->read($this->name_search);
        $condition = array();
        if ($id != null and is_numeric($id)) {
            $search['id_'] = $id;
        }


        if (is_array($search)):
            if (!empty($search['id_']) && is_numeric($search['id_'])):
                $condition[] = array($TABLE . '.id = "' . $search['id_'] . '"');
            endif;
            if (!empty($search['tipo_importacao'])):
                $condition[] = $TABLE . '.tipo_importacao = "' . $search['tipo_importacao'] . '"';
            endif;
            if (!empty($search['status'])):
                $condition[] = $TABLE . '.status = ' . $search['status'];
            endif;
        endif;



        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        //        if($this->perfil_id != $this->perfil_root){
        //            $condition[] = $TABLE.$this->status_default;
        //            $condition[] = $TABLE.'.id <> '.$this->uRoot;
        //        }
        #END- USUÁRIO AUTORIZADO DEFAULT

        //        krumo($_SESSION);
        //        exit();


        $condition[] = $TABLE . '.cliente_id = ' . $this->cliente_id;





        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 15,
            'order' => array('id' => 'DESC')
        );



        $this->loadModel('Cliente');


        #BUSCA STATUS
        $statusArr = $this->Funcoes->parametros('Status', 'list', NULL, true, 'Status...');
        $this->set('statusArr', $statusArr);

        #BEGIN - CRIANDO LOG
        $this->loadModel('Log');
        $this->Log->create();
        $data_log = array(
            'id' => '',
            'log' => 'Acesso - Importação',
            'description'         =>  'Acesso Importação',
            'server_description'  =>  '',
            'data_cadastro'       =>  date('Y/m/d H:i:s'),
            'usuario_id'          =>  $this->Session->read('Auth.Usuario.id')
        );
        $this->Log->save($data_log);
        #END - CRIANDO LOG

        $this->$TABLE->recursive = 1;
        $this->set('rows', $this->Paginator->paginate());
        $this->set('search', $search);
    }



    /**
     * SALVAR NOVO E EDITAR!
     * @param type $id
     * @return type
     * @throws Exception
     * 
     * #http://php.net/manual/en/function.fgetcsv.php
#       https://github.com/PHPOffice/PHPExcel
        #http://ptcomputador.com/P/php-mysql-programming/92751.html
        #https://stackoverflow.com/questions/7766317/how-to-convert-excel-xls-to-csv-using-php
        #http://hayageek.com/convert-xls-to-csv-in-php/
        #https://stackoverflow.com/questions/6895665/convert-xlsx-file-to-csv-file-using-php
        
     */
    public function admin_add()
    {
        $TABLE = $this->table;
        $this->Session->delete('erro_validacao');

        if ($this->request->is(array('post', 'put'))) {
            $tipo_importacao = $this->data[$TABLE]['tipo_importacao'];
            $FILE = $this->data['Importacao']['arquivo'];
            $uploadFolder = WWW_ROOT . 'files' . DS . 'uploads' . DS . 'importacao' . DS;


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

            if ($successMove) {
                $data_save = array();
                $titulo = array();
                $row = 1;
                if (($handle = fopen($file_full, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                        $num = count($data);
                        $data_save[$row] = array();
                        // while($c < $num)
                        for ($c = 0; $c < $num; $c++) {
                            // krumo($c);
                            if ($row > 1) {
                                #@$data_save[$row - 1][$titulo[$c]] = $data[$c];
                                @$data_save[$row - 1][$titulo[$c]] = $this->Funcoes->isoToUtf8($data[$c]);
                            } else {
                                #@$titulo[$c] = $data[$c];
                                @$titulo[$c] = $data[$c];
                            }
                        }

                        $row++;
                    }
                    fclose($handle);
                }
                unset($data);


                #krumo($data_save);
                #exit;


                #VALIDAÇÃO 
                #S/N COLOCAR ZERO E UM - RETORNAR NA VALIDAÇÃO SIM OU NÃO
                #

                $dataSource = $this->$TABLE->getDataSource();
                $dataSource->begin();
                try {

                    $data_atual = date('Y-m-d H:i:s');
                    $dataImp = array();
                    $dataImp['id'] = '';
                    $dataImp['tipo_importacao'] = $tipo_importacao;
                    $dataImp['cliente_id'] = $this->cliente_id;
                    $dataImp['arquivo_importado'] = $file_name;
                    $dataImp['data_cadastro'] = $data_atual;
                    $dataImp['usuario_criador_id'] = $this->usuario_id;
                    $dataImp['status'] = 1;

                    $this->$TABLE->create();
                    if (!$this->$TABLE->save($dataImp)) {
                        throw new Exception();
                    }

                    $importacao_id = $this->$TABLE->id;
                    $dataImp['id'] = $importacao_id;


                    $this->erro_linha = array();
                    if ($data_save > 0) {
                        $valorTotal = 0;

                        $this->tipo_importacao = $tipo_importacao;
                        $return_vc = $this->valida_cabecalho($data_save[1]);


                        #krumo($tipo_importacao);
                        #krumo($data_save);
                        #krumo($dataImp);
                        #krumo($return_vc);
                        #exit;

                        if (count($data_save) > 1) {
                            if (count($data_save[count($data_save)]) == 0) {
                                unset($data_save[count($data_save)]);
                            }
                        }




                        // krumo(1);#TEST


                        #Beneficiario *******************                        
                        if ($tipo_importacao == 'beneficiario') {
                            $this->admin_carga_beneficiario($data_save, $dataImp);
                        } else if ($tipo_importacao == 'afastado') {
                            $this->admin_carga_afastado($data_save, $dataImp);
                        } else if ($tipo_importacao == 'beneficio_previdenciario') {
                            $this->admin_carga_beneficio_previdenciario($data_save, $dataImp);
                        } else if ($tipo_importacao == 'absenteismo') {
                            $this->admin_carga_absenteismo($data_save, $dataImp);
                        } else if ($tipo_importacao == 'sinistro') {
                            $this->admin_carga_sinistro($data_save, $dataImp);
                        } else if ($tipo_importacao == 'fatura') {
                            $this->admin_carga_faturamento($data_save, $dataImp);
                        }
                    }

                    // krumo($this->erro);
                    // krumo($this->erro_linha);
                    // exit;

                    if (count($data_save) == 0) {
                        #$this->erro[] = 'NÃO EXISTEM DADOS A SEREM GRAVADOS';
                        $this->erro[] = array('linha' => '', 'descricao' => 'NÃO EXISTEM DADOS A SEREM GRAVADOS');
                    }

                    // krumo(111); #TEST

                    if (count($this->erro) > 0) {
                        throw new Exception();
                    }
                    #debug($this->Importacao->getDataSource()->getLog(false, false));

                    //    krumo('import ok'); #TEST
                    //    $dataSource->rollback();#TEST
                    //    exit();#TEST

                    $dataSource->commit();
                    #$this->erro[] = 'Importação Efetuada!';
                    $this->erro[] = array('linha' => '', 'descricao' => 'Importação Efetuada!');

                    #krumo($this->erro);
                    #debug($this->Importacao->getDataSource()->getLog(false, false));
                    #exit;


                    $this->Session->setFlash("Importação de {$tipo_importacao} concluída com sucesso");
                    #CHAMAR URL DW PARA ATUALIZAÇÃO
                    $this->redirect(array('action' => 'index'));
                } catch (Exception $ex) {
                    $this->loadModel('Beneficiario');
                    //    debug($this->Beneficiario->getDataSource()->getLog(false, false));
                    //    krumo('erro catch');

                    //    exit;

                    $erro = 0;
                    // krumo($this->erro);#TEST
                    if (count($this->erro) > 0) {
                        if (in_array('Importação Efetuada!', $this->erro)) {
                            foreach ($this->erro as $kEr => $vEr) {
                                if (preg_match('/Importação Efetuada/', $vEr)) {
                                    $this->erro[$kEr] = 'Importação Efetuada com Sucesso, porém tivemos problemas com a rotina.';
                                    $erro = 1;
                                }
                            }
                        }
                    }


                    // krumo($erro);#TEST
                    // krumo($this->erro);#TEST
                    // exit;#TEST

                    if ($erro == 1) {
                        $html = 'Importação Concluída com Sucesso!!!';

                        #AVISO VIA EMAIL (erro na data calculada ou 
                        #GRAVAR NA LOG
                    } else {
                        #CRIAR TELA DE ERRO DE IMPORTAÇÃO
                        #$html = implode('<br>',$this->erro);
                        #$html = '<span style="font-size:12px; font-weight:bold;">Erro na Importação</span> <br><span style="font-size:10px; font-weight:bold; ">'.$html.'<br><br><span style="font-size:12px; font-weight:bold; ">Para dar continuidade, anexe o arquivo novamente!</span></span>';
                        $this->Session->write('erro_validacao', $this->erro);
                        $dataSource->rollback();
                        $this->redirect(array('action' => 'validacao'));
                    }

                    $this->Session->setFlash($html);

                    #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
                    $this->Session->write('error_form', $this->data);
                    $this->redirect($this->referer());
                    #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
                }
            }
        }



        #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
        if (!$this->request->is(array('post', 'put'))) {
            //            krumo($this->Session->read('error_form'));
            //            exit;
            $error_form = $this->Session->read('error_form');
            $this->Session->delete('error_form');
            if (is_array($error_form)) {
                $data_new = array_merge($this->data, $error_form);
                $this->request->data = $data_new;
            }
        }
        #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO



        $this->loadModel('Beneficio');
        $this->loadModel('Empresa');


        $beneficioArr = $this->Beneficio->find('list', array('conditions' => array('status' => 1, 'cliente_id' => $this->cliente_id), 'fields' => 'id,descricao'));
        $beneficioArr = $this->Funcoes->select_merge($beneficioArr);

        $empresaArr = $this->Empresa->find('list', array('conditions' => array('status' => 1, 'cliente_id' => $this->cliente_id), 'fields' => 'id,nome'));
        $empresaArr = $this->Funcoes->select_merge($empresaArr);

        $mesArr = array('01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril', '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro');
        $mesArr = $this->Funcoes->select_merge($mesArr);


        $anoArr = array();
        $i = date('Y');
        while ($i >= 2015) {
            $anoArr[$i] = $i;
            $i--;
        }
        $anoArr = $this->Funcoes->select_merge($anoArr);


        $this->set('beneficioArr', $beneficioArr);
        $this->set('empresaArr', $empresaArr);
        $this->set('mesArr', $mesArr);
        $this->set('anoArr', $anoArr);
    }



    public function admin_carga_beneficiario($data_save, $dataImp)
    {
        $this->loadModel('Beneficiario');
        $this->loadModel('Empresa');
        $this->loadModel('GrupoEmpresarial');

        #BUSCAR PELO GRUPO EMPRESARIAL TIPO DE IMPORTAÇÃO

        #krumo($this->Session->read('Auth.Usuario.grupo_empresarial_id'));


        foreach ($data_save as $data_k => $data_v) {


            if (count($data_v) == 0) {
                if (count($data_save) != $data_k) {
                    #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- Linha Vazia.</i>";
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'Linha Vazia');
                }
                continue;
            }

            #INICIO - VALIDAÇÃO
            $this->erro_linha   = array();
            $data_nascimento = NULL;
            if ($data_v['data_nascimento'] != '') {
                $data_nascimento = $this->Funcoes->dateToDb($data_v['data_nascimento']);
                $data_nascimento = $this->valida_dado($data_nascimento, 'data_nascimento');
            }
            $sexo = $this->valida_dado($data_v['sexo'], 'sexo');


            $nome = trim($data_v['nome']);
            if ($nome == '') {
                $this->erro_linha[] = "- CAMPO NOME É OBRIGATÓRIO.";
            }



            if (!in_array($data_v['situacao'], ['Ativo', 'Inativo'])) {
                $this->erro_linha[] = "- Situação Inválida.";
            }



            $cpf = trim($data_v['cpf']);
            $cpf = str_replace(' ', '', $cpf);
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            #$cpf = str_pad($cpf, 11,STR_PAD_LEFT);
            #$cpf = $this->Funcoes->monta_cpf($cpf);
            if ($data_v['cpf'] == '') {
                $this->erro_linha[] = "- CAMPO CPF É OBRIGATÓRIO.";
            } elseif (strlen($cpf) != 11) {
                $this->erro_linha[] = "- CAMPO CPF ESTÁ IMCOMPLETO.";
            }


            $cliente_id = (isset($data_v['cliente_id']) && $data_v['cliente_id'] != '') ? $data_v['cliente_id'] :  $this->cliente_id;


            $cnpj = trim($data_v['cnpj']);
            $cnpj = str_replace(' ', '', $cnpj);
            $cnpj = str_replace('.', '', $cnpj);
            $cnpj = str_replace('-', '', $cnpj);
            $cnpj = str_replace('/', '', $cnpj);
            if ($cnpj == '') {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ Obrigatório');
                continue;
            }
            if (strlen($cnpj) != 14) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ deve ser somente números e ter exatamente 14 números');
                continue;
            }
            #$cnpj = str_pad($cnpj, 14,STR_PAD_LEFT);



            #$return_valida_cpf = $this->busca_cpf($cpf,$nome); #GRAVA NO ERRO_LINHA
            #$valor_do_seguro  = $this->Funcoes->moedaToDb($data_v['valor_do_seguro']);


            #CRIAÇÃO DE EMPRESA CASO NÃO EXISTA
            try {

                $empresaArr = $this->Empresa->find('first', array('conditions' => array('cnpj' => $cnpj, 'cliente_id' => $cliente_id), 'recursive' => -1));
                if (count($empresaArr) > 0) {
                    $empresa_id = $empresaArr['Empresa']['id'];
                } else {
                    $razao_social = $filial = 'Z - Criado via Importação!';
                    if (isset($data_v['razao_social'])) {
                        $razao_social = $data_v['razao_social'];
                    }
                    if (isset($data_v['filia'])) {
                        $filial = $data_v['filia'];
                    }

                    $this->Empresa->create();
                    $data_save_emp = array();
                    $data_save_emp['id'] = '';

                    $data_save_emp['cliente_id'] = $cliente_id;
                    $data_save_emp['cnpj'] = $cnpj;
                    $data_save_emp['razao_social'] = $razao_social;
                    $data_save_emp['nome'] = $filial;
                    $data_save_emp['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save_emp['data_cadastro'] = $dataImp['data_cadastro'];


                    if (!$this->Empresa->save($data_save_emp)) {
                        $this->erro_linha[] = "- Erro ao criar a empresa.";
                    } else {
                        $empresa_id = $this->Empresa->id;
                    }
                }
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar a empresa.";
            }





            if (count($this->erro_linha) > 0) {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i>".implode("<br>",$this->erro_linha)."</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => $this->erro_linha);
                continue;
            }

            #FIM - VALIDAÇÃO


            #$countB = $this->Beneficiario->find('count',array('conditions'=>array('cpf'=>$cpf,'cliente_id'=>$this->cliente_id)));
            $countB = $this->Beneficiario->find('first', ['conditions' => ['cpf' => $cpf, 'cliente_id' => $cliente_id], 'fields' => 'id, situacao', 'recursive' => -1]);

            if (count($countB) > 0) {
                if ($countB['Beneficiario']['situacao'] != $data_v['situacao']) {
                    $dataS = array();
                    $dataS['id'] = $countB['Beneficiario']['id'];
                    $dataS['empresa_id'] = $empresa_id;
                    $dataS['situacao'] = $data_v['situacao'];


                    if (!$this->Beneficiario->save($dataS)) {
                        #$this->erro[] = 'ERRO CADASTRO DE BENEFICIARIO '.$data_k; #GERAR LOG ERRO
                        $this->erro[] = array('linha' => $data_k, 'descricao' => 'ERRO CADASTRO DE BENEFICIARIO ' . $data_k);
                        $this->erro_exec = true;
                    }
                } else {
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'BENEFICIÁRIO EXISTENTE ' . $data_k);
                    $this->erro_exec = true;
                }
            } else {
                $rsB = $this->Beneficiario->find('all', array('conditions' => array('cpf' => $cpf), 'recursive' => -1));
                if (count($rsB) > 0) {
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'BENEFICIÁRIO EXISTENTE EM OUTRO CLIENTE' . $data_k);
                    $this->erro_exec = true;
                } else {

                    try {
                        $this->Beneficiario->create();
                        $dataS = array();
                        $dataS['cliente_id']        = $cliente_id;
                        $dataS['empresa_id']        = $empresa_id;
                        $dataS['nome_social']       = $data_v['nome_social'];
                        $dataS['nome']              = $data_v['nome'];
                        $dataS['cpf']               = $cpf;
                        // $dataS['beneficio']         = $data_v['beneficio'];
                        $dataS['data_nascimento']   = $data_nascimento;
                        // $dataS['tipo_de_seguro']    = $data_v['tipo_de_seguro'];
                        // $dataS['banco']             = $data_v['banco'];
                        // $dataS['agencia']           = $data_v['agencia'];
                        // $dataS['conta']             = $data_v['conta'];
                        // $dataS['tipo_de_conta']     = $data_v['tipo_de_conta'];
                        // $dataS['endereco']          = $data_v['endereco'];
                        // $dataS['bairro']            = $data_v['bairro'];
                        // $dataS['cep']               = $data_v['cep'];
                        // $dataS['cidade']            = $data_v['cidade'];
                        // $dataS['estado']            = $data_v['estado'];
                        $dataS['telefone']          = $data_v['telefone'];
                        $dataS['grupo']             = $data_v['grupo'];
                        $dataS['subgrupo']          = $data_v['subgrupo'];
                        $dataS['matricula']         = $data_v['matricula'];
                        $dataS['telefone']          = $data_v['telefone'];
                        $dataS['telefone1']         = $data_v['telefone1'];
                        $dataS['telefone2']         = $data_v['telefone2'];
                        // $dataS['telefone3']         = $data_v['telefone3'];
                        // $dataS['telefone4']         = $data_v['telefone4'];
                        // $dataS['telefone5']         = $data_v['telefone5'];
                        // $dataS['telefone6']         = $data_v['telefone6'];
                        // $dataS['telefone7']         = $data_v['telefone7'];
                        // $dataS['telefone8']         = $data_v['telefone8'];
                        // $dataS['telefone9']         = $data_v['telefone9'];
                        $dataS['sexo']              = $sexo;
                        // $dataS['altura']            = $data_v['altura'];
                        // $dataS['peso']              = $data_v['peso'];
                        $dataS['profissao']         = $data_v['profissao'];
                        $dataS['ocupacao']          = $data_v['ocupacao'];
                        $dataS['pessoa_politicamente_exposta'] = $data_v['pessoa_politicamente_exposta'];
                        $dataS['realiza_alguma_atividade_perigosa_na_profissao'] = $data_v['realiza_alguma_atividade_perigosa_na_profissao'];
                        $dataS['possui_deficiencia'] = $data_v['possui_deficiencia'];
                        $dataS['rg']                = $data_v['rg'];
                        $dataS['estado_civil']      = $data_v['estado_civil'];
                        $dataS['email']             = $data_v['email'];
                        $dataS['situacao']          = $data_v['situacao'];
                        // $dataS['valor_do_seguro']   = $valor_do_seguro;
                        // $dataS['beneficiario1']     = $data_v['beneficiario1'];
                        // $dataS['parentesco1']       = $data_v['parentesco1'];
                        // $dataS['beneficiario2']     = $data_v['beneficiario2'];
                        // $dataS['parentesco2']       = $data_v['parentesco2'];
                        // $dataS['beneficiario3']     = $data_v['beneficiario3'];
                        // $dataS['parentesco3']       = $data_v['parentesco3'];
                        // $dataS['beneficiario4']     = $data_v['beneficiario4'];
                        // $dataS['parentesco4']       = $data_v['parentesco4'];
                        $dataS['importacao_id']     = $dataImp['id'];
                        $dataS['data_cadastro']     = $dataImp['data_cadastro'];
                        $dataS['usuario_criador_id'] = $this->usuario_id;
                        $dataS['usuario_atualizacao_id'] = NULL;
                        $dataS['data_atualizacao']  = NULL;
                        $dataS['status'] = 1;



                        if (!$this->Beneficiario->save($dataS)) {
                            #$this->erro[] = 'ERRO CADASTRO DE BENEFICIARIO '.$data_k; #GERAR LOG ERRO
                            $this->erro[] = array('linha' => $data_k, 'descricao' => 'ERRO CADASTRO DE BENEFICIARIO ' . $data_k);
                            $this->erro_exec = true;
                        }
                    } catch (Exception $ex) {
                        $this->erro[] = array('linha' => $data_k, 'descricao' => 'ERRO CADASTRO DE BENEFICIARIO ' . $data_k);
                        $this->erro_exec = true;
                    }
                }
            }





            if ($this->erro_exec) {
                throw new Exception();
            }

            // krumo($dataS);
            // krumo($this->erro);

            // exit;
        }

        // krumo($this->erro_exec);


    }


    public function admin_carga_afastado($data_save, $dataImp)
    {
        $this->loadModel('Beneficiario');
        $this->loadModel('Afastado');
        $this->loadModel('Empresa');


        foreach ($data_save as $data_k => $data_v) {

            if (count($data_v) == 0) {
                if (count($data_save) != $data_k) {
                    #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- Linha Vazia.</i>";
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'Linha Vazia');
                }
                continue;
            }



            #INICIO - VALIDAÇÃO
            $this->erro_linha   = array();


            $nome = trim($data_v['nome_colaborador']);
            if ($nome == '') {
                $this->erro_linha[] = "- CAMPO NOME É OBRIGATÓRIO.";
            }

            $cpf = trim($data_v['cpf']);
            $cpf = str_replace(' ', '', $cpf);
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            if ($cpf == '') {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- CPF Obrigatório.</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF Obrigatório');
                continue;
            }
            if (strlen($cpf) != 11) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF deve ser somente números e ter exatamente 11 números');
                continue;
            }
            #$cpf = str_pad($cpf, 11,STR_PAD_LEFT);
            #$cpf = $this->Funcoes->monta_cpf($cpf);


            #valida situacao
            if ($data_v['situacao'] == '') {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'Situação obrigatória');
                continue;
            } else {
                if (!in_array($data_v['situacao'], array('A', 'RT'))) {
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'Tipo de Situação inválida');
                    continue;
                }
            }

            #krumo($data_v['data_nascimento']);

            $data_nascimento = $this->valida_dado($data_v['data_nascimento'], 'data_nascimento');
            #krumo($data_nascimento);
            #exit;

            #$data_nascimento = $this->Funcoes->dateToDb($data_nascimento);

            #$sexo            = $this->valida_dado($data_v['sexo'],'sexo');

            $telefone = $data_v['telefone'];

            $cliente_id = (isset($data_v['cliente_id']) && $data_v['cliente_id'] != '') ? $data_v['cliente_id'] :  $this->Session->read('Auth.Usuario.cliente_id');


            $cnpj = trim($data_v['cnpj']);
            $cnpj = str_replace(' ', '', $cnpj);
            $cnpj = str_replace('.', '', $cnpj);
            $cnpj = str_replace('-', '', $cnpj);
            $cnpj = str_replace('/', '', $cnpj);
            if ($cnpj == '') {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ Obrigatório');
                continue;
            }
            if (strlen($cnpj) != 14) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ deve ser somente números e ter exatamente 14 números');
                continue;
            }
            #$cnpj = str_pad($cnpj, 14,STR_PAD_LEFT);



            #$return_valida_cpf = $this->busca_cpf($cpf); #GRAVA NO ERRO_LINHA #SE JA EXISTE
            #$valor_do_seguro  = $this->Funcoes->moedaToDb($data_v['valor_do_seguro']);



            #CRIAÇÃO DE EMPRESA CASO NÃO EXISTA
            try {

                $empresaArr = $this->Empresa->find('first', array('conditions' => array('cnpj' => $cnpj, 'cliente_id' => $cliente_id), 'recursive' => -1));
                if (count($empresaArr) > 0) {
                    $empresa_id = $empresaArr['Empresa']['id'];
                } else {
                    $razao_social = $filial = 'Z - Criado via Importação!';
                    // if(isset($data_v['razao_social'])){
                    //     $razao_social = $data_v['razao_social'];
                    // }
                    // if(isset($data_v['filia'])){
                    //     $filial = $data_v['filia'];
                    // }

                    $this->Empresa->create();
                    $data_save_emp = array();
                    $data_save_emp['id'] = '';
                    $data_save_emp['cliente_id'] = $cliente_id;
                    $data_save_emp['cnpj'] = $cnpj;
                    $data_save_emp['razao_social'] = $razao_social;
                    $data_save_emp['nome'] = $filial;
                    $data_save_emp['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save_emp['data_cadastro'] = $dataImp['data_cadastro'];


                    if (!$this->Empresa->save($data_save_emp)) {
                        $this->erro_linha[] = "- Erro ao criar a empresa.";
                    } else {
                        $empresa_id = $this->Empresa->id;
                    }
                }
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar a empresa.";
            }
            #FIM CRIAÇÃO EMPRESA





            #CRIAÇÃO DE BENEFICIÁRIO CASO NÃO EXISTA
            try {
                $beneficiarioArr = $this->Beneficiario->find('first', array('conditions' => array('cpf' => $cpf), 'recursive' => -1));
                if (count($beneficiarioArr) > 0) {
                    $beneficiario_id = $beneficiarioArr['Beneficiario']['id'];
                } else {
                    $this->Beneficiario->create();
                    $data_save = array();
                    $data_save['id'] = '';
                    $data_save['cliente_id'] = $cliente_id;
                    $data_save['empresa_id'] = $empresa_id;
                    $data_save['nome'] = $data_v['nome_colaborador'];
                    $data_save['pis'] = $data_v['pis'];
                    $data_save['cpf'] = $cpf;
                    $data_save['data_nascimento'] = $data_nascimento;
                    $data_save['nome_mae'] = $data_v['nome_mae'];
                    $data_save['ocupacao'] = $data_v['cargo'];
                    $data_save['endereco'] = $data_v['rua'] . ' ' . $data_v['num'] . ' ' . $data_v['complemento'];
                    $data_save['bairro'] = $data_v['bairro'];
                    $data_save['cidade'] = $data_v['cidade'];
                    $data_save['estado'] = $data_v['uf'];
                    $data_save['cep'] = $data_v['cep'];
                    $data_save['telefone'] = $data_v['telefone'];
                    $data_save['telefone1'] = $data_v['telefone1'];
                    $data_save['telefone2'] = $data_v['telefone2'];
                    $data_save['situacao'] = 'Ativo';
                    $data_save['status'] = 1;
                    $data_save['importacao_id'] = $dataImp['id'];
                    $data_save['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save['data_cadastro'] = $dataImp['data_cadastro'];

                    if (!$this->Beneficiario->save($data_save)) {
                        $this->erro[] = array('linha' => $data_k, 'descricao' => ' Erro ao criar o beneficiário');
                    } else {
                        $beneficiario_id = $this->Beneficiario->id;
                    }
                }
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar o beneficiário.</i>";
            }


            $data_inicio_afastamento = null;
            if ($data_v['data_inicio_afastamento'] != '') {
                $data_inicio_afastamento = $this->valida_data($data_v['data_inicio_afastamento'], 'Data de Início do Afastamento');
            }

            #$data_inicio_afastamento = $this->Funcoes->dateToDb($data_v['data_inicio_afastamento']);
            #$data_inicio_afastamento = $this->valida_dado($data_inicio_afastamento,'data_inicio_afastamento');

            $data_fim_afastamento = $this->Funcoes->dateToDb($data_v['data_fim_afastamento']);
            $data_fim_afastamento = $this->valida_dado($data_fim_afastamento, 'data_fim_afastamento');




            #CASO EXISTA O BENEFICIARIO AFASTAMENTO, IDENTIFICAR A SITUACAO PARA SALVAR ELA. 
            #REGRA - ATUALIZACAR SE FOR DIFERNTE (ATUALIZAR: DATA DE ATUALIZACAO - DATA DE CADASTRO )

            if (count($this->erro_linha) > 0) {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i>".implode("<br>",$this->erro_linha)."</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => $this->erro_linha);
                continue;
            }

            #VALIDAÇÃO DE REPETIÇÃO 
            #$afastArr = $this->Afastado->find('first',array('conditions'=>array('beneficiario_id'=>$beneficiario_id,'data_inicio_afastamento'=>$data_inicio_afastamento,'data_fim_afastamento'=>$data_fim_afastamento) , 'fields'=>'id,situacao','recursive'=>-1 ));
            $afastArr = $this->Afastado->find('first', array('conditions' => array('beneficiario_id' => $beneficiario_id, 'data_inicio_afastamento' => $data_inicio_afastamento), 'fields' => 'id,situacao', 'recursive' => -1));

            $dataS = array();
            if (count($afastArr) > 0) {
                // if($afastArr['Afastado']['situacao'] == $data_v['situacao']){
                //     continue;
                // }
                $dataS['id'] = $afastArr['Afastado']['id'];
                // $dataS['data_inicio_afastamento']   = $afastArr['Afastado']['data_inicio_afastamento'];
                // $dataS['beneficiario_id']           = $afastArr['Afastado']['beneficiario_id'];
                // $dataS['usuario_atualizacao_id'] = $this->usuario_id;
                // $dataS['data_atualizacao']  = $dataImp['data_cadastro'];
            } else {
                $this->Afastado->create();
                $dataS['importacao_id']      = $dataImp['id'];
                $dataS['empresa_id']         = $empresa_id;
                $dataS['data_inicio_afastamento']   = $data_inicio_afastamento;
                $dataS['beneficiario_id']           = $beneficiario_id;
                $dataS['cid']                       = $data_v['cid'];
                $dataS['tipo_afastamento']          = $data_v['tipo_afastamento'];
                $dataS['assistencia_medica']        = $data_v['assistencia_medica'];
                if (isset($data_v['plano_assistencia_medica'])) {
                    $dataS['plano_assistencia_medica']  = $data_v['plano_assistencia_medica'];
                }
                $dataS['data_cadastro']      = $dataImp['data_cadastro'];
                $dataS['usuario_criador_id'] = $this->usuario_id;
                $dataS['status'] = 1;
            }

            $dataS['data_fim_afastamento']      = $data_fim_afastamento;
            $dataS['situacao'] = $data_v['situacao'];



            if (!$this->Afastado->save($dataS)) {
                #$this->erro[] = 'ERRO CADASTRO DE AFASTAMENTO '.$data_k; #GERAR LOG ERRO
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'ERRO CADASTRO DE AFASTAMENTO ' . $data_k);
                $this->erro_exec = true;
            }



            if ($this->erro_exec) {
                throw new Exception();
            }
        }
    }


    public function admin_carga_beneficio_previdenciario($data_save, $dataImp)
    {
        $this->loadModel('Beneficiario');
        $this->loadModel('BeneficioPrevidenciario');
        $this->loadModel('Empresa');
        $this->loadModel('EspecieBp');


        foreach ($data_save as $data_k => $data_v) {

            if (count($data_v) == 0) {
                if (count($data_save) != $data_k) {
                    #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- Linha Vazia.</i>";
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'Linha Vazia');
                }
                continue;
            }


            #INICIO - VALIDAÇÃO
            $this->erro_linha   = array();


            $nome = trim($data_v['nome']);
            if ($nome == '') {
                $this->erro_linha[] = "- CAMPO NOME É OBRIGATÓRIO.";
            }

            // $cpf = trim($data_v['cpf']);
            // $cpf = str_replace(' ','',$cpf);
            // $cpf = str_replace('.','',$cpf);
            // $cpf = str_replace('-','',$cpf);
            // if($cpf == ''){
            //     #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- CPF Obrigatório.</i>";
            //     $this->erro[] = array('linha'=>$data_k,'descricao'=>'CPF Obrigatório');
            //     continue;
            // }
            // $cpf = str_pad($cpf, 11,STR_PAD_LEFT);
            // #$cpf = $this->Funcoes->monta_cpf($cpf);

            $cpf = trim($data_v['cpf']);
            $cpf = str_replace(' ', '', $cpf);
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            if ($cpf == '') {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- CPF Obrigatório.</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF Obrigatório');
                continue;
            }
            if (strlen($cpf) != 11) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF deve ser somente números e ter exatamente 11 números');
                continue;
            }

            $cliente_id = (isset($data_v['cliente_id']) && $data_v['cliente_id'] != '') ? $data_v['cliente_id'] :  $this->Session->read('Auth.Usuario.cliente_id');

            $cnpj = trim($data_v['cnpj']);
            $cnpj = str_replace(' ', '', $cnpj);
            $cnpj = str_replace('.', '', $cnpj);
            $cnpj = str_replace('-', '', $cnpj);
            $cnpj = str_replace('/', '', $cnpj);
            $cnpj = str_replace(' ', '', $cnpj);

            if ($cnpj == '') {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- CNPJ Obrigatório.</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ Obrigatório');
                continue;
            }

            if (strlen($cnpj) != 14) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ deve ser somente números e ter exatamente 14 números');
                continue;
            }
            #$cnpj = str_pad($cnpj, 14,STR_PAD_LEFT);

            $data_nascimento = $this->Funcoes->dateToDb($data_v['data_nascimento']);
            $data_nascimento = $this->valida_dado($data_nascimento, 'data_nascimento');


            #CRIAÇÃO DE EMPRESA CASO NÃO EXISTA
            try {

                $empresaArr = $this->Empresa->find('first', array('conditions' => array('cnpj' => $cnpj, 'cliente_id' => $cliente_id), 'recursive' => -1));
                if (count($empresaArr) > 0) {
                    $empresa_id = $empresaArr['Empresa']['id'];
                } else {
                    $razao_social = $filial = 'Z - Criado via Importação!';
                    if (isset($data_v['razao_social'])) {
                        $razao_social = $data_v['razao_social'];
                    }
                    if (isset($data_v['filia'])) {
                        $filial = $data_v['filia'];
                    }

                    $this->Empresa->create();
                    $data_save_emp = array();
                    $data_save_emp['id'] = '';
                    $data_save_emp['cliente_id'] = $cliente_id;
                    $data_save_emp['cnpj'] = $cnpj;
                    $data_save_emp['razao_social'] = $razao_social;
                    $data_save_emp['nome'] = $filial;
                    $data_save_emp['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save_emp['data_cadastro'] = $dataImp['data_cadastro'];


                    if (!$this->Empresa->save($data_save_emp)) {
                        $this->erro_linha[] = "- Erro ao criar a empresa.";
                    } else {
                        $empresa_id = $this->Empresa->id;
                    }
                }
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar a empresa.";
            }











            #CRIAÇÃO DE BENEFICIÁRIO CASO NÃO EXISTA    
            try {
                $beneficiarioArr = $this->Beneficiario->find('first', array('conditions' => array('cpf' => $cpf), 'recursive' => -1));

                if (count($beneficiarioArr) > 0) {
                    $beneficiario_id = $beneficiarioArr['Beneficiario']['id'];
                } else {
                    $this->Beneficiario->create();
                    $data_save = array();
                    $data_save['id'] = '';
                    $data_save['cliente_id'] = $cliente_id;
                    $data_save['empresa_id'] = $empresa_id;
                    $data_save['nome'] = $data_v['nome'];
                    $data_save['cpf'] = $cpf;
                    $data_save['data_nascimento'] = $data_nascimento;
                    $data_save['importacao_id'] = $dataImp['id'];
                    $data_save['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save['data_cadastro'] = $dataImp['data_cadastro'];

                    if (!$this->Beneficiario->save($data_save)) {
                        $this->erro_linha[] = "- Erro ao criar o beneficiário.</i>";
                    } else {
                        $beneficiario_id = $this->Beneficiario->id;
                    }
                }
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar o beneficiário.</i>";
            }


            #CRIAÇÃO DE EMPRESA CASO NÃO EXISTA
            try {
                $empresaArr = $this->Empresa->find('first', array('conditions' => array('cnpj' => $cnpj, 'cliente_id' => $cliente_id), 'recursive' => -1));

                if (count($empresaArr) > 0) {
                    $empresa_id = $empresaArr['Empresa']['id'];
                } else {
                    $razao_social = $filial = 'Z - Criado via Importação!';
                    if (isset($data_v['razao_social'])) {
                        $razao_social = $data_v['razao_social'];
                    }
                    if (isset($data_v['filia'])) {
                        $filial = $data_v['filia'];
                    }

                    $this->Empresa->create();
                    $data_save = array();
                    $data_save['id'] = '';
                    $data_save['cliente_id'] = $cliente_id;
                    $data_save['cnpj'] = $data_v['cnpj'];
                    $data_save['razao_social'] = $razao_social;
                    $data_save['nome'] = $filial;
                    $data_save['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save['data_cadastro'] = $dataImp['data_cadastro'];

                    if (!$this->Empresa->save($data_save)) {
                        $this->erro_linha[] = "Erro ao criar a empresa.</i>";
                    } else {
                        $empresa_id = $this->Empresa->id;
                    }
                }

                // $this->erro_linha[] = "EMPRESA INEXISTENTE.";
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar a empresa.";
            }



            #$data_afastamento = $this->Funcoes->dateToDb($data_v['data_afastamento']);
            #$data_afastamento = $this->valida_dado($data_afastamento,'data_afastamento');

            $especieArr = array();
            $especie_id = '';
            if (strlen($data_v['especie']) > 0) {
                $especieArr = explode(' - ', $data_v['especie']);
                if (count($especieArr) < 2) {
                    $this->erro_linha[] = "- Espécie sem referência.</i>";
                } else {
                    $especie_id = $especieArr[0];
                    unset($especieArr[0]);
                    $especie_nome = implode(' - ', $especieArr);

                    $especieBpArr = $this->EspecieBp->find('first', array('conditions' => array('id' => $especie_id), 'recursive' => -1));

                    if (count($especieBpArr) == 0) {
                        $this->EspecieBp->create();
                        $data_save = array();
                        $data_save['id'] = $especie_id;
                        $data_save['nome'] = $especie_nome;
                        if (!$this->EspecieBp->save($data_save)) {
                            $this->erro_linha[] = "- Náo foi possível salvar a nova espécie.</i>";
                        }
                        #krumo($this->EspecieBp->id);

                    }
                }
            } else {
                $this->erro_linha[] = "- Espécie inexistente.</i>";
            }


            $data_entrada_requerimento = $this->Funcoes->dateToDb($data_v['data_entrada_requerimento']);
            $data_entrada_requerimento = $this->valida_dado($data_entrada_requerimento, 'data_entrada_requerimento');

            $data_inicio = $this->Funcoes->dateToDb($data_v['data_inicio']);
            $data_inicio = $this->valida_dado($data_inicio, 'data_inicio');

            $data_despacho = $this->Funcoes->dateToDb($data_v['data_despacho']);
            $data_despacho = $this->valida_dado($data_despacho, 'data_despacho');

            $data_despacho = $this->Funcoes->dateToDb($data_v['data_despacho']);
            $data_despacho = $this->valida_dado($data_despacho, 'data_despacho');

            $data_realizacao_pericia = $this->Funcoes->dateToDb($data_v['data_realizacao_pericia']);
            $data_realizacao_pericia = $this->valida_dado($data_realizacao_pericia, 'data_realizacao_pericia');

            $data_limite = $this->Funcoes->dateToDb($data_v['data_limite']);
            $data_limite = $this->valida_dado($data_limite, 'data_limite');

            $data_indeferimento = $this->Funcoes->dateToDb($data_v['data_indeferimento']);
            $data_indeferimento = $this->valida_dado($data_indeferimento, 'data_indeferimento');

            $data_cessacao = $this->Funcoes->dateToDb($data_v['data_cessacao']);
            $data_cessacao = $this->valida_dado($data_cessacao, 'data_cessacao');


            $nb = '';
            if ($data_v['nb'] != '') {
                $nb = $this->Funcoes->bigintval($data_v['nb']);
            }

            $nit = '';
            if ($data_v['nit'] != '') {
                $nit = $this->Funcoes->bigintval($data_v['nit']);
            }






            #VALIDAÇÃO DE REPETIÇÃO
            $existBP = $this->BeneficioPrevidenciario->find('count', array('conditions' => array('beneficiario_id' => $beneficiario_id, 'especie_bp_id' => $especie_id, 'situacao' => $data_v['situacao'], 'data_cessacao' => $data_cessacao), 'recursive' => -1));
            if ($existBP > 0) {
                $this->warning_linha[] = "- Linha:{data_k} Já foi salvo anteriormente!";
                continue;
            }


            if (count($this->erro_linha) > 0) {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i>".implode("<br>",$this->erro_linha)."</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => $this->erro_linha);
                continue;
            }
            #FIM - VALIDAÇÃO





            try {
                $this->BeneficioPrevidenciario->create();
                $dataS = array();
                $dataS['id']                        = '';
                $dataS['empresa_id']                = $empresa_id;
                $dataS['beneficiario_id']           = $beneficiario_id;
                $dataS['nb']                        = $nb;
                $dataS['nit']                       = $nit;
                $dataS['especie']                   = $especie_nome;
                $dataS['especie_bp_id']             = $especie_id;
                $dataS['situacao']                  = $data_v['situacao'];
                $dataS['data_entrada_requerimento'] = $data_entrada_requerimento;
                $dataS['data_inicio']               = $data_inicio;
                $dataS['data_despacho']             = $data_despacho;
                $dataS['data_realizacao_pericia']   = $data_realizacao_pericia;
                $dataS['conclusao_pericia_medica']  = $data_v['conclusao_pericia_medica'];
                $dataS['data_limite']               = $data_limite;
                $dataS['data_indeferimento']        = $data_indeferimento;
                $dataS['data_cessacao']             = $data_cessacao;
                $dataS['nexo_tecnico']              = $data_v['nexo_tecnico'];

                $dataS['importacao_id']     = $dataImp['id'];
                $dataS['data_cadastro']     = $dataImp['data_cadastro'];
                $dataS['usuario_criador_id'] = $this->usuario_id;
                $dataS['usuario_atualizacao_id'] = NULL;
                $dataS['data_atualizacao']  = NULL;
                $dataS['status'] = 1;

                // krumo($data_v['nb']);
                // krumo($data_v['nit']);
                // krumo($nb);
                // krumo($nit);
                // krumo($dataS);
                // exit;
                #$this->BeneficioPrevidenciario->save($dataS);

                #krumo($dataS);
                #krumo($this->BeneficioPrevidenciario->save($dataS));
                #exit;

                if (!$this->BeneficioPrevidenciario->save($dataS)) {
                    #$this->erro[] = 'ERRO CADASTRO DE BENEFICIO PREVIDENCIARIO '.$data_k; #GERAR LOG ERRO
                    $this->erro[] = array('linha' => '', 'descricao' => 'ERRO CADASTRO DE BENEFICIO PREVIDENCIARIO - SAVE - ' . $data_k);
                    $this->erro_exec = true;
                }
            } catch (Exception $ex) {

                // echo $ex->queryString;
                // debug($this->Beneficiario->getDataSource()->getLog(false, false));
                // exit;

                #$this->erro[] = 'ERRO CADASTRO DE BENEFICIO PREVIDENCIARIO '.$data_k; #GERAR LOG ERRO
                $this->erro[] = array('linha' => '', 'descricao' => 'ERRO CADASTRO DE BENEFICIO PREVIDENCIARIO - BEFORE SAVE - ' . $data_k);
                $this->erro_exec = true;
            }



            if ($this->erro_exec) {
                throw new Exception();
            }
        }
    }


    public function admin_carga_absenteismo($data_save, $dataImp)
    {
        $this->loadModel('Beneficiario');
        $this->loadModel('Absenteismo');
        $this->loadModel('Empresa');


        foreach ($data_save as $data_k => $data_v) {

            if (count($data_v) == 0) {
                if (count($data_save) != $data_k) {
                    #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- Linha Vazia.</i>";
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'Linha Vazia');
                }
                continue;
            }


            #INICIO - VALIDAÇÃO
            $this->erro_linha   = array();


            $nome = trim($data_v['nome_colaborador']);
            if ($nome == '') {
                $this->erro_linha[] = "- CAMPO NOME É OBRIGATÓRIO.";
            }


            //    $cpf = trim($data_v['cpf']);
            //    $cpf = str_replace(' ','',$cpf);
            //    $cpf = str_replace('.','',$cpf);
            //    $cpf = str_replace('-','',$cpf);
            //    if($cpf == ''){
            //        #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- CPF Obrigatório.</i>";
            //        $this->erro[] = array('linha'=>$data_k,'descricao'=>'CPF Obrigatório');
            //        continue;
            //    }
            //    $cpf = str_pad($cpf, 11,STR_PAD_LEFT);
            //    #$cpf = $this->Funcoes->monta_cpf($cpf);


            $cpf = trim($data_v['cpf']);
            $cpf = str_replace(' ', '', $cpf);
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            if ($cpf == '') {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- CPF Obrigatório.</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF Obrigatório');
                continue;
            }
            if (strlen($cpf) != 11) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF deve ser somente números e ter exatamente 11 números');
                continue;
            }


            $cliente_id = (isset($data_v['cliente_id']) && $data_v['cliente_id'] != '') ? $data_v['cliente_id'] :  $this->Session->read('Auth.Usuario.cliente_id');




            $cnpj = trim($data_v['cnpj']);
            $cnpj = str_replace(' ', '', $cnpj);
            $cnpj = str_replace('.', '', $cnpj);
            $cnpj = str_replace('-', '', $cnpj);
            $cnpj = str_replace('/', '', $cnpj);
            $cnpj = str_replace(' ', '', $cnpj);

            if ($cnpj == '') {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- CNPJ Obrigatório.</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ Obrigatório');
                continue;
            }
            if (strlen($cnpj) != 14) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ deve ser somente números e ter exatamente 14 números');
                continue;
            }
            #$cnpj = str_pad($cnpj, 14,STR_PAD_LEFT);


            #CRIAÇÃO DE EMPRESA CASO NÃO EXISTA
            #$cliente_id = $this->Session->read('Auth.Usuario.cliente_id');
            try {
                $this->loadModel('Empresa');
                $empresaArr = $this->Empresa->find('first', array('conditions' => array('cnpj' => $cnpj, 'cliente_id' => $cliente_id), 'recursive' => -1));

                if (count($empresaArr) > 0) {
                    $empresa_id = $empresaArr['Empresa']['id'];
                } else {
                    $razao_social = $filial = 'Z - Criado via Importação!';
                    if (isset($data_v['razao_social'])) {
                        $razao_social = $data_v['razao_social'];
                    }
                    if (isset($data_v['filia'])) {
                        $filial = $data_v['filia'];
                    }

                    $this->Empresa->create();
                    $data_save = array();
                    $data_save['id'] = '';
                    $data_save['cliente_id'] = $cliente_id;
                    $data_save['cnpj'] = $cnpj;
                    $data_save['razao_social'] = $razao_social;
                    $data_save['nome'] = $filial;
                    $data_save['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save['data_cadastro'] = $dataImp['data_cadastro'];

                    if (!$this->Empresa->save($data_save)) {
                        $this->erro_linha[] = "Erro ao criar a empresa.</i>";
                    } else {
                        $empresa_id = $this->Empresa->id;
                    }
                }

                // $this->erro_linha[] = "EMPRESA INEXISTENTE.";
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar a empresa.";
            }


            $data_saida = $this->Funcoes->dateToDb($data_v['data_saida']);
            $data_saida = $this->valida_dado($data_saida, 'data_saida');

            $data_retorno = $this->Funcoes->dateToDb($data_v['data_retorno']);
            $data_retorno = $this->valida_dado($data_retorno, 'data_retorno');

            $dias = $data_v['qtde_dias_atestado'];

            $beneficiarioArr = $this->Beneficiario->find('first', array('conditions' => array("REPLACE(REPLACE(cpf,'.',''),'-','') = '{$cpf}'"), 'recursive' => -1));


            if (count($beneficiarioArr) > 0) {
                $beneficiario_id = $beneficiarioArr['Beneficiario']['id'];
            } else {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'Colaborador ' . $data_v['nome_colaborador'] . ' Inexistente');
                continue;
            }



            #VALIDAÇÃO DE REPETIÇÃO
            $existBP = $this->Absenteismo->find('count', array(
                'conditions' => array(
                    'beneficiario_id' => $beneficiario_id,
                    'especialidade_id' => $data_v['especialidade_id'],
                    'emissor_id' => $data_v['emissor_id'],
                    'data_saida' => $data_saida,
                    'tipo_absenteismo_id' => $data_v['tipo_absenteismo_id']
                ),
                'recursive' => -1
            ));



            if ($existBP > 0) {
                $this->warning_linha[] = "- Linha:{data_k} Já foi salvo anteriormente!";
                continue;
            }

            if ($data_saida == '') {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'Data de saída Obrigatória');
                continue;
            }

            if ($data_retorno == '' && $dias == '') {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'É Obrigatório a Data de Retonro ou Quantidade de dias de atestado');
                continue;
            }



            if (count($this->erro_linha) > 0) {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i>".implode("<br>",$this->erro_linha)."</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => $this->erro_linha);
                continue;
            }
            #FIM - VALIDAÇÃO



            if ($data_saida != '' && $data_retorno != '' && $dias == '') {
                $dias = $this->Funcoes->dias_entre_datas($data_saida, $data_retorno);
            }

            if ($data_saida != '' && $dias != '') {
                $data_retorno = $this->Funcoes->calculaData('Y-m-d', $data_saida . ' 00:00:01', ' + ' . $dias . ' DAY');
            }


            try {
                $this->Absenteismo->create();
                $dataS = array();
                $dataS['id']                         = '';
                $dataS['beneficiario_id']            = $beneficiario_id;
                $dataS['matricula']                  = $data_v['matricula'];
                $dataS['empresa_id']                 = $empresa_id;
                $dataS['cpf']                        = $cpf;
                $dataS['documento_id']               = $data_v['documento_id'];
                $dataS['motivo_id']                  = $data_v['motivo_id'];
                $dataS['hospital_clinica']           = $data_v['hospital_clinica'];
                $dataS['nome_colaborador']           = $data_v['nome_colaborador'];
                $dataS['data_saida']                 = $data_saida;
                $dataS['data_retorno']               = $data_retorno;
                $dataS['qtde_dias_atestado']         = $dias;
                $dataS['hora_saida']                 = $data_v['hora_saida'];
                $dataS['hora_retorno']               = $data_v['hora_retorno'];
                $dataS['cid']                        = $data_v['cid'];
                $dataS['especialidade_id']           = $data_v['especialidade_id'];
                $dataS['emissor_id']                 = $data_v['emissor_id'];
                $dataS['profissional']               = $data_v['profissional'];
                $dataS['num_crm']                    = $data_v['num_crm'];
                $dataS['tipo_absenteismo_id']        = $data_v['tipo_absenteismo_id'];
                $dataS['importacao_id']              = $dataImp['id'];
                $dataS['data_cadastro']              = $dataImp['data_cadastro'];
                $dataS['usuario_criador_id']         = $this->usuario_id;
                $dataS['usuario_atualizacao_id']     = NULL;
                $dataS['data_atualizacao']           = NULL;
                $dataS['status'] = 1;

                #var_dump($dataS);


                #echo $dataS['documento_id'];
                if (!$this->Absenteismo->save($dataS)) {
                    #$this->erro[] = 'ERRO CADASTRO DE BENEFICIO PREVIDENCIARIO '.$data_k; #GERAR LOG ERRO
                    $this->erro[] = array('linha' => '', 'descricao' => 'ERRO CADASTRO DE ABSENTEISMO ' . $data_k);
                    $this->erro_exec = true;
                }
            } catch (Exception $ex) {
                //    echo $ex->queryString;
                //    debug($this->Absenteismo->getDataSource()->getLog(false, false));


                #$this->erro[] = 'ERRO CADASTRO DE BENEFICIO PREVIDENCIARIO '.$data_k; #GERAR LOG ERRO
                $this->erro[] = array('linha' => '', 'descricao' => 'ERRO CADASTRO ABSENTEISMO ' . $data_k);
                $this->erro_exec = true;
            }


            if ($this->erro_exec) {
                throw new Exception();
            }
        }
    }


    public function admin_carga_sinistro($data_save, $dataImp)
    {
        $this->loadModel('Sinistro');
        $this->loadModel('Empresa');
        $this->loadModel('Beneficiario');



        foreach ($data_save as $data_k => $data_v) {

            if (count($data_v) == 0) {
                if (count($data_save) != $data_k) {
                    #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- Linha Vazia.</i>";
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'Linha Vazia');
                }
                continue;
            }



            #INICIO - VALIDAÇÃO
            $this->erro_linha   = array();





            #SINISTRO NOVO
            #importacao_id (CRIAÇÃO ID) TB
            #cliente_id (session) 
            #empresa_id (CRIAÇÃO) A PARTIR DE DADOS
            #beneficio_id (dorpdown)
            #beneficiario (CRIAÇÃO) A PARTIR DE DADOS
            #procedimento_id (CRIAÇÃO) A PARTIR DE DADOS
            #usuario_id (quem fez upload )
            #usuario_atualziacao_id (quem fez atualização)


            #OBRIGATÓRIO EMPRESA (cliente_id, cnpj, razao_social, filial)
            #OBRIGATÓRIO BENEFICIARIO (cliente_id, empresa_id,cpf_beneficiario,nome_beneficiario,dt_nascimento,sexo)
            #OBRIGATÓRIO PROCEDIMENTO (operadora_id)
            #OBRIGATÓRIO GERAL (matricula, numero_carteira, sexo, elegibilidade, nome_prestador, ds_plano, ds_procedimento, valor, dt_evento, dt_pagamento, )





            krumo($data_v);
            exit;




            $nome = trim($data_v['nome_colaborador']);
            if ($nome == '') {
                $this->erro_linha[] = "- CAMPO NOME É OBRIGATÓRIO.";
            }

            $cpf = trim($data_v['cpf']);
            $cpf = str_replace(' ', '', $cpf);
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            if ($cpf == '') {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- CPF Obrigatório.</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF Obrigatório');
                continue;
            }
            if (strlen($cpf) != 11) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF deve ser somente números e ter exatamente 11 números');
                continue;
            }
            #$cpf = str_pad($cpf, 11,STR_PAD_LEFT);
            #$cpf = $this->Funcoes->monta_cpf($cpf);


            #valida situacao
            if ($data_v['situacao'] == '') {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'Situação obrigatória');
                continue;
            } else {
                if (!in_array($data_v['situacao'], array('A', 'RT'))) {
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'Tipo de Situação inválida');
                    continue;
                }
            }

            #krumo($data_v['data_nascimento']);

            $data_nascimento = $this->valida_dado($data_v['data_nascimento'], 'data_nascimento');
            #krumo($data_nascimento);
            #exit;

            #$data_nascimento = $this->Funcoes->dateToDb($data_nascimento);

            #$sexo            = $this->valida_dado($data_v['sexo'],'sexo');

            $telefone = $data_v['telefone'];




            $cnpj = trim($data_v['cnpj']);
            $cnpj = str_replace(' ', '', $cnpj);
            $cnpj = str_replace('.', '', $cnpj);
            $cnpj = str_replace('-', '', $cnpj);
            $cnpj = str_replace('/', '', $cnpj);
            if ($cnpj == '') {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ Obrigatório');
                continue;
            }
            if (strlen($cnpj) != 14) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ deve ser somente números e ter exatamente 14 números');
                continue;
            }
            #$cnpj = str_pad($cnpj, 14,STR_PAD_LEFT);



            #$return_valida_cpf = $this->busca_cpf($cpf); #GRAVA NO ERRO_LINHA #SE JA EXISTE
            #$valor_do_seguro  = $this->Funcoes->moedaToDb($data_v['valor_do_seguro']);



            #CRIAÇÃO DE EMPRESA CASO NÃO EXISTA
            try {

                $empresaArr = $this->Empresa->find('first', array('conditions' => array('cnpj' => $cnpj, 'cliente_id' => $this->cliente_id), 'recursive' => -1));
                if (count($empresaArr) > 0) {
                    $empresa_id = $empresaArr['Empresa']['id'];
                } else {
                    $razao_social = $filial = 'Z - Criado via Importação!';
                    // if(isset($data_v['razao_social'])){
                    //     $razao_social = $data_v['razao_social'];
                    // }
                    // if(isset($data_v['filia'])){
                    //     $filial = $data_v['filia'];
                    // }

                    $this->Empresa->create();
                    $data_save_emp = array();
                    $data_save_emp['id'] = '';
                    $data_save_emp['cliente_id'] = $this->cliente_id;
                    $data_save_emp['cnpj'] = $data_v['cnpj'];
                    $data_save_emp['razao_social'] = $razao_social;
                    $data_save_emp['nome'] = $filial;
                    $data_save_emp['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save_emp['data_cadastro'] = $dataImp['data_cadastro'];


                    if (!$this->Empresa->save($data_save_emp)) {
                        $this->erro_linha[] = "- Erro ao criar a empresa.";
                    } else {
                        $empresa_id = $this->Empresa->id;
                    }
                }
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar a empresa.";
            }
            #FIM CRIAÇÃO EMPRESA





            #CRIAÇÃO DE BENEFICIÁRIO CASO NÃO EXISTA
            try {
                $beneficiarioArr = $this->Beneficiario->find('first', array('conditions' => array('cpf' => $cpf), 'recursive' => -1));
                if (count($beneficiarioArr) > 0) {
                    $beneficiario_id = $beneficiarioArr['Beneficiario']['id'];
                } else {
                    $this->Beneficiario->create();
                    $data_save = array();
                    $data_save['id'] = '';
                    $data_save['cliente_id'] = $this->Session->read('Auth.Usuario.cliente_id');
                    $data_save['empresa_id'] = $empresa_id;
                    $data_save['nome'] = $data_v['nome_colaborador'];
                    $data_save['pis'] = $data_v['pis'];
                    $data_save['cpf'] = $cpf;
                    $data_save['data_nascimento'] = $data_nascimento;
                    $data_save['nome_mae'] = $data_v['nome_mae'];
                    $data_save['ocupacao'] = $data_v['cargo'];
                    $data_save['endereco'] = $data_v['rua'] . ' ' . $data_v['num'] . ' ' . $data_v['complemento'];
                    $data_save['bairro'] = $data_v['bairro'];
                    $data_save['cidade'] = $data_v['cidade'];
                    $data_save['estado'] = $data_v['uf'];
                    $data_save['cep'] = $data_v['cep'];
                    $data_save['telefone'] = $data_v['telefone'];
                    $data_save['telefone1'] = $data_v['telefone1'];
                    $data_save['telefone2'] = $data_v['telefone2'];
                    $data_save['situacao'] = 'Ativo';
                    $data_save['status'] = 1;
                    $data_save['importacao_id'] = $dataImp['id'];
                    $data_save['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save['data_cadastro'] = $dataImp['data_cadastro'];

                    if (!$this->Beneficiario->save($data_save)) {
                        $this->erro[] = array('linha' => $data_k, 'descricao' => ' Erro ao criar o beneficiário');
                    } else {
                        $beneficiario_id = $this->Beneficiario->id;
                    }
                }
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar o beneficiário.</i>";
            }


            $data_inicio_afastamento = null;
            if ($data_v['data_inicio_afastamento'] != '') {
                $data_inicio_afastamento = $this->valida_data($data_v['data_inicio_afastamento'], 'Data de Início do Afastamento');
            }

            #$data_inicio_afastamento = $this->Funcoes->dateToDb($data_v['data_inicio_afastamento']);
            #$data_inicio_afastamento = $this->valida_dado($data_inicio_afastamento,'data_inicio_afastamento');

            $data_fim_afastamento = $this->Funcoes->dateToDb($data_v['data_fim_afastamento']);
            $data_fim_afastamento = $this->valida_dado($data_fim_afastamento, 'data_fim_afastamento');




            #CASO EXISTA O BENEFICIARIO AFASTAMENTO, IDENTIFICAR A SITUACAO PARA SALVAR ELA. 
            #REGRA - ATUALIZACAR SE FOR DIFERNTE (ATUALIZAR: DATA DE ATUALIZACAO - DATA DE CADASTRO )

            if (count($this->erro_linha) > 0) {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i>".implode("<br>",$this->erro_linha)."</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => $this->erro_linha);
                continue;
            }

            #VALIDAÇÃO DE REPETIÇÃO 
            $afastArr = $this->Afastado->find('first', array('conditions' => array('beneficiario_id' => $beneficiario_id, 'data_inicio_afastamento' => $data_inicio_afastamento, 'data_fim_afastamento' => $data_fim_afastamento), 'fields' => 'id,situacao', 'recursive' => -1));

            $dataS = array();
            if (count($afastArr) > 0) {
                if ($afastArr['Afastado']['situacao'] == $data_v['situacao']) {
                    continue;
                }
                $dataS['id'] = $afastArr['Afastado']['id'];
                $dataS['usuario_atualizacao_id'] = $this->usuario_id;
                $dataS['data_atualizacao']  = $dataImp['data_cadastro'];
            } else {
                $this->Afastado->create();
                $dataS['importacao_id']      = $dataImp['id'];
                $dataS['empresa_id']         = $empresa_id;
                $dataS['data_cadastro']      = $dataImp['data_cadastro'];
                $dataS['usuario_criador_id'] = $this->usuario_id;
                $dataS['status'] = 1;
            }


            $dataS['beneficiario_id']           = $beneficiario_id;
            $dataS['data_inicio_afastamento']   = $data_inicio_afastamento;
            $dataS['data_fim_afastamento']      = $data_fim_afastamento;
            $dataS['cid']                       = $data_v['cid'];
            $dataS['tipo_afastamento']          = $data_v['tipo_afastamento'];
            $dataS['assistencia_medica']        = $data_v['assistencia_medica'];
            if (isset($data_v['plano_assistencia_medica'])) {
                $dataS['plano_assistencia_medica']  = $data_v['plano_assistencia_medica'];
            }

            $dataS['situacao'] = $data_v['situacao'];



            if (!$this->Afastado->save($dataS)) {
                #$this->erro[] = 'ERRO CADASTRO DE AFASTAMENTO '.$data_k; #GERAR LOG ERRO
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'ERRO CADASTRO DE AFASTAMENTO ' . $data_k);
                $this->erro_exec = true;
            }



            if ($this->erro_exec) {
                throw new Exception();
            }
        }
    }

    public function admin_carga_faturamento($data_save, $dataImp)
    {
        $this->loadModel('Beneficiario');
        $this->loadModel('Afastado');
        $this->loadModel('Empresa');


        foreach ($data_save as $data_k => $data_v) {

            if (count($data_v) == 0) {
                if (count($data_save) != $data_k) {
                    #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- Linha Vazia.</i>";
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'Linha Vazia');
                }
                continue;
            }



            #INICIO - VALIDAÇÃO
            $this->erro_linha   = array();


            $nome = trim($data_v['nome_colaborador']);
            if ($nome == '') {
                $this->erro_linha[] = "- CAMPO NOME É OBRIGATÓRIO.";
            }

            $cpf = trim($data_v['cpf']);
            $cpf = str_replace(' ', '', $cpf);
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            if ($cpf == '') {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i><br>- CPF Obrigatório.</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF Obrigatório');
                continue;
            }
            if (strlen($cpf) != 11) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CPF deve ser somente números e ter exatamente 11 números');
                continue;
            }
            #$cpf = str_pad($cpf, 11,STR_PAD_LEFT);
            #$cpf = $this->Funcoes->monta_cpf($cpf);


            #valida situacao
            if ($data_v['situacao'] == '') {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'Situação obrigatória');
                continue;
            } else {
                if (!in_array($data_v['situacao'], array('A', 'RT'))) {
                    $this->erro[] = array('linha' => $data_k, 'descricao' => 'Tipo de Situação inválida');
                    continue;
                }
            }

            #krumo($data_v['data_nascimento']);

            $data_nascimento = $this->valida_dado($data_v['data_nascimento'], 'data_nascimento');
            #krumo($data_nascimento);
            #exit;

            #$data_nascimento = $this->Funcoes->dateToDb($data_nascimento);

            #$sexo            = $this->valida_dado($data_v['sexo'],'sexo');

            $telefone = $data_v['telefone'];




            $cnpj = trim($data_v['cnpj']);
            $cnpj = str_replace(' ', '', $cnpj);
            $cnpj = str_replace('.', '', $cnpj);
            $cnpj = str_replace('-', '', $cnpj);
            $cnpj = str_replace('/', '', $cnpj);
            if ($cnpj == '') {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ Obrigatório');
                continue;
            }
            if (strlen($cnpj) != 14) {
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'CNPJ deve ser somente números e ter exatamente 14 números');
                continue;
            }
            #$cnpj = str_pad($cnpj, 14,STR_PAD_LEFT);



            #$return_valida_cpf = $this->busca_cpf($cpf); #GRAVA NO ERRO_LINHA #SE JA EXISTE
            #$valor_do_seguro  = $this->Funcoes->moedaToDb($data_v['valor_do_seguro']);



            #CRIAÇÃO DE EMPRESA CASO NÃO EXISTA
            try {

                $empresaArr = $this->Empresa->find('first', array('conditions' => array('cnpj' => $cnpj, 'cliente_id' => $this->cliente_id), 'recursive' => -1));
                if (count($empresaArr) > 0) {
                    $empresa_id = $empresaArr['Empresa']['id'];
                } else {
                    $razao_social = $filial = 'Z - Criado via Importação!';
                    // if(isset($data_v['razao_social'])){
                    //     $razao_social = $data_v['razao_social'];
                    // }
                    // if(isset($data_v['filia'])){
                    //     $filial = $data_v['filia'];
                    // }

                    $this->Empresa->create();
                    $data_save_emp = array();
                    $data_save_emp['id'] = '';
                    $data_save_emp['cliente_id'] = $this->cliente_id;
                    $data_save_emp['cnpj'] = $data_v['cnpj'];
                    $data_save_emp['razao_social'] = $razao_social;
                    $data_save_emp['nome'] = $filial;
                    $data_save_emp['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save_emp['data_cadastro'] = $dataImp['data_cadastro'];


                    if (!$this->Empresa->save($data_save_emp)) {
                        $this->erro_linha[] = "- Erro ao criar a empresa.";
                    } else {
                        $empresa_id = $this->Empresa->id;
                    }
                }
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar a empresa.";
            }
            #FIM CRIAÇÃO EMPRESA





            #CRIAÇÃO DE BENEFICIÁRIO CASO NÃO EXISTA
            try {
                $beneficiarioArr = $this->Beneficiario->find('first', array('conditions' => array('cpf' => $cpf), 'recursive' => -1));
                if (count($beneficiarioArr) > 0) {
                    $beneficiario_id = $beneficiarioArr['Beneficiario']['id'];
                } else {
                    $this->Beneficiario->create();
                    $data_save = array();
                    $data_save['id'] = '';
                    $data_save['cliente_id'] = $this->Session->read('Auth.Usuario.cliente_id');
                    $data_save['empresa_id'] = $empresa_id;
                    $data_save['nome'] = $data_v['nome_colaborador'];
                    $data_save['pis'] = $data_v['pis'];
                    $data_save['cpf'] = $cpf;
                    $data_save['data_nascimento'] = $data_nascimento;
                    $data_save['nome_mae'] = $data_v['nome_mae'];
                    $data_save['ocupacao'] = $data_v['cargo'];
                    $data_save['endereco'] = $data_v['rua'] . ' ' . $data_v['num'] . ' ' . $data_v['complemento'];
                    $data_save['bairro'] = $data_v['bairro'];
                    $data_save['cidade'] = $data_v['cidade'];
                    $data_save['estado'] = $data_v['uf'];
                    $data_save['cep'] = $data_v['cep'];
                    $data_save['telefone'] = $data_v['telefone'];
                    $data_save['telefone1'] = $data_v['telefone1'];
                    $data_save['telefone2'] = $data_v['telefone2'];
                    $data_save['situacao'] = 'Ativo';
                    $data_save['status'] = 1;
                    $data_save['importacao_id'] = $dataImp['id'];
                    $data_save['usuario_criador_id'] = $dataImp['usuario_criador_id'];
                    $data_save['data_cadastro'] = $dataImp['data_cadastro'];

                    if (!$this->Beneficiario->save($data_save)) {
                        $this->erro[] = array('linha' => $data_k, 'descricao' => ' Erro ao criar o beneficiário');
                    } else {
                        $beneficiario_id = $this->Beneficiario->id;
                    }
                }
            } catch (Exception $ex) {
                $this->erro_linha[] = "- Erro ao criar o beneficiário.</i>";
            }


            $data_inicio_afastamento = null;
            if ($data_v['data_inicio_afastamento'] != '') {
                $data_inicio_afastamento = $this->valida_data($data_v['data_inicio_afastamento'], 'Data de Início do Afastamento');
            }

            #$data_inicio_afastamento = $this->Funcoes->dateToDb($data_v['data_inicio_afastamento']);
            #$data_inicio_afastamento = $this->valida_dado($data_inicio_afastamento,'data_inicio_afastamento');

            $data_fim_afastamento = $this->Funcoes->dateToDb($data_v['data_fim_afastamento']);
            $data_fim_afastamento = $this->valida_dado($data_fim_afastamento, 'data_fim_afastamento');




            #CASO EXISTA O BENEFICIARIO AFASTAMENTO, IDENTIFICAR A SITUACAO PARA SALVAR ELA. 
            #REGRA - ATUALIZACAR SE FOR DIFERNTE (ATUALIZAR: DATA DE ATUALIZACAO - DATA DE CADASTRO )

            if (count($this->erro_linha) > 0) {
                #$this->erro[] = "<h5>Linha {$data_k} </h5> <i>".implode("<br>",$this->erro_linha)."</i>";
                $this->erro[] = array('linha' => $data_k, 'descricao' => $this->erro_linha);
                continue;
            }

            #VALIDAÇÃO DE REPETIÇÃO 
            $afastArr = $this->Afastado->find('first', array('conditions' => array('beneficiario_id' => $beneficiario_id, 'data_inicio_afastamento' => $data_inicio_afastamento, 'data_fim_afastamento' => $data_fim_afastamento), 'fields' => 'id,situacao', 'recursive' => -1));

            $dataS = array();
            if (count($afastArr) > 0) {
                if ($afastArr['Afastado']['situacao'] == $data_v['situacao']) {
                    continue;
                }
                $dataS['id'] = $afastArr['Afastado']['id'];
                $dataS['usuario_atualizacao_id'] = $this->usuario_id;
                $dataS['data_atualizacao']  = $dataImp['data_cadastro'];
            } else {
                $this->Afastado->create();
                $dataS['importacao_id']      = $dataImp['id'];
                $dataS['empresa_id']         = $empresa_id;
                $dataS['data_cadastro']      = $dataImp['data_cadastro'];
                $dataS['usuario_criador_id'] = $this->usuario_id;
                $dataS['status'] = 1;
            }


            $dataS['beneficiario_id']           = $beneficiario_id;
            $dataS['data_inicio_afastamento']   = $data_inicio_afastamento;
            $dataS['data_fim_afastamento']      = $data_fim_afastamento;
            $dataS['cid']                       = $data_v['cid'];
            $dataS['tipo_afastamento']          = $data_v['tipo_afastamento'];
            $dataS['assistencia_medica']        = $data_v['assistencia_medica'];
            if (isset($data_v['plano_assistencia_medica'])) {
                $dataS['plano_assistencia_medica']  = $data_v['plano_assistencia_medica'];
            }

            $dataS['situacao'] = $data_v['situacao'];



            if (!$this->Afastado->save($dataS)) {
                #$this->erro[] = 'ERRO CADASTRO DE AFASTAMENTO '.$data_k; #GERAR LOG ERRO
                $this->erro[] = array('linha' => $data_k, 'descricao' => 'ERRO CADASTRO DE AFASTAMENTO ' . $data_k);
                $this->erro_exec = true;
            }



            if ($this->erro_exec) {
                throw new Exception();
            }
        }
    }



    public function admin_validacao()
    {

        if (!$this->Session->check('erro_validacao') || count($this->Session->read('erro_validacao')) == 0) {
            $this->Session->setFlash('Sem validações em aberto! ');
            $this->redirect(array('action' => 'admin_add'));
        }


        $rows = $this->Session->read('erro_validacao');
        #$html = implode('<br>',$row);
        #$html = '<span style="font-size:12px; font-weight:bold;">Erro na Importação</span> <br><span style="font-size:10px; font-weight:bold; ">'.$html.'<br><br><span style="font-size:12px; font-weight:bold; ">Para dar continuidade, anexe o arquivo novamente!</span></span>';
        #krumo($html);
        #krumo($row);
        #exit;

        $this->set('rows', $rows);
    }




    /**
     * DELETAR 
     * @param type $id
     */
    public function admin_delete($id = null)
    {
        $TABLE = $this->table;

        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if ($id == 1 && in_array($this->perfil_id, $this->perfil_adm)) {
            $this->Session->setFlash($this->msg_nao_existe);
            $this->redirect(array('action' => 'index'));
        }
        #END- USUÁRIO AUTORIZADO DEFAULT
        #$this->$NotificacaoUsuario->deleteAll(array('NotificacaoUsuario.usuario_id' => $usuario_id), false);


        if ($id !== null) { #EXCLUSÃO UNITÁRIA
            $this->$TABLE->id = $id;
            if (!$this->$TABLE->exists($id)) {
                $this->Session->setFlash($this->msg_nao_existe);
            } else {
                $import = $this->$TABLE->find('first', array('conditions' => array('id' => $id), 'fields' => 'tipo_importacao,arquivo_importado', 'recursive' => -1));

                if (!isset($import[$TABLE]['tipo_importacao']) || !in_array($import[$TABLE]['tipo_importacao'], array('beneficiario'))) {
                    $this->Session->setFlash($this->msg_nao_existe);
                    $this->redirect(array('action' => 'index'));
                    // }else{
                    //     $tipo_importacao = $import[$TABLE]['tipo_importacao'];
                    //     if(!$this->$TABLE->query("DELETE FROM {$tipo_importacao} WHERE importacao_id = {$id}")){
                    //         if(!$this->$TABLE->query("DELETE FROM importacao WHERE id = {$id}")){
                    //              $this->Session->setFlash($this->msg_excluido);
                    //              $this->Funcoes->deleteFile($import[$TABLE]['arquivo_importado'],'importacao');
                    //         }else{
                    //             $this->Session->setFlash($this->msg_excluido_erro.' 1');
                    //         }
                    //     }else{
                    //        $this->Session->setFlash($this->msg_excluido_erro.' 2');
                    //     }


                    #MONTAR ESTRUTURA DE EXCLUSÃO DE IMPORTAÇÃO
                    // SELECT * FROM importacao WHERE id = 318;
                    // select * FROM beneficiario WHERE importacao_id = 318;
                    // SELECT * FROM afastado WHERE importacao_id = 318; 


                    #OLD DELETE
                    /*
                    $TABLE2 = ucwords($import[$TABLE]['tipo_importacao']);
                    $this->loadModel($TABLE2);
                    $dataSource = $this->$TABLE->getDataSource();
                    $dataSource->begin();
                    try{
                        if(!$this->$TABLE2->deleteAll(array($TABLE2.'.importacao_id' => $id), false)){
                            throw new Exception();
                        }
                        if(!$this->$TABLE->delete($id)){
                            throw new Exception();
                        }
                        $this->Session->setFlash($this->msg_excluido);
                        $dataSource->commit();
                    } catch (Exception $ex) {
                        $this->Session->setFlash($this->msg_excluido_erro);
                        $dataSource->rollback();
                    }*/
                }
            }
        } else { #EXCLUSÃO MULTIPLA

            if (isset($this->params['named']['ids']) && $this->params['named']['ids'] != '') {
                $idsArr = explode('_', $this->params['named']['ids']);
                $setFlash = 'Exclusões: <br>';
                $data = array();
                foreach ($idsArr as $id):
                    if ($id != '') {
                        $this->$TABLE->id = $id;
                        if (!$this->$TABLE->exists($id)) {
                            $setFlash .= 'O ID: ' . $id . ' não EXISTE, portanto não pode ser EXCLUÍDO! <br /> ';
                        } else {
                            $import = $this->$TABLE->find('first', array('conditions' => array('id' => $id), 'fields' => 'tipo_importacao,arquivo_importado', 'recursive' => -1));
                            if (!isset($import[$TABLE]['tipo_importacao']) || !in_array($import[$TABLE]['tipo_importacao'], array('beneficiario'))) {
                                $this->Session->setFlash($this->msg_nao_existe);
                                $this->redirect(array('action' => 'index'));
                            } else {
                                $tipo_importacao = $import[$TABLE]['tipo_importacao'];

                                #MONTAR ESTRUTURA DE EXCLUSÃO DE IMPORTAÇÃO
                                // SELECT * FROM importacao WHERE id = 318;
                                // select * FROM beneficiario WHERE importacao_id = 318;
                                // SELECT * FROM afastado WHERE importacao_id = 318; 


                                // if(!$this->$TABLE->query("DELETE FROM {$tipo_importacao} WHERE importacao_id = {$id}")){
                                //     if(!$this->$TABLE->query("DELETE FROM importacao WHERE id = {$id}")){
                                //         $setFlash .= 'O ID: ' . $id . ' foi EXCLUÍDO com sucesso! <br />';
                                //         $this->Funcoes->deleteFile($import[$TABLE]['arquivo_importado'],'importacao');
                                //     }else{
                                //         $setFlash .= 'O ID: ' . $id . ' não pode ser EXCLUÍDO! Por favor, tente novamente.  <br /> ';
                                //     }
                                // }else{
                                //     $setFlash .= 'O ID: ' . $id . ' não pode ser EXCLUÍDO! Por favor, tente novamente.  <br /> ';
                                // }

                                #OLD DELETE
                                /*
                                $TABLE2 = ucwords($import[$TABLE]['tipo_importacao']);
                                $this->loadModel($TABLE2);
                                $dataSource = $this->$TABLE->getDataSource();
                                $dataSource->begin();
                                try{
                                    if(!$this->$TABLE2->deleteAll(array($TABLE2.'.importacao_id' => $id), false)){
                                        throw new Exception();
                                    }
                                    if(!$this->$TABLE->delete($id)){
                                        throw new Exception();
                                    }
                                    $setFlash .= 'O ID: ' . $id . ' foi EXCLUÍDO com sucesso! <br />';
                                    $dataSource->commit();
                                } catch (Exception $ex) {
                                    $setFlash .= 'O ID: ' . $id . ' não pode ser EXCLUÍDO! Por favor, tente novamente.  <br /> ';
                                    $dataSource->rollback();
                                }
                            */
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








    /**
     * uploads files to the server
     * @params:
     * 		$folder 	= the folder to upload the files e.g. 'img/files'
     * 		$formdata 	= the array containing the form files
     * 		$itemId 	= id of the item (optional) will create a new sub folder
     * @return:
     * 		will return an array with the success of each file upload
     * 
     * Extension MIME Type
      .doc      application/msword
      .dot      application/msword

      .docx     application/vnd.openxmlformats-officedocument.wordprocessingml.document
      .dotx     application/vnd.openxmlformats-officedocument.wordprocessingml.template
      .docm     application/vnd.ms-word.document.macroEnabled.12
      .dotm     application/vnd.ms-word.template.macroEnabled.12

      .xls      application/vnd.ms-excel
      .xlt      application/vnd.ms-excel
      .xla      application/vnd.ms-excel

      .xlsx     application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
      .xltx     application/vnd.openxmlformats-officedocument.spreadsheetml.template
      .xlsm     application/vnd.ms-excel.sheet.macroEnabled.12
      .xltm     application/vnd.ms-excel.template.macroEnabled.12
      .xlam     application/vnd.ms-excel.addin.macroEnabled.12
      .xlsb     application/vnd.ms-excel.sheet.binary.macroEnabled.12

      .ppt      application/vnd.ms-powerpoint
      .pot      application/vnd.ms-powerpoint
      .pps      application/vnd.ms-powerpoint
      .ppa      application/vnd.ms-powerpoint

      .pptx     application/vnd.openxmlformats-officedocument.presentationml.presentation
      .potx     application/vnd.openxmlformats-officedocument.presentationml.template
      .ppsx     application/vnd.openxmlformats-officedocument.presentationml.slideshow
      .ppam     application/vnd.ms-powerpoint.addin.macroEnabled.12
      .pptm     application/vnd.ms-powerpoint.presentation.macroEnabled.12
      .potm     application/vnd.ms-powerpoint.template.macroEnabled.12
      .ppsm     application/vnd.ms-powerpoint.slideshow.macroEnabled.12

      .mdb      application/vnd.ms-access
     */
    function uploadFiles($folder, $formdata)
    {


        #MONTA CAMINHO E NOME DO ARQUIVO
        $uploadFolder = $folder;
        $filename = str_replace('.' . $ext, '', $formdata['name']);
        $filename = $this->normalizaeUrl($filename) . '_' . time();


        #CRIA AS PASTAS 
        if (!file_exists($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }


        // list of permitted file types, this is only images but documents can be added
        $permitted = array(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'text/plain',
            'text/csv',
            'text/x-csv',
            'application/vnd.openxmlformats-',
            'officedocument.spreadsheetml.sheet',
            'text/comma-separated-values',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/anytext',
            'text/plain'
        );

        // loop through and deal with the files
        foreach ($formdata as $file) {
            // replace spaces with underscores
            $filename = str_replace(' ', '_', $file['name']);
            // assume filetype is false
            $typeOK = false;
            // check filetype is ok
            foreach ($permitted as $type) {
                if ($type == $file['type']) {
                    $typeOK = true;
                    break;
                }
            }

            // if file type ok upload the file
            if ($typeOK) {
                // switch based on error code
                switch ($file['error']) {
                    case 0:
                        // check filename already exists
                        if (!file_exists($folder_url . '/' . $filename)) {
                            // create full filename
                            $full_url = $folder_url . '/' . $filename;
                            $url = $rel_url . '/' . $filename;
                            // upload the file
                            $success = move_uploaded_file($file['tmp_name'], $url);
                        } else {
                            // create unique filename and upload file
                            ini_set('date.timezone', 'Europe/London');
                            $now = date('Y-m-d-His');
                            $full_url = $folder_url . '/' . $now . $filename;
                            $url = $rel_url . '/' . $now . $filename;
                            $success = move_uploaded_file($file['tmp_name'], $url);
                        }
                        // if upload was successful
                        if ($success) {
                            // save the url of the file
                            $result['urls'][] = $url;
                        } else {
                            $result['errors'][] = "Error uploaded $filename. Please try again.";
                        }
                        break;
                    case 3:
                        // an error occured
                        $result['errors'][] = "Error uploading $filename. Please try again.";
                        break;
                    default:
                        // an error occured
                        $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                        break;
                }
            } elseif ($file['error'] == 4) {
                // no file was selected for upload
                $result['nofiles'][] = "No file Selected";
            } else {
                // unacceptable file type
                $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
            }
        }

        return $result;
    }



    public function valida_cabecalho($data)
    {
        $estrutura_banco = $this->array_coluna_obrigatoria();
        // krumo($estrutura_banco);
        // krumo($data);
        // exit;

        if (count($estrutura_banco) == 0) {
            $html = '<span style="font-size:14px; font-weight:bold;">ERRO: Colunas não encontradas, informe o administrador do sistema.</span>';
            $this->Session->setFlash($html);

            #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
            $this->Session->write('error_form', $this->data);
            $this->redirect(array('action' => 'add'));
            #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
            exit;
        }



        // krumo($estrutura_banco);
        // krumo($data);
        // exit;


        if (count($estrutura_banco) != count($data)) {
            $html = '<span style="font-size:14px; font-weight:bold;">ERRO: Contagem de colunas Inválida!.</span>';
            $this->Session->setFlash($html);

            #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
            $this->Session->write('error_form', $this->data);
            $this->redirect(array('action' => 'add'));
            #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
            exit;
        }
        //        krumo($data);
        //        krumo($estrutura_banco);
        //        exit;

        $erro = array();

        if (count($data) > 0) {
            foreach ($data as $kData => $vData) {
                if (!array_key_exists($kData, $estrutura_banco)) {
                    $erro[] = $kData;
                }
            }
        }

        //        krumo(count($erro));
        //        exit;
        /*  ERRO nos seguintes nomes das colunas do cabeçalho:
            - realiza_alguma_atividade_perigosa_na_profissao
            - email */

        if (count($erro) > 0) {
            $hmtl_erro = implode('<br> - ', $erro);
            $html = '<span style="font-size:14px; font-weight:bold;">ERRO nos seguintes nomes das colunas do cabeçalho: <br> - ' . $hmtl_erro . '</span>';
            $this->Session->setFlash($html);

            //            krumo($this->data);
            //            echo $html;
            //            exit;
            #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
            $this->Session->write('error_form', $this->data);
            //            krumo($return);
            $this->redirect(array('action' => 'add'));
            #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
            //            krumo(1);
            exit;
        }
        //        echo 'etste';
        //        exit;
        return true;
    }

    /**
     * VERIFICA O CRUZAMENTO DO NOME E TIPO RETORNANDO 
     * @param type $dado
     * @param type $nome
     * @param string $tipo
     * @return type
     */
    function valida_dado($dado, $nome)
    {
        $estrutura_banco_db = $this->array_coluna_obrigatoria_db();
        $dado = trim($dado);

        $return = $dado;


        if (array_key_exists($nome, $estrutura_banco_db)) {
            if ($estrutura_banco_db[$nome]['aceita_nulo']) {
                if ($dado != '') {
                    if (strlen($dado) > (int) $estrutura_banco_db[$nome]['tamanho'] && !is_null($estrutura_banco_db[$nome]['tamanho'])) {
                        $this->erro_linha[] = "- ERRO NO CAMPO '{$nome}', NÃO DEVE EXCEDER {$estrutura_banco_db[$nome]['tamanho']} CARACTERES";
                        $return = null;
                    } else {
                        if (in_array($estrutura_banco_db[$nome]['tipo'], array('timestamp', 'date'))) {
                            $return = $this->valida_data($dado);
                        } else if (in_array($estrutura_banco_db[$nome]['tipo'], array('bigint', 'int', 'tinyint'))) {
                            $return = $this->valida_inteiro($dado);
                        } else if (in_array($estrutura_banco_db[$nome]['tipo'], array('decimal', 'float'))) {
                            $return = $this->valida_decimal($dado);
                        } else if (in_array($estrutura_banco_db[$nome]['tipo'], array('varchar'))) {
                            $return = $this->valida_varchar($dado);
                        }
                    }
                }
            } else {
                if ($dado != '') {
                    if (strlen($dado) > (int) $estrutura_banco_db[$nome]['tamanho'] && !is_null($estrutura_banco_db[$nome]['tamanho'])) {
                        $this->erro_linha[] = "- ERRO NO CAMPO '{$nome}', NÃO DEVE EXCEDER {$estrutura_banco_db[$nome]['tamanho']} CARACTERES";
                        $return = null;
                    } else {
                        if (in_array($estrutura_banco_db[$nome]['tipo'], array('timestamp', 'date'))) {
                            $return = $this->valida_data($dado, $nome);
                            if ($return === null) {
                                $this->erro_linha[] = "- ERRO NO CAMPO '{$nome}', DEVE SER DO TIPO DATA";
                            }
                        } else if (in_array($estrutura_banco_db[$nome]['tipo'], array('bigint', 'int', 'tinyint'))) {
                            $return = $this->valida_inteiro($dado);
                            if ($return === null) {
                                $this->erro_linha[] = "- ERRO NO CAMPO '{$nome}', DEVE SER NUMEROS INTEIROS";
                            }
                        } else if (in_array($estrutura_banco_db[$nome]['tipo'], array('decimal', 'float'))) {
                            $return = $this->valida_decimal($dado);
                            if ($return === null) {
                                $this->erro_linha[] = "- ERRO NO CAMPO '{$nome}', DEVE SER DO TIPO DECIMAL";
                            }
                        } else if (in_array($estrutura_banco_db[$nome]['tipo'], array('varchar'))) {
                            $return = $this->valida_varchar($dado);
                            if ($return === null) {
                                $this->erro_linha[] = "- ERRO NO CAMPO '{$nome}', DEVE SER DO TIPO TEXTO";
                            }
                        }
                    }

                    if ($nome == 'sexo' && !in_array(strtolower($dado), array('m', 'f'))) {
                        $this->erro_linha[] = "- ERRO NO CAMPO '{$nome}', DEVE SER M ou F";
                    }
                    if ($nome == 'tipo_registro' && !in_array($dado, array(0, 1, 2))) {
                        $this->erro_linha[] = "- ERRO NO CAMPO '{$nome}', DEVE SER 1, 2 ou 3";
                    }
                } else {
                    $this->erro_linha[] = "- ERRO NO CAMPO '{$nome}', NÃO DEVE SER VAZIO.";
                    $return = null;
                }
            }
        }

        return $return;
    }

    /**
     * timestamp, date
     */
    public function valida_data($dado, $nome = '')
    {

        if ($dado != '') {

            if (strlen($dado) == 10) {
                if (preg_match('/-/', $dado)) {
                    $dataArr = explode('-', $dado);
                    if (strlen($dataArr[0]) == 4 && strlen($dataArr[1]) == 2 && strlen($dataArr[2]) == 2) {
                        return $dado;
                    } else {
                        $this->erro_linha[] = "- Erro na data:{$nome}.</i>";
                        return false;
                    }
                } else {
                    $dataArr = explode('/', $dado);
                    if (strlen($dataArr[0]) == 2 && strlen($dataArr[1]) == 2 && strlen($dataArr[2]) == 4) {
                        return $this->Funcoes->dateToDb($dado);
                    } else {
                        $this->erro_linha[] = "- Erro na data:{$nome}.</i>";
                        return false;
                    }
                }
            } else {
                $this->erro_linha[] = "- Erro na data {$nome}.</i>";
                return false;
            }
        } else {
            return $dado;
        }



        // if($data_v['data_inicio_afastamento'] != ''){
        //     $data_inicio_afastamento = $this->valida_data($data_v['data_inicio_afastamento']);
        //     if($data_inicio_afastamento == null){
        //         $this->erro_linha[] = "- Erro na tada de início do afatamento.</i>";
        //     }   
        // }
        // krumo($dado);
        // exit;
        return (strlen($dado) == 10) ? $this->Funcoes->dateToDb($dado) : null;
    }

    /**
     * bigint, int, tinyint
     */
    public function valida_inteiro($dado)
    {
        return is_numeric($dado) ? $dado : null;
    }

    /**
     * decimal, float
     */
    public function valida_decimal($dado)
    {
        return is_float($dado) ? $dado : null;
    }

    /**
     * varchar
     */
    public function valida_varchar($dado)
    {
        return ($dado != '') ? $dado : null;
    }


    /**
     * Beneficiario: 44
     * Afastado: 25
     * Beneficio: 19
     */
    public function array_coluna_obrigatoria()
    {
        $data = array();
        $data['beneficiario']['cliente_id'] = 'cliente_id';
        $data['beneficiario']['cnpj'] = 'cnpj';
        $data['beneficiario']['razao_social'] = 'razao_social';
        $data['beneficiario']['filial'] = 'filial';
        $data['beneficiario']['grupo'] = 'grupo';
        $data['beneficiario']['subgrupo'] = 'subgrupo';
        $data['beneficiario']['matricula'] = 'matricula';
        $data['beneficiario']['nome_social'] = 'nome_social';
        $data['beneficiario']['nome'] = 'nome';
        $data['beneficiario']['cpf'] = 'cpf';
        $data['beneficiario']['data_nascimento'] = 'data_nascimento';
        $data['beneficiario']['turno'] = 'turno';
        $data['beneficiario']['profissao'] = 'profissao';
        $data['beneficiario']['ocupacao'] = 'ocupacao';
        $data['beneficiario']['pessoa_politicamente_exposta'] = 'pessoa_politicamente_exposta';
        $data['beneficiario']['realiza_alguma_atividade_perigosa_na_profissao'] = 'realiza_alguma_atividade_perigosa_na_profissao';
        $data['beneficiario']['possui_deficiencia'] = 'possui_deficiencia';
        $data['beneficiario']['rg'] = 'rg';
        $data['beneficiario']['estado_civil'] = 'estado_civil';
        $data['beneficiario']['sexo'] = 'sexo';
        $data['beneficiario']['email'] = 'email';
        $data['beneficiario']['telefone'] = 'telefone';
        $data['beneficiario']['telefone1'] = 'telefone1';
        $data['beneficiario']['telefone2'] = 'telefone2';
        $data['beneficiario']['situacao'] = 'situacao';

        // $data['beneficiario']['beneficio'] = 'beneficio';
        // $data['beneficiario']['tipo_de_seguro'] = 'tipo_de_seguro';
        // $data['beneficiario']['banco'] = 'banco';
        // $data['beneficiario']['agencia'] = 'agencia';
        // $data['beneficiario']['conta'] = 'conta';
        // $data['beneficiario']['tipo_de_conta'] = 'tipo_de_conta';
        // $data['beneficiario']['endereco'] = 'endereco';
        // $data['beneficiario']['bairro'] = 'bairro';
        // $data['beneficiario']['cep'] = 'cep';
        // $data['beneficiario']['cidade'] = 'cidade';
        // $data['beneficiario']['estado'] = 'estado';
        // $data['beneficiario']['telefone1'] = 'telefone1';
        // $data['beneficiario']['telefone2'] = 'telefone2';
        // $data['beneficiario']['telefone3'] = 'telefone3';
        // $data['beneficiario']['telefone4'] = 'telefone4';
        // $data['beneficiario']['telefone5'] = 'telefone5';
        // $data['beneficiario']['telefone6'] = 'telefone6';
        // $data['beneficiario']['telefone7'] = 'telefone7';
        // $data['beneficiario']['telefone8'] = 'telefone8';
        // $data['beneficiario']['telefone9'] = 'telefone9';
        // $data['beneficiario']['altura'] = 'altura';
        // $data['beneficiario']['peso'] = 'peso';
        // $data['beneficiario']['valor_do_seguro'] = 'valor_do_seguro';
        // $data['beneficiario']['beneficiario1'] = 'beneficiario1';
        // $data['beneficiario']['parentesco1'] = 'parentesco1';
        // $data['beneficiario']['beneficiario2'] = 'beneficiario2';
        // $data['beneficiario']['parentesco2'] = 'parentesco2';
        // $data['beneficiario']['beneficiario3'] = 'beneficiario3';
        // $data['beneficiario']['parentesco3'] = 'parentesco3';
        // $data['beneficiario']['beneficiario4'] = 'beneficiario4';
        // $data['beneficiario']['parentesco4'] = 'parentesco4';

        $data['afastado']['cliente_id'] = 'cliente_id';
        $data['afastado']['nome_colaborador'] = 'nome_colaborador';
        $data['afastado']['cnpj'] = 'cnpj';
        $data['afastado']['pis'] = 'pis';
        $data['afastado']['cpf'] = 'cpf';
        $data['afastado']['data_nascimento'] = 'data_nascimento';
        $data['afastado']['nome_mae'] = 'nome_mae';
        $data['afastado']['cargo'] = 'cargo';
        $data['afastado']['data_inicio_afastamento'] = 'data_inicio_afastamento';
        $data['afastado']['data_fim_afastamento'] = 'data_fim_afastamento';
        $data['afastado']['cid'] = 'cid';
        $data['afastado']['tipo_afastamento'] = 'tipo_afastamento';
        $data['afastado']['assistencia_medica'] = 'assistencia_medica';
        $data['afastado']['plano_assitencia_medica'] = 'plano_assitencia_medica';
        $data['afastado']['logradouro'] = 'logradouro';
        $data['afastado']['rua'] = 'rua';
        $data['afastado']['num'] = 'num';
        $data['afastado']['complemento'] = 'complemento';
        $data['afastado']['bairro'] = 'bairro';
        $data['afastado']['cidade'] = 'cidade';
        $data['afastado']['uf'] = 'uf';
        $data['afastado']['cep'] = 'cep';
        $data['afastado']['telefone'] = 'telefone';
        $data['afastado']['telefone1'] = 'telefone1';
        $data['afastado']['telefone2'] = 'telefone2';
        $data['afastado']['situacao'] = 'situacao';

        $data['beneficio_previdenciario']['cliente_id'] = 'cliente_id';
        $data['beneficio_previdenciario']['cpf'] = 'cpf';
        $data['beneficio_previdenciario']['cnpj'] = 'cnpj';
        #$data['beneficio_previdenciario']['razao_social'] = 'razao_social'; #MÁRCIA DISSE NÃO TER NO LAYOUT
        #$data['beneficio_previdenciario']['filial'] = 'filial';#MÁRCIA DISSE NÃO TER NO LAYOUT
        $data['beneficio_previdenciario']['nb'] = 'nb';
        $data['beneficio_previdenciario']['nome'] = 'nome';
        $data['beneficio_previdenciario']['data_nascimento'] = 'data_nascimento';
        $data['beneficio_previdenciario']['nit'] = 'nit';
        $data['beneficio_previdenciario']['especie'] = 'especie';
        $data['beneficio_previdenciario']['situacao'] = 'situacao';
        $data['beneficio_previdenciario']['data_entrada_requerimento'] = 'data_entrada_requerimento';
        $data['beneficio_previdenciario']['data_inicio'] = 'data_inicio';
        $data['beneficio_previdenciario']['data_despacho'] = 'data_despacho';
        $data['beneficio_previdenciario']['data_realizacao_pericia'] = 'data_realizacao_pericia';
        $data['beneficio_previdenciario']['conclusao_pericia_medica'] = 'conclusao_pericia_medica';
        $data['beneficio_previdenciario']['data_limite'] = 'data_limite';
        $data['beneficio_previdenciario']['data_indeferimento'] = 'data_indeferimento';
        $data['beneficio_previdenciario']['data_cessacao'] = 'data_cessacao';
        $data['beneficio_previdenciario']['nexo_tecnico'] = 'nexo_tecnico';

        $data['absenteismo']['cliente_id'] = 'cliente_id';
        $data['absenteismo']['cnpj'] = 'cnpj';
        $data['absenteismo']['matricula'] = 'matricula';
        $data['absenteismo']['cpf'] = 'cpf';
        $data['absenteismo']['documento_id'] = 'documento_id';
        $data['absenteismo']['motivo_id'] = 'motivo_id';
        $data['absenteismo']['hospital_clinica'] = 'hospital_clinica';
        $data['absenteismo']['nome_colaborador'] = 'nome_colaborador';
        $data['absenteismo']['data_saida'] = 'data_saida';
        $data['absenteismo']['data_retorno'] = 'data_retorno';
        $data['absenteismo']['qtde_dias_atestado'] = 'qtde_dias_atestado';
        $data['absenteismo']['hora_saida'] = 'hora_saida';
        $data['absenteismo']['hora_retorno'] = 'hora_retorno';
        $data['absenteismo']['cid'] = 'cid';
        $data['absenteismo']['especialidade_id'] = 'especialidade_id';
        $data['absenteismo']['emissor_id'] = 'emissor_id';
        $data['absenteismo']['profissional'] = 'profissional';
        $data['absenteismo']['num_crm'] = 'num_crm';
        $data['absenteismo']['tipo_absenteismo_id'] = 'tipo_absenteismo_id';
        #$data['absenteismo']['razao_social'] = 'razao_social';
        #$data['absenteismo']['fialial'] = 'filial';
        #$data['absenteismo']['data_nascimento'] = 'data_nascimento';
        #$data['absenteismo']['cargo_id'] = 'cargo_id';
        #$data['absenteismo']['setor_id'] = 'setor_id';
        #$data['absenteismo']['departamento_id'] = 'departamento_id';
        #NÃO ESTÃO
        #$data['absenteismo']['parte_corpo_id'] = 'parte_corpo_id';
        #$data['absenteismo']['observacao'] = 'observacao';
        #$data['absenteismo']['arquivo'] = 'arquivo';
        $data['absenteismo']['situacao'] = 'situacao';


        $data['sinistro']['cnpj'] = 'cnpj';
        $data['sinistro']['chave_beneficiario'] = 'chave_beneficiario';
        $data['sinistro']['matricula'] = 'matricula';
        $data['sinistro']['numero_carteira_titular'] = 'numero_carteira_titular';
        $data['sinistro']['numero_carteira_titular_complemento'] = 'numero_carteira_titular_complemento';
        $data['sinistro']['cpf_titular'] = 'cpf_titular';
        $data['sinistro']['nome_titular'] = 'nome_titular';
        $data['sinistro']['numero_carteira'] = 'numero_carteira';
        $data['sinistro']['numero_carteira_complemento'] = 'numero_carteira_complemento';
        $data['sinistro']['cpf_beneficiario'] = 'cpf_beneficiario';
        $data['sinistro']['nome_beneficiario'] = 'nome_beneficiario';
        $data['sinistro']['sexo'] = 'sexo';
        $data['sinistro']['elegibilidade'] = 'elegibilidade';
        $data['sinistro']['dt_nascimento'] = 'dt_nascimento';
        $data['sinistro']['idade'] = 'idade';
        $data['sinistro']['tipo_reembolso'] = 'tipo_reembolso';
        $data['sinistro']['cod_prestador'] = 'cod_prestador';
        $data['sinistro']['nome_prestador'] = 'nome_prestador';
        $data['sinistro']['cidade_prestador'] = 'cidade_prestador';
        $data['sinistro']['uf_prestador'] = 'uf_prestador';
        $data['sinistro']['cod_faixa_etaria_ans'] = 'cod_faixa_etaria_ans';
        $data['sinistro']['plano_id'] = 'plano_id';
        $data['sinistro']['ds_plano'] = 'ds_plano';
        $data['sinistro']['nro_conta_medica'] = 'nro_conta_medica';
        $data['sinistro']['cod_procedimento'] = 'cod_procedimento';
        $data['sinistro']['ds_procedimento'] = 'ds_procedimento';
        $data['sinistro']['qtde_procedimento'] = 'qtde_procedimento';
        $data['sinistro']['tipo_servico'] = 'tipo_servico';
        $data['sinistro']['conta_medica'] = 'conta_medica';
        $data['sinistro']['valor'] = 'valor';
        $data['sinistro']['valor_coparticipacao'] = 'valor_coparticipacao';
        $data['sinistro']['senha'] = 'senha';
        $data['sinistro']['nr_autorizacao'] = 'nr_autorizacao';
        $data['sinistro']['prestador_tipo'] = 'prestador_tipo';
        $data['sinistro']['local_atendimento'] = 'local_atendimento';
        $data['sinistro']['cod_especialidade'] = 'cod_especialidade';
        $data['sinistro']['ds_especialidade'] = 'ds_especialidade';
        $data['sinistro']['dt_evento'] = 'dt_evento';
        $data['sinistro']['dt_pagamento'] = 'dt_pagamento';
        $data['sinistro']['cid'] = 'cid';
        $data['sinistro']['ds_cid'] = 'ds_cid';
        $data['sinistro']['operadora'] = 'operadora';
        $data['sinistro']['tipo_servico_operadora'] = 'tipo_servico_operadora';
        $data['sinistro']['tipo_internacao'] = 'tipo_internacao';
        $data['sinistro']['tipo_entrada'] = 'tipo_entrada';
        $data['sinistro']['campo_1_coluna'] = 'campo_1_coluna';
        $data['sinistro']['campo_1_dado'] = 'campo_1_dado';
        $data['sinistro']['campo_2_coluna'] = 'campo_2_coluna';
        $data['sinistro']['campo_2_dado'] = 'campo_2_dado';
        $data['sinistro']['campo_3_coluna'] = 'campo_3_coluna';
        $data['sinistro']['campo_3_dado'] = 'campo_3_dado';
        $data['sinistro']['campo_4_coluna'] = 'campo_4_coluna';
        $data['sinistro']['campo_4_dado'] = 'campo_4_dado';
        $data['sinistro']['situacao'] = 'situacao';


        $data['faturamento']['competencia'] = 'competencia';
        $data['faturamento']['valor_fatura'] = 'valor_fatura';
        $data['faturamento']['qtd_vidas'] = 'qtd_vidas';
        $data['faturamento']['valor_sinistro'] = 'valor_sinistro';
        $data['faturamento']['coparticipacao'] = 'coparticipacao';
        $data['faturamento']['revisao'] = 'revisao';
        $data['faturamento']['reembolso'] = 'reembolso';




        $return = array();
        if ($this->tipo_importacao != '' && isset($data[$this->tipo_importacao])) {
            $return = $data[$this->tipo_importacao];
        }

        return $return;
    }

    /**
     * RETORNA CAMPOS OBRIGATÓRIOS
     * @param type $tipo_importacao
     * @return type
     * Não será mais utilizado devido as colunas nao necessáris 
     */
    public function array_coluna_obrigatoria_db()
    {
        $TABLE = $this->table;
        $db = &$this->$TABLE->getDataSource()->config['database'];
        $like = '%samed';
        if ($db != '') {
            $like = $db;
        }
        if (count($this->estrutura_banco_db) > 0) {
            $estrutura_banco_db = $this->estrutura_banco_db;
        } else {
            $sql = "SELECT TABLE_NAME, COLUMN_NAME, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
                        FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE table_schema LIKE '{$like}' and TABLE_NAME in ('beneficiario')
                        ORDER BY TABLE_NAME, ORDINAL_POSITION;";
            $dados = $this->$TABLE->query($sql);
            $estrutura_banco_db = array();
            if (count($dados) > 0) {
                foreach ($dados as $dado) {
                    $dado = $dado['COLUMNS'];
                    $estrutura_banco_db[$dado['TABLE_NAME']][$dado['COLUMN_NAME']] = array('tipo' => $dado['DATA_TYPE'], 'tamanho' => $dado['CHARACTER_MAXIMUM_LENGTH'], 'aceita_nulo' => ($dado['IS_NULLABLE'] == 'NO' ? false : true));
                }
            }
        }


        return isset($estrutura_banco_db[$this->tipo_importacao]) ? $estrutura_banco_db[$this->tipo_importacao] : $estrutura_banco_db['beneficiario'];
    }



    public function busca_cpf($cpf, $nome = '')
    {
        $this->loadModel('Beneficiario');

        $beneficiarioArr = $this->Beneficiario->find('count', array('conditions' => array('cpf' => $cpf, 'status' => 1), 'recursive' => -1));

        if ($beneficiarioArr > 0) {
            $this->erro_linha[] = '- Beneficiario já existente. - ' . $nome;
            return false;
        }
        return true;
    }



    public function send_file($id = null)
    {
        $this->autoRender = false;
        $dataArr = $this->Importacao->find('first', array('conditions' => array('id' => $id), 'fields' => 'arquivo_importado', 'recursive' => -1));
        $url = 'files/uploads/importacao/' . $dataArr['Importacao']['arquivo_importado'];
        $this->response->file($url, array('download' => true));
    }
}
