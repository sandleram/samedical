<?php

namespace Vendor;

/**
 * Description of PHPExcel
 *
 * @author felisberto.junior
 */
class PHPExcel {
    /**
     * Objeto da classe PHPExcel
     * @var object
     */
    private $oPHPExcel;
    /**
     * Para controle de qual foi a última linha adicionada
     * @var int
     */
    private $iLinha;
    
    /**
     * Faz o include de PHPExcel e instancia o objeto
     */
    function __construct()
    {
        //Incluindo o PHPExcel (autoload)
        require_once __DIR__ . DIRECTORY_SEPARATOR . "PHPExcel" . DIRECTORY_SEPARATOR . "PHPExcel.php";
        
        $this->destruirPHPExcel(true);
    }
    
    /**
     * Destrutor da classe
     */
    function __destruct()
    {
        $this->destruirPHPExcel();
    }
    
    /**
     * Retorna o index da planilha
     * @param string $sNome
     * @return int
     */
    private function getIndexPlanilhaPorNome($sPlanilha)
    {
        if(!is_numeric($sPlanilha)){
            $aPlanilhas = $this->oPHPExcel->getSheetNames();
            $sPlanilha = array_search($sPlanilha, $aPlanilhas);
            if(is_array($sPlanilha))
                $sPlanilha = $sPlanilha[0];
        }
        
        return $sPlanilha;
    }
    
    /**
     * Valida extensão do arquivo e retorna o objeto de PHPExcel_IOFactory
     * @param string $sNomeArquivo
     * @return \PHPExcel_IOFactory
     * @throws \PHPExcel_Exception
     */
    private function getObjEscrita($sNomeArquivo)
    {
        $aExtensoesValidas = [
            'xls'  => 'Excel5',
            'xlsx' => 'Excel2007',
            'html' => 'CSV',
            'csv'  => 'CSV'
        ];
        
        //Validando a extensão do arquivo
        $sExtensao = strtolower(pathinfo($sNomeArquivo, PATHINFO_EXTENSION));
        if(!in_array(strtolower($sExtensao), array_keys($aExtensoesValidas)))
            throw new \PHPExcel_Exception("Extensão inválida");
        
        \PHPExcel_Settings::setZipClass(\PHPExcel_Settings::PCLZIP);
        
        return \PHPExcel_IOFactory::createWriter($this->oPHPExcel, $aExtensoesValidas[$sExtensao]);
    }
    
    /**
     * Retorna a cor de acordo hexa com a string passada
     * @param string $sCor
     * @return string
     */
    private function getCor($sCor)
    {
        $sCor = strtolower(trim($sCor, '# '));
        
        $aCores = [
            'azulado-medio'     => '8ea9db',
            'azulado-claro'     => 'dae1f3',
            'vermelho'          => 'f44242',
            'vermelho-claro'    => 'fee6e6',
            'preto'             => '000000',
            'branco'            => 'ffffff',
            'verde'             => '00ff00',
            'verde-claro'       => 'a6f2d6',
            'verde-escuro'      => '009300',
            'azul'              => '0000ff',
            'amarelo'           => 'faff1c',
            'amarelo-claro'     => 'fffac2',
            'cinza'             => 'c0c0c0'
        ];
        
        return ($aCores[$sCor] ?: $sCor);
    }
    
    /**
     * Retorna a linha corrente do arquivo
     * @param bool $isLinhaAnterior
     * @return int
     */
    public function getLinha($isLinhaAnterior = false)
    {
        return ($isLinhaAnterior && $this->iLinha > 0 ? ($this->iLinha-1) : $this->iLinha);
    }
    
    /**
     * Seta o metadado createdby do arquivo
     */
    public function setCriadoPor($sCriadoPor)
    {
        $this->oPHPExcel->getProperties()->setCreator(utf8_encode($sCriadoPor));
    }
    
    /**
     * Seta o metadado title do arquivo
     */
    public function setTitulo($sTitulo)
    {
        $this->oPHPExcel->getProperties()->setTitle(utf8_encode($sTitulo));
    }
    
    /**
     * Seta o metadado subject do arquivo
     * @param string $sAssunto
     */
    public function setAssunto($sAssunto)    
    {
        $this->oPHPExcel->getProperties()->setSubject(utf8_encode($sAssunto));
    }
    
    /**
     * Altera o metadado de descrição
     * @param string $sDescricao
     */
    public function setDescricao($sDescricao)
    {
        $this->oPHPExcel->getProperties()->setDescription(utf8_encode($sDescricao));
    }
    
    /**
     * Altera o metadado keywords da planilha
     * @param string $sPalavrasChave
     */
    public function setPalavrasChave($sPalavrasChave)
    {
        if(is_array($sPalavrasChave))
            $sPalavrasChave = implode(" ", $sPalavrasChave);
        
        $this->oPHPExcel->getProperties()->setKeywords(utf8_encode($sPalavrasChave));
    }
    
    /**
     * Cria uma nova planilha
     */
    public function addPlanilha($sNomePlanilha = false, $isAtivar = true)
    {
        if(empty($sNomePlanilha))
            $sNomePlanilha = "Planilha" . $this->oPHPExcel->getSheetCount();
        
        //Se não existir
        if(!$this->oPHPExcel->sheetCodeNameExists(utf8_encode($sNomePlanilha))){
            $iSheet = $this->oPHPExcel->getSheetCount();
            $this->oPHPExcel->createSheet($iSheet);
            $this->oPHPExcel->getSheet($iSheet)->setTitle(utf8_encode($sNomePlanilha));
        }
        
        if($isAtivar)
            $this->selecionaPlanilhaPorNome($sNomePlanilha);
    }
    
    /**
     * Remove a planilha
     * @param string $sPlanilha
     */
    public function removerPlanilha($sPlanilha)
    {
        if(!$this->existePlanilha($sPlanilha))
            return;
        
        $this->oPHPExcel->removeSheetByIndex($this->getIndexPlanilhaPorNome($sPlanilha));
    }
    
    /**
     * Checa se a planilha existe
     * @param string $sPlanilha
     * @return boolean
     */
    public function existePlanilha($sPlanilha)
    {
        return $this->oPHPExcel->sheetCodeNameExists(utf8_encode($sPlanilha));
    }
    
    /**
     * Renomeia a planilha
     * @param type $sNomeAntigo
     * @param type $sNovoNome
     */
    public function renomeiaPlanilha($sNomeAntigo, $sNovoNome)
    {
        if(!$this->existePlanilha($sNomeAntigo))
            return;
        
        $this->oPHPExcel->getSheet($this->getIndexPlanilhaPorNome($sNomeAntigo))->setTitle(utf8_encode($sNovoNome));
    }
    
    /**
     * Carrega o atributo $aColunas
     * @param array $aColunas
     */
    public function setColunas($aColunas = [])
    {
        $this->aColunas = $aColunas;
    }
    
    /**
     * Seleciona a planilha de trabalho pelo índice
     * @param int $iIndice
     */
    public function selecionaPlanilhaPorIndice($iIndice)
    {
        if(!$this->oPHPExcel->sheetCodeNameExists($iIndice))
            throw new \PHPExcel_Exception("Planilha inexistente:  índice {$iIndice}");
            
        $this->oPHPExcel->setActiveSheetIndex($iIndice);
        $this->setUltimaLinha();
    }
    
    /**
     * Seleciona a planilha de trabalho pelo nome
     * @param int $iIndice
     */
    public function selecionaPlanilhaPorNome($sNomePlanilha)
    {
        if(!$this->oPHPExcel->sheetNameExists(utf8_encode($sNomePlanilha)))
            throw new \PHPExcel_Exception("Planilha inexistente:  {$sNomePlanilha}");
            
        $this->oPHPExcel->setActiveSheetIndexByName(utf8_encode($sNomePlanilha));
        $this->setUltimaLinha();
    }
    
    /**
     * Carrega a última linha da planilha
     */
    public function setUltimalinha()
    {
        $this->iLinha = ($this->oPHPExcel->getActiveSheet()->getHighestRow() ?: 0);
    }
    
    /**
     * Adiciona cabeçalho
     * @throws \PHPExcel_Exception
     */
    public function addLinhaTitulo($aEstilo = [])
    {
        if(empty($this->aColunas))
            throw new \PHPExcel_Exception("Colunas inválidas");
        
        $this->addLinha($this->aColunas, array_keys($this->aColunas), $aEstilo);
    }
    
    /**
     * Adiciona uma linha à planilha
     * @param array $aValores
     * @param array $aColunas
     */
    public function addLinha($aValores, $aColunas = [], $aEstilo = [])
    {
        if(empty($aColunas))
            $aColunas = array_keys($this->aColunas);
        
        $iCol = 0;
        foreach($aColunas AS $kCol){
            $this->addValor($iCol, $aValores[$kCol]);
            $iCol++;
        }
        
        if(!empty($aEstilo))
            $this->aplicarEstilo($aEstilo);
        
        $this->iLinha++;
    }
    
    /**
     * Remove a linha passada por parâmetro
     * @param int $iLinha
     */
    public function remLinha($iLinha = null)
    {
        if(null === $iLinha)
            $iLinha = $this->iLinha;
        elseif($iLinha < $this->iLinha)
            $this->iLinha--;
        
        $this->oPHPExcel->getActiveSheet()->removeRow($iLinha);
    }
    
    /**
     * Adiciona um valor a uma célula
     * @param string|int $sCol
     * @param string $sValor
     * @param int|false $iLinha
     */
    public function addValor($sCol, $sValor, $iLinha = false)
    {
        if(is_numeric($sCol))
            $sCol = self::getLetraColuna($sCol);
        
        if(false === $iLinha)
            $iLinha = $this->iLinha;
        
        $this->oPHPExcel->getActiveSheet()->setCellValue("{$sCol}{$iLinha}", utf8_encode($sValor));
    }
    
    /**
     * Aplica o autosize nas colunas
     */
    public function setLarguraAutomaticaColunas($sColIni = false, $sColFim = false)
    {
        if(false === $sColIni)
            $sColIni = "A";
        
        if(false === $sColFim)
            $sColFim = $this->getUltimaColuna();
        
        foreach($this->getRangeColumnsExcel($sColIni, $sColFim) AS $sCol)
            $this->oPHPExcel->getActiveSheet()->getColumnDimension($sCol)->setAutoSize(true);
    }
    
    /**
     * Ajusta a altura da linha de acordo com o conteúdo
     * @param int $iLinhaIni
     * @param int $iLinhaFim
     * @return boolean
     */
    public function setAlturaAutomaticaLinhas($iLinhaIni = false, $iLinhaFim = false)
    {
        if(!is_numeric($iLinhaIni))
            $iLinhaIni = 0;
        
        if(!is_numeric($iLinhaFim))
            $iLinhaFim = $this->getUltimaLinha();
        
        if($iLinhaIni > $iLinhaFim)
            return false;
        
        for($i = $iLinhaIni; $i <= $iLinhaFim; $i++){
            $this->setQuebraDeLinha("A{$i}:" . $this->getUltimaColuna() . "{$i}");
            $this->oPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }
    }
    
    /**
     * Permite a quebra automática de linha dentro da célula
     * @param string $sIntervalo
     */
    public function setQuebraDeLinha($sIntervalo)
    {
        $this->oPHPExcel->getActiveSheet()->getStyle($sIntervalo)->getAlignment()->setWrapText(true);
    }
    
    /**
     * Retorna a letra da última coluna da planilha
     * @return array
     */
    public function getUltimaColuna()
    {
        return self::getLetraColuna($this->getUltimaColunaNumero());
    }
    
    /**
     * Retorna a letra da última coluna da planilha
     * @return array
     */
    public function getUltimaColunaNumero()
    {
        return (count($this->aColunas)-1);
    }
    
    /**
     * Retorna a última linha
     * @return int
     */
    public function getUltimaLinha()
    {
        return $this->oPHPExcel->getActiveSheet()->getHighestRow();
    }
    
    /**
    * Retorna todas as colunas da planilha de um intervalo de colunas
    * @param int|string $sColIni
    * @param int|string $sColFim
    * @return array
    */
    public function getRangeColumnsExcel($sColIni, $sColFim){
        $iColIni = (is_int($sColIni) ? $sColIni : self::getNumeroColuna($sColIni));
        $iColFim = (is_int($sColFim) ? $sColFim : self::getNumeroColuna($sColFim));

        if($iColIni > $iColFim)
            return [];

        $aCols = [];
        while($iColIni <= $iColFim){
            $aCols[] = self::getLetraColuna(($iColIni-1));
            $iColIni++;
        }

        return $aCols;
    }
    
    /**
     * Cria um select com opções pré-definidas
     * @param string $sIntervalo
     * @param array $aConfig
     */
    public function setSelecaoValores($sIntervalo, $aConfig = [])
    {
        if(empty($sIntervalo))
            return;
        
        if(!is_array($aConfig))
            $aConfig = [];
        
        //Setando valores padrão
        foreach([
            'permite_vazio'   => false,
            'exibe_msg_input' => false,
            'exibe_msg_erro'  => [
                "titulo"    => "Erro",
                "msg"       => "Selecione um item da lista"
            ]
        ] AS $sParam => $sValorPadrao)
            $aConfig[$sParam] = ($aConfig[$sParam] ?: $sValorPadrao);
        
        //Criando objeto de validação
        $objValidation =& $this->oPHPExcel->getActiveSheet()->getCell($sIntervalo)->getDataValidation();
        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
        $objValidation->setShowDropDown(true);
        
        foreach($aConfig AS $kConf => $sConf){
            switch($kConf){
                case 'permite_vazio':
                    $objValidation->setAllowBlank(!empty($sConf));
                    break;
                
                case 'exibe_msg_input':
                    $objValidation->setShowInputMessage(!empty($sConf));
                    break;
                
                case 'exibe_msg_erro':
                    $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $objValidation->setShowErrorMessage(!empty($sConf));
                    
                    if(is_array($sConf)){
                        if(!empty($sConf['titulo'])){
                            $objValidation->setErrorTitle($sConf['titulo']);
                            $objValidation->setPromptTitle($sConf['titulo']);
                        }
                        
                        if(!empty($sConf['msg'])){
                            $objValidation->setError($sConf['msg']);
                            $objValidation->setPrompt($sConf['msg']);
                        }
                    }
                    
                    break;
            }
        }
        
        $objValidation->setFormula1('"'.utf8_encode((is_array($aConfig['aOpcoes']) ? implode(", ", $aConfig['aOpcoes']) : $aConfig['aOpcoes'])).'"');
    }
    
    /**
     * Aplica estilos pré-definidos à celula
     * @param array $aEstilos
     * @param string $sInterval
     */
    public function aplicarEstilo($aEstilos, $sInterval = false, &$oObjStyle = false)
    {
        if(empty($sInterval))
            $sInterval = "A{$this->iLinha}:" . $this->getUltimaColuna() . $this->iLinha;
        
        if(empty($oObjStyle) || !is_object($oObjStyle)){
            unset($oObjStyle);
            @$oObjStyle =& $this->oPHPExcel->getActiveSheet()->getStyle($sInterval);
        }
        
        if(!is_array($aEstilos))
            $aEstilos = [$aEstilos];
            
        $aEstiloAplicar = [];
        foreach(($aEstilos ?: []) AS $kEstilo => $sEstilo){
            switch((is_numeric($kEstilo) ? $sEstilo : $kEstilo)){
                //Aplica negrito
                case 'bold':
                case 'negrito':
                    $oObjStyle->getFont()->setBold(true);
                    break;
                //Altera o tamanho da fonte
                case 'size':
                case 'font-size':
                    if(is_numeric($sEstilo))
                        $oObjStyle->getFont()->setSize($sEstilo);
                    break;
                //Alinha verticalmente e horizontalmente
                case 'centralizar':
                    $aEstiloAplicar['alignment']['horizontal'] = \PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
                    $aEstiloAplicar['alignment']['vertical'] = \PHPExcel_Style_Alignment::VERTICAL_CENTER;
                    break;
                //Alinha horizontalmente
                case 'align-center':
                    $aEstiloAplicar['alignment']['horizontal'] = \PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
                    break;
                //Alinha à direita
                case 'align-right':
                    $aEstiloAplicar['alignment']['horizontal'] = \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
                    break;
                //Alinha à esquerda
                case 'align-left':
                    $aEstiloAplicar['alignment']['horizontal'] = \PHPExcel_Style_Alignment::HORIZONTAL_LEFT;
                    break;
                //Alinha verticalmente
                case 'vertical-align-center':
                    $aEstiloAplicar['alignment']['vertical'] = \PHPExcel_Style_Alignment::VERTICAL_CENTER;
                    break;
                //Altera a cor
                case 'cor':
                case 'color':
                case 'font-color':
                    $aEstiloAplicar['font']['color']['rgb'] = $this->getCor($sEstilo);
                    break;
                //Aplica bordas, recebendo lrtb nas configurações (left, right, top, bottom - respectivamente)
                case 'border-black':
                case 'borda':
                    if(is_numeric($kEstilo))
                        $sEstilo = "lrtb";
                    
                    $aLocais = [
                        'l' => 'left',
                        'r' => 'right',
                        't' => 'top',
                        'b' => 'bottom'
                    ];
                    
                    foreach(str_split($sEstilo) AS $sLocal){
                        $sLocal = $aLocais[$sLocal];
                        
                        if(empty($sLocal))
                            continue;
                        
                        $aEstiloAplicar['borders'][$sLocal]['style'] = \PHPExcel_Style_Border::BORDER_THIN;
                        $aEstiloAplicar['borders'][$sLocal]['color']['rgb'] = $this->getCor('preto');
                    }
                    
                    break;
                //Altera a cor de fundo
                case 'background-color':
                case 'cor-de-fundo':
                case 'cor-fundo':
                    $aEstiloAplicar['fill']['type'] = \PHPExcel_Style_Fill::FILL_SOLID;
                    $aEstiloAplicar['fill']['color']['rgb'] = $this->getCor($sEstilo);
                    break;
                //Geral
                case 'geral':
                    $oObjStyle->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
                    break;
                //Hora
                case 'hora':
                case 'time':
                    $oObjStyle->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                    break;
                //Texto
                case 'text':
                case 'texto':
                    $oObjStyle->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                    break;
                //Numérico
                case 'inteiro':
                case 'integer':
                case 'int':
                    $oObjStyle->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    break;
                //Porcentagem
                case 'porcentagem':
                case 'porc':
                case 'percent':
                case 'perc':
                    $oObjStyle->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                    break;
                //Decimal
                case 'float':
                case 'decimal':
                case 'real':
                case 'monetario':
                    $oObjStyle->getNumberFormat()->setFormatCode('#,##0.00');
                    break;
                //Mesclar células
                case 'merge':
                case 'mesclar':
                    $this->oPHPExcel->getActiveSheet()->mergeCells($sInterval);
                    break;
            }
        }
        
        if(!empty($aEstiloAplicar))
            $oObjStyle->applyFromArray($aEstiloAplicar);
    }
    
    /**
     * Protege a planilha utiliando uma senha, exceto os intervalos passados
     * @param type $aCelulasExcecao
     * @param type $sSenha
     */
    public function protegerPlanilha($aConfig = [])
    {   
        //Setando configurações padrões
        foreach([
            'ordenar'         => true,
            'inserirLinha'    => false,
            'formatarCelulas' => true,
            'senha'           => 'PHPExcelVS2',
            'aCelulasExcecao' => []
        ] AS $sChave => $sValor){
            if(!isset($aConfig[$sChave]))
                $aConfig[$sChave] = $sValor;
        }
        
        $this->oPHPExcel->getActiveSheet()->getProtection()->setSheet(true)
            ->setSort($aConfig['ordenar'])
            ->setInsertRows($aConfig['inserirLinha'])
            ->setFormatCells($aConfig['formatarCelulas'])
            ->setPassword(utf8_encode($aConfig['senha']));
        
        if(!empty($aConfig['aCelulasExcecao']))
            $this->desprotegerCelulas($aConfig['aCelulasExcecao']);
    }
    
    /**
     * Oculta a planilha passada por parâmetro ou a planilha corrente
     * @param string $sPlanilha
     */
    public function ocultarPlanilha($sPlanilha = false, $isVeryHidden = false)
    {
        $sParam = ($isVeryHidden ? \PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN : \PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
        if(!empty($sPlanilha))
            $this->oPHPExcel->getSheetByName($sPlanilha)->setSheetState($sParam);
        else
            $this->oPHPExcel->getActiveSheet()->setSheetState($sParam);
    }
    
    /**
     * Desprotege um intervalo de células
     * @param array $aIntervalo
     */
    public function desprotegerCelulas($aIntervalo)
    {
        foreach(($aIntervalo ?: []) AS $sIntervalo)
            $this->oPHPExcel->getActiveSheet()->getStyle($sIntervalo)->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
    }
    
    /**
     * Aplica formatação condicional às células
     * @param string $sIntervalo
     * @param array $aCondicionais
     */
    public function aplicarEstiloCondicional($sIntervalo, $sTipo = false, $aCondicionais = [], $aEstilos = [])
    {
        $objConditionalStyle = new \PHPExcel_Style_Conditional();
        
        switch($sTipo){
            case 'celula':
                $objConditionalStyle->setConditionType(\PHPExcel_Style_Conditional::CONDITION_CELLIS);
                break;
            case 'texto':
                $objConditionalStyle->setConditionType(\PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT);
                break;
            case 'expressao':
                $objConditionalStyle->setConditionType(\PHPExcel_Style_Conditional::CONDITION_EXPRESSION);
                break;
            default:
                $objConditionalStyle->setConditionType(\PHPExcel_Style_Conditional::CONDITION_NONE);
                $this->oPHPExcel->getActiveSheet()->getStyle($sIntervalo)->setConditionalStyles($objConditionalStyle);
                return;
        }
        
        $isAddCond = false;
        foreach($aCondicionais AS $sSinal => $sValor){
            if(empty($sValor))
                continue;
            
            switch($sSinal){
                case "<":
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_LESSTHAN);
                    break;
                case ">":
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_GREATERTHAN);
                    break;
                case "=":
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_EQUAL);
                    break;
                case "!=":
                case "<>":
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_NOTEQUAL);
                    break;
                case "<=":
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_LESSTHANOREQUAL);
                    break;
                case ">=":
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_GREATERTHANOREQUAL);
                    break;
                case "entre":
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_BETWEEN);
                    break;
                case 'comecacom':
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_BEGINSWITH);
                    break;
                case 'terminacom':
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_ENDSWITH);
                    break;
                case 'contem':
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_CONTAINSTEXT);
                    break;
                case 'naocontem':
                    $objConditionalStyle->setOperatorType(\PHPExcel_Style_Conditional::OPERATOR_NOTCONTAINS);
                    break;
                default:
                    continue 2;
                    break;
            }
            
            $objConditionalStyle->addCondition($sValor);
            $isAddCond = true;
        }
        
        if(!$isAddCond)
            return;
        
        $this->aplicarEstilo($aEstilos, $sIntervalo, $objConditionalStyle->getStyle());
        $conditionalStyles = $this->oPHPExcel->getActiveSheet()->getStyle($sIntervalo)->getConditionalStyles();
        array_push($conditionalStyles, $objConditionalStyle);
        
        $this->oPHPExcel->getActiveSheet()->getStyle($sIntervalo)->setConditionalStyles(array_filter($conditionalStyles));
    }
    
    /**
     * Retorna o conteúdo da planilha em um array
     * @return array
     */
    public function getArray()
    {
        return $this->oPHPExcel->getActiveSheet()->toArray();
    }
    
    /**
     * Força o download do arquivo
     * @param string $sNomeArquivo
     */
    public function downloadArquivo($sNomeArquivo)
    {   
        $objWriter = $this->getObjEscrita($sNomeArquivo);
        ob_start();
        ob_clean();
        $objWriter->save('php://output');
        $sDadosExcel = ob_get_clean();
        
        $iFileSize = (function_exists('mb_strlen') ? mb_strlen($sDadosExcel, '8bit') : strlen($sDadosExcel));
        
        $aContentType = [
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'html' => 'text/html',
            'csv'  => 'text/csv'
        ];
        
        $sExtensao = strtolower(pathinfo($sNomeArquivo, PATHINFO_EXTENSION));
        
        header("Content-Type: {$aContentType[$sExtensao]}");
        header('Content-Disposition: attachment; filename="'.basename(utf8_encode($sNomeArquivo)).'"');
        header('Content-Length: ' . $iFileSize);
        header("Pragma: no-cache");
        header("Expires: 0");
        die($sDadosExcel);
    }
    
    /**
     * Salva arquivo em diretório
     * @param string $sNomeArquivo
     */
    public function salvarArquivo($sCaminhoCompletoArquivo, $isSubstituirSeExistir = false)
    {
        if(!$isSubstituirSeExistir && file_exists($sCaminhoCompletoArquivo))
            throw new \PHPExcel_Exception("Arquivo já existe: " . basename($sCaminhoCompletoArquivo));
        
        $sDir = pathinfo($sCaminhoCompletoArquivo, PATHINFO_DIRNAME);
        if(!is_dir($sDir))
            throw new \PHPExcel_Exception("Diretório inexistente: {$sDir}");
            
        $objWriter = $this->getObjEscrita($sCaminhoCompletoArquivo);
        return $objWriter->save(utf8_encode($sCaminhoCompletoArquivo));
    }
    
    /**
     * Destrói o objeto PHPExcel e instancia novamente a classe
     */
    public function destruirPHPExcel($isCriaNovoObjeto = false)
    {
        if(is_object($this->oPHPExcel) && 'PHPExcel' === get_class($this->oPHPExcel)){
            \PHPExcel_Calculation::unsetInstance($this->oPHPExcel);
            $this->oPHPExcel->disconnectWorksheets();
            $this->oPHPExcel = null;
        }
        
        if($isCriaNovoObjeto){
            $this->oPHPExcel = new \PHPExcel();
            $this->setTitulo("VS2 - Victory Solutions");
            $this->setCriadoPor("VS2 - Victory Solutions");
            \PHPExcel_Settings::setZipClass(\PHPExcel_Settings::PCLZIP);
        }
        
        $this->iLinha = 0;
    }
    
    /**
     * Retorna a letra da coluna de acordo com o número passado
     * @param int $iNumeroColuna
     * @return string
     */
    public static function getLetraColuna($iNumeroColuna)
    {
        return \PHPExcel_Cell::stringFromColumnIndex($iNumeroColuna);
    }
    
    /**
     * Retorna o index da coluna baseado na letra
     * @param string $sLetraColuna
     * @return int
     */
    public static function getNumeroColuna($sLetraColuna)
    {
        return \PHPExcel_Cell::columnIndexFromString($sLetraColuna);
    }
    
    /**
     * Lê planilha e retorna array contendo os dados
     * @param string $sArquivo
     * @param string $sPlanilha
     * @param boolean $isPrimeiraLinhaCabecalho
     * @param array $aCols
     * @return array
     */
    public static function getDadosPlanilha($sArquivo, $sPlanilha = false, $isPrimeiraLinhaCabecalho = false, $aCols = false, $isCalculaFormula = true){
        $oReader = \PHPExcel_IOFactory::createReaderForFile($sArquivo);
        
        if(!empty($sPlanilha))
            $oReader->setLoadSheetsOnly(utf8_encode($sPlanilha));
        else
            $oReader->setReadDataOnly(true);
        
        $oXLS = $oReader->load($sArquivo);
        
        if(!empty($sPlanilha))
            $oXLS->setActiveSheetIndexByName(utf8_encode($sPlanilha));
        else
            $oXLS->setActiveSheetIndex(0);
        
        $nColunas = $oXLS->getSheet()->getHighestColumn();
        $nLinhas  = $oXLS->getSheet()->getHighestRow();
        $aDados   = $aCabecalho = Array();
        
        //Se a primeira linha não for cabeçalho e $aCols estiver preenchida, gerar nome de acordo com a coluna A, B, C, etc...
        if(!$isPrimeiraLinhaCabecalho && !empty($aCols)){
            $aCols = array_values($aCols);
            for($iCol = 0; $iCol <= self::getNumeroColuna($nColunas); $iCol++)
                $aCabecalho[self::getLetraColuna($iCol)] = ($aCols[$iCol] ?: self::getLetraColuna($iCol));
        }
        
        $sFnGetValue = ($isCalculaFormula ? "getCalculatedValue" : "getValue");
        for ($i = 1; $i <= $nLinhas; $i++){
            if(empty($aCabecalho)){
                $isEmpty = true;
                for($ii = 0; $ii <= self::getNumeroColuna($nColunas); $ii++){
                    $sVal = trim($oXLS->getSheet()->getCellByColumnAndRow($ii, $i)->$sFnGetValue());
                    if(!empty($sVal))
                        $isEmpty = false;

                    $aCabecalho[self::getLetraColuna($ii)] = (empty($sVal) || !$isPrimeiraLinhaCabecalho ? self::getLetraColuna($ii) : utf8_decode($sVal));
                    if(!empty($aCols) && isset($aCols[$aCabecalho[self::getLetraColuna($ii)]]))
                        $aCabecalho[self::getLetraColuna($ii)] = $aCols[$aCabecalho[self::getLetraColuna($ii)]];
                }

                if($isEmpty){
                    $aCabecalho = Array();
                    continue;
                }
                else if($isPrimeiraLinhaCabecalho)
                    continue;
            }
            
            $isEmpty = true;
            for($ii = 0; $ii <= self::getNumeroColuna($nColunas); $ii++){
                $sVal = utf8_decode(trim($oXLS->getSheet()->getCellByColumnAndRow($ii, $i)->$sFnGetValue()));
                if(!empty($sVal))
                    $isEmpty = false;
                
                $sCol = $aCabecalho[self::getLetraColuna($ii)];
                if(!empty($aCols) && is_array($aCols) && !in_array($sCol, $aCols))
                    continue;

                $aDados[$i][$sCol] = $sVal;
            }

            if($isEmpty)
                unset($aDados[$i]);
        }
        
        \PHPExcel_Calculation::unsetInstance($oXLS);
        $oXLS->disconnectWorksheets();
        $oXLS = null;
        
        return $aDados;
    }
}