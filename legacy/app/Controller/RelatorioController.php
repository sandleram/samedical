<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class RelatorioController extends AppController
{
    var $uses = false; //NÃO USA BANCO NESTA CONTROLLER


    #SUCESSO DEVE SER MÍNUSCULO
    public $components = array('Paginator', 'Funcoes');
    private $name_search;
    private $table;
    public $msg_nao_existe = 'Relatório Inexistente';
    public $msg_salvo = 'O Relatório foi SALVO com sucesso!';
    public $msg_salvo_erro = 'Não foi possível SALVAR o Relatório, verifique as informações ou tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema.';
    public $msg_salvo_erro_login = 'Não foi possível efetuar o FILTRO ou SALVAR porque foi deslogado, tente novamente!';
    public $msg_excluido = 'O Relatório foi EXCLUÍDO com sucesso!';
    public $msg_excluido_erro = 'Não foi possível EXCLUIR o Relatório, tente mais tarde! Caso o problem persista, informe o erro para o administrador do sistema';
    public $tipoExportacao = '';
    private $chave = 'redsfe51ewfwe1t65h1yjzn5la1a515145165233asdf51vbgasdtorio';

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

        $this->name_search = 'pesquisa_' . $this->params['action'];
        $this->set('name_search', $this->name_search);
    }


    public function admin_busca_unset($search)
    {
        $this->autoRender = false;

        $referer = explode('/', $_SERVER['HTTP_REFERER']);
        $action_referer = $referer[count($referer) - 1];

        parent::all_busca_unset($search, 'pesquisa_admin_' . $action_referer);




        $this->redirect($this->referer());
    }


    public function admin_index()
    {
        $permissoes = $this->Session->read('Auth.permissoes');
        $this->set('permissoes', $permissoes);
    }

    public function converter_tipo_array($array, $tipo = 'int')
    {
        if (count($array) > 0) {
            foreach ($array as $kArray => $vArray) {
                if ($tipo == 'int') {
                    $array[$kArray] = (int) $vArray;
                } elseif ($tipo == 'bool') {
                    $array[$kArray] = (bool) $vArray;
                } else {
                    $array[$kArray] = (bool) $vArray;
                }
            }
        }
        return $array;
    }

    public function converter_tipo($data, $tipo = 'int')
    {
        if ($data != '') {
            if ($tipo == 'int') {
                $data = (int) $data;
            } elseif ($tipo == 'bool') {
                $data = (bool) $data;
            } else {
                $data = (bool) $data;
            }
        }
        return $data;
    }


    public function admin_beneficio_previdenciario()
    {
        $TABLE = $this->table;


        #SEARCH
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'] . '_form_busca'])):
                $this->Session->write('pesquisa_' . $this->params['action'], $this->data[$this->params['controller'] . '_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        $search = $this->Session->read('pesquisa_' . $this->params['action']);
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
            if (isset($search['cpf']) && $search['cpf'] != ''):
                $cpf = $search['cpf'];
                $cpf = str_replace('.', '', $cpf);
                $cpf = str_replace('-', '', $cpf);

                $condition[] = $TABLE . '.cpf = "' . $cpf . '"';
            endif;

            if (isset($search['status']) && $search['status'] != ''):
                $condition[] = $TABLE . '.status = ' . $search['status'];
            endif;
        endif;




        #BEGIN- USUÁRIO AUTORIZADO DEFAULT
        if ($this->Session->read('Auth.Usuario.id') != $this->uRoot) {
            $condition[] = $TABLE . $this->status_default;
        }
        $condition[] = $TABLE . '.cliente_id = ' . $this->cliente_id;
        #END- USUÁRIO AUTORIZADO DEFAULT






        $this->loadModel('BeneficioPrevidenciario');
        $this->loadModel('Beneficio');
        $beneficioArr = $this->Beneficio->find('list', array('conditions' => array('status' => 1, 'cliente_id' => $this->cliente_id), 'fields' => 'id,descricao_breakeven', 'recursive' => -1));
        $this->set('beneficioArr', $beneficioArr);
        #beneficio previdenciario
        if (isset($row['BeneficioPrevidenciario']) && count($row['BeneficioPrevidenciario']) > 0) {
            foreach ($row['BeneficioPrevidenciario'] as $benefPrevArr) {
                $listBP = array();
                $listBP['id']                       = $benefPrevArr['id'];
                $listBP['importacao_id']            = $benefPrevArr['importacao_id'];
                $listBP['data_proxima_pericia']     = $benefPrevArr['data_proxima_pericia'];
                $listBP['num_requerimento']         = $benefPrevArr['num_requerimento'];
                $listBP['nb']                       = $benefPrevArr['nb'];
                $listBP['especie']                  = $benefPrevArr['especie'];
                $listBP['especie_bp_id']            = $benefPrevArr['especie_bp_id'];
                $listBP['situacao']                 = $benefPrevArr['situacao'];
                $listBP['data_entrada_requerimento'] = $benefPrevArr['data_entrada_requerimento'];
                $listBP['data_inicio']              = $benefPrevArr['data_inicio'];
                $listBP['data_despacho']            = $benefPrevArr['data_despacho'];
                $listBP['data_realizacao_pericia']  = $benefPrevArr['data_realizacao_pericia'];
                $listBP['conclusao_pericia_medica'] = $benefPrevArr['conclusao_pericia_medica'];
                $listBP['data_limite']              = $benefPrevArr['data_limite'];
                $listBP['data_indeferimento']       = $benefPrevArr['data_indeferimento'];
                $listBP['data_cessacao']            = $benefPrevArr['data_cessacao'];
                $listBP['nexo_tecnico']             = $benefPrevArr['nexo_tecnico'];
                $listBP['data_cadastro']            = $benefPrevArr['data_cadastro'];
                $btnLink = Router::url(array('controller' => 'beneficio_previdenciario', 'action' => 'add', $row['Beneficiario']['id'], $benefPrevArr['id']), true);
                if ($row['Beneficiario']['pis'] == '' && (isset($benefPrevArr['nit']) && $benefPrevArr['nit'] != '')) {
                    $row['Beneficiario']['pis'] = $benefPrevArr['nit'];
                }


                $listBP['btn'] = '';
                if (isset($permissoes['Beneficio_previdenciario']['permissao']) && $permissoes['Beneficio_previdenciario']['permissao'] > 1) {
                    $listBP['btn'] = '<a class="btn btn-primary btn-xs" href="' . $btnLink . '">Editar</a>';
                }

                $listBPAll[] = $listBP;
            }
        }

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 15,
            'order' => array('id' => 'DESC')
        );

        #ADICIONAR
        $this->$TABLE->recursive = 2;
        $this->set('rows', $this->Paginator->paginate());
    }


    public function admin_gerencial()
    {
        $TABLE = $this->table;


        $this->loadModel('Beneficio');
        $beneficioArr = $this->Beneficio->find('list', array('conditions' => array('status' => 1, 'cliente_id' => $this->cliente_id), 'fields' => 'id,descricao_breakeven', 'recursive' => -1));
        $this->set('beneficioArr', $beneficioArr);

        $this->loadModel('Operadora');
        $operadoraArr = $this->Operadora->find('list', array('conditions' => array('status' => 1), 'fields' => 'id,nome'));
        $this->set('operadoraArr', $operadoraArr);


        $this->loadModel('TipoBeneficio');
        $tipoBeneficioArr = $this->TipoBeneficio->find('list', array('conditions' => array('status' => 1), 'fields' => 'id,descricao'));
        $this->set('tipoBeneficioArr', $tipoBeneficioArr);


        $elegibilidadeArr = array('' => 'Todos', 'T' => 'Titular', 'D' => 'Dependente');
        $this->set('elegibilidadeArr', $elegibilidadeArr);


        $anoArr = array('' => 'Ano...');
        $ano_atual = date('Y');
        for ($ano = $ano_atual; $ano >= 2000; $ano--) {
            $anoArr[$ano] = $ano;
        }
        $this->set('anoArr', $anoArr);



        $mesArr = array(
            '' => 'Mês...',
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maior',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        );
        $this->set('mesArr', $mesArr);

        $periodoArr = array('' => 'Selecione...');
        $ano_atual = date('Y');
        for ($periodo = 1; $periodo <= 12; $periodo++) {
            $periodoArr[$periodo] = $periodo;
        }
        $this->set('periodoArr', $periodoArr);

        $simnaoArr = $this->Funcoes->parametros('Sim/Não');
        $this->set('simnaoArr', $simnaoArr);



        #$paginasArr = $tipoArr = $this->Funcoes->parametros('relatorio_paginas', 'list', null, false);
        #$this->set('paginasArr',$paginasArr);

        $this->loadModel('Parametro');
        $paginasArr = $this->Parametro->find('all', array('conditions' => array('tipo' => 'relatorio_paginas'), 'fields' => 'nome, valor, ordenacao', 'recursive' => -1));
        $paginasArr = $this->Funcoes->retiraSubArray($paginasArr, 'Parametro');
        $this->set('paginasArr', $paginasArr);


        if ($this->request->is(array('post', 'put'))) {
            $url = 'http://138.68.1.49/relatorio';
            $data = array();


            $paginas = $this->data['paginas'];
            //$paginas = $this->converter_tipo_array($this->data['paginas']);

            $dataPagina = array();
            foreach ($paginas as $pgV) {
                $pgArr = explode('_', $pgV);
                $dataPagina[$pgArr[0]] = (int) $pgArr[1];
            }

            $data['paginas'] = $dataPagina;
            $data['plano_ids'] = $this->converter_tipo_array($this->data['Relatorio']['plano_id']);
            $data['subfatura_ids'] = $this->converter_tipo_array($this->data['Relatorio']['subfatura_id']);
            $data['cliente_id'] = $this->Session->read('Auth.Usuario.cliente_id');
            $data['beneficio_ids'] = $this->converter_tipo_array($this->data['Relatorio']['beneficio_id']);
            $data['data_referencia'] = $this->data['Relatorio']['data_referencia'];
            $data['periodo'] = $this->data['Relatorio']['periodo'];
            $data['competencia'] = $this->data['Relatorio']['ano'] . '-' . str_pad($this->data['Relatorio']['mes'], 2, 0, STR_PAD_LEFT) . '-01';
            $data['copart'] = (bool) $this->data['Relatorio']['copart'];
            $data['elegibilidade'] = $this->data['Relatorio']['elegibilidade'];
            $data['maiores_utilizadores'] = (int) $this->data['Relatorio']['maiores_utilizadores'];
            $data['maiores_prestadores'] = (int) $this->data['Relatorio']['maiores_prestadores'];
            $data['qtd_consultas_hiper'] = (int) $this->data['Relatorio']['qtd_consultas_hiper'];
            $data['trinta_hiper'] = (isset($this->data['trinta_hiper']) && $this->data['trinta_hiper'] == 1) ? true : false;
            $data['logo_cliente'] = $this->Funcoes->busca_logo('cliente', $this->cliente_id);

            //krumo($this->data);
            //krumo($data);
            //echo json_decode($data);
            //exit;

            /*   
            $data = array();
            $data['paginas'] = array(1=>1);
            $data['plano_ids'] = array(6,7,8,9,10,11,12,13);
            $data['subfatura_ids'] = array(1);
            $data['cliente_id'] = 1;
            $data['beneficio_ids'] = array(1);
            $data['data_referencia'] = 'data_pagamento';
            $data['periodo'] = 12;
            $data['competencia'] = "2017-01-01";
            $data['copart'] = true;
            $data['maiores_utilizadores'] = 20;
            $data['maiores_prestadores'] = 20;
            $data['qtd_consultas_hiper'] = 12;
            $data['trinta_hiper'] = true;
            */

            $result = parent::curlCall($url, $data);
            $result = (array) json_decode($result);

            $dateTimeNow = date('Y-m-d H:i:s');
            if (count($result) > 0 && isset($result['viewpdf'])) {
                #header('Location: '.$result['viewpdf']);
                echo "<script type=\"text/javascript\" language=\"Javascript\">window.open('" . $result['viewpdf'] . "');</script>";
                $link = "Caso não tenha aberto automaticamente, <a href='{$result['viewpdf']}' target='_blank'> Clique Aqui </a>";
                $this->Session->setFlash("Relatório Gerado com Sucesso! " . $link);
                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Relatório - Gerencial',
                    'description'         => json_encode($data),
                    'server_description'  => '',
                    'data_cadastro'       => $dateTimeNow,
                    'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                $this->Session->write('error_form', $this->data);
            } else {


                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Erro Relatório - Gerencial',
                    'description'         => json_encode($data),
                    'server_description'  => '',
                    'data_cadastro'       => $dateTimeNow,
                    'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                $this->Session->write('error_form', $this->data);
                $this->Session->setFlash("Erro ao Gerar o Relatório. Por favor, tente novamente mais tarde! ");
                #echo '<script>alert("Erro ao Gerar o Relatório. Favor tente mais tarde!");</script>';
                $this->redirect(array('action' => 'gerencial'));
            }
        } else {
            #BEGIN - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
            if ($this->Session->check('error_form')) {
                $error_form = $this->Session->read('error_form');
                $this->Session->delete('error_form');
                if (is_array($error_form)) {
                    $data_new = array_merge($this->data, $error_form);
                    $this->request->data = $data_new;
                }
            }
            #END - RETORNO DADOS EM CASO DE ERRO NA INSERÇÃO
        }
    }


    public function admin_limpar_session()
    {
        $this->Session->delete('error_form');
        $this->redirect(array('action' => 'gerencial'));
    }

    public function admin_exportacao()
    {
        $TABLE = $this->table;


        $this->loadModel('Beneficio');
        $beneficioArr = $this->Beneficio->find('list', array('conditions' => array('status' => 1, 'cliente_id' => $this->cliente_id), 'fields' => 'id,descricao', 'recursive' => -1));
        $beneficioArr = $this->Funcoes->select_merge($beneficioArr, 'Beneficio...');
        $this->set('beneficioArr', $beneficioArr);

        $elegibilidadeArr = array('' => 'Todos', 'T' => 'Titular', 'D' => 'Dependente');
        $this->set('elegibilidadeArr', $elegibilidadeArr);

        $anoArr = array('' => 'Ano...');
        $ano_atual = date('Y');
        for ($ano = $ano_atual; $ano >= 2000; $ano--) {
            $anoArr[$ano] = $ano;
        }
        $this->set('anoArr', $anoArr);


        $mesArr = array(
            '' => 'Mês...',
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maior',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        );
        $this->set('mesArr', $mesArr);

        $periodoArr = array('' => 'Selecione...');
        $ano_atual = date('Y');
        for ($periodo = 1; $periodo <= 12; $periodo++) {
            $periodoArr[$periodo] = $periodo;
        }
        $this->set('periodoArr', $periodoArr);

        $simnaoArr = $this->Funcoes->parametros('Sim/Não');
        $this->set('simnaoArr', $simnaoArr);

        $tipoExportacaoArr = array('' => 'Selecione...', 'sinistro' => 'Sinistro', 'fatura' => 'Fatura');
        $this->set('tipoExportacaoArr', $tipoExportacaoArr);



        if ($this->request->is(array('post', 'put'))) {
            $url = 'http://138.68.1.49/excel';
            $data = array();

            //    krumo($this->data);
            //    exit;

            $data['tipo_arquivo'] = $this->data['Relatorio']['tipo'];
            $data['cliente_id'] = $this->Session->read('Auth.Usuario.cliente_id');
            $data['beneficio_ids'] = array_values(array($this->converter_tipo($this->data['Relatorio']['beneficio_id'])));
            $data['data_referencia'] = 'data_lancamento';
            if ($this->data['Relatorio']['tipo'] == 'sinistro') {
                $data['data_referencia'] = $this->data['Relatorio']['data_referencia'];
            }


            $competencia = $this->data['Relatorio']['mes_ano'];
            $meses_anteriores = (isset($this->data['meses_anteriores']) && $this->data['meses_anteriores'] == 1) ? true : false;

            if ($meses_anteriores) {
                $format = "Y-m-d";
                $periodo = $this->data['Relatorio']['periodo'];
                $calculo = " - {$periodo} MONTH";
                $competencia = $this->Funcoes->calculaData($format, $competencia, $calculo);
            }
            $data['competencia'] = $competencia;
            $data['periodo'] = $this->data['Relatorio']['periodo'];
           




            #echo json_encode($data);
            #exit;

            /**
             * $format = "d/m/Y H:i:s";
             * $date = "2009-05-20 06:34:00";
             * $calculo = "+ 3 days";
             * calculaData( $format, $date, $calculo );
             */

            $result = parent::curlCall($url, $data);
            $result = (array) json_decode($result);

            #krumo($result);
            #exit;


            $dateTimeNow = date('Y-m-d H:i:s');
            if (count($result) > 0 && isset($result['download']) && $result['status'] == 'ok') {
                #echo "<script type=\"text/javascript\" language=\"Javascript\">window.open('".$result['downloadpdf']."'); </script>"; 

                $link = "Caso não tenha aberto automaticamente, <a href='{$result['download']}' target='_blank'> Clique Aqui </a>";
                $this->Session->setFlash("Relatório Gerado com Sucesso! " . $link);

                #GRAVA LOG
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Exportação - ' . ucwords($this->data['Relatorio']['tipo']),
                    'description'         => json_encode($data),
                    'server_description'  => json_encode($result),
                    'mensagem'            => "Relatório Gerado com Sucesso! " . $link,
                    'data_cadastro'       => $dateTimeNow,
                    'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);

                $this->redirect($result['download']);

                #$url_old = Router::url('/admin/relatorio/exportacao',true);
                #echo "<script type=\"text/javascript\" language=\"Javascript\">window.location.href='".$url_old."';</script>"; 
                #echo "<script type=\"text/javascript\" language=\"Javascript\">window.history.back();</script>"; 
                #$this->redirect(array('action'=>'exportacao'));

            } else {

                #GRAVA LOG
                $mensagem = "Erro ao Gerar a Exportação. Por favor, tente novamente mais tarde! ";
                $this->loadModel('Log');
                $this->Log->create();
                $data_log = array(
                    'id' => '',
                    'log'                 => 'Erro Exportação - ' . ucwords($this->data['Relatorio']['tipo']),
                    'description'         => json_encode($data),
                    'server_description'  => json_encode($result),
                    'mensagem'            => $mensagem,
                    'data_cadastro'       => $dateTimeNow,
                    'usuario_id'          => $this->Session->read('Auth.Usuario.id')
                );
                $this->Log->save($data_log);
                $this->Session->setFlash($mensagem);
                #echo '<script>alert("Erro ao Gerar o Relatório. Favor tente mais tarde!");</script>';
                $this->redirect(array('action' => 'exportacao'));
            }
        }
    }


    public function admin_busca_acesso() {}


    public function admin_teste_curl()
    {
        $data = array();
        $data['paginas'] = array(1 => 1);
        $data['plano_ids'] = array(6, 7, 8, 9, 10, 11, 12, 13);
        $data['subfatura_ids'] = array(1);
        $data['cliente_id'] = 1;
        $data['beneficio_ids'] = array(1);
        $data['data_referencia'] = 'data_pagamento';
        $data['periodo'] = 12;
        $data['competencia'] = "2017-01-01";
        $data['copart'] = true;
        $data['maiores_utilizadores'] = 20;
        $data['maiores_prestadores'] = 20;
        $data['qtd_consultas_hiper'] = 12;
        $data['trinta_hiper'] = true;


        ini_set('MAX_EXECUTION_TIME', 300);
        $url = 'http://138.68.1.49/relatorio';
        $ch = curl_init($url);
        $payload = json_encode($data);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $result = (array) json_decode($result);
        if (count($result) == 0 && isset($result['viewpdf'])) {
            #header('Location: '.$result['viewpdf']);
            echo "<script type=\"text/javascript\" language=\"Javascript\">window.open('" . $result['viewpdf'] . "');</script>";
        } else {
            echo '<script>alert("Erro ao Gerar o Relatório"); window.close();</script>';
        }

        exit;
    }


    public function admin_teste_curl_multiple()
    {

        // Init connection 
        //$ch = curl_init(); 
        //// Set curl options curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt'); 
        //curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt'); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        //curl_setopt($ch, CURLOPT_POST, 1); 
        //// Perform login 
        //curl_setopt($ch, CURLOPT_URL, "http://www.mysite/login.php"); 
        //$post = array('username' => 'username' , 'password' => 'password'); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, 
        //http_build_query($post)); 
        //$result = curl_exec($ch); 
        //// Send multiple requests after being logged on 
        //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1); 
        //for($i = 0 ; $i  'changing_value'); 
        //    curl_setopt($ch, CURLOPT_URL, 'www.myweb.ee/changing_url'); 
        //    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post)); 
        //    curl_exec($ch); 
        //} 

    }


    #BEGIN - AFASTADOS 
    public function admin_afastados($download = 0)
    {
        $TABLE = 'Afastado';
        $this->loadModel($TABLE);
        if ($this->request->is('post')):
            if (isset($this->data['Relatorio_afastado_form_busca'])):
                $this->Session->write($this->name_search, $this->data['Relatorio_afastado_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        $search = $this->Session->read($this->name_search);
        $condition = array();
        $condition[] = $TABLE . '.situacao = "A"';

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
            if (isset($search['cpf']) && $search['cpf'] != ''):
                $cpf = $search['cpf'];
                $cpf = str_replace('.', '', $cpf);
                $cpf = str_replace('-', '', $cpf);

                $condition[] = $TABLE . '.cpf = "' . $cpf . '"';
            endif;

            if (isset($search['status']) && $search['status'] != ''):
                $condition[] = $TABLE . '.status = ' . $search['status'];
            endif;
        endif;



        #$condition[] = 'Importacao.cliente_id = '.$this->cliente_id;
        # $condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        $condition[] = 'Afastado.status = 1 ';
        #$condition[] = 'Afastado.status = 1 ';
        #$condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        #$condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        # $condition[] = 'Afastado.empresa_id = 346';


        #$this->$TABLE->recursive = 2;


        if ($download == 1) {
            $this->admin_afastados_download($this->chave, $condition);
            exit;
        }


        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 30,
            'order' => array('id' => 'DESC'),
            'group' => array('Afastado.beneficiario_id'),
            'recursive' => 2
        );

        #krumo($condition);

        $rows = $this->Paginator->paginate($TABLE);

        $this->set('rows', $rows);
        $this->set('search', $search);
        $this->set('TABLE', $TABLE);
    }

    public function admin_afastados_download($chave, $condition)
    {
        if ($chave != $this->chave) {
            die;
        }
        $TABLE = 'Afastado';


        #BUSCA DOWNLOAD
        #$this->$TABLE->unBindModel(array('belongsTo' => array('Beneficiario'=>array('belongsTo' => array('Beneficiario')))));
        $this->loadModel('Beneficiario');
        $this->Beneficiario->unBindModel(array(
            'belongsTo' => array('UsuarioCriador', 'UsuarioAtualizacao', 'Empresa'),
            'hasMany' => array('Afastado', 'BeneficioPrevidenciario', 'Absenteismo')
        ));
        $this->Beneficiario->BindModel(array('hasMany' => array('Atendimento' => array('conditions' => ['status' => 1], 'limit' => 1, 'order' => ['data_cadastro' => 'desc']))));

        $rows = $this->$TABLE->find('all', [
            'conditions' => $condition,
            'group' => ['Afastado.beneficiario_id'],
            'order' => ['Afastado.id' => 'DESC'],
            // 'limit'=>10,
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


            $list[] = [
                'cliente' => $this->Funcoes->utf8ToIso($row['Beneficiario']['Cliente']['nome']),
                'id' => $row[$TABLE]['beneficiario_id'],
                'beneficiario' => $this->Funcoes->utf8ToIso($row['Beneficiario']['nome']),
                'cpf' => $cpf,
                'data_inicio' => $this->Funcoes->dateToView(@$row[$TABLE]['data_inicio_afastamento']),
                'data_fim' => $this->Funcoes->dateToView(@$row[$TABLE]['data_fim_afastamento']),
                'cid' => $row[$TABLE]['cid'],
                'tipo_afastamento' => $this->Funcoes->utf8ToIso($row[$TABLE]['tipo_afastamento']),
                'assistencia_medica' => $this->Funcoes->utf8ToIso($row[$TABLE]['assistencia_medica']),
                'plano_assistencia_medica' => $this->Funcoes->utf8ToIso($row[$TABLE]['plano_assistencia_medica']),
                'situacao' => ($row[$TABLE]['situacao'] == 'A') ? 'Afastado' : 'Retorno ao Trabalho',
                'data_interacao' => $data_interacao,
                'desc_interacao' => $desc_interacao,
                'data_cadastro' => $this->Funcoes->dateToView($row[$TABLE]['data_cadastro'], true),
                'via' => $this->Funcoes->utf8ToIso(($row[$TABLE]['importacao_id'] == '') ? 'Entrada Manual' : 'Importação')
            ];
        }

        // krumo($list);
        // exit;

        //Gerando planilha .xlsx para download
        App::import('Vendor', 'PHPExcel');
        $oPlanilha = new \Vendor\PHPExcel();
        $oPlanilha->addPlanilha("Afastados");
        $oPlanilha->setColunas([
            'cliente' => 'Cliente',
            'id' => 'ID Beneficiario',
            'beneficiario' => 'Beneficiario',
            'cpf' => 'CPF',
            'data_inicio' => 'Data Inicio Afastamento',
            'data_fim' => 'Data Fim Afastamento',
            'cid' => 'CID',
            'tipo_afastamento' => 'Tipo de Afastamento',
            'assistencia_medica' => $this->Funcoes->utf8ToIso('Assistência Médica'),
            'plano_assistencia_medica' => $this->Funcoes->utf8ToIso('Plano de Assistência Médica'),
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
    #END - AFASTADOS 
    #BEGIN - AFASTADOS 
    public function admin_beneficiarios($download = 0)
    {
        $TABLE = 'Beneficiario';
        $this->loadModel($TABLE);
        if ($this->request->is('post')):
            if (isset($this->data['Relatorio_afastado_form_busca'])):
                $this->Session->write($this->name_search, $this->data['Relatorio_afastado_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        $search = $this->Session->read($this->name_search);
        $condition = array();
        $condition[] = 'Beneficiario.status = 1 ';
        $condition[] = 'Beneficiario.cliente_id = ' . $this->cliente_id;

        // krumo($this->Session->read('Auth.Usuario');
        // exit;

        if ($download == 1) {
            $this->admin_beneficiarios_download($this->chave, $condition);
            echo 'exportação não está disponível!';
            exit;
        }


        $this->paginate = array(
            'conditions' => $condition,
            'limit' => 30,
            'order' => array('id' => 'DESC'),
            'recursive' => 2
        );

        #krumo($condition);

        $rows = $this->Paginator->paginate($TABLE);

        $this->set('rows', $rows);
        $this->set('search', $search);
        $this->set('TABLE', $TABLE);
    }

    public function admin_beneficiarios_download($chave, $condition)
    {
        if ($chave != $this->chave) {
            die;
        }
        $TABLE = 'Beneficiario';


        #BUSCA DOWNLOAD
        #$this->$TABLE->unBindModel(array('belongsTo' => array('Beneficiario'=>array('belongsTo' => array('Beneficiario')))));
        $this->loadModel('Beneficiario');
        $rows = $this->$TABLE->find('all', [
            'conditions' => $condition,
            'order' => ['Beneficiario.id' => 'DESC'],
            'recursive' => 2
        ]);

        #TRATA DOWNLOAD
        $list = [];
        foreach ($rows as $row) {

            $cpf = $row['Beneficiario']['cpf'];
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);


            $list[] = [
                'cliente' => $this->Funcoes->utf8ToIso($row['Cliente']['nome']),
                'id' => $row['Beneficiario']['id'],
                'beneficiario' => $this->Funcoes->utf8ToIso($row['Beneficiario']['nome']),
                'cpf' => $cpf,
                'situacao' => $this->Funcoes->utf8ToIso($row['Beneficiario']['situacao']),
                'data_cadastro' => $this->Funcoes->dateToView($row['Beneficiario']['data_cadastro'], true),
                'via' => $this->Funcoes->utf8ToIso(($row['Beneficiario']['importacao_id'] == '') ? 'Entrada Manual' : 'Importação')
            ];
        }

        // krumo($list);
        // exit;

        //Gerando planilha .xlsx para download
        App::import('Vendor', 'PHPExcel');
        $oPlanilha = new \Vendor\PHPExcel();
        $oPlanilha->addPlanilha("Afastados");
        $oPlanilha->setColunas([
            'cliente' => 'Cliente',
            'id' => 'ID Beneficiario',
            'beneficiario' => 'Beneficiario',
            'cpf' => 'CPF',
            'situacao' => $this->Funcoes->utf8ToIso('Situação'),
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

        $oPlanilha->downloadArquivo("beneficiarios_" . date('d-m-Y H_m_s') . ".xlsx");


        exit;
    }
    #END - AFASTADOS 



    #BEGIN - ATENDIMENTOS PENDENTES 
    public function admin_atendimentos_pendentes($download = 0)
    {
        $TABLE = 'Agendamento';
        $this->loadModel($TABLE);
        if ($this->request->is('post')):
            if (isset($this->data['relatorio_atendimentos_pendentes_form_busca'])):
                $this->Session->write($this->name_search, $this->data['relatorio_atendimentos_pendentes_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;
        $search = $this->Session->read($this->name_search);

        $condition = array();

        if (!in_array($this->Session->read('Auth.Usuario.perfil_id'), [1, 2, 3, 13])) {
            $condition[] = $TABLE . '.usuario_agendamento_id = ' . $this->Session->read('Auth.Usuario.id');
        }

        if (is_array($search)):
            if (!empty($search['cod_']) && is_numeric($search['cod_'])):
                $condition[] = array($TABLE . '.id = "' . $search['cod_'] . '"');
            endif;
            if (in_array($this->Session->read('Auth.Usuario.perfil_id'), [1, 2, 3, 13])) {
                if (isset($search['usuario_agendamento_id']) && $search['usuario_agendamento_id'] != ''):
                    $condition[] = $TABLE . '.usuario_agendamento_id = ' . $search['usuario_agendamento_id'];
                endif;
            }
            if (isset($search['status']) && $search['status']  != ''):
                $condition[] = $TABLE . '.status = ' . $search['status'];
            endif;

        endif;

        $this->set('search', $search);
        #END - BUSCA



        #$condition[] = 'Importacao.cliente_id = '.$this->cliente_id;
        #$condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        $condition[] = 'Agendamento.status = 0 ';
        #$condition[] = 'Afastado.status = 1 ';
        #$condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        #$condition[] = 'Beneficiario.cliente_id = '.$this->cliente_id;
        # $condition[] = 'Afastado.empresa_id = 346';





        #$this->$TABLE->recursive = 2;


        if ($download == 1) {
            $this->admin_atendimentos_pendentes_download($this->chave, $condition);
            exit;
        }


        $this->paginate = array(
            'Agendamento' =>
            array(
                'conditions' => $condition,
                'limit' => 30,
                'order' => array('Agendamento.status' => 'asc', 'Agendamento.data_hora' => 'asc'),
                'recursive' => 3
            )
        );

        #krumo($condition);

        $rows = $this->Paginator->paginate($TABLE);
        // krumo($rows);
        // exit;


        $this->loadModel('Usuario');

        $perfis_qv = '1,2,3,6,7,8,12,13';
        $dataArr = $this->Usuario->find('all', array(
            'conditions' => array('Usuario.perfil_id IN (' . $perfis_qv . ')', 'Usuario.status' => 1),
            'field' => 'Usuario.id,Usuario.nome',
            'order' => array('Usuario.usuario'),
            'recursive' => -1
        ));

        $usrList = $this->Session->read('usr_search_at_pend');
        if (!$this->Session->check('usr_search_at_pend')) {
            $usrList = array();
            if (count($dataArr) > 0) {
                $cod_user_not_allowed = array(276076, 345841, 313678, 275725, 8, 9);
                foreach ($dataArr as $usersArr) {
                    if (!in_array($usersArr['Usuario']['id'], $cod_user_not_allowed)) {
                        $usrList[$usersArr['Usuario']['id']] = ucwords(strtolower($usersArr['Usuario']['nome']));
                    }
                }
            }
            $usrList = $this->Funcoes->select_merge($usrList, 'Responsável...');

            $this->Session->write('usr_search_at_pend', $usrList);
        }

        $this->set('usrList', $usrList);


        $this->set('rows', $rows);
        $this->set('search', $search);
        $this->set('TABLE', $TABLE);
    }

    public function admin_atendimentos_pendentes_download($chave, $condition)
    {
        if ($chave != $this->chave) {
            die;
        }
        $TABLE = 'Agendamento';


        #BUSCA DOWNLOAD
        #$this->$TABLE->unBindModel(array('belongsTo' => array('Beneficiario'=>array('belongsTo' => array('Beneficiario')))));
        // $this->loadModel('Beneficiario');
        // $this->Beneficiario->unBindModel(array('belongsTo' => array('Cliente','UsuarioCriador','UsuarioAtualizacao','Empresa'),
        //                                        'hasMany'=>array('Afastado','BeneficioPrevidenciario','Absenteismo')));
        // $this->Beneficiario->BindModel(array('hasMany' => array('Atendimento'=>array('conditions'=>['status'=>1],'limit'=>1,'order'=>['data_cadastro'=>'desc']))));

        // $rows = $this->$TABLE->find('all',['conditions'=>$condition,
        //                                 'group'=>['Afastado.beneficiario_id'],
        //                                 'order'=>['Afastado.id'=>'DESC'],
        //                                 'recursive'=> 2
        //                                 ]);


        #$this->Beneficiario->unBindModel(array('belongsTo' => array('Cliente','UsuarioCriador','UsuarioAtualizacao','Empresa'),'hasMany'=>array('Afastado','BeneficioPrevidenciario','Absenteismo')));

        $this->loadModel('Atendimento');
        $this->Atendimento->unBindModel(array('hasMany' => array('Agendamento'), 'belongsTo' => array('UsuarioResponsavel')));

        $this->loadModel('Beneficiario');
        $this->Beneficiario->unBindModel(array('hasMany' => array('Afastado', 'BeneficioPrevidenciario', 'Absenteismo', 'Atendimento'), 'belongsTo' => array('UsuarioCriador', 'UsuarioAtualizacao', 'Empresa')));

        $this->loadModel('Usuario');
        $this->Usuario->unBindModel(array('hasMany' => array('UsuarioCliente', 'UsuarioBi'), 'belongsTo' => array('UsuarioCriador', 'Perfil', 'GrupoEmpresarial')));





        // $this->Beneficiario->unBindModel(array('belongsTo' => array('Cliente','UsuarioCriador','UsuarioAtualizacao','Empresa'),
        // 'hasMany'=>array('Afastado','BeneficioPrevidenciario','Absenteismo')));
        // $this->Beneficiario->BindModel(array('hasMany' => array('Atendimento'=>array('conditions'=>['status'=>1],'limit'=>1,'order'=>['data_cadastro'=>'desc']))));
        $rows = $this->$TABLE->find('all', [
            'conditions' => $condition,
            'order' => array('Agendamento.status' => 'asc', 'Agendamento.data_hora' => 'asc'),
            'recursive' => 3,
            #'limit'=>10
        ]);



        // krumo($condition);
        // krumo($rows);
        // exit;

        #TRATA DOWNLOAD
        $list = [];
        foreach ($rows as $row) {

            $cpf = $row['Atendimento']['Beneficiario']['cpf'];
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            $cpf = $this->Funcoes->formata_cpf($cpf);



            $list[] = [
                'cliente' => $this->Funcoes->utf8ToIso($row['Atendimento']['Beneficiario']['Cliente']['nome']),
                'id' => $row['Atendimento']['beneficiario_id'],
                'beneficiario' => $this->Funcoes->utf8ToIso($row['Atendimento']['Beneficiario']['nome']),
                'cpf' => $cpf,
                'data_agendamento' => $this->Funcoes->dateToView(@$row[$TABLE]['data_hora']),
                'responsavel' => $this->Funcoes->utf8ToIso($row['UsuarioAgendamento']['nome']),
                'quem_agendou' => $this->Funcoes->utf8ToIso($row['Usuario']['nome']),
                'data_criacao' => $this->Funcoes->dateToView($row[$TABLE]['data_cadastro'], true),
                'status' => 'Aguardando'
            ];
        }



        //Gerando planilha .xlsx para download
        App::import('Vendor', 'PHPExcel');
        $oPlanilha = new \Vendor\PHPExcel();
        $oPlanilha->addPlanilha("Atendimentos Pendentes");
        $oPlanilha->setColunas([
            'cliente' => 'Cliente',
            'id' => $this->Funcoes->utf8ToIso('ID Beneficiário'),
            'beneficiario' => $this->Funcoes->utf8ToIso('Beneficiário'),
            'cpf' => 'CPF',
            'data_agendamento' => 'Data do Agendamento',
            'responsavel' => $this->Funcoes->utf8ToIso('Responsável'),
            'quem_agendou' => 'Quem Agendou',
            'data_criacao' => $this->Funcoes->utf8ToIso('Data de Criação'),
            'status' => 'Status'
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

        $oPlanilha->downloadArquivo("atendimentos_pendentes " . date('d-m-Y H_m_s') . ".xlsx");


        exit;
    }
    #END - ATENDIMENTOS PENDENTES

}
