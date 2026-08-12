<style>
.display_none{display:none;}

.hm div {display:none;position:relative; z-index: 1000; font-size: 12px; text-align: left;}
.hm:hover div {display:block;position:absolute;top:0;margin-left:25px}
.hm {
  /*width:0.8em;height:0.8em;
  
  font:20px/0.8em "segoe ui",arial,helvetica,sans-serif;
  border:1px solid #999;
  border-radius:50%;*/
  font-size:13px;
  text-align:center;
  color:#999;
  float:right;
  margin-left: 8px;
  padding:1px;
  margin-top: -3px;
}

.hm div {
  width:20em;
  color:#fff;
  background:#aaa;
  border-radius:5px;
  padding:5px 10px;
}
</style>

<div class="col-sm-12 col-md-8 col-lg-9">

    <?php
        $ajudaMsg = '<div class="hm">?<div>[AJUDA]</div></div>';
        $ajudaMsg = '<div class="hm"><i class=" fa fa-question-circle"></i> <div>[AJUDA]</div></div> ';


        echo $this->Funcoes->msg($this->Session->flash());
        echo $this->Form->create(
                               'HmProntuario', array(
                                    'type' => 'file',
                                    'id' => $this->params['controller'] . '-form',
                                    'url' => array(
                                        'controller' => 'hm_prontuario',
                                        'action' => 'programa',
                                        $this->params['pass'][0]
                                    ),
                                    'class' => 'smart-form client-form form-horizontal '
                                 )
                                );

        $cod_hm_historico_old = '';
        if(isset($row['cod_hm_historico_old'])){
            $cod_hm_historico_old = $row['cod_hm_historico_old'];
        }
        echo '<input name="data[HmResposta][cod_hm_historico_old]"  value="'.$cod_hm_historico_old.'" type="hidden">';

        #if(in_array($cod_hm_programa,array(7,8))):
        echo '<input name="data[HmProntuario][hora_inicio]"  value="'.date('Y-m-d H:i:s').'" type="hidden">';
        #endif;             

	
        
        $obrigatorio = '<span class="campo_obrigatorio">*</span>';
    ?>
            <div class="well no-padding">
                <header>
                    <?php 
                        echo 'Programa '.$nome_programa;
                    ?>
                </header>
                <fieldset>
                    <?php
                        $class_margin = '';
                        if(isset($row['Perguntas']) && count($row['Perguntas']) > 0){
                            $class_margin = 'margin-top:40px;';
                        }
                        $PERMITE_SUB = false;
                        $TABLE_SUB = '';
                        if(isset($cod_hm_programa)){
                            if(array_key_exists($cod_hm_programa, $programa_sub_arr) && $cod_hm_programa != 3){
                                $TABLE_SUB = $programa_sub_arr[$cod_hm_programa]['class'];
                                $NOME_SUB = $programa_sub_arr[$cod_hm_programa]['nome'];
                                $PERMITE_SUB = true;
                            }
                        }
                    
                    
#BEGIN - exibição por grupo           
                        if(isset($cod_hm_programa) && array_key_exists($cod_hm_programa, $programa_sub_arr) && $cod_hm_programa != 3 && count($row['Grupos'])>0)
                        {
                            echo  '<style>'
                                . '.form-horizontal .control-label, .form-horizontal .radio, .form-horizontal .checkbox, .form-horizontal .radio-inline, .form-horizontal .checkbox-inline {padding-top: 0px !important;}  '
                                . '</style>';
                            #NECESSÁRIO VERIFICAR: 
                            # - Se alguma pergunta não tem grupo, se cria um grupo "outros"
                            # - Se nenhuma das perguntas tem grupos, pular a utilização de grupos reutilizando o código.
                            
                            $count_grupo = count($row['Grupos']);
                            $width_ = 'width:25%;';
                            $height_ = 'height:70px;';
                            if($count_grupo == 5){
                                $width_ = 'width:20%;';
                                $height_ = 'height:80px;';
                            }elseif($count_grupo == 6){
                                $width_ = 'width:16.3%;';
                                $height_ = 'height:83px;';
                            }elseif($count_grupo > 6){
                                $width_ = 'width:14.2%;';
                                $height_ = 'height:83px;';
                            }
			    
                            
                            echo '<div class="row" >
                                    <h5>Perguntas do Programa</h5>
                                 </div>';
                            echo '  <div class="rows" style="margin: 20px 20px 30px 20px;  padding-bottom:30px;" >';
                            echo '      <div id="bootstrap-wizard-1" >';

                            #BEGIN - CABEÇALHO DOS GRUPOS
                            #echo '<div class="rows" style="margin-top:40px;">';
                            echo '          <div class="form-bootstrapWizard">';
                            echo '              <ul class="bootstrapWizard form-wizard">';
                            $countG = 1;
                            $conta_exclusao = 0;

                            #krumo($row);exit;
                            foreach($row['Grupos'] as $grupo){
				                $cod_grupos = $grupo['cod_grupo'];
                                $active = ($countG == 1)? 'active' : '' ; #ALTERAR PARA 1 #teste
                                $style_step = ($countG > 9) ? 'padding: 7px 8px !important;': '';
        //                        echo '              <li class="'.$active.' col-xs-4 col-sm-4 col-md-3 col-lg-1" data-target="#step1" style=" '.$height_.' ">';
                                echo '              <li class="onTopStep '.$active.'" data-target="#step'.$countG.'" style=" '.$height_.' '.$width_.' ">';
                                echo '                  <a href="#tab'.$countG.'" data-toggle="tab"> <span class="step" style="'.$style_step.'">'.$countG.'</span>  <span class="title hidden-xs font-sm">'.str_replace(' - ','<br>',$grupo['nome']).'</span> </a>';
                                echo '              </li>';
                                $countG++;
                            }
                            echo '              </ul>';
                            echo '              <div class="clearfix"></div>';
                            echo '          </div>';
                            #END - CABEÇALHO DOS GRUPOS

                            #BEGIN - CONTEUDO DOS GRUPOS (PERGUNTAS)
                            $countG = 1;
                            $multiplo_num = 0;
                            $multiplo_num_por_pergunta = 0; #contador para gerar repeticoes
                            $categoria_tmp = '';
                            echo '          <div class="tab-content" >';
                            foreach($row['Grupos'] as $grupo){
				                $cod_grupos = $grupo['cod_grupo'];
                                $active = ($countG == 1)? 'active' : '' ;  #ALTERAR PARA 1 #teste
                                echo '              <div class="tab-pane '.$active.'" id="tab'.$countG.'">';
                                echo '                  <h6 style="border-bottom:1px dotted #d3d3d3; margin-top:20px;"><strong>'.$countG.' - '.$grupo['nome'].'</strong></h6>'; 
                                #echo '                  <h6 style="border-bottom:1px dotted #d3d3d3; margin-top:20px;"><strong>'.$countG.' - '.$grupo['nome'].' - '.$cod_grupos.'</strong></h6>'; #teste
                                echo '                  <div style="margin-top:20px;" class="content_multiplo">';
                                if(count($grupo['perguntas']) > 0){
        //                            $numPerg = count($grupo['perguntas']);
                                    $numPerg = 0;
                                    $htmlInput = '';
                                    $categoria_ativa = '';

                                    #ORDENAÇÃO PERGUNTAS GRUPO
                                    

                                    foreach($grupo['perguntas'] as $kPerguntas => $perguntaArr){
                                      

                                        #BEGIN - VERIFICA LINHAS PARA QUEBRA
                                        $inicio_mesma_linha = false;
                                        if(isset($grupo['perguntas'][$kPerguntas-1]) && !is_null($perguntaArr['HmPergunta']['mesma_linha'])){
                                            $vinculo_old = $grupo['perguntas'][$kPerguntas-1]['HmPergunta']['mesma_linha'];
                                            $vinculo = $perguntaArr['HmPergunta']['mesma_linha'];
                                            if($vinculo_old == $vinculo){
                                                $inicio_mesma_linha = true;
                                            }
                                        }

                                        $fim_mesma_linha = false;
                                        if(isset($grupo['perguntas'][$kPerguntas+1]) && !is_null($perguntaArr['HmPergunta']['mesma_linha'])){
                                            $vinculo_pos = $grupo['perguntas'][$kPerguntas+1]['HmPergunta']['mesma_linha'];
                                            $vinculo = $perguntaArr['HmPergunta']['mesma_linha'];
                                            if($vinculo_pos == $vinculo){
                                                $fim_mesma_linha = true;
                                            }
                                        }
                                        #END - VERIFICA LINHAS PARA QUEBRA

                                        #BEGIN - CATEGORIA
                                        $fim_categoria = false;

                                        if(!is_null($perguntaArr['HmPergunta']['categoria'])){
                                            $categoria_ativa = true;
                                            $categoria_qtd = 0;
                                            $categoria_btn  = '<div style="margin-top:20px;margin-bottom:30px; padding: 15px 20px 20px 20px; border:1px solid #ccc; border-radius: 10px; background-color:#fff; box-shadow:0px 9px 10px 0px rgba(50, 50, 50, 0.50);-moz-box-shadow:0px 9px 10px 0px rgba(50, 50, 50, 0.50);-webkit-box-shadow:0px 9px 10px 0px rgba(50, 50, 50, 0.50);">';
                                            $categoria_btn .= '<div style="margin-bottom:20px; border-bottom:1px dotted #d3d3d3; ">';
                                            $categoria_btn .= '   <h6>';
                                            $categoria_btn .=         $perguntaArr['HmPergunta']['categoria'];
                                            $categoria_btn .= '   </h6>';

                                            #BOTÃO PARA INCLUSÃO DE CONTEÚDO DINÂMICO
                                            if($perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                
                                                #FAZ A CONTAGEM PARA GERAR O JAX DE REPETIÇÃO
                                                $data_repeticao_ajax = 0;
                                                $inputs_hidden = '';
                                                $categoria_pergunta = $this->Funcoes->slug($perguntaArr['HmPergunta']['categoria']);
                                                if(isset($row['HmRespostasDinamicasMultiplas'][$cod_grupos][$categoria_pergunta])){
                                                    $data_repeticao_ajax = count($row['HmRespostasDinamicasMultiplas'][$cod_grupos][$categoria_pergunta]); 
                                                    


                                                    #DESENVOLVIMENTO 
                                                    #RETORNO PERGUNTAS PARA ALIMENTAR NO AJAX
                                                    
                                                    if($categoria_tmp !=  $cod_grupos.'_'.$categoria_pergunta){
                                                        $inputs_hidden = '';
                                                        $categoria_tmp = $cod_grupos.'_'.$categoria_pergunta;
                                                        $inputs_hidden .= '<div class="retorno_dados_'.$cod_grupos.'_'.$categoria_pergunta.'"> ';
                                                        $count_retorno = 0;
                                                        foreach($row['HmRespostasDinamicasMultiplas'][$cod_grupos][$categoria_pergunta] as $kRetorno => $vRetorno){
                                                            if(count($vRetorno)>0 ){
                                                                ksort($vRetorno);
                                                                $inputs_hidden .= '<div class="retorno_campos" data_count_retorno = "'.$count_retorno.'">';

                                                                foreach($vRetorno as $vReposta){
                                                                    $valor = '';
                                                                    if(!empty($vReposta['cod_hm_opcao'])){
                                                                        $valor = $vReposta['cod_hm_opcao'];
                                                                    }elseif(!empty($vReposta['data'])){
                                                                        $valor = $vReposta['data'];
                                                                    }elseif(!empty($vReposta['inteiro'])){
                                                                        $valor = $vReposta['inteiro'];
                                                                    }else{
                                                                        $valor = $vReposta['descritivo'];
                                                                    }
                                                                    $inputs_hidden .= '<input type="hidden" value="'.$valor.'" id="HmProntuario'.$vReposta['cod_hm_pergunta'].'">';#teste deixar hidden
                                                                }
                                                                $inputs_hidden .= '</div>';
                                                            }
                                                            $count_retorno++;
                                                        }
                                                        $inputs_hidden .= '</div>';    
                                                        $categoria_btn .= $inputs_hidden;
                                                    }

                                                }
                                                $categoria_btn .= '   <div class="text-right" style="margin-top: -22px; margin-bottom: 10px;" >
                                                                        <a class="btn btn-success inclusao_dinamica" 
                                                                            data_inclusao = "[DATA_INCLUSAO]"  
                                                                            data_repeticao_ajax = "'.$data_repeticao_ajax.'" 
                                                                            data_class_retorno="retorno_dados_'.$cod_grupos.'_'.$categoria_pergunta.'" 
                                                                            data_categoria="'.$categoria_pergunta.'" 
                                                                            href="javascript:void(0);"
                                                                            style="padding:3px 7px 3px 5px;">
                                                                            <i class="fa fa-plus"></i> <span class="hidden-mobile">Incluir</span>
                                                                        </a>
                                                                      </div>';


                                                
                                            }
                                            $categoria_btn .= '</div>';

                                            if(!isset($grupo['perguntas'][$kPerguntas-1])){
                                                
                                                if($perguntaArr['HmPergunta']['multiplo'] == 1){
                                                    $multiplo_num++;
                                                }
                                                $htmlInput .= str_replace('[DATA_INCLUSAO]',$multiplo_num,$categoria_btn);

                                            }elseif(isset($grupo['perguntas'][$kPerguntas-1]) && $grupo['perguntas'][$kPerguntas-1]['HmPergunta']['categoria'] != $perguntaArr['HmPergunta']['categoria']){
                                                if($perguntaArr['HmPergunta']['multiplo'] == 1){
                                                    $multiplo_num++;
                                                }
                                                $htmlInput .= str_replace('[DATA_INCLUSAO]',$multiplo_num,$categoria_btn);
                                            }

                                            if(!isset($grupo['perguntas'][$kPerguntas+1])){
                                                $fim_categoria = true;
                                            }elseif(isset($grupo['perguntas'][$kPerguntas+1]) && $grupo['perguntas'][$kPerguntas+1]['HmPergunta']['categoria'] != $perguntaArr['HmPergunta']['categoria']){
                                                $fim_categoria = true;
                                            }



                                        }
                                        #END - CATEGORIA


                                        #DEPENDENCIA HIDDEN
                                        $class_dep_hidden = '';
                                        $classPergNum = 'pergunta_numero';

                                        $numPerg++;
                                        $margintop = $numPerg > 1 ? 'margin-top:10px;': '';
                                        if(!$inicio_mesma_linha){
                                            $class_duplica_categoria = '';
                                            #if($categoria_ativa && $categoria_qtd == 0 && $perguntaArr['HmPergunta']['multiplo'] == '1'){
                                            
                                            if($categoria_ativa && $perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                
                                                // if($multiplo_num_por_pergunta == 0){
                                                    $class_duplica_categoria = 'duplica_categoria_'.$multiplo_num.' retorno_row';    
                                                // }
                                                $multiplo_num_por_pergunta++;
                                            }

                                            $htmlInput .= '<div class="row ' . $class_dep_hidden . ' '.$class_duplica_categoria.'" style="'.$margintop.'">';

                                            if($fim_categoria){
                                                $categoria_ativa = false;
                                                $multiplo_num_por_pergunta = 0;
                                            }
                                        }
                                        // krumo($categoria_ativa);


                                        #INCLUSÃO DAS COLUNAS DO SECTION PARA DEFINIÇÃO DO TAMANHO DENTRO DA TELA
                                        #col-xs-11 col-sm-11 col-md-11 col-lg-11
                                        $cols = 'margin-left-15 margin-right-15' ;
                                        if($perguntaArr['HmPergunta']['tipo'] == 'text'){ $cols = 'col col-8' ;
                                        }elseif($perguntaArr['HmPergunta']['tipo'] == 'combobox'){ $cols = 'col col-3' ;
                                        }elseif($perguntaArr['HmPergunta']['tipo'] == 'date'){ $cols = 'col col-3' ;
                                        }elseif($perguntaArr['HmPergunta']['tipo'] == 'hour'){ $cols = 'col col-2' ;
                                        }elseif($perguntaArr['HmPergunta']['tipo'] == 'checkbox'){ $cols = 'col col-8' ;
                                        }elseif($perguntaArr['HmPergunta']['tipo'] == 'textarea'){ $cols = 'col col-4' ;
                                        }elseif(in_array($perguntaArr['HmPergunta']['tipo'],array('numero_inteiro','numero_decimal','inteiro','telefone','peso','altura','imc','pressao'))){ $cols = 'col col-2' ;
                                        }

                                        #if(preg_match('/Colesterol/',$perguntaArr['HmPergunta']['pergunta']) || preg_match('/Trigliceri/',$perguntaArr['HmPergunta']['pergunta'])){$cols = 'col col-2'; }
                                        $style='';
                                        if(in_array($perguntaArr['HmPergunta']['tipo'],array('textarea','text')) && is_null($perguntaArr['HmPergunta']['mesma_linha'])){ 
                                            $cols = 'margin-left-15 margin-right-15';
                                            $style = 'float:left;width:98%;';
                                        }
                                        if(in_array($perguntaArr['HmPergunta']['tipo'],array('checkbox')) && is_null($perguntaArr['HmPergunta']['mesma_linha'])){ 
                                            $cols = 'margin-left-15 margin-right-15';
                                            $style = 'float:left;width:98%;';
                                        }
                                        
                                        #PERGUNTA
                                        
                                        if($perguntaArr['HmPergunta']['tipo'] == 'cod_usuario'){
                                            #$style='style="display:none;"';#DESCOMENTAR
                                        }


                                        #pula o imc se não tem resposta
                                        if ($perguntaArr['HmPergunta']['tipo'] == 'imc' && !isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])) {
                                            continue;
                                        }


                                        $htmlInput .= ' <section class="'.$cols.'" style="'.$style.'">';


                                        $ajd = '';
                                        if($perguntaArr['HmPergunta']['ajuda'] != ''){
                                            $ajd = str_replace('[AJUDA]',$perguntaArr['HmPergunta']['ajuda'],$ajudaMsg);
                                        }
                                        $htmlInput .= ' <label style="margin-bottom:5px; ">
                                                            '.$perguntaArr['HmPergunta']['pergunta'].$ajd.' 
                                                        </label>';

                                        if($_SESSION['cod_usuario'] == 93686){
//                                            $htmlInput .= ' <span style="color:red;">'.$perguntaArr['HmPergunta']['tipo'].'</span>';#TESTE
                                        }

                                        #RADIO TIPO
                                        if ($perguntaArr['HmPergunta']['tipo'] == 'radio' ) {
                                            $htmlInput .= '<div class="col col-12">';
                                            if (count($perguntaArr['HmOpcao']) > 0) {
                                                foreach ($perguntaArr['HmOpcao'] as $opcoesArr) {
                                                    #DEPENDENCIA SHOW
                                                    $data_dep_show = '';
                                                    $class='';
                                                    if(count($row['PerguntasDepShow'])> 0){
                                                        if (array_key_exists($perguntaArr['HmPergunta']['cod_hm_pergunta'], $row['PerguntasDepShow'])){
                                                            #PARA OCULTAR - busca perguntas para montar ação reversa
                                                            $dataPergOculta = array();
                                                            $class='click_show_dep_radio';
                                                            foreach($row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']] as $opcOculta){
                                                                foreach($opcOculta as $pergOculta){
                                                                    $dataPergOculta[$pergOculta] = $pergOculta;
                                                                }
                                                            }

                                                            #MONTA REGRA DE "ACAO" DE OCULTAR E EXIBIR
                                                            $dataAcaoArr = $dataPergArr = array();
                                                            foreach($dataPergOculta as $pergOculta){
                                                                if(isset($row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']][$opcoesArr['cod_hm_opcao']]) && 
                                                                    in_array($pergOculta,$row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']][$opcoesArr['cod_hm_opcao']])){
                                                                    $dataAcaoArr[] = 1;
                                                                    $dataPergArr[] = $pergOculta;
                                                                }else{
                                                                    $dataAcaoArr[] = 0;
                                                                    $dataPergArr[] = $pergOculta;
                                                                }
                                                            }
                                                            $data_dep_show = ' dep_acao="'.implode(',',$dataAcaoArr).'" dep_pergunta="'.implode(',',$dataPergArr).'"';
                                                        }
                                                    }
                                                    


                                                    #NOME DO CAMPO PARA PERGUNTAS MÚLTIPLAS (DINÂMICAS)
                                                    $name_ = 'data[HmResposta][radio][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']';
                                                    $name_multiplo_ = '';
                                                    if($perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                        $name_ = 'data[HmResposta][radio][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']['.$conta_exclusao.']';
                                                        $name_multiplo_ = 'data[HmResposta][radio][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][MULTIPLO_NUM]';
                                                    }
                                                    #'name'=>$name_ ,'name_multiplo'=>$name_multiplo_
                                                    #name="'.$name_.'" name_multiplo="'.$name_multiplo_.'"

                                                    
                                                    $check = '';
                                                    $htmlInput .= '<label class="radio descheck_radio " rel_id="radio_' . $opcoesArr['cod_hm_pergunta'] . '" style="padding-top:0;">';
                                                    $htmlInput .= '<input type="radio" class="'.$class.'" '.$data_dep_show.' name="'.$name_.'" name_multiplo="'.$name_multiplo_.'" id="radio_' . $opcoesArr['cod_hm_pergunta'] . '" value="' . $opcoesArr['cod_hm_opcao'] . '" '.$check.'>';
                                                    $htmlInput .= '<i></i>' . $opcoesArr['descricao'];
                                                    $htmlInput .= '</label>';
                                                }
                                            }
                                            $htmlInput .= '</div>';

                                        }
                                        #CHECKBOX
                                        elseif ($perguntaArr['HmPergunta']['tipo'] == 'checkbox') {
                                            $htmlInput .= '<div class="row" >';
                                            $htmlInput .= '<div class="col col-4" class="checkbox">';
                                            if (count($perguntaArr['HmOpcao']) > 0) {
                                                foreach ($perguntaArr['HmOpcao'] as $kOcoes => $opcoesArr) {
                                                    if($kOcoes > 0 && $kOcoes % ceil(count($perguntaArr['HmOpcao']) / 3) == 0){ #DIVIDE POR 2 AS COLUNAS
                                                        $htmlInput .= '</div><div class="col col-4" class="checkbox">';
                                                    }
                                                    #DEPENDENCIA SHOW
                                                    $data_dep_show = '';
                                                    $class='';
                                                    if(isset($row['PerguntasDepShow']) && count($row['PerguntasDepShow'])> 0){
                                                        if (array_key_exists($perguntaArr['HmPergunta']['cod_hm_pergunta'], $row['PerguntasDepShow'])){
                                                            #PARA OCULTAR - busca perguntas para montar ação reversa
                                                            $dataPergOculta = array();
                                                            foreach($row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']] as $opcOculta){
                                                                foreach($opcOculta as $pergOculta){
                                                                    $dataPergOculta[$pergOculta] = $pergOculta;
                                                                }
                                                            }

                                                            #MONTA REGRA DE "ACAO" DE OCULTAR E EXIBIR
                                                            $dataAcaoArr = $dataPergArr = array();
                                                            foreach($dataPergOculta as $pergOculta){
                                                                if(isset($row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']][$opcoesArr['cod_hm_opcao']]) && 
                                                                    in_array($pergOculta,$row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']][$opcoesArr['cod_hm_opcao']])){
                                                                    $dataAcaoArr[] = 1;
                                                                    $dataPergArr[] = $pergOculta;
                                                                    $class='class="click_show_dep_checkbox"';
                                                                }
                                                            }
                                                            if(count($dataAcaoArr) > 0){
                                                                $data_dep_show = ' dep_pergunta="'.implode(',',$dataPergArr).'"';
                                                            }
                                                        }
                                                    }

                                                    $check = '';
                                                    if(isset($row['HmRespostasDinamicas'])){
                                                        if(isset($row['HmRespostasDinamicas'][$opcoesArr['cod_hm_pergunta']])) {
                                                            if(array_key_exists($opcoesArr['cod_hm_opcao'],$row['HmRespostasDinamicas'][$opcoesArr['cod_hm_pergunta']])){
                                                                $check .= ' checked="checked"';
                                                            }
                                                        }
                                                    }
                                                   
                                                    #NOME DO CAMPO PARA PERGUNTAS MÚLTIPLAS (DINÂMICAS)
                                                    $name_ = 'data[HmResposta][checkbox][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][]';
                                                    $name_multiplo_ = '';
                                                    if($perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                        $name_ = 'data[HmResposta][checkbox][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']['.$conta_exclusao.'][]';
                                                        $name_multiplo_ = 'data[HmResposta][checkbox][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][MULTIPLO_NUM][]';
                                                    }
                                                    #'name'=>$name_ ,'name_multiplo'=>$name_multiplo_
                                                    #name="'.$name_.'" name_multiplo="'.$name_multiplo_.'"

                                                    $htmlInput .= '<label class="checkbox">';
                                                    $htmlInput .= '<input type="checkbox" '.$class.' '.$data_dep_show.' name="'.$name_.'" name_multiplo="'.$name_multiplo_.'" id="checkbox_' . $opcoesArr['cod_hm_pergunta'] . '" value="' . $opcoesArr['cod_hm_opcao'] . '" '.$check.'>';
                                                    $htmlInput .= '<i></i>' . $opcoesArr['descricao'];
                                                    $htmlInput .= '</label>';
                                                }
                                            }
                                            $htmlInput .= '</div>';
                                            $htmlInput .= '</div>';

                                        }
                                        #COMBOBOX
                                        elseif ($perguntaArr['HmPergunta']['tipo'] == 'combobox') {
                                            #NOME DO CAMPO PARA PERGUNTAS MÚLTIPLAS (DINÂMICAS)
                                            $name_ = 'data[HmResposta][select][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']';
                                            $name_multiplo_ = '';
                                            $id_ = '';
                                            if($perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                $name_ = 'data[HmResposta][select][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']['.$conta_exclusao.']';
                                                $name_multiplo_ = 'data[HmResposta][select][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][MULTIPLO_NUM]';
                                                $id_ = 'HmProntuario'.$perguntaArr['HmPergunta']['cod_hm_pergunta'];
                                            }
                                            #'name'=>$name_ ,'name_multiplo'=>$name_multiplo_
                                            #name="'.$name_.'" name_multiplo="'.$name_multiplo_.'"
                                            
                                            $disabled = $state = '';
                                            $htmlInput .= '<label class="select" >';
                                            $htmlInput .= '<select class="input-sm " id="'.$id_.'" name="'.$name_.'" name_multiplo="'.$name_multiplo_.'" '.$disabled.'>';
                                            if (count($perguntaArr['HmOpcao']) > 0) {
                                                $htmlInput .= '<option value="" >Selecione...</option>';


                                                $tmp_categoria = '';
                                                foreach ($perguntaArr['HmOpcao'] as  $opcoesArr) {

                                                    #BEGIN - Categoria no select
                                                    if($opcoesArr['categoria'] != '' && $tmp_categoria != $opcoesArr['categoria']){
                                                        $tmp_categoria = $opcoesArr['categoria'];
                                                        $htmlInput .= '<optgroup label="'.$tmp_categoria.'">';
                                                    }
                                                    #END - Categoria no select

                                                    $selected = '';

                                                    if(isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]) && 
                                                       in_array($opcoesArr['cod_hm_opcao'],$row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])){
                                                        $selected = 'selected="selected"';
                                                    }
                                                    $htmlInput .= '<option value="' . $opcoesArr['cod_hm_opcao'] . '" '.$selected.'>' . $opcoesArr['descricao'] . '</option>';
                                                }
                                                #BEGIN - Categoria no select
                                                if($tmp_categoria != ''){
                                                    $htmlInput .= '</optgroup>';
                                                }
                                                #END - Categoria no select
                                            }
                                            $htmlInput .= '</select> <i></i></label>';

                                        }
                                        #DATA
                                        elseif ($perguntaArr['HmPergunta']['tipo'] == 'date') {
                                            $htmlInput .= '<label class="input"> <i class="icon-append fa fa-calendar"></i>';
                                            #$htmlInput .= $this->Form->input($perguntaArr['HmPergunta']['cod_hm_pergunta'], array('name'=>'data[HmResposta][date][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][]'   , 'type' => 'date', 'label' => false, 'div' => false, 'placeholder' => $perguntaArr['HmPergunta']['pergunta'], 'class' => 'input_login date_mask', 'minYear' => date('Y') - 100, 'maxYear' => date('Y') - 10, 'dateFormat' => 'DMY', 'selected' => ''));
                                            #$htmlInput .= $this->Form->input($perguntaArr['HmPergunta']['cod_hm_pergunta'], array('name'=>'data[HmResposta][date][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][]','label' => false, 'div' => false, 'placeholder' => '__/__/____', 'class' => 'date_mask col3 margin-right-cadastre', 'maxlength' => '10'));
                                            $value = '';
                                            if(isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]) && 
                                               $row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['data'] != ''){
                                                $value = $row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['data'];
                                            }

                                            #NOME DO CAMPO PARA PERGUNTAS MÚLTIPLAS (DINÂMICAS)
                                            $name_ = 'data[HmResposta][date][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']';
                                            $name_multiplo_ = '';
                                            if($perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                $name_ = 'data[HmResposta][date][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']['.$conta_exclusao.']';
                                                $name_multiplo_ = 'data[HmResposta][date][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][MULTIPLO_NUM]';
                                            }

                                            $htmlInput .= $this->Form->date($perguntaArr['HmPergunta']['cod_hm_pergunta'], array('name'=>$name_, 'name_multiplo'=>$name_multiplo_,'dateFormat' => 'DMY', 'label' => false, 'div' => false, 'placeholder' => '','dateFormat' => 'DMY', 'class' => 'col3 margin-right-cadastre ', 'maxlength' => '10','value'=>$value));
                                            $htmlInput .= '</label>';
                                        }
                                        #TEXTAREA    
                                        elseif ($perguntaArr['HmPergunta']['tipo'] == 'textarea') {
                                            $value = '';
                                            if(isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]) && 
                                               $row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['descritivo'] != ''){
                                                $value = $row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['descritivo'];
                                            }

                                            #NOME DO CAMPO PARA PERGUNTAS MÚLTIPLAS (DINÂMICAS)
                                            $name_ = 'data[HmResposta][textarea][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']';
                                            $name_multiplo_ = '';
                                            if($perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                $name_ = 'data[HmResposta][textarea][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']['.$conta_exclusao.']';
                                                $name_multiplo_ = 'data[HmResposta][textarea][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][MULTIPLO_NUM]';
                                            }
                                            #'name'=>$name_ ,'name_multiplo'=>$name_multiplo_

                                            $htmlInput .= '<label class="textarea">';
                                            $htmlInput .= $this->Form->textarea($perguntaArr['HmPergunta']['cod_hm_pergunta'], array('name'=>$name_ ,'name_multiplo'=>$name_multiplo_  ,'label' => false, 'div' => false, 'placeholder' => str_replace(':','',$perguntaArr['HmPergunta']['pergunta']), 'class' => 'custom-scroll', 'rows' => 3,'value'=>$value));
                                            $htmlInput .= '</label>';
                                        }
                                        else{

                                            $conditions_add = array();
                                            $class = '';
                                            $icon = '';
                                            if(isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])){
                                                $conditions_add = array('value'=>$row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['descritivo']);
                                            }


                                            if($perguntaArr['HmPergunta']['tipo'] == 'numero_inteiro'){
                                                $conditions_add = array('data-number'=>'9?999');
                                                if(isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])){
                                                    $conditions_add = array('value'=>$row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['descritivo'],'data-number'=>'9?999');
                                                }
                                                $class = 'input_numerico';
                                            }elseif($perguntaArr['HmPergunta']['tipo'] == 'telefone'){
                                                $class = 'tel_mask';
                                                $icon = '<i class="icon-append fa fa-phone"></i>';
                                            }elseif($perguntaArr['HmPergunta']['tipo'] == 'hour'){
                                                $class = 'timepicker';
                                                $icon = '<i class="icon-append fa fa-clock-o"></i>';
                                            }elseif($perguntaArr['HmPergunta']['tipo'] == 'peso'){
                                                $class = 'peso_mask';
                                                $icon = '<i class="icon-append fa fa-dashboard"></i>';
                                            }elseif($perguntaArr['HmPergunta']['tipo'] == 'altura'){
                                                $class = 'altura_mask';
                                                $icon = '<i class="icon-append fa fa-male"></i>';
                                            }elseif($perguntaArr['HmPergunta']['tipo'] == 'imc'){
                                                $conditions_add = array('readyonly'=>true,'disabled'=>true);
                                                if(isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])){
                                                    $conditions_add = array('value'=>$row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['descritivo'],'readyonly'=>true,'disabled'=>true);
                                                }
                                            }elseif($perguntaArr['HmPergunta']['tipo'] == 'pressao'){
                                                $class = 'pressao_mask';
                                            }elseif($perguntaArr['HmPergunta']['tipo'] == 'cod_usuario'){
                                                $conditions_add = array('value'=>$_SESSION['cod_usuario']);
                                                if(isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])){
                                                    $conditions_add = array('value'=>$row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['descritivo']);
                                                }
                                            }

                                            

                                            #NOME DO CAMPO PARA PERGUNTAS MÚLTIPLAS (DINÂMICAS)
                                            $name_ = 'data[HmResposta][input][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']';
                                            $name_multiplo_ = '';
                                            if($perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                $name_ = 'data[HmResposta][input][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']['.$conta_exclusao.']';
                                                $name_multiplo_ = 'data[HmResposta][input][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][MULTIPLO_NUM]';
                                            }
                                            #'name'=>$name_ ,'name_multiplo'=>$name_multiplo_
                                            


                                            #NOME DA PERGUNTA
                                            $htmlInput .= ' <label class="input">';
                                            $htmlInput .= $icon;
                                            $htmlInput .=    $this->Form->input($perguntaArr['HmPergunta']['cod_hm_pergunta'], array_merge(array('name'=>$name_ ,'name_multiplo'=>$name_multiplo_,'label' => false, 'div' => false, 'class' => 'input_login '.$class),$conditions_add));
                                            $htmlInput .= ' </label>';
                                        }

                                        #DESCRITIVO DO CAMPO
                                        if(!is_null($perguntaArr['HmPergunta']['descritivo'])){
                                            $htmlInput .= '<div class="note">'.$perguntaArr['HmPergunta']['descritivo'].'</div>';
                                        }                                
                                        $htmlInput .= '</section>';
                                        if(!$fim_mesma_linha){
                                            
                                            #FINAL DA CATEGORIA
                                            if($fim_categoria){
                                                $display_none_excluir = 'display:none;';
                                                if($categoria_qtd > 0 && $perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                #if($perguntaArr['HmPergunta']['multiplo'] == '1'){
                                                    $display_none_excluir = '';
                                                }
                                                $htmlInput .= '   <div class="text-right " style=" margin-bottom: 10px; margin-right: 10px; ">
                                                                    <a class="btn btn-danger btn_exclusao" href="javascript:void(0);" ajaxmsg="Tem certeza que deseja excluir este registro?" style="padding:3px 7px 3px 5px; '.$display_none_excluir.'" data_value = "">
                                                                        <i class="fa fa-trash-o"></i> <span class="hidden-mobile">Excluir</span>
                                                                    </a>
                                                                  </div>';

                                                $htmlInput .= '</div>';
                                                $conta_exclusao++;
                                            }
                                            $htmlInput .= '</div>';
                                        }
                                    }
                                        echo $htmlInput;
                                }
                                echo '                  </div>';
                                echo '              </div>';
                                $countG++;
                            }
                            echo '          </div>';
                            #END - CONTEUDO DOS GRUPOS (PERGUNTAS)
                            echo '      </div>';
                            echo '  <div class="onTop hidden-xs hidden-sm" style="text-align:center; cursor:pointer; display:none;  bottom:0; right:10px; position:fixed; z-index:100000; "> 
                                        <div style="background-color: #EEE; 
                                            border-color: #777; 
                                            font-weight:bold; 
                                            width: 90px;
                                            color: #737373; 
                                            font-size: 12x; 
                                            border-radius: 10px 10px 0 0; 
                                            height: 22px; 
                                            padding: 12px 0 0 0; 
                                            margin-top: 32px; float:right;    
                                             box-shadow: 0px -1px 5px 0px rgba(50, 50, 50, 0.50); 
                                            -moz-box-shadow: 0px -1px 5px 0px rgba(50, 50, 50, 0.50);  
                                            -webkit-box-shadow: 0px -1px 5px 0px rgba(50, 50, 50, 0.50);
                                            opacity: 0.9;
                                            ">
                                            Menu <i class="fa fa-arrow-up"></i>
                                        </div>
                                    </div>';
                            echo '  </div>';
                            
                            echo '  <hr style="border-bottom: 16px solid #fff; margin: 40px -14px 40px -14px; border-top:none; ' 
                                    . '-webkit-box-shadow:0px 9px 10px 0px rgba(50, 50, 50, 0.50); '
                                    . '-moz-box-shadow:0px 9px 10px 0px rgba(50, 50, 50, 0.50); '
                                    . 'box-shadow:0px 9px 10px 0px rgba(50, 50, 50, 0.50); ">';
                        }
#END - exibição por grupo                        
#BEGIN - exibição sem grupo
                        else
                        {
                            $htmlInput = '';
                            if(isset($row['Perguntas']) && count($row['Perguntas']) > 0){
                                echo '  <div class="row">
                                            <h5>Perguntas do Programa</h5>
                                        </div>';

                                $kPerguntaAnterior = '';

                                #INICIA A LISTA DE PERGUNTAS
                                $numPerg = 0;
                                foreach($row['Perguntas'] as $kPergunta => $perguntaArr){
                                    $numPerg++;
                                    #DEPENDENCIA HIDDEN
                                    $class_dep_hidden = '';
                                    $classPergNum = 'pergunta_numero';
                                    if(count($row['PerguntasDepHidden'])>0){
                                        if (in_array($perguntaArr['HmPergunta']['cod_hm_pergunta'], $row['PerguntasDepHidden'])) {
                                            #BEGIN - EXIBIR PERGUNTA FOI RESPONDIDA
                                            $class_dep_hidden = 'display_none pergunta_' . $perguntaArr['HmPergunta']['cod_hm_pergunta'];
                                            $classPergNum = '';
                                            #END - EXIBIR PERGUNTA FOI RESPONDIDA
                                        }
                                    }
                                    
                                    $margintop = $numPerg > 1 ? 'margin-top:15px;': '';
                                    $htmlInput .= '<div class="row ' . $class_dep_hidden . '" style="">';
                                    #INCLUSÃO DAS COLUNAS DO SECTION PARA DEFINIÇÃO DO TAMANHO DENTRO DA TELA
                                    $cols = 'col-10' ;
                                    if($perguntaArr['HmPergunta']['tipo'] == 'text'){ $cols = 'col-6' ;
                                    }elseif(in_array($perguntaArr['HmPergunta']['tipo'],array('numero_inteiro','numero_decimalinteiro'))){ $cols = 'col-4' ;
                                    }
                                    $htmlInput .= '<section class="col '.$cols.'">';

                                    #NOME DA PERGUNTA
                                    $htmlInput .= '<label style="margin-bottom:5px;">
                                                            <span class="exibi_pergunta '.$classPergNum.'"></span>
                                                            '.$perguntaArr['HmPergunta']['pergunta'].'
                                                    </label><br>';


                                    #RADIO
                                    if ($perguntaArr['HmPergunta']['tipo'] == 'radio' ) {
                                        $htmlInput .= '<div class="col col-12">';
                                        if (count($perguntaArr['HmOpcao']) > 0) {
                                            foreach ($perguntaArr['HmOpcao'] as $opcoesArr) {
                                                #DEPENDENCIA SHOW
                                                $data_dep_show = '';
                                                $class='';
                                                if(count($row['PerguntasDepShow'])> 0){
                                                    if (array_key_exists($perguntaArr['HmPergunta']['cod_hm_pergunta'], $row['PerguntasDepShow'])){
                                                        #PARA OCULTAR - busca perguntas para montar ação reversa
                                                        $dataPergOculta = array();
                                                        $class='click_show_dep_radio';
                                                        foreach($row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']] as $opcOculta){
                                                            foreach($opcOculta as $pergOculta){
                                                                $dataPergOculta[$pergOculta] = $pergOculta;
                                                            }
                                                        }

                                                        #MONTA REGRA DE "ACAO" DE OCULTAR E EXIBIR
                                                        $dataAcaoArr = $dataPergArr = array();
                                                        foreach($dataPergOculta as $pergOculta){
                                                            if(isset($row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']][$opcoesArr['cod_hm_opcao']]) && 
                                                                in_array($pergOculta,$row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']][$opcoesArr['cod_hm_opcao']])){
                                                                $dataAcaoArr[] = 1;
                                                                $dataPergArr[] = $pergOculta;
                                                            }else{
                                                                $dataAcaoArr[] = 0;
                                                                $dataPergArr[] = $pergOculta;
                                                            }
                                                        }
                                                        $data_dep_show = ' dep_acao="'.implode(',',$dataAcaoArr).'" dep_pergunta="'.implode(',',$dataPergArr).'"';
                                                    }
                                                }


                                                $check = '';
                                                $htmlInput .= '<label class="radio descheck_radio " rel_id="radio_' . $opcoesArr['cod_hm_pergunta'] . '" style="padding-top:0;">';
                                                $htmlInput .= '<input type="radio" class="'.$class.'" '.$data_dep_show.' name="data[HmResposta][radio][' . $opcoesArr['cod_hm_pergunta'] . ']" id="radio_' . $opcoesArr['cod_hm_pergunta'] . '" value="' . $opcoesArr['cod_hm_opcao'] . '" '.$check.'>';
                                                $htmlInput .= '<i></i>' . $opcoesArr['descricao'];
                                                $htmlInput .= '</label>';
                                            }
                                        }
                                        $htmlInput .= '</div>';


                                    #CHECKBOX
                                    } 
                                    elseif ($perguntaArr['HmPergunta']['tipo'] == 'checkbox') {
                                        $htmlInput .= '<div class="row">';
                                        $htmlInput .= '<div class="col col-4" class="checkbox">';
                                        if (count($perguntaArr['HmOpcao']) > 0) {

                                            foreach ($perguntaArr['HmOpcao'] as $kOcoes => $opcoesArr) {
                                                if($kOcoes > 0 && $kOcoes % ceil(count($perguntaArr['HmOpcao']) / 2) == 0){ #DIVIDE POR 2 AS COLUNAS
                                                    $htmlInput .= '</div><div class="col col-4" class="checkbox">';
                                                }

                                                #DEPENDENCIA SHOW
                                                $data_dep_show = '';
                                                $class='';
                                                if(count($row['PerguntasDepShow'])> 0){
                                                    if (array_key_exists($perguntaArr['HmPergunta']['cod_hm_pergunta'], $row['PerguntasDepShow'])){
                                                        #PARA OCULTAR - busca perguntas para montar ação reversa
                                                        $dataPergOculta = array();
                                                        foreach($row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']] as $opcOculta){
                                                            foreach($opcOculta as $pergOculta){
                                                                $dataPergOculta[$pergOculta] = $pergOculta;
                                                            }
                                                        }

                                                        #MONTA REGRA DE "ACAO" DE OCULTAR E EXIBIR
                                                        $dataAcaoArr = $dataPergArr = array();
                                                        foreach($dataPergOculta as $pergOculta){
                                                            if(isset($row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']][$opcoesArr['cod_hm_opcao']]) && 
                                                                in_array($pergOculta,$row['PerguntasDepShow'][$perguntaArr['HmPergunta']['cod_hm_pergunta']][$opcoesArr['cod_hm_opcao']])){
                                                                $dataAcaoArr[] = 1;
                                                                $dataPergArr[] = $pergOculta;
                                                                $class='class="click_show_dep_checkbox"';
                                                            }
                                                        }
                                                        if(count($dataAcaoArr) > 0){
                                                            $data_dep_show = ' dep_pergunta="'.implode(',',$dataPergArr).'"';
                                                        }

                                                    }
                                                }


                                                $check = '';
                                                if(isset($session['HmResposta'])){
                                                    if(isset($session['HmResposta'][$opcoesArr['cod_hm_pergunta']])) {
                                                        if(in_array($opcoesArr['cod_hm_opcao'],$session['HmResposta'][$opcoesArr['cod_hm_pergunta']])){
                                                            $check .= ' checked="checked"';
                                                        }
                                                    }
                                                }


                                                $htmlInput .= '<label class="checkbox">';
                                                $htmlInput .= '<input type="checkbox" '.$class.' '.$data_dep_show.' name="data[HmResposta][checkbox][' . $opcoesArr['cod_hm_pergunta'] . '][]" id="checkbox_' . $opcoesArr['cod_hm_pergunta'] . '" value="' . $opcoesArr['cod_hm_opcao'] . '" '.$check.'>';
                                                $htmlInput .= '<i></i>' . $opcoesArr['descricao'];
                                                $htmlInput .= '</label>';
                                                $htmlInput .= '</section>';
                                            }
                                        }
                                        $htmlInput .= '</div>';
                                        $htmlInput .= '</div>';


                                    #COMBOBOX
                                    }
                                    elseif ($perguntaArr['HmPergunta']['tipo'] == 'combobox') {
                                        $disabled = $state = '';
                                        $htmlInput .= '<label class="select" style="margin-top:10px;">';
                                        $htmlInput .= '<select class="input-sm " name="data[HmResposta][select][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']" '.$disabled.'>';
                                        if (count($perguntaArr['HmOpcao']) > 0) {
                                            $htmlInput .= '<option value="" >Selecione...</option>';
                                            $tmp_categoria = '';
                                            foreach ($perguntaArr['HmOpcao'] as $opcoesArr) {
                                                #BEGIN - Categoria no select
                                                if($opcoesArr['categoria'] != '' && $tmp_categoria != $opcoesArr['categoria']){
                                                    $tmp_categoria = $opcoesArr['categoria'];
                                                    $htmlInput .= '<optgroup label="'.$tmp_categoria.'">';
                                                }
                                                #END - Categoria no select

                                                $selected = '';

                                                if(isset($session['HmResposta'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]) && 
                                                   in_array($opcoesArr['cod_hm_opcao'],$session['HmResposta'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])){
                                                    $selected = 'selected="selected"';
                                                }
                                                $htmlInput .= '<option value="' . $opcoesArr['cod_hm_opcao'] . '" '.$selected.'>' . $opcoesArr['descricao'] . '</option>';
                                            }
                                            #BEGIN - Categoria no select
                                            if($tmp_categoria != ''){
                                                $htmlInput .= '</optgroup>';
                                            }
                                            #END - Categoria no select
                                        }

                                        $htmlInput .= '</select> <i></i></label>';


                                    #DATA
                                    }
                                    elseif ($perguntaArr['HmPergunta']['tipo'] == 'date') {
                                        $htmlInput .= '<label class="input">';
                                        $htmlInput .= $this->Form->input($perguntaArr['HmPergunta']['cod_hm_pergunta'], array('name'=>'data[HmResposta][date][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][]'   , 'type' => 'date', 'label' => false, 'div' => false, 'placeholder' => $perguntaArr['HmPergunta']['pergunta'], 'class' => 'input_login date_mask', 'minYear' => date('Y') - 100, 'maxYear' => date('Y') - 10, 'dateFormat' => 'DMY', 'selected' => ''));
                                        $htmlInput .= '</label>';


                                    #TEXTAREA    
                                    }
                                    elseif ($perguntaArr['HmPergunta']['tipo'] == 'textarea') {
                                        $htmlInput .= '<label class="textarea">';
                                        $htmlInput .= $this->Form->textarea($perguntaArr['HmPergunta']['cod_hm_pergunta'], array('name'=>'data[HmResposta][textarea][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][]'   ,'label' => false, 'div' => false, 'placeholder' => $perguntaArr['HmPergunta']['pergunta'], 'class' => 'custom-scroll', 'rows' => 3));
                                        $htmlInput .= '</label>';
                                    } 
                                    else {
                                        $htmlInput .= '<label class="input">';
                                        $htmlInput .= $this->Form->input($perguntaArr['HmPergunta']['cod_hm_pergunta'], array('name'=>'data[HmResposta][input][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']'   ,'label' => false, 'div' => false, 'class' => 'input_login'));
                                        $htmlInput .= '</label>';
                                    }

                                    $htmlInput .= '</div>';
                                    $htmlInput .= '</section>';

                                }
                                echo $htmlInput;
                            }    
                        }

#END - exibição sem grupo
                        
                    ?>
                    
                    
                    <div class="row" style="<?php echo $class_margin;?>">
                        <h5>Informações do Atendimento</h5>
                    </div>
                    
                     <?php  if($cod_hm_programa == 2): ?>
                        <div class="row" style="margin-bottom:20px; ">
                            <section class="col col-4">
                                <label class="label">Tipo do Atendimento <?php echo $obrigatorio; ?></label>
                                <label class="select">
                                    <?php echo $this->Form->input('tipo_acolhimento', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Acolhimento', 'class' => 'input_login', 'options' => $tipo_acolhimento_arr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-3 exibi_cid" style="display:none;">
                                <label class="label">CID - Classificação Internacional de Doenças</label>
                                <label class="input">
                                    <?php echo $this->Form->input('cid', array('label' => false, 'div' => false, 'placeholder' => 'CID', 'class' => 'input_login','maxlength'=>'6' ,'default' => '')); ?>
                                    <i></i>
                                </label>
				<div class="note"><strong>Exemplo:</strong> F41.9</div> 
                            </section>
                        </div>
                    <?php endif; ?>
                    
                
                    
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Lista de Ação:</label>
                            <label class="select">
                                <?php echo $this->Form->input('cod_lista_acao', array('label' => false, 'div' => false, 'placeholder' => 'Lista de Ação', 'class' => 'input_login', 'options' => $row['lista_acao'], 'default' => '')); ?>
                                <i></i>
                            </label>
                            <span class="note">Lembrete: Incluir este registro para esta pessoa na lista de ação.
                        </section>

                        <section class="col col-4 exibir_acao" style="display: none ;">
                            <label class="label">Ação: <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('cod_acao', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Atendimento', 'class' => 'input_login', 'options' => array(''=>'Escolha uma Lista de ação!'), 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2 exibir_acao" style="display: none ;">
                            <label class="label">Data: <?php echo $obrigatorio; ?></label>
                            <label class="input">
                                <?php echo $this->Form->date('data_acao', array('minYear' => date('Y'), 'maxYear' => date('Y') , 'label' => 'Data da Ação', 'div' => false, 'placeholder' => 'Data da ação', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                            </label>
                        </section>                        
                    </div>




                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Atendimento feito por: <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('tipo_atendimento', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Atendimento', 'class' => 'input_login', 'options' => $tipoAtendimentoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4 exibir_status_telefone" style="display: none;">
                            <label class="label">Status da Conversa: <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('cod_hm_status', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Atendimento', 'class' => 'input_login', 'options' => $statusLigacaoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        
                        <section class="col col-1 exibi_hora_atendimento" style="display: none;">
                            <label class="label">Tempo Total: <?php echo $obrigatorio; ?></label>
                            <label class="input">
                                <?php echo $this->Form->input('at_horas', array('label' => false, 'div' => false, 'placeholder' => 'HH', 'class' => 'input_login', 'style' => 'width:90%', 'default' => '' ,'maxlength'=>'2')); ?>
                            </label>
                        </section>
                        <section class="col col-1 exibi_hora_atendimento" style="display: none; margin-left:-32px; ">
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <?php echo $this->Form->input('at_minutos', array('label' => false, 'div' => false, 'placeholder' => 'MM', 'class' => 'input_login', 'style' => 'width:90%','default' => '','maxlength'=>'2')); ?>
                            </label>
                        </section>
                    </div>


                    <section  style="margin-top:30px; margin-bottom:30px;">
                        <label class="label">Descrição Médica:</label>
                        <label class="textarea">
                            <?php echo $this->Form->textarea('texto', array('rows' => 2, 'style' => 'width:100%;', 'style' => 'width:100%;', 'label' => false, 'div' => false, 'placeholder' => 'Introdução')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa  fa-chevron-right txt-color-blueLight"></i> Entre com a Descrição</b>
                        </label>
                    </section>
                    
                    <div class="row" style="margin-top:20px;">
                        <section class="col col-4">
                            <label class="label">Deseja responder o atendimento do VS? <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('resposta_atendimento', array('label' => false, 'div' => false, 'placeholder' => 'Responder Atendimetno', 'class' => 'input_login', 'options' => $simNaoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <section  style=" display:none;" class="exibi_descricao_vs">
                        <label class="label">Descrição para o Atendimento do VS:</label>
                        <label class="textarea">
                            <?php echo $this->Form->textarea('texto_atendimento', array('rows' => 2, 'style' => 'width:100%;', 'style' => 'width:100%;', 'label' => false, 'div' => false, 'placeholder' => 'Introdução')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa  fa-chevron-right txt-color-blueLight"></i> Entre com a Descrição</b>
                        </label>
                    </section>
                </fieldset>
            </div>    
            <footer>
                <button type="submit" class="btn btn-primary ">
                    Salvar
                </button>
                <span class="campo_obrigatorio">* Campos Obrigatórios</span>
            </footer>
            <?php 
                if(isset($conta_exclusao)){
                    $contador_exclusao = $conta_exclusao;
                    if(isset($row['conta_multiplo'])){
                        $contador_exclusao = $row['conta_multiplo'];
                    }
                    echo '<input type="hidden" value="'.$conta_exclusao.'" id="conta_exclusao">';
                }
            ?>
             
             

        <?php echo $this->Form->end();?>


    <!-- end widget div -->


<?php

    #krumo($row);
?>

</div>
<script>
    $(document).ready(function() {
        CKEDITOR.replace( 'data[HmProntuario][texto]', {<?php echo str_replace('280px','180px',$config_ckeditor);?>});
        CKEDITOR.replace( 'data[HmProntuario][texto_atendimento]', {<?php echo str_replace('280px','180px',$config_ckeditor);?>});
        
        $('#HmProntuarioCodListaAcao').change(function(){
            cod_lista_acao = $(this).val();
            //console.log(cod_lista_acao);

            if(cod_lista_acao == ''){
                $('.exibir_acao').fadeOut('slow');
                $("#HmProntuarioCodAcao").empty();
                $("#HmProntuarioCodAcao").append('<option value="">Carregando...</option>');
                $("#HmProntuarioDataAcao").val('');

            }else{
                $('.exibir_acao').fadeIn('slow');
                $("#HmProntuarioCodAcao").empty();
                $("#HmProntuarioCodAcao").append('<option value="">Carregando...</option>');

                $.ajax({
                    url:"<?php echo Router::url('/hm_prontuario/busca_acoes/',true); ?>",
                    data: {busca: cod_lista_acao},
                    dataType: "json",
                    type: 'POST',
                    async: true,
                    cache: false,
                    statusCode: {
                        403: function() {alert(' Desculpe, você perdeu sua sessão, logue-se novamente!'); location.reload(true);},
                        404: function() {alert(' Desculpe, a página solicitada não foi encontrada!'); },
                        500: function() {alert(' Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!'); }
                    },
                    success: function(data) {
                        //console.log(data.lenght);
                        //console.log(data);
                        
                        $("#HmProntuarioCodAcao").empty();
                        $("#HmProntuarioCodAcao").append('<option value="">Selecione...</option>');
                        jQuery.each(data, function(i, val) {
                            $("#HmProntuarioCodAcao").append('<option value="'+i+'">'+val+'</option>');
                        });
                    },
                    error: function(result) {
                        alert('Erro ao buscar o calendário, Tente novamente!!!');
                    }
                }); 
            }
            



        })






        //RETORNO MULTIPLO
        setTimeout(function(){
            $('.inclusao_dinamica').each(function (key, val) {
                qtd_repete  = $(this).attr('data_repeticao_ajax');
                class_retorno  = $(this).attr('data_class_retorno');
                data_categoria  = $(this).attr('data_categoria');
                //CRIA QUANTIDADE NECESSITADA
                if(qtd_repete > 1){
                    i = 1;
                    while(i < qtd_repete){
                        $(this).click(); 
                        i++;
                    }
                }

                //ALIMENTA CAMPOS 
                if(qtd_repete > 0){
                // jQuery.each(perguntaArr, function(i, pergunta) {})
                // $('.pergunta_'+perguntaArr[i]+' input[type=radio]').each(function (key, val) {});
                // str.match(/ain/g);
                // jQuery.each(acaoArr, function(i, acao) {
                // $('.pergunta_'+perguntaArr[i]+' input[type=radio]').each(function (key, val) {
                // 
                // if(class_retorno == 'retorno_dados_7_medicamentos'){  
                //     console.log(id_retorno);
                //     console.log(linha_chave);
                // }   



                    linha_chave_tmp = '';
                    $('.'+class_retorno+' .retorno_campos input').each(function (key1, val1) {
                        linha_chave = $(this).parent().attr('data_count_retorno');
                        id_retorno = $(this).attr('id');
                        val_retorno = $(this).val();

                        if(linha_chave == 0){

                            // busca uma ou mais linhas da div row "retorno_row"
                            $('.'+class_retorno).parent().parent().find('.retorno_row #'+id_retorno).each(function (key2, val2) {
                                $(this).val(val_retorno);    
                            })


                        }else{
                            // busca somente uma linha "retorno_duplicado_row"
                            $('.'+class_retorno).parent().parent().find('.retorno_duplicado_row').each(function (key2, val2) {
                                if(key2 == (linha_chave-1)){
                                    $(this).find('#'+id_retorno).each(function (key3, val3) {
                                        $(this).val(val_retorno); 
                                    })
                                }
                                
                            });
                        }

                    })

                    
                    //EXCLUIR TODOS RETORNOS
                    $('.'+class_retorno).html(''); //teste deixar descomentado
                    

                }
            });
        },1000);



        


        $('.inclusao_dinamica').click(function(){
            id_categoria = $(this).attr('data_inclusao');
            conta_exclusao = parseInt($('#conta_exclusao').val());
            $('#conta_exclusao').val(conta_exclusao+1);

            //console.log(conta_exclusao);

            //duplica o content
            content_div = '';
            $( '.duplica_categoria_'+id_categoria ).each(function( index ) {
                content_div = content_div + $(this).html();
            });

            //determinar o número do id para repetição
            content_div = content_div.replace(/\MULTIPLO_NUM/g,conta_exclusao);
            //content_div = $('.duplica_categoria_'+id_categoria).html();

            //replica para o existente
            content_div_all = '<div class="row duplicado_'+conta_exclusao+' retorno_duplicado_row" style="border-top: 1px dotted #d3d3d3; margin-top:15px; padding-top:15px; ">'+content_div+'</div>';
            $(this).parent().parent().parent().append(content_div_all);

            //zera o conteudo novo
            $('.duplicado_'+conta_exclusao+" input").each(function( index ) {
                $(this).val('');
                $(this).attr('name',$(this).attr('name_multiplo'));
            });
            $('.duplicado_'+conta_exclusao+" select").each(function( index ) {
                $(this).val('');
                $(this).attr('name',$(this).attr('name_multiplo'));
            });
            $('.duplicado_'+conta_exclusao+" textarea").each(function( index ) {
                $(this).val('');
                 $(this).attr('name',$(this).attr('name_multiplo'));
            });
            $('.duplicado_'+conta_exclusao+" .btn_exclusao").show();
            $('.duplicado_'+conta_exclusao+" .btn_exclusao").attr('data_value',conta_exclusao);




            //document.getElementsByClassName('.duplicado_'+conta_exclusao).reset();
            //$('.duplicado_'+conta_exclusao).find('form')[0].reset();
            //$('.duplicado_'+conta_exclusao+" input").trigger('reset');
            //$('.duplica_categoria_'+id_categoria+" input[type=text], textarea").val("");

            //console.log(content_div_all);
            //alert(content_div);
            //duplica_categoria_
            //content_multiplo
        });

        
        //função de exclusao do botão da linha adicionada
        $(document).on('click', '.btn_exclusao', function() {
            msg = $(this).attr('ajaxmsg');
            if(confirm(msg)){
                id_exclusao = $(this).attr('data_value');
                if(id_exclusao != ''){
                    $('.duplicado_'+id_exclusao).remove();
                }    
            }
        });


        
        //EXIBIÇÃO DEPENDENCIAS
            //RADIO
//            $('.click_show_dep_radio').click(function(){
            $(document).on('click', '.click_show_dep_radio', function() {
                var acao = $(this).attr('dep_acao');
                var pergunta = $(this).attr('dep_pergunta');
                
                acaoArr = acao.split(',');
                perguntaArr = pergunta.split(',');

            
                //MULTIPLO
                jQuery.each(acaoArr, function(i, acao) {
                    if(acao == 1){//exibir
                        $('.pergunta_'+perguntaArr[i]).show();
                        
                        //GERA NUMERO DO MAPEAMENTO 
                        $('.pergunta_'+perguntaArr[i]+' .exibi_pergunta').addClass('pergunta_numero');
                        gera_numero_mapeamento();
                        
                    }else{//ocultar
                        $('.pergunta_'+perguntaArr[i]).hide();
                        $('.pergunta_'+perguntaArr[i]+' input[type=radio]').each(function (key, val) {
                            $(this).removeAttr('checked');
                        });
                        $('.pergunta_'+perguntaArr[i]+' input[type=checkbox]').each(function (key, val) {
                            $(this).removeAttr('checked');
                        });
                        $('.pergunta_'+perguntaArr[i]+' select').each(function (key, val) {
                            $(this).val('');
                        });
                        
                        //GERA NUMERO DO MAPEAMENTO 
                        $('.pergunta_'+perguntaArr[i]+' .exibi_pergunta').removeClass('pergunta_numero');
                        gera_numero_mapeamento();
                    }
                });
            });
            
            //CHECKBOX
//            $('.click_show_dep_checkbox').click(function(){
            $(document).on('click', '.click_show_dep_checkbox', function() {
                var pergunta = $(this).attr('dep_pergunta');
                var perguntaArr = pergunta.split(',');
                var valor = $(this).val();
                var acao = 0;
                var acao_multi = 0;
                
                if ($(this).is(':checked')) {
                    acao = 1;
                }else{
                    idPergunta =  $(this).attr('id').replace('checkbox_', '');
                    //BUSCA INFORMAÇÕES QUE ESTÃO COM CHEKCBOX ATIVO E QUE TENHA A PERGUNTA ATIVA
                    $('.pergunta_'+idPergunta+' #checkbox_'+idPergunta).each(function() {
                        if ($(this).is(':checked') && valor != $(this).val()) {
                            if($(this).attr("dep_pergunta")){
                                pergunta2 = $(this).attr('dep_pergunta');
                                pergunta2Arr = pergunta2.split(',');
                                if($.inArray(pergunta, pergunta2Arr) != -1){
                                    jQuery.each(pergunta2Arr, function(i2, pergunta2) {
                                        //VAI OCULTAR SOMENTE A PERGUNTA EXATA
                                        if(pergunta == pergunta2){
                                            acao_multi = 1;
                                        }
                                    }); 
                                }
                            }
                        }
                    });
                }
//                alert(acao);
                
                //MULTIPLO
                jQuery.each(perguntaArr, function(i, pergunta) {
                    if(acao == 1){//exibir
                        $('.pergunta_'+pergunta).show();
                        
                        //GERA NUMERO DO MAPEAMENTO 
                        $('.pergunta_'+pergunta+' .exibi_pergunta').addClass('pergunta_numero');
                        gera_numero_mapeamento();
                        
                        
                    }else{//ocultar
                        if(acao_multi == 0){
                            $('.pergunta_'+pergunta).hide();
                            
                            $('.pergunta_'+pergunta+' input[type=radio]').each(function (key, val) {
                                $(this).removeAttr('checked');
                            });
                            $('.pergunta_'+pergunta+' input[type=checkbox]').each(function (key, val) {
                                $(this).removeAttr('checked');
                            });
                            
                            //GERA NUMERO DO MAPEAMENTO 
                            $('.pergunta_'+pergunta+' .exibi_pergunta').removeClass('pergunta_numero');
                            gera_numero_mapeamento();
                            
                        }
                    }
                });
                
                
            });
           
           
            function mascara_peso() {
                var v = this.value, integer = v.split('.')[0];
                v = v.replace(/\D/g, "");
                v = v.replace(/^[0]+/, "");
                if (v.length <= 3 || !integer) {
                    if (v.length === 1) v = ' 00,' + v;
                    if (v.length === 2) v = ' 0' + v[0]+','+v[1];
                    if (v.length === 3) v = ' ' + v[0]+v[1]+','+v[2];
                    if (v.length === 4) v = v[0]+v[1]+v[2]+','+v[3];
                } else {
                    v = v[0]+v[1]+v[2]+','+v[3];
                }
//                console.log(v);
                if(v > '200'){
                    v = ' ' + v[0]+v[1]+','+v[2];
                }
                
                this.value = v;
            }
            
            $(document).on('keyup', '#HmRespostaPeso', mascara_peso);
            
            
            
            
            //ATIVA DEPENDÊNCIA JQUERY
            gera_dependencia_disabled();
            function gera_dependencia_disabled(){
                $( ".click_show_dep_radio" ).each(function( index ) {
                    if($(this).is(':disabled') && $(this).is(':checked')){
                        $(this).prop("disabled", false).prop("checked", false).click().prop("disabled", true);
                    }
                });
                $( ".click_show_dep_checkbox" ).each(function( index ) {
                    if($(this).is(':disabled') && $(this).is(':checked')){
                        $(this).prop("disabled", false).prop("checked", false).click().prop("disabled", true);
//                        $(this).prop("disabled", false);
//                        $(this).prop("checked", false);
//                        $(this).click();
//                        $(this).prop("disabled", true);
                    }
                });
            }
            
            
            //GERA NUMERO DO MAPEAMENTO
            gera_numero_mapeamento();
            function gera_numero_mapeamento(){
                numero = 1;
                $( ".pergunta_numero" ).each(function( index ) {
                    $(this).text(numero+' - ');
                    numero++;
                });
            }
            
            
            
            
            $('#HmProntuarioTipoAtendimento').change(function(){
                tipo = $(this).val();
                if(tipo != '' && tipo == 0){
                    $('.exibi_hora_atendimento').hide();
                    $('#HmProntuarioAtHoras').val('');
                    $('#HmProntuarioAtMinutos').val('');
                    $('.exibir_status_telefone').fadeIn('slow');
                }else{
                    $('.exibir_status_telefone').fadeOut('slow');
                    $('#HmProntuarioCodHmStatus').val('');
                    $('.exibi_hora_atendimento').fadeIn('slow');
                }
            });
            
	    
	    $('#HmProntuarioTipoAcolhimento').change(function(){
                tipo = $(this).val();
                if(tipo != '' && (tipo == 2 || tipo == 3 || tipo == 4)){
                    $('.exibi_cid').fadeIn('slow');
                }else{
                    $('.exibi_cid').fadeOut('slow');
                }
            });
	    
	    
            
            $('#HmProntuarioCodHmStatus').change(function(){
                status = $(this).val();
                if(status != ''){
                    $('.exibi_hora_atendimento').fadeIn('slow');
                }else{
                    $('.exibi_hora_atendimento').fadeOut('slow');
                    $('#HmProntuarioAtHoras').val('');
                    $('#HmProntuarioAtMinutos').val('');
                }
            });
            
            
            
            //DESCRIÇÃO VS
            $('#HmProntuarioRespostaAtendimento').change(function(){
                if($(this).val() == 1){
                    $('.exibi_descricao_vs').fadeIn('slow');
                }else{
                    $('.exibi_descricao_vs').fadeOut('slow');
                }
            });
            
            
          
            
            
            //RETIRAR CLICK DO RADIO BUTTON
            //EXAMPLE: <label class="radio descheck_radio " rel_id="radio_1" style="padding-top:0;">
            $('.descheck_radio').dblclick(function(){
                id = $(this).attr('rel_id');
                $( ".descheck_radio #"+id ).each(function( index ) {
                    $(this).prop('checked', false);
                });
            });
            
        // setTimeout(function(){
        //     //document.getElementsByClassName("cke_editable").height = '200px';
        //     $('.cke_editable').attr('style','height:200px;');
        //     console.log('oi');
        // },4000);
        // 
        
        $('.onTopStep').click(function(){
            scroll();
        })
        $('.onTop').click(function(){
            scroll();
        })




            
    });
    $(window).scroll(function() {

        if ($(this).scrollTop()< 550)
         {
            $('.onTop').fadeOut('slow');
         }
        else
         {
          $('.onTop').fadeIn('slow');
         }
     });

     function scroll() {
        //$(window).scrollTop(330);
        $("html, body").animate({ scrollTop: 330 }, 400);
    }
</script> 
