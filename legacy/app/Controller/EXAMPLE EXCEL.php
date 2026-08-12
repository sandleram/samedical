<?php

App::uses('AppController', 'Controller');

class ImportacaoCopartController extends AppController {

    public $components = array('Paginator', 'Funcoes');
    public $validateUser = '';
    public $name_search = '';
    public $table = '';



    public function beforeFilter() {
        parent::beforeFilter();
        $TABLE = $this->table;

	$operadoraArr = array();
	$this->loadModel('Operadora');
	$sql = "select SUBSTRING_INDEX( cod_operadora,',',9999999999999999999) as cod_operadoras
		 from importacao_copart
		 group by cod_operadora ";
	$operdorasArr = $this->$TABLE->query($sql);
	if(isset($operdorasArr[0][0]['cod_operadoras']) && $operdorasArr[0][0]['cod_operadoras'] != ''){
	    $operadoras = $operdorasArr[0][0]['cod_operadoras'];
	    $operadoraArr = $this->Operadora->find('list',array('coditions'=>array('cod_operadora in ('.$operadoras.')'),'fields'=>'cod_operadora, nome','order'=>array('nome')));
	}

	$dataDef = ($this->params['action'] == 'index') ? 'Operadora...' : 'Selecione...';
        $operadoraArr = $this->Funcoes->select_merge($operadoraArr, $dataDef);
	$this->set('operadoraArr',$operadoraArr);


	if($this->params['action'] == 'index_download'){
	    if(isset($this->params['pass'][1])){
		$this->set('nome_arquivo',$this->params['pass'][1]);
	    }
	}
	$this->set('controller',$this->params['controller']);
    }




    /**
     * AUTOMÁTICO
     * Retira da sessão a busca feita pelo usuário Element/admin/search_filter.ctp
     * @param type $search
     */
    public function busca_unset($search) {
        $this->autoRender = false;
        parent::all_busca_unset($search,$this->name_search);
        $this->redirect(array('action' => 'index'));
    }

    public function index($id_export = '') {
        $this->set('title_for_layout', 'Copart :: Importações');
	      $TABLE = $this->table;

        #BEGIN - BUSCA
        if ($this->request->is('post')):
            if (isset($this->data[$this->params['controller'].'_form_busca'])):
                $this->Session->write($this->name_search, $this->data[$this->params['controller'].'_form_busca']); //USADO PARA PAGINAÇÃO
            endif;
        endif;

        $search = $this->Session->read($this->name_search);
        $condition = array();
        $condition[] = $this->table.".cod_conta = {$_SESSION['cod_conta']}"; #2 É EXCLUÍDO
        $condition['OR'] = array($this->table.".tipo_registro = ''",$this->table.".tipo_registro IS NULL",$this->table.".tipo_registro = 2");


        if (is_array($search)):
            if (!empty($search['cod_']) && is_numeric($search['cod_'])):
                $condition[] = array($this->table.'.codigo_conjunto = "' . $search['cod_'] . '"');
            endif;
            if (!empty($search['competencia'])):
		$condition[] = array("date_format({$this->table}.data_pagto_sinistro,'%m/%Y') = '{$search['competencia']}'" );
            endif;
            if (!empty($search['operadora'])):
                $buscaArr = explode(' ',$search['operadora']);
                if(count($buscaArr)> 0):
                   foreach($buscaArr as $vBusca):
                       $condition[] = 'Operadora.nome like "%' . $vBusca . '%"';
                   endforeach;
                endif;
            endif;
        endif;
        $this->set('search', $search);
        #END - BUSCA


	#BUSCA PARA EXPOTRAÇÃO
	if($id_export !== ''){
        $rsCopart = $this->$TABLE->find('first', [
            'conditions' => [
                'cod_importacao_copart' => $id_export
            ],
            'fields'=>"codigo_conjunto, date_format(data_pagto_sinistro,'%Y-%m-%01') as competencia"
        ]);
        
		if(isset($rsCopart[$TABLE]['codigo_conjunto']) && $rsCopart[$TABLE]['codigo_conjunto'] != ''){
		    $condition = array();
		    $condition[] = 'CopartExtrato.codigo_conjunto = '.$rsCopart[$TABLE]['codigo_conjunto'];
		    $condition[] = "date_format(CopartExtrato.competencia, '%Y-%m-01') = '".$rsCopart[0]['competencia']."'";
		    $this->loadModel('CopartExtrato');
		    $fields = "CopartExtrato.carteira,CopartExtrato.nome_titular,CopartExtrato.carteira_titular,"
		      . "CopartExtrato.matricula,CopartExtrato.cpf_titular,CopartExtrato.prestador,"
		      . "CopartExtrato.valor_original,CopartExtrato.valor_desconto,DATE_FORMAT(CopartExtrato.competencia, '%m/%Y') AS competencia,"
		      . "CopartExtrato.isento,CopartExtrato.tipo,CopartExtrato.liberacao_vh";
		    $rows = $this->CopartExtrato->find('all',array('conditions'=>$condition,'fields'=>$fields));
		}else{
		    echo '<h3>Nenhum registro foi encontrado</h3><br><br> ';
		    sleep(5);
		    $this->redirect($this->referer());
		}

//	    krumo($joins);
//	    krumo($fields);
//	    krumo($order);
//	    krumo($condition);
//	    exit;

        //Gerando planilha .xlsx para download
        App::import('Vendor', 'PHPExcel');
        $oPlanilha = new \Vendor\PHPExcel();
        $oPlanilha->addPlanilha("Coparticipação");
        $oPlanilha->setColunas([
            'carteira'          => 'Carteira',
            'nome_titular'      => 'Nome Titular',
            'carteira_titular'  => 'Carteria Titular',
            'matricula'         => 'Matricula',
            'cpf_titular'       => 'CPF Titular',
            'prestador'         => 'Prestador',
            'valor_original'    => 'Valor Original',
            'valor_desconto'    => 'Valor Desconto',
            'competencia'       => 'Competência',
            'isento'            => 'Isento',
            'tipo'              => 'Tipo',
            'liberacao_vh'      => 'Liberacao VH'
        ]);
        
        $oPlanilha->addLinhaTitulo([
            'negrito',
            'borda'     => 'tlrb',
            'cor-fundo' => 'azulado-claro',
            'texto'
        ]);
        
        foreach($rows AS $row){
            foreach($row[0] AS $k => $v)
                $row['CopartExtrato'][$k] = $v;
            
            $oPlanilha->addLinha($row['CopartExtrato'], [], [
                'borda' => 'lrb',
                'texto',
            ]);
        }
        
        $oPlanilha->aplicarEstilo('monetario', 'G2:H' . $oPlanilha->getUltimaLinha());
        $oPlanilha->setLarguraAutomaticaColunas();
        $oPlanilha->setAlturaAutomaticaLinhas();
        $oPlanilha->downloadArquivo("Coparticipação {$rsCopart[$TABLE]['codigo_conjunto']}.xlsx");
	}else{
	    #BUSCA INDEX
	    $fields = " ImportacaoCopart.cod_importacao_copart,
			ImportacaoCopart.data_importacao,
			date_format(ImportacaoCopart.data_pagto_sinistro,'%m/%Y') as competencia,
			Operadora.nome as operadora,
			ImportacaoCopart.codigo_conjunto,
			sum(ImportacaoCopart.valor_original) as valor_original,
			sum(ImportacaoCopart.valor_reembolso) as valor_reembolso,
			(select if(sum(valor_desconto) is null, 0 , sum(valor_desconto)) from copart_extrato AS c where codigo_conjunto = ImportacaoCopart.codigo_conjunto AND date_format(c.competencia, '%m/%Y') =
                  date_format(ImportacaoCopart.data_pagto_sinistro, '%m/%Y')) as valor_desconto,
			if((select count(liberacao_vh) from copart_extrato where codigo_conjunto = ImportacaoCopart.codigo_conjunto and liberacao_vh = 1) > 0, 1, 0) as liberacao_vh ,
			(select count(1) from copart_extrato where codigo_conjunto = ImportacaoCopart.codigo_conjunto) as qtd_extrato,
      (SELECT   cod_empresa_beneficiario FROM importacao_copart AS b WHERE b.codigo_conjunto = ImportacaoCopart.codigo_conjunto AND date_format(b.data_pagto_sinistro,'%m/%Y') = date_format(ImportacaoCopart.data_pagto_sinistro,'%m/%Y') AND cod_empresa_beneficiario IS NOT NULL    LIMIT 1 ) AS cod_beneficiario_teste ";
	    $joins = array(
			    array(  'table' => 'beneficio',
				    'alias' => 'Beneficio',
				    'type' => 'INNER',
				    'conditions' => array('Beneficio.cod_beneficio = ImportacaoCopart.cod_beneficio')
			    ),
			    array(  'table' => 'operadora',
				    'alias' => 'Operadora',
				    'type' => 'INNER',
				    'conditions' => array('Operadora.cod_operadora = Beneficio.cod_operadora')
			    )
			);
	    $order = array("data_pagto_sinistro"=>"DESC");
	    $group = array("date_format(data_pagto_sinistro,'%m/%Y')", "codigo_conjunto");
	    $this->paginate = array(
		  'fields'=>$fields,
		  'conditions' => $condition,
		  'joins'=>$joins,
		  #'contain' => array('Aluno' ,'Usuario'),
		  #'link' => array('Financeiro' => array('Aluno' => 'Usuario')),
		  'group' => $group,
		  'order' => $order,
		  'limit' => 10
	      );
	      $rows = $this->Paginator->paginate();
	}
        //debug($this->$TABLE->getDataSource()->getLog(false, false));
        //krumo($rows);
	       //exit();
        $this->set('rows', $rows);
    }


    public function delete($id = null) {
        $TABLE = $this->table;

	 if ($id !== null) { #EXCLUSÃO UNITÁRIA
            $rsCopart = $this->$TABLE->find('first',array('conditions'=>array('cod_importacao_copart'=>$id),'fields'=>'codigo_conjunto'));

	     if(isset($rsCopart[$TABLE]['codigo_conjunto']) && $rsCopart[$TABLE]['codigo_conjunto'] != ''){
		$this->$TABLE->query('DELETE FROM copart_extrato WHERE codigo_conjunto = "'.$rsCopart[$TABLE]['codigo_conjunto'].'";');
		$this->$TABLE->query('DELETE FROM importacao_copart WHERE codigo_conjunto = "'.$rsCopart[$TABLE]['codigo_conjunto'].'";');
		$setFlash = $this->msg_excluido;
		$this->Session->setFlash($this->msg_excluido);
		$return_log = parent::grava_log($TABLE.' - Exclusão Individual',$this->data,$setFlash);
	    }else{
		$setFlash = $this->msg_excluido_erro;
		$this->Session->setFlash($this->msg_excluido_erro);
		$return_log = parent::grava_log($TABLE.' - Erro Exclusão Individual',$this->data,$setFlash);
	    }

        } else {#EXCLUSÃO MULTIPLA
            if (isset($this->params['named']['ids']) && $this->params['named']['ids'] != '') {
                $idsArr = explode('_', $this->params['named']['ids']);
                $setFlash = '';
                foreach ($idsArr as $id) {
		    if($id != ''){
			$rsCopart = $this->$TABLE->find('first',array('conditions'=>array('cod_importacao_copart'=>$id),'fields'=>'codigo_conjunto'));
			if(isset($rsCopart[$TABLE]['codigo_conjunto']) && $rsCopart[$TABLE]['codigo_conjunto'] != ''){
			    $this->$TABLE->query('DELETE FROM copart_extrato WHERE codigo_conjunto = "'.$rsCopart[$TABLE]['codigo_conjunto'].'";');
			    $this->$TABLE->query('DELETE FROM importacao_copart WHERE codigo_conjunto = "'.$rsCopart[$TABLE]['codigo_conjunto'].'";');
			   $setFlash = $this->msg_excluido;
			   $this->Session->setFlash($this->msg_excluido);
			   $return_log = parent::grava_log($TABLE.' - Exclusão Individual',$this->data,$setFlash);
		       }else{
			   $setFlash = $this->msg_excluido_erro;
			   $this->Session->setFlash($this->msg_excluido_erro);
			   $return_log = parent::grava_log($TABLE.' - Erro Exclusão Individual',$this->data,$setFlash);
		       }
		    }
                }
                $this->Session->setFlash($setFlash);
            } else {
                $this->Session->setFlash(__('Nenhum registro foi selecionado'));
            }
        }

//        krumo($data);
//        debug($this->$TABLE->getDataSource()->getLog(false, false));
//        exit();
        $this->redirect(array('action' => 'index'));
    }


    /**
     *
     * @param type $id
     * @param type $action (0 =>  , 1)
     */
    public function liberacao_vh($codigo_conjunto, $action) {
        $TABLE = $this->table;
        $data1 = $data2 = array();
	$this->loadModel('CopartExtrato');
	$date_now = date('Y-m-d H:i:s');
	$dataSource = $this->$TABLE->getDataSource();

	try {
	    $this->CopartExtrato->unBindmodel(array('belongsTo'=>array('EmpresaBeneficiario')));
	    $this->CopartExtrato->updateAll(array('CopartExtrato.liberacao_vh'=>$action), array('CopartExtrato.codigo_conjunto'=>$codigo_conjunto));
	    $this->Session->setFlash(__($this->msg_salvo));
	    $dataSource->commit();
//	    debug($this->$TABLE->getDataSource()->getLog(false, false));
//	    exit;

	    #BEGIN - CRIANDO LOG
//	    $this->loadModel('QvLog');
//	    $this->QvLog->create();
//	    $data_log = array('cod_log' =>'',
//			      'log'                 => 'Cadastra Ordenação - '.$TABLE,
//			      'description'         => json_encode($this->Funcoes->utf8ize($this->data)),
//			      'server_description'  => '',
//			      'data_cadastro'       => date('Y-m-d H:i:s'),
//			      'cod_usuario'          => $this->Session->read('cod_usuario')
//		);
//	    $this->QvLog->save($data_log);
	    #END - CRIANDO LOG
	} catch (Exception $ex) {
	    debug($this->$TABLE->getDataSource()->getLog(false, false));exit;
	    if ($flash == '') {
		$flash = 'Erro: Não foi possível efetuar a mudança de ordenação. Tente novamente mais tarde!';
	    }
	    $dataSource->rollback();


	    $this->Session->setFlash($flash);
	    #BEGIN - CRIANDO LOG ERRO
//	    $this->loadModel('QvLog');
//	    $this->QvLog->create();
//	    $data_log = array('cod_log' =>'',
//			      'log'                 => 'Erro Ordenação - '.$TABLE,
//			      'mensagem'            => $flash,
//			      'description'         => json_encode($this->Funcoes->utf8ize($this->data)),
//			      'server_description'  => json_encode($this->Funcoes->utf8ize($this->params)),
//			      'data_cadastro'       => date('Y-m-d H:i:s'),
//			      'cod_usuario'          => $this->Session->read('cod_usuario')
//		);
//	    $this->QvLog->save($data_log);
	    #END - CRIANDO LOG ERRO


	    $this->redirect($this->referer());
	}


        $this->redirect(array('action' => 'index'));
    }



    public function index_download($id = '') {
        ini_set('memory_limit', '12048M');
        ini_set('max_execution_time', 90000000);
        $this->layout = 'download';
        $this->index($id);
    }


    public function extrato_clientes($cod_conjunto = null,$mes = null,$ano = null) 
    {   
        $TABLE = $this->table;
        $beneficiariosArr = $this->$TABLE->find('all', [
            'conditions' => [
                'ImportacaoCopart.codigo_conjunto = "'.$cod_conjunto.'"  ',
                'DATE_FORMAT(ImportacaoCopart.data_pagto_sinistro,"%m-%Y") = "'.$mes.'-'.$ano.'"  '
            ],
            'fields' => [
                "ImportacaoCopart.nome_beneficiario",
                "IFNULL(ebtit.cod_empresa_beneficiario, ImportacaoCopart.cod_empresa_beneficiario) AS cod_empresa_beneficiario",
                "ImportacaoCopart.nome_titular",
                "Extrato.carteira"
            ],
            'joins' => [
                [
                    'table' => 'copart_extrato',
                    'alias' => 'Extrato',
                    'type' => 'INNER',
                    'conditions' => [
                        'Extrato.cod_importacao_copart = ImportacaoCopart.cod_importacao_copart'
                    ]
                ],
                [
                    'table' => 'empresa_beneficiario',
                    'alias' => 'eb',
                    'type' => 'INNER',
                    'conditions' => [
                        'eb.cod_empresa_beneficiario = Extrato.cod_empresa_beneficiario'
                    ]
                ],
                [
                    'table' => 'dado_plano',
                    'alias' => 'dpltit',
                    'type' => 'LEFT',
                    'conditions' => [
                        "dpltit.cod_beneficio = Extrato.cod_beneficio",
                        "CAST(dpltit.carteira AS UNSIGNED) = CAST(Extrato.carteira_titular AS UNSIGNED)"
                    ]
                ],
                [
                    'table' => 'empresa_beneficiario',
                    'alias' => 'ebtit',
                    'type' => 'LEFT',
                    'conditions' => [
                        'ebtit.cod_empresa = eb.cod_empresa',
                        'ebtit.cod_dado_pessoal = dpltit.cod_dado_pessoal'
                    ]
                ]
            ],
            'order'  => [
                'ImportacaoCopart.nome_beneficiario ASC'
            ],
            'group'  => [
                'ImportacaoCopart.cod_empresa_beneficiario'
            ]
        ]);
        
        //Adicionando valores do array [0] para ImportacaoCopart
        $beneficiariosArr = array_map(function($aB){
            foreach($aB[0] AS $kD => $sD)
                $aB['ImportacaoCopart'][$kD] = $sD;
            
            return $aB;
        }, $beneficiariosArr);
        
        $this->layout = 'mural';
        $this->set('rows', $beneficiariosArr);
    }
}
