<style>

.display_none{display:none;}
</style>

<div class="col-sm-12 col-md-8 col-lg-9">

    <?php
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
                                        foreach ($perguntaArr['HmOpcao'] as $opcoesArr) {
                                            $selected = '';
                                            if(isset($session['HmResposta'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]) && 
                                               in_array($opcoesArr['cod_hm_opcao'],$session['HmResposta'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])){
                                                $selected = 'selected="selected"';
                                            }
                                            $htmlInput .= '<option value="' . $opcoesArr['cod_hm_opcao'] . '" '.$selected.'>' . $opcoesArr['descricao'] . '</option>';
                                        }
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
                        

                        $class_margin = '';
                        if(isset($row['Perguntas']) && count($row['Perguntas']) > 0){
                            $class_margin = 'margin-top:40px;';
                        }
                        $PERMITE_SUB = false;
                        if(isset($cod_hm_programa)){
                            if(array_key_exists($cod_hm_programa, $programa_sub_arr)){
                                $TABLE_SUB = $programa_sub_arr[$cod_hm_programa]['class'];
                                $NOME_SUB = $programa_sub_arr[$cod_hm_programa]['nome'];
                                $PERMITE_SUB = true;
                            }
                        }
                        $PERMITE_SUB
                    ?>
                    <div class="rows" style="margin-top:40px;">
                        <header style="border-bottom:1px dashed #ccc;">
                            <h3>Respostas <?php echo $NOME_SUB;?> </h3>
                        </header>
                        <div class="row" style="margin:20px;">
                            <form id="wizard-1" novalidate="novalidate">
                                <div id="bootstrap-wizard-1" class="col-sm-12">
                                    <div class="form-bootstrapWizard">
                                        <ul class="bootstrapWizard form-wizard">
                                            <li class="active" data-target="#step1">
                                                <a href="#tab1" data-toggle="tab"> <span class="step">1</span> <span class="title">Basic information</span> </a>
                                            </li>
                                            <li data-target="#step2">
                                                <a href="#tab2" data-toggle="tab"> <span class="step">2</span> <span class="title">Billing information</span> </a>
                                            </li>
                                            <li data-target="#step3">
                                                <a href="#tab3" data-toggle="tab"> <span class="step">3</span> <span class="title">Domain Setup</span> </a>
                                            </li>
                                            <li data-target="#step4">
                                                <a href="#tab4" data-toggle="tab"> <span class="step">4</span> <span class="title">Save Form</span> </a>
                                            </li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="tab-content" style="margin-top:20px;">
                                        <div class="tab-pane active" id="tab1">
                                            <br>
                                            <h5><strong>Step 1 </strong> - Basic Information</h5>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-envelope fa-lg fa-fw"></i></span>
                                                            <input class="form-control input-lg" placeholder="email@address.com" type="text" name="email" id="email">

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
                                                            <input class="form-control input-lg" placeholder="First Name" type="text" name="fname" id="fname">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
                                                            <input class="form-control input-lg" placeholder="Last Name" type="text" name="lname" id="lname">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="tab2">
                                            <br>
                                            <h5><strong>Step 2</strong> - Billing Information</h5>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-flag fa-lg fa-fw"></i></span>
                                                            <select name="country" class="form-control input-lg">
                                                                <option value="" selected="selected">Select Country</option>
                                                                <option value="United States">United States</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-map-marker fa-lg fa-fw"></i></span>
                                                            <select class="form-control input-lg" name="city">
                                                                <option value="" selected="selected">Select City</option>
                                                                <option>Amsterdam</option>
                                                                <option>Atlanta</option>
                                                                <option>Baltimore</option>
                                                                <option>Boston</option>
                                                                <option>Buenos Aires</option>
                                                                <option>Calgary</option>
                                                                <option>Chicago</option>
                                                                <option>Denver</option>
                                                                <option>Dubai</option>
                                                                <option>Frankfurt</option>
                                                                <option>Hong Kong</option>
                                                                <option>Honolulu</option>
                                                                <option>Houston</option>
                                                                <option>Kuala Lumpur</option>
                                                                <option>London</option>
                                                                <option>Los Angeles</option>
                                                                <option>Melbourne</option>
                                                                <option>Mexico City</option>
                                                                <option>Miami</option>
                                                                <option>Minneapolis</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-envelope-o fa-lg fa-fw"></i></span>
                                                            <input class="form-control input-lg" placeholder="Postal Code" type="text" name="postal" id="postal">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-phone fa-lg fa-fw"></i></span>
                                                            <input class="form-control input-lg" data-mask="+99 (999) 999-9999" data-mask-placeholder= "X" placeholder="+1" type="text" name="wphone" id="wphone">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-mobile fa-lg fa-fw"></i></span>
                                                            <input class="form-control input-lg" data-mask="+99 (999) 999-9999" data-mask-placeholder= "X" placeholder="+01" type="text" name="hphone" id="hphone">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab3">
                                            <br>
                                            <h5><strong>Step 3</strong> - Domain Setup</h5>
                                            <div class="alert alert-info fade in">
                                                <button class="close" data-dismiss="alert">
                                                    ×
                                                </button>
                                                <i class="fa-fw fa fa-info"></i>
                                                <strong>Info!</strong> Place an info message box if you wish.
                                            </div>
                                            <div class="form-group">
                                                <label>This is a label</label>
                                                <input class="form-control input-lg" placeholder="Another input box here..." type="text" name="etc" id="etc">
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab4">
                                            <br>
                                            <h5><strong>Step 4</strong> - Save Form</h5>
                                            <br>
                                            <h1 class="text-center text-success"><strong><i class="fa fa-check fa-lg"></i> Complete</strong></h1>
                                            <h4 class="text-center">Click next to finish</h4>
                                            <br>
                                            <br>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    
                    
                    
                    
                    <div class="row" style="<?php echo $class_margin;?>">
                        <h5>Informações do Atendimento</h5>
                    </div>
                    
                     <?php  if($cod_hm_programa == 2): ?>
                        <div class="row" style="margin-bottom:20px; ">
                            <section class="col col-4">
                                <label class="label">Tipo do Acolhimento <?php echo $obrigatorio; ?></label>
                                <label class="select">
                                    <?php echo $this->Form->input('tipo_acolhimento', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Acolhimento', 'class' => 'input_login', 'options' => $tipo_acolhimento_arr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                            </section>
                        </div>
                    <?php endif; ?>
                
                    
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
        
        <?php echo $this->Form->end();?>


    <!-- end widget div -->



</div>
<script>
    $(document).ready(function() {
        CKEDITOR.replace( 'data[HmProntuario][texto]', {<?php echo str_replace('280px','180px',$config_ckeditor);?>});
        CKEDITOR.replace( 'data[HmProntuario][texto_atendimento]', {<?php echo str_replace('280px','180px',$config_ckeditor);?>});
        
        
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
            
    });
</script> 