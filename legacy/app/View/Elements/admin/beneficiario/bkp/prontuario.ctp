<style type="text/css">
    .smart-timeline-icon {left: 85px !important;}
    .smart-timeline-time {width: 78px !important;}
    .smart-timeline-list:after{left: 100px !important;}
    .smart-timeline-list li {min-height: 65px }
    .scroll {
        overflow-x: hidden;
        overflow-y: scroll;
        height: 700px;
        white-space:unset;
    }

</style>
<div class="col-sm-12 col-md-4 col-lg-3">

    <div class="well well-light well-sm no-margin no-padding">
        <div class="row">
            <?php  if ($this->params['action'] == 'programa') { ?>
                <div rel="" style=" font-size:13px; color: white; right: 4px;z-index: 10000; position: absolute; margin-top:2px;">
                    <i class="fa fa-minus-circle btn_oculta_info" style="cursor:pointer;"></i>
                    <i class="fa fa-plus-circle btn_add_info"  style="cursor:pointer; color:green; display:none;"></i>
                </div>
            <?php } ?>
            <div class="col-sm-12 oculta_info">
                <div id="myCarousel" class="carousel fade profile-carousel">
                    <div class="air air-top-left padding-10">
                        <h4 class="txt-color-white font-md">
                            <?php
                            if (isset($row['DadoPessoal']['data_nascimento']) && $row['DadoPessoal']['data_nascimento'] != '') {
                                echo $this->Funcoes->dateToView($row['DadoPessoal']['data_nascimento']);
                                echo '<span style="font-size:11px !important;"> (' . $this->Funcoes->idade($row['DadoPessoal']['data_nascimento']) . ' anos) </span>';
                            }
                            ?>
                        </h4>
                    </div>
                    <div class="carousel-inner">
                        <div class="item active">
                            <?php
                            $img = 'vs/profile/s6.png';
                            echo $this->Html->image($img, array("alt" => "",
                                "title" => 'banner',
                                "class" => "",
                                "style" => "height:100px; width:100%;"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 oculta_info" style="padding-left:25px; padding-right:20px;  ">
                <div class="row">
                    <div class="col-sm-3 profile-pic" style="padding-left:0;">
                        <?php
                        $img = $row['avatar'];

                        if ($img == '') {
                            $img = 'vs/profile/avatar/masc.png';
                            if (isset($row['DadoPessoal']['sexo']) && $row['DadoPessoal']['sexo'] == 'F') {
                                $img = 'vs/profile/avatar/fem.png';
                            }
                            $style = '';
                        } else {
                            $cor = 'background-color: #F2F5FA;';
                            if (!preg_match('/avatar.jpg/', $img)) {
                                $cor = 'border: 5px solid #e8e8e8; padding: 5px; background-color: white;';
                            }
                            $style = ' border-radius: 35px;' . $cor;
                        }

                        echo $this->Html->image($img, array("alt" => "",
                            "title" => 'Usu�rio',
                            "class" => "",
                            "style" => "width:70px;height:70px;" . $style));
                        ?>

                        <!--    <div class="padding-10">
                                    <h4 class="font-md"><strong>1,543</strong>
                                        <br>
                                        <small>Followers</small></h4>
                                    <br>
                                    <h4 class="font-md"><strong>419</strong>
                                        <br>
                                        <small>Connections</small></h4>
                                </div>-->
                    </div>

                    <div class="col-sm-9" style="margin-top:10px;">
                        <h3>
                            <?php
                            $plural = (count($row['DadoPessoal']['EmpresaBeneficiario']) > 1) ? 's' : '';
                            $nomeArr = explode(' ', $row['DadoPessoal']['nome']);
                            echo $nomeArr[0] . ' ';
                            unset($nomeArr[0]);
                            echo '<span style="font-size:13px;">' . implode(' ', $nomeArr) . '</span><br>';
                            ?>
                        </h3>
                    </div>
                    <div class="col-sm-12" style="margin-top:10px; padding-bottom: 20px;">

                        <?php
                        if ($row['DadoPessoal']['cpf'] != '') {
                            echo ' <div style="font-size:10px; margin-top:5px;"><b>CPF:</b>: ' . $this->Funcoes->formata_cpf($row['DadoPessoal']['cpf']) . '</div>';
                        }
                        if (trim($row['DadoPessoal']['nome_mae']) != '') {
                            echo ' <div style="font-size:10px; margin-top:5px;"><b>M�E</b>: ' . $row['DadoPessoal']['nome_mae'] . '</div>';
                        }
                        ?>
                        <ul class="list-unstyled">
                            <?php
                            #TELEFONE
                            if (isset($row['DadoPessoal']['DadoTelefone']) && count($row['DadoPessoal']['DadoTelefone']) > 0) {
                                foreach ($row['DadoPessoal']['DadoTelefone'] as $telArr) {
                                    echo '<li style="margin-bottom:2px;">';
                                    echo '<p class="text-muted"> <i class="fa fa-phone"></i> &nbsp;';
                                    echo '<span class="txt-color-darken">';
                                    $telefone = $this->Funcoes->masc_tel($telArr['Telefone']['ddd'] . $telArr['Telefone']['numero']);
                                    echo $telefone;
                                    if ($this->params['action'] != 'programa' && $_SESSION['cod_conta'] == 44) {
                                        echo '  <a href="' . Router::url('/hm_prontuario/delete_telefone/' . $telArr['Telefone']['cod_telefone']) . '" title="Excluir Telefone ' . $telefone . '" class="ajaxMsg" ajaxmsg="Tem certeza que deseja Excluir o telefone ' . $telefone . ' ?">';
                                        echo $this->Html->image('vs/trash.png');
                                        echo '  </a>';
                                    }
                                    echo '</span>';
                                    echo '</p>';
                                    echo '</li>';
                                }
                            }

                            #EMAIL
                            if (isset($row['DadoPessoal']['Usuario']) && count($row['DadoPessoal']['Usuario']) > 0 && $row['DadoPessoal']['Usuario']['email'] != '') {
                                echo '<li>';
                                echo '<p class="text-muted"><i class="fa fa-envelope"></i>&nbsp;&nbsp;';
                                echo '  <a href="mailto:' . $row['DadoPessoal']['Usuario']['email'] . '">';
                                echo $row['DadoPessoal']['Usuario']['email'];
                                echo '  </a>';
                                echo '</p>';
                                echo '</li>';
                            }
                            ?>
                        </ul>

                        <?php

                        #EMPRESAS
                        echo '<br><h3><small> <b>Empresa' . $plural . '</b>:</h3></small>';
                        echo '<span><ul>';
                        if (count($row['DadoPessoal']['EmpresaBeneficiario']) > 0) {
                            foreach ($row['DadoPessoal']['EmpresaBeneficiario'] as $eb) {
                                if (isset($eb['Empresa']) && count($eb['Empresa']) > 0) {
                                    echo '<li style="font-size: 10px;">';
                                    $elegibilidade = '';
                                    if ($eb['elegibilidade'] == 'T') {
                                        $elegibilidade = 'Titular';
                                    } else if ($eb['elegibilidade'] == 'D') {
                                        $elegibilidade = 'Dependente';
                                    } else if ($eb['elegibilidade'] == 'A') {
                                        $elegibilidade = 'Agregado';
                                    }
                                    echo '<strong>Elegibilidade:</strong> ' . strtoupper($elegibilidade) . '<br>';
                                    echo $eb['Empresa']['nome'];
                                    echo '</li>';
                                }
                            }
                        }
                        echo '</ul></span>';


                        #PLANOS
                        echo '<br><h3><small> <b>Planos</b>:</h3></small>';
                        echo '<span><ul>';
                        if (count($row['DadoPessoal']['DadoPlano']) > 0) {
                            $totalPlanos = 0;
                            foreach ($row['DadoPessoal']['DadoPlano'] as $pl) {
                                if (isset($pl['Plano']) && $pl['cod_dado_plano_situacao'] == 1) {
                                    echo '<li style="font-size: 10px;">';
                                    $planosHtml = '';
                                    if (isset($produtoArr[$pl['Beneficio']['cod_produto']])) {
                                        $produto = str_replace('PLANOS DE ', '', $produtoArr[$pl['Beneficio']['cod_produto']]);
                                        $produto = str_replace('PLANOS ', '', $produto);
                                        $produto = str_replace('ODONTOL�GICOS', 'ODONTOL�GICO', $produto);
                                        $planosHtml .= '<strong> ' . $produto . '</strong><br>';
                                    }
                                    if (isset($operadoraArr[$pl['Beneficio']['cod_operadora']])) {
                                        $planosHtml .= '<strong> ' . $operadoraArr[$pl['Beneficio']['cod_operadora']] . '</strong><br>';
                                    }

                                    echo $planosHtml;
                                    echo '<strong>Plano:</strong> ' . $pl['Plano']['nome'] . '<br>';
                                    echo '<strong>Carteira:</strong> ' . $pl['carteira'] . '<br><br>';

                                    echo '</li>';
                                    $totalPlanos++;
                                }
                            }
                            if ($totalPlanos == 0) {
                                echo 'Nenhum Plano Ativo';
                            }
                        }
                        echo '</ul></span>';
			
			
			#GDC - EXIBINDO STATUS DE CRONICIDADES
			if(isset($row['infoGDC']) && count($row['infoGDC']) > 0){
			    echo '<br><h3><small> <b>Gest�o de Cr�nico</b>:</h3></small>';
			    echo '<span><ul style="list-style-type:none;">';
			    foreach($row['infoGDC'] as $infoGDC){
    				echo '<li style="font-size: 10px; margin:3px 3px 3px 0 ;">';
    				if(isset($infoGDC['pergunta'])){
				    $desc = (($infoGDC['descricao'] != '')? $infoGDC['descricao']: 'N�o Respondido.') ;
				    if(preg_match('/Recusou/',$desc)){
					$desc = '<span style="color:white; background-color:red; padding: 1px 2px;"> '.$desc.' </span>';
				    }
    				    echo "<strong>{$infoGDC['pergunta']}:</strong> {$desc}";
    				}else{
    				    if(count($infoGDC) > 0){
					$ki = 0;
					$err = 0;
					foreach($infoGDC as $kInfoGDCSub => $infoGDCSub){
					    if(count($infoGDC) == 1 && $infoGDCSub['descricao'] == ''){
						echo "<strong>{$infoGDC[$kInfoGDCSub]['pergunta']}:</strong> Nenhum Selecionado. ";
						$err = 1;
						break;
					    }
					    if($ki == 0){
						echo "<strong>{$infoGDC[$kInfoGDCSub]['pergunta']}:</strong> ";
						echo '<span><ul>';
					    }
					    if($infoGDCSub['descricao'] != ''){
						echo '<li style="font-size: 10px;"> '.$infoGDCSub['descricao'].' </li>';
					    }
					    $ki++;
					}
					if($err == 0){
					    echo '</ul></span>';
					}
    				    }
    				}
    				echo '</li>';
			    }
			    echo '</ul></span>';
			}else{
			    echo '<br><h3><small> <b>Gest�o de Cr�nico</b>:</h3></small>';
			    echo '<span><ul style="list-style-type:none;">';
			    echo 'Nenhum Registro Gerado';
			    echo '</ul></span>';
			}
			
			
                        if ($this->params['action'] != 'programa') {
                            echo '<br><h3><small> <b>A��es</b>:</h3></small>';
                            if (isset($row['DadoPessoal']['Usuario']) && count($row['DadoPessoal']['Usuario']) > 0 && $row['DadoPessoal']['Usuario']['email'] != '') {
                                echo '<a href="mailto:' . $row['DadoPessoal']['Usuario']['email'] . '" class="btn btn-default btn-xs" style="margin: 5px;">
                                        <i class="fa fa-envelope-o"></i> Enviar E-mail
                                      </a>';
                            }
                            echo ' <br> <a href="javascript:void(0);" class="btn btn-primary btn-xs abrir_cria_telefone" style="margin: 5px;">
                                        <i class="fa fa-phone"></i> Incluir Telefone
                                    </a>';
                            echo ' <br> <a href="' . Router::url('/hm_agendamento/add/' . $row['HmProntuario']['cod_hm_prontuario'], true) . '" class="btn btn-success btn-xs abrir_cria_programa " style="margin: 5px;">
                                        <i class="fa fa-plus-circle"></i> Abrir Novo Programa
                                    </a>';

                            if ($_SESSION['cod_usuario'] == 93686) {
                                echo '<hr><h3><small> <b>A��es Relat�rio M�dico</b>:</h3></small>';
                                echo ' <a href="' . Router::url('/hm_prontuario/relatorio_medico/' . $row['DadoPessoal']['cod_dado_pessoal'], true) . '" target="_blank" class="btn btn-default btn-xs  " style="margin: 5px;">
                                            <i class="fa fa-stethoscope"></i> Relat�rio M�dico
                                        </a>';
                                echo '<br><a href="' . Router::url('/hm_prontuario/relatorio_medico_download/' . $row['DadoPessoal']['cod_dado_pessoal'], true) . '" target="_blank" class="btn btn-default btn-xs gera_link_relatorio_medico" style="margin: 5px;">
                                            <i class="fa fa-link"></i> Relat�rio M�dico PDF
                                        </a>';
                            }
                        }


//                        echo ' <br> <a href="'.Router::url('/hm_agendamento/index/'.$row['HmProntuario']['cod_hm_prontuario'].'/3',true).'" class="btn btn-success btn-xs abrir_cria_programa " style="margin: 5px;">
//                                    <i class="fa fa-list"></i> Programas Agendados
//                                </a>';
                        ?>
                    </div>
                </div>
            </div>


            <!-- CRIA TELEFONE-->
            <div class="col-sm-12 cria_telefone" style="padding:0 20px 20px 20px; display:none;">
                <div class="row">
                    <div class="col-sm-12 aviso_telefone" style="margin-top:10px; padding:10px 30px;" >
                        <h5 style="border-bottom: 1px solid #CCC; margin-left: -15px; font-weight: bold;">Atualizar Telefone</h5>
                        <?php
                        echo $this->Form->create(
                                'HmProntuario', array(
                            'type' => 'file',
                            'id' => 'prontuario-telefone-form',
                            'url' => array(
                                'controller' => 'hm_prontuario',
                                'action' => 'cria_telefone',
                            ),
                            'class' => 'smart-form client-form '
                                )
                        );
                        ?>

                        <div class="row">
                            <section class="col-sm-12">
                                <label class="label">Telefone Celular</label>
                                <label class="input"> <i class="icon-append fa fa-mobile-phone"></i>
                                    <?php echo $this->Form->hidden('cod_dado_pessoal', array('value' => $row['HmProntuario']['cod_dado_pessoal'])); ?>
                                    <?php echo $this->Form->input('telefone_celular', array('label' => false, 'div' => false, 'placeholder' => 'Telefone Celular', 'class' => 'input_login tel_cel_mask', 'maxlength' => '15')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone Celular</b>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col-sm-8">
                                <label class="label">Telefone</label>
                                <label class="input"> <i class="icon-append fa fa-phone"></i>
                                    <?php echo $this->Form->input('telefone_outros', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone</b>
                                </label>
                            </section>
                            <section class="col-sm-4">
                                <label class="label">Ramal</label>
                                <label class="input"> <i class="icon-append fa fa-share"></i>
                                    <?php echo $this->Form->input('ramal_outros', array('label' => false, 'div' => false, 'placeholder' => 'Ramal', 'class' => 'input_login ', 'maxlength' => '10')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Ramal</b>
                                </label>
                            </section>
                        </div>
                        <div class="row" style="text-align: right; ">
                            <button type="submit" class="btn btn-primary btn-sm criar_telefone_submit">
                                Criar
                            </button>
                        </div>

                        <?php echo $this->Form->end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    #SEPARA AS TELAS
    $PERMITE_SUB = false;
    if (isset($caseArr['HmHistorico']['cod_hm_programa'])) {
        if (array_key_exists($caseArr['HmHistorico']['cod_hm_programa'], $programa_sub_arr)) {
            $TABLE_SUB = $programa_sub_arr[$caseArr['HmHistorico']['cod_hm_programa']]['class'];
            $NOME_SUB = $programa_sub_arr[$caseArr['HmHistorico']['cod_hm_programa']]['nome'];
            $PERMITE_SUB = true;
        }
    }

    if ($this->params['action'] == 'view' || $PERMITE_SUB) {
        echo '</div>';
        echo '<div class="col-sm-12 col-md-8 col-lg-9">';
    }


    #REGRA DE CORES E EXIBI��O DE CONCLUS�O DO CASE
    $bkcolor = $bordercolor = $space_ = $descritivo_fim = '';

    if ($PERMITE_SUB) {
        if (count($caseArr) > 0) {
            #$bkcolor = 'background-color:#f3f3d8;';
            $bkcolor = 'background-image: linear-gradient(to left,  #fff, #f3f3d8,#f3f3d8)';
            if ($caseArr['HmHistorico']['cod_hm_programa'] == 7) {
                #$bkcolor = 'background-color:#d3d3d3;';
                $bkcolor = 'background-image: linear-gradient(to left,  #fff, #ececec)';
                $bordercolor = 'border: 1px solid #ccc !important;';
            } else {
                if ($caseArr[$TABLE_SUB]['cod_hm_status'] != '4' && $caseArr[$TABLE_SUB]['data_conclusao'] != '') {
                    $space_ = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    $descritivo_fim = '<b>Finalizado:</b> ' . $this->Funcoes->dateToView($caseArr[$TABLE_SUB]['data_conclusao'], true) . ' por ' . $caseArr['UsuarioConclusao']['usuario'];
                    #$bkcolor = 'background-color:#d8f3dc;';
                    $bkcolor = 'background-image: linear-gradient(to left,  #fff, #d8f3dc)';
                    $bordercolor = 'border: 1px solid #2bad45 !important;';
                }
            }
        }
    }
    ?>
    <div class="well well-sm" style="<?php echo $bordercolor; ?>">

        <?php
        if ($PERMITE_SUB) {
            $descritivo = '';
            #$cod_hm_case_management = '';
            $cod_key = 'cod_hm_' . $this->params['action'];

            if (count($caseArr) > 0) {
                $descritivo .= '<span class="note"> ';
                $descritivo .= $space_ . '<b>Criado:</b> ' . $this->Funcoes->dateToView($caseArr[$TABLE_SUB]['data_cadastro'], true) . ' por ' . $caseArr['Usuario']['usuario'] . '<br>';
                $descritivo .= $descritivo_fim;
                $descritivo .= '</span>';
                $cod_key = ' #' . $caseArr[$TABLE_SUB][$cod_key];
            }



            echo '<h2 style=" padding: 10px 0 10px 14px; ' . $bkcolor . '">';
            if ($TABLE_SUB == 'HmApi') {
                echo '<div style="border-bottom: 1px dotted #ccc; width:610px;">Atividades de ' . $NOME_SUB . ' </div>';
            } else {
                echo '<div style="border-bottom: 1px dotted #ccc; width:370px;">Atividades ' . $NOME_SUB . ' </div>';
            }


            echo $descritivo;
            echo '</h2>';
        } else {
            echo '<h2 style="border-bottom: 1px dotted #ccc;">Hist�rico de Atividades</h2>';
        }
        ?>


        <?php
        $em_execucao = $agendado = 0;


#if($this->params['action'] == 'case_management'){
        if (isset($caseArr['HmHistorico']['cod_hm_programa']) && array_key_exists($caseArr['HmHistorico']['cod_hm_programa'], $programa_sub_arr) && $caseArr['HmHistorico']['cod_hm_programa'] != 7) {
	    #CONTAR EM ABERTO PARA BLOQUEAR A FINALIZA��O
            if (isset($row['HmHistorico']) && count($row['HmHistorico']) > 0) {
                if (!isset($caseArr['UsuarioConclusao']['usuario']) || $caseArr['UsuarioConclusao']['usuario'] == '') {
                    foreach ($row['HmHistorico'] as $hist) {
                        if ($hist['cod_hm_status'] == '4') { #em execu��o
                            $em_execucao++;
                        }
                        if ($hist['cod_hm_status'] == '3') { #agendado
                            $agendado++;
                        }
                    }

                    if ($em_execucao > 0 || $agendado > 0) {
                        $btn_finalizar = '<a  href="javascript:void(0);" class="btn btn-default btn-xs aviso_permissao" style="margin: 0 5px 5px 5px; color: gray;">'
                                . '<i class="fa fa-remove"></i> Finalizar ' . $programa_sub_arr[$caseArr['HmHistorico']['cod_hm_programa']]['nome'] . '</a>';
                    } else {
                        $btn_finalizar = '<a  href="' . Router::url('/hm_prontuario/fechar_programa_sub/' . $caseArr['HmHistorico']['cod_hm_programa'] . '/' . $caseArr['HmHistorico'][$programa_sub_arr[$caseArr['HmHistorico']['cod_hm_programa']]['cod']], true) . '" class="ajaxMsg btn btn-danger btn-xs " style="margin: 5px;" ajaxmsg="Tem certeza que deseja FINALIZAR este ' . $programa_sub_arr[$caseArr['HmHistorico']['cod_hm_programa']]['nome'] . '?">'
                                . '<i class="fa fa-remove"></i> Finalizar ' . $programa_sub_arr[$caseArr['HmHistorico']['cod_hm_programa']]['nome'] . '</a>';
                    }
                    echo '  <div style="text-align:right; margin-top: -25px;">' . $btn_finalizar . ' </div>';
                }
            }
        }


        $imagens = array();
        $rosto = $this->Html->image($img, array("alt" => "",
            "title" => 'Usu�rio',
            "class" => "",
            "style" => "width:32px;height:32px;"));

        $iconsArr['face'] = $rosto;
        $iconsArr['file'] = '<i class="fa fa-file-text"></i>';
        $iconsArr['graph'] = '<i class="fa fa-bar-chart-o"></i>'; #txt-color-greenDark
        $iconsArr['user'] = '<i class="fa fa-user"></i>';
        $iconsArr['read'] = '<i class="fa fa-pencil"></i>';
	
        $class_scroll = '';
        if ($this->params['action'] == 'programa') {
            $class_scroll = 'scroll';
        }
        ?>
        <!-- INICIO - HISTORICO DE ATIVIDADES Timeline Content -->
	
        <div class="smart-timeline <?php echo $class_scroll; ?> " >
            <ul class="smart-timeline-list">
                <?php
                if (isset($row['HmHistorico']) && count($row['HmHistorico']) > 0) {
                    $temp_case = array();
                    foreach ($row['HmHistorico'] as $k => $hmhistorico) {

                        #BEGIN - FAZ EFEITO DE MOVER PARA O LADO OS ATENDIMENTOS ENCADEADAS
                        $class_identa = '';
                        $identa_chave = '';
                        if (in_array($hmhistorico['cod_hm_status'], array($hm_status_sem_contato, $hm_status_deixou_recado))) {
                            if ($hmhistorico['cod_atendimento'] != '') {
                                $class_identa = $hmhistorico['cod_atendimento'] . '_identa_atividades';
                            }
                        } else if (in_array($hmhistorico['cod_hm_status'], array($hm_status_concluido))) {
                            $class_identa = 'identa_atividades';
                            $identa_chave = $hmhistorico['cod_atendimento'] . '_identa_atividades';
                        }
                        #END - FAZ EFEITO DE MOVER PARA O LADO OS ATENDIMENTOS ENCADEADAS
                        #FAZ A VERIFICA��O PARA DEIXAR SOMENTE UM CASE MANAGEMENT
//                            if($hmhistorico['cod_hm_programa'] == 3){
//                                krumo($hmhistorico['cod_hm_historico']);
//                                if(!is_null($hmhistorico['cod_hm_case_management']) &&
//                                   isset($caseArr[$hmhistorico['cod_hm_case_management']]) &&
//                                   $this->params['action'] != 'case_management'){
//                                    krumo(1);
//                                    if($hmhistorico['cod_hm_historico'] != $caseArr[$hmhistorico['cod_hm_case_management']]['cod_hm_historico']){
//                                        continue;
//                                    }
//                                }
////                            }else{ 5088.9999
////                                if($this->params['action'] == 'case_management'){
////                                    continue;
////                                }
//                            }
//					    if($_SESSION['cod_usuario'] == '93686'):
//						echo ' - teste';
//						krumo($tipo_acolhimento_arr);
//					    endif;
                        ?>

                        <li style="border-top:1px solid #d3d3d3;" class="<?php echo $class_identa; ?>" identa_chave="<?php echo $hmhistorico['cod_atendimento']; ?>" identa_class="<?php echo $identa_chave; ?>" >
                            <div class="smart-timeline-icon"><?php echo $iconsArr['face']; ?></div>
                            <div class="smart-timeline-time">
                                <small>
                                    <?php echo ($hmhistorico['data_atualizacao'] != '')? $this->Funcoes->data_hora_recado($hmhistorico['data_atualizacao']) : $this->Funcoes->data_hora_recado($hmhistorico['data_cadastro']);  ?>
                                    <br>
                                    <span style="color:#d3d3d3;">(<?php echo $this->Funcoes->dateToView($hmhistorico['data_cadastro']); ?>)</span>
                                </small>
                            </div>
                            <div class="smart-timeline-content">
				
                                <?php
                                $margin = "";
                                if (isset($hmhistorico['HmPrograma']['nome'])) {
                                    $margin = "margin-top:10px;";
                                    ?>
                                    <p class="text-info">
                                        <strong>
                                            <?php
                                            echo 'Programa :: ';
                                            if ($hmhistorico['cod_hm_programa'] == 2 && $hmhistorico['tipo_acolhimento'] != '' && isset($tipo_acolhimento_arr[$hmhistorico['tipo_acolhimento']])) {
						                        echo 'Atendimento Especializado :: ';
                                                echo $tipo_acolhimento_arr[$hmhistorico['tipo_acolhimento']];
                                            } else {
                                                echo $hmhistorico['HmPrograma']['nome'];
                                            }
                                            ?>
                                        </strong>
					<?php
					    if ($hmhistorico['cod_atendimento'] != '') {
                                                $style_tmp = 'width:55px;height:48px; margin-top: -20px;';
                                                if (!in_array($this->params['action'], array('view', 'case_management'))) {
                                                    $style_tmp = 'width:60px;height:50px; ';
                                                }
                                                echo '<a href="' . ENDERECO . 'admin/atendimento/visualizar.php?acao=visualizar&cod_atendimento=' . $hmhistorico['cod_atendimento'] . '" '
                                                . 'target="_blank" class="ajaxMsg" style="margin: 5px;" '
                                                . 'ajaxmsg="O sistema ir� abrir uma nova aba para o atendimento vinculado, confirma?">';
                                                echo $this->Html->image('vs/hm_atendimento.png', array("alt" => "",
                                                    "title" => 'Atendimento do VS Vinculado (' . $hmhistorico['cod_atendimento'] . ')',
                                                    "class" => "",
                                                    "style" => $style_tmp));
                                                echo '</a>';
                                            }
					?>
					
                                    </p>
				    <p>
                                        <?php
					echo '<span class="text-info"><b>Status: </b></span>';

                                        #CASE MANAGEMENT
                                        $valida_programas = false;
                                        if (array_key_exists($hmhistorico['cod_hm_programa'], $programa_sub_arr)) {
                                            $TABLE_FOR_SUB = $programa_sub_arr[$hmhistorico['cod_hm_programa']]['class'];
                                            $COD_KEY_SUB = $programa_sub_arr[$hmhistorico['cod_hm_programa']]['cod'];
                                            $NOME_BTN_SUB = $programa_sub_arr[$hmhistorico['cod_hm_programa']]['nome'];
                                            $URL_SUB = str_replace('cod_hm_', '', $COD_KEY_SUB);
                                            if (!is_null($hmhistorico[$COD_KEY_SUB]) && isset($caseArr[$hmhistorico['cod_hm_programa']][$hmhistorico[$COD_KEY_SUB]])) {
                                                $valida_programas = true;
                                            }
                                        }

                                        #PROGRAMAS (GDC | API | CASE MANAGEMENT)
                                        if ($valida_programas) {
                                            $case = $caseArr[$hmhistorico['cod_hm_programa']][$hmhistorico[$COD_KEY_SUB]];

                                            echo $this->Html->image($case['status_icon'], array('style' => 'width:15px;'));
                                            echo ' <span class="note">';
                                            echo $case['status'];
                                            #concluido
                                            if ($case['cod_hm_status'] == 2) {
                                                $usuario_att = ($case['usuario_concluiu'] != '') ? ' <i> por ' . $case['usuario_concluiu'] . '</i>' : '';
                                                $data_concluido = ' (' . $this->Funcoes->data_hora_recado($case['data_conclusao']) . $usuario_att . ')';
                                                echo $data_concluido;
                                                echo '  <br><br><a href="' . Router::url('/hm_prontuario/' . $URL_SUB . '/' . $hmhistorico['cod_hm_prontuario'] . '/' . $hmhistorico[$COD_KEY_SUB] . '/' . $hmhistorico['cod_hm_programa'], true) . '" class="btn btn-success btn-xs " style="margin: 5px;" >
                                                                <i class="fa fa-suitcase"></i> Acessar ' . $NOME_BTN_SUB . '
                                                            </a>';
                                                #em andamento
                                            } else {
                                                $usuario_att = (isset($case['usuario'])) ? ' (<i> iniciado por ' . $case['usuario'] . '</i>)' : '';
                                                echo $usuario_att;
                                                echo ' <br><br> <a href="' . Router::url('/hm_prontuario/' . $URL_SUB . '/' . $hmhistorico['cod_hm_prontuario'] . '/' . $hmhistorico[$COD_KEY_SUB] . '/' . $hmhistorico['cod_hm_programa'], true) . '" class="btn btn-warning btn-xs " style="margin: 5px;" >
                                                                <i class="fa fa-suitcase"></i> Acessar ' . $NOME_BTN_SUB . ' 
                                                            </a>';
                                            }
                                            echo '</span>';
                                            #TODOS PROGRAMAS
                                        } else {
                                            echo $this->Html->image($hmhistorico['HmStatus']['icon'], array('style' => 'width:15px;'));
                                            echo ' <span class="note">';
                                            $data_concluido = '';
                                            #CONCLUIDO
                                            if (in_array($hmhistorico['cod_hm_status'], array($hm_status_cancelado, $hm_status_concluido, $hm_status_sem_contato, $hm_status_deixou_recado))) { #
                                                $usuario_att = (isset($hmhistorico['UsuarioAtualizacao']['usuario'])) ? ' <i> por ' . $hmhistorico['UsuarioAtualizacao']['usuario'] . '</i>' : '';
                                                if(isset($hmhistorico['tipo_atendimento']) && isset($tipoAtendimentoArr[$hmhistorico['tipo_atendimento']]) ){
						    $via = ($hmhistorico['tipo_atendimento'] != 1)? ' via ': '';
						    $mente = ($hmhistorico['tipo_atendimento'] == 1)? 'mente' :'' ;
						    $data_concluido = ' '.$via.$tipoAtendimentoArr[$hmhistorico['tipo_atendimento']].$mente.' ';
						    
						}
						$data_concluido .= ' (' . $this->Funcoes->data_hora_recado($hmhistorico['data_atualizacao']) . $usuario_att . ')';
                                            }

                                            echo $hmhistorico['HmStatus']['nome'] . $data_concluido;

                                            #EXIBI��O CONTATO (SEM CONTATO OU RECADO)
                                            if (in_array($hmhistorico['cod_hm_status'], array($hm_status_sem_contato, $hm_status_deixou_recado))) {
                                                $usuario_att = (isset($hmhistorico['UsuarioAtualizacao']['usuario'])) ? ' <i> por ' . $hmhistorico['UsuarioAtualizacao']['usuario'] . '</i>' : '';
                                                $data_concluido = ' (' . $this->Funcoes->data_hora_recado($hmhistorico['data_atualizacao']) . $usuario_att . ')';
                                                #echo $data_concluido;
                                            }

                                            if ($hmhistorico['cod_hm_status'] == $hm_status_aguardando_exec) { #AGUARDANDO EXECUCAO
                                                //                                                echo '<br><br>';
                                                echo '  <a href="' . Router::url('/hm_prontuario/programa/' . $hmhistorico['cod_hm_historico'], true) . '" class="ajaxMsg btn btn-success btn-xs abrir_cria_programa " style="margin: 5px;" ajaxmsg="Tem certeza que deseja Assumir o programa `' . $hmhistorico['HmPrograma']['nome'] . '� deste Benefici�rio?">
							    <i class="fa fa-user-md"></i> Iniciar Programa
							</a>';

                                                echo '  <a href="' . Router::url('/hm_prontuario/cancelar_programa/' . $hmhistorico['cod_hm_historico'], true) . '" class="ajaxMsg btn btn-danger btn-xs " style="margin: 5px;" ajaxmsg="Tem certeza que deseja CANCELAR o programa `' . $hmhistorico['HmPrograma']['nome'] . '� deste Benefici�rio?">
							    <i class="fa fa-remove"></i> Cancelar
							</a>';
                                            }
                                            if ($hmhistorico['cod_hm_status'] == $hm_status_em_execucao) { #EM EXECUCAO
                                                //                                                echo '<br>';
                                                $usuario_att = (isset($hmhistorico['UsuarioAtualizacao']['usuario'])) ? ' (<i> por ' . $hmhistorico['UsuarioAtualizacao']['usuario'] . '</i>)' : '';
                                                $cod_usuario_att = (isset($hmhistorico['UsuarioAtualizacao']['usuario'])) ? $hmhistorico['cod_usuario_atualizacao'] : $hmhistorico['cod_usuario'];
                                                echo $usuario_att;
                                                echo '  <a href="' . Router::url('/hm_prontuario/programa/' . $hmhistorico['cod_hm_historico'], true) . '" class="ajaxMsg btn btn-warning btn-xs abrir_cria_programa " style="margin: 5px;" ajaxmsg="Tem certeza que deseja Assumir o programa `' . $hmhistorico['HmPrograma']['nome'] . '� deste Benefici�rio?">
							    <i class="fa fa-user-md"></i> Continuar Programa
							</a>';
						if(($cod_usuario_att != '' && $_SESSION['cod_usuario'] == $cod_usuario_att) || $_SESSION['cod_usuario'] == '93686'){
						    echo '  <a href="' . Router::url('/hm_prontuario/cancelar_programa/' . $hmhistorico['cod_hm_historico'], true) . '" class="ajaxMsg btn btn-danger btn-xs " style="margin: 5px;" ajaxmsg="Tem certeza que deseja CANCELAR o programa `' . $hmhistorico['HmPrograma']['nome'] . '� deste Benefici�rio?">
								<i class="fa fa-remove"></i> Cancelar
							    </a>';
						}
                                            }
                                            
                                            echo '</span>';
                                        }
                                        ?>
                                    </p>
				    
				    
                                    <?php
				    #INCLUS�O DO CID
				    if(isset($hmhistorico['cid']) && $hmhistorico['cid'] != ''){
					echo '<p style="margin-top:5px;">';
                                        echo '<span class="bg-color-red txt-color-white" style="padding:0 2px ;"><b>CID:</b></span> ';
					echo '<span class="note txt-color-red" style="background-color:#f9f2f4; text-decoration:underline;">';
					echo $hmhistorico['cid'];
					echo '</span>';
					echo '</p>';
				    }
				    
				    #INCLUS�O DO TEMPO DE ATENDIMENTO AUTOM�TICO
                     if ($hmhistorico['tempo_trabalho'] != '' && $hmhistorico['tempo_trabalho'] > 0 && $valida_programas == FALSE) {
                        echo '<p style="margin-top:8px;">';
                        echo '<span class="text-info"><b>Tempo do Atendimento: </b></span>';
                        echo '<span class="note ">';
                        $m_plural = ($hmhistorico['tempo_trabalho'] > 1) ? 'minutos' : 'minuto';
                        echo $hmhistorico['tempo_trabalho'] . ' ' . $m_plural;
                        echo '</span>';
                        echo '</p>';
                    }
                    ?>


				<?php
                                }
                                #CASE MANAGEMENT - N�O EXIBE RESPOSTAS
                                $valida_programas = false;
                                if (array_key_exists($hmhistorico['cod_hm_programa'], $programa_sub_arr)) {
                                    $TABLE_FOR_SUB = $programa_sub_arr[$hmhistorico['cod_hm_programa']]['class'];
                                    $COD_KEY_SUB = $programa_sub_arr[$hmhistorico['cod_hm_programa']]['cod'];
                                    $NOME_BTN_SUB = $programa_sub_arr[$hmhistorico['cod_hm_programa']]['nome'];
                                    $URL_SUB = str_replace('cod_hm_', '', $COD_KEY_SUB);
                                    if (!is_null($hmhistorico[$COD_KEY_SUB]) && isset($caseArr[$hmhistorico['cod_hm_programa']][$hmhistorico[$COD_KEY_SUB]])) {
                                        $valida_programas = true;
                                    }
                                }


                                #if(is_null($hmhistorico['cod_hm_case_management']) || ($this->params['action'] == 'case_management' && !is_null($hmhistorico['cod_hm_case_management']))){
                                if ($valida_programas == false || ($this->params['action'] == $URL_SUB && !is_null($hmhistorico[$COD_KEY_SUB]))) {
				    ?>
                                    <div style="<?php echo $margin; ?>">
                                        <p>
                                            <?php
                                            //MAPEAMENTO
                                            if ($hmhistorico['cod_hm_programa'] == 1 && count($hmhistorico['Mapeamento']) > 0) {
                                                $percent_geral = isset($hmhistorico['Mapeamento']['QvPreenchido']['percentual_geral']) ? $hmhistorico['Mapeamento']['QvPreenchido']['percentual_geral'] : 0;
                                                $class_color_txt = 'txt-color-green';
                                                $percent_geral = ($percent_geral < 100) ? $percent_geral++ : $percent_geral = 100;
                                                if ($percent_geral <= 25) {
                                                    $class_color_txt = 'txt-color-red';
                                                } elseif ($percent_geral > 25 && $percent_geral <= 80) {
                                                    $class_color_txt = 'txt-color-orange';
                                                }


                                                echo '<div class="easy-pie-chart ' . $class_color_txt . ' easyPieChart" data-percent="' . $percent_geral . '" data-pie-size="60" style="font-size:10px; ">
                                                             <span class="percent percent-sign font-xs" rel="' . $percent_geral . '">' . $percent_geral . '</span>
                                                          </div>';

                                                if ($hmhistorico['Mapeamento']['QvPreenchido']['mapa_pessoal_enviado'] != '') {

                                                    $nomeDoArquivo = "mapeamento_{$hmhistorico['Mapeamento']['QvPreenchido']['cod_preenchido']}.pdf";
                                                    $endDoArquivoLink = ENDERECO . 'v4/files/uploads/pdf/mapeamento_individual/' . $nomeDoArquivo;
                                                    $endDoArquivoLinkValida = 'files/uploads/pdf/mapeamento_individual/' . $nomeDoArquivo;
                                                    if (file_exists($endDoArquivoLinkValida)) {
                                                        echo '&nbsp;&nbsp;&nbsp; <a href="' . $endDoArquivoLink . '" target="_blank">Mapa Pessoal</a>';
                                                    }
                                                }
                                                echo '<br>';
                                            }


                                            #RESPOSTAS DO PROGRAMA
					    if (count($hmhistorico['Resposta']) > 0 && (!array_key_exists($hmhistorico['cod_hm_programa'], $programa_sub_arr) || $hmhistorico['cod_hm_programa'] == 3)) {
                                                $respostasArr = $hmhistorico['Resposta'];
                                                echo '<h5 style="font-size:15px; border-bottom: 1px dotted #d3d3d3;"> Respostas do Programa </h5>';
                                                foreach ($hmhistorico['Resposta'] as $respostaArr) {
                                                    // echo '<u><strong>'.$respostaArr['HmPergunta']['pergunta'].'</strong></u><br>';
                                                    // echo '<strong>Resposta: </strong>';
                                                    echo '<section style="margin-bottom:5px;">';
                                                    echo '  <label class="Bold"><strong>' . $respostaArr['HmPergunta']['pergunta'] . ': </strong></label>';
                                                    echo '  <label>';
                                                    if (in_array($respostaArr['HmPergunta']['tipo'], array('text', 'textarea'))) {
                                                        echo $respostaArr['HmResposta']['descritivo'];
                                                    } elseif (in_array($respostaArr['HmPergunta']['tipo'], array('numero_inteiro', 'numero_decimal'))) {
                                                        echo $respostaArr['HmResposta']['inteiro'];
                                                    } elseif (in_array($respostaArr['HmPergunta']['tipo'], array('data'))) {
                                                        echo $this->Funcoes->dateToView($respostaArr['HmResposta']['data']);
                                                    } elseif (in_array($respostaArr['HmPergunta']['tipo'], array('radio', 'checkbox', 'combobox'))) {
                                                        if (isset($respostaArr['HmOpcao']['descricao'])) {
                                                            echo $respostaArr['HmOpcao']['descricao'];
                                                        }
                                                    }
                                                    echo '  </label>';
                                                    echo '</section>';
                                                }
                                            }
                                            ?>
                                        </p>
                                        <p>
                                            <?php
                                            if (trim($hmhistorico['texto']) != '' && !preg_match('/Prontu�rio Iniciado/', $hmhistorico['texto'])) {
                                                echo '<h5 style="font-size:15px; border-bottom: 1px dotted #d3d3d3; "> Descritivo do Programa </h5>';
                                                echo $hmhistorico['texto'];
                                            }

                                            #para prontuario iniciado
                                            if (trim($hmhistorico['texto']) != '' && preg_match('/Prontu�rio Iniciado/', $hmhistorico['texto'])) {
                                                $usuario_att = (isset($hmhistorico['Usuario']['usuario'])) ? ' <i> por ' . $hmhistorico['Usuario']['usuario'] . '</i>' : '';
                                                echo $hmhistorico['texto'];
                                                echo $usuario_att;
                                            }

                                            if (trim($hmhistorico['texto_atendimento']) != '') {
                                                echo '<h5 style="font-size:15px; border-bottom: 1px dotted #d3d3d3; "> Descritivo para o Atendimento </h5>';
                                                echo $hmhistorico['texto_atendimento'];
                                            }
                                            ?>
                                        </p>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                        <?php
                    }
                } else {
                    ?>
                    <li>
                        <div class="smart-timeline-icon"><?php echo $iconsArr['face']; ?></div>
                        <div class="smart-timeline-time">
                            <small><?php echo $this->Funcoes->data_hora_recado(date('Y-m-d H:i:s')); ?></small></div>
                        <div class="smart-timeline-content">
                            <p><a href="javascript:void(0);"><strong>Nenhum Registro</strong></a></p>
                            <p>At� o momento nenhuma atividade foi encontrada!</p>
                        </div>
                    </li>

                <?php } ?>

            </ul>
        </div>
        <?php
	
	#CRIA��O GR�FICA DA SINISTRALIDADE PARA CASE MANAGEMENT
        if ($this->params['action'] == 'case_management' && count($sinistroArr) > 0) {
            ?>
            <div class="row">
                <article class="col-xs-11 col-sm-11 col-md-11 col-lg-11" style="margin-left:4%;" >
                    <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false" data-widget-fullscreenbutton="true">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                            <h2>Utiliza��o <span class="note">(De <?php echo $sinistroArr['data_inicio']; ?> a  <?php echo $sinistroArr['data_fim']; ?> | Total: <?php echo $this->Funcoes->monetary($sinistroArr['total']); ?>)</span></h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox"></div>
                            <div class="widget-body no-padding">
                                <div id="saleschart" class="chart"></div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <?php
            #RESPOSTAS DO PROGRAMA
        } 
	else if (in_array($this->params['action'], array('gdc', 'api')) && isset($row['Grupos']) && count($row['Grupos']) > 0 ) { #COMENTAR �LTIMO
//	    krumo($row);
//	    exit;
	    
	    $disabled_test = 'disabled="disabled"';
	    
            $count_grupo = count($row['Grupos']);
            $width_ = 'width:25%;';
            $height_ = 'height:70px;';
            if ($count_grupo == 5) {
                $width_ = 'width:20%;';
                $height_ = 'height:80px;';
            } elseif ($count_grupo == 6) {
                $width_ = 'width:16.3%;';
                $height_ = 'height:83px;';
            } elseif ($count_grupo > 6) {
                $width_ = 'width:14.2%;';
                $height_ = 'height:83px;';
            }
//
            echo '<div class="rows" style="margin-top:40px;">';
            echo '<header style="border-bottom:1px solid #d3d3d3;">';
            echo "<h3>Respostas {$NOME_SUB}</h3>";
            echo '</header>';
            echo '  <div class="row" style="margin:10px;">';
            echo '      <div id="bootstrap-wizard-1" >';

            #BEGIN - CABE�ALHO DOS GRUPOS
            echo '          <div class="form-bootstrapWizard">';
            echo '              <ul class="bootstrapWizard form-wizard">';
            $countG = 1;
	    $conta_exclusao = 0;
//	    echo $row['Grupos'][8]['nome'];exit;
//	    krumo($row);exit;
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
            #END - CABE�ALHO DOS GRUPOS
            #BEGIN - CONTEUDO DOS GRUPOS (PERGUNTAS)
            echo '          <div class="tab-content" >';
            $countG = 1;
	    $multiplo_num = 0;
	    $multiplo_num_por_pergunta = 0; #contador para gerar repeticoes
	    $categoria_tmp = '';
//	    krumo($row);
            foreach ($row['Grupos'] as $cod_grupos => $grupo) {
		$cod_grupos = $grupo['cod_grupo'];
                $active = ($countG == 1) ? 'active' : '';
                echo '              <div class="tab-pane ' . $active . '" id="tab' . $countG . '">';
                echo '                  <h5 style="border-bottom:1px dotted #d3d3d3;;"><strong>' . $countG . ' - ' . $grupo['nome'] . '</strong></h5>';
                echo '                  <div class="row smart-form " style="margin-top:20px;">';

                if (count($grupo['perguntas']) > 0) {
		    #$numPerg = count($grupo['perguntas']);
                    $numPerg = 0;
                    $htmlInput = '';
		    $categoria_ativa = '';
                    foreach ($grupo['perguntas'] as $kPerguntas => $perguntaArr) {
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
                        if (isset($grupo['perguntas'][$kPerguntas + 1]) && !is_null($perguntaArr['HmPergunta']['mesma_linha'])) {
                            $vinculo_pos = $grupo['perguntas'][$kPerguntas + 1]['HmPergunta']['mesma_linha'];
                            $vinculo = $perguntaArr['HmPergunta']['mesma_linha'];
                            if ($vinculo_pos == $vinculo) {
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

			    #BOT�O PARA INCLUS�O DE CONTE�DO DIN�MICO
			    if($perguntaArr['HmPergunta']['multiplo'] == '1'){

				#FAZ A CONTAGEM PARA GERAR O JAX DE REPETI��O
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
						
//						 if($cod_grupos == 7){
//						    krumo($vRetorno);
//						    krumo($inputs_hidden);
//						    exit;
//						}
						
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



                        #INCLUS�O DAS COLUNAS DO SECTION PARA DEFINI��O DO TAMANHO DENTRO DA TELA
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
                        $style = '';
                        if ($perguntaArr['HmPergunta']['tipo'] == 'cod_usuario') {
                            #$style='style="display:none;"';#DESCOMENTAR
                        }
			
			#pula o imc se n�o tem resposta
			if ($perguntaArr['HmPergunta']['tipo'] == 'imc' && !isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])) {
			    continue;
			}
			
			
                        $htmlInput .= ' <section class="' . $cols . '" ' . $style . '>';
                        $htmlInput .= ' <label style="margin-bottom:5px; ">
                                                    ' . $perguntaArr['HmPergunta']['pergunta'] . '
                                                </label>';

                        if ($_SESSION['cod_usuario'] == 93686) {
//                            $htmlInput .= ' <span style="color:red;">' . $perguntaArr['HmPergunta']['tipo'] . '</span>'; #TESTE
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
					    #PARA OCULTAR - busca perguntas para montar a��o reversa
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



				    #NOME DO CAMPO PARA PERGUNTAS M�LTIPLAS (DIN�MICAS)
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
				    $htmlInput .= '<input type="radio" '.$disabled_test.' class="'.$class.'" '.$data_dep_show.' name="'.$name_.'" name_multiplo="'.$name_multiplo_.'" id="radio_' . $opcoesArr['cod_hm_pergunta'] . '" value="' . $opcoesArr['cod_hm_opcao'] . '" '.$check.'>';
				    $htmlInput .= '<i></i>' . $opcoesArr['descricao'];
				    $htmlInput .= '</label>';
				}
			    }
			    $htmlInput .= '</div>';

			}
			#CHECKBOX
			elseif ($perguntaArr['HmPergunta']['tipo'] == 'checkbox') {
			     $htmlInput .= '<div class="row">';
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
					    #PARA OCULTAR - busca perguntas para montar a��o reversa
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

				    #NOME DO CAMPO PARA PERGUNTAS M�LTIPLAS (DIN�MICAS)
				    $name_ = 'data[HmResposta][checkbox][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][]';
				    $name_multiplo_ = '';
				    if($perguntaArr['HmPergunta']['multiplo'] == '1'){
					$name_ = 'data[HmResposta][checkbox][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']['.$conta_exclusao.'][]';
					$name_multiplo_ = 'data[HmResposta][checkbox][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][MULTIPLO_NUM][]';
				    }
				    #'name'=>$name_ ,'name_multiplo'=>$name_multiplo_
				    #name="'.$name_.'" name_multiplo="'.$name_multiplo_.'"

				    $htmlInput .= '<label class="checkbox">';
				    $htmlInput .= '<input type="checkbox" '.$disabled_test.' '.$class.' '.$data_dep_show.' name="'.$name_.'" name_multiplo="'.$name_multiplo_.'" id="checkbox_' . $opcoesArr['cod_hm_pergunta'] . '" value="' . $opcoesArr['cod_hm_opcao'] . '" '.$check.'>';
				    $htmlInput .= '<i></i>' . $opcoesArr['descricao'];
				    $htmlInput .= '</label>';
				}
			    }
			    $htmlInput .= '</div>';
			    $htmlInput .= '</div>';

			}
			#COMBOBOX
			elseif ($perguntaArr['HmPergunta']['tipo'] == 'combobox') {
			    #NOME DO CAMPO PARA PERGUNTAS M�LTIPLAS (DIN�MICAS)
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
			    $htmlInput .= '<select '.$disabled_test.' class="input-sm " id="'.$id_.'" name="'.$name_.'" name_multiplo="'.$name_multiplo_.'" '.$disabled.'>';
			    if (count($perguntaArr['HmOpcao']) > 0) {
				$htmlInput .= '<option value="" >Selecione...</option>';
				foreach ($perguntaArr['HmOpcao'] as $opcoesArr) {
				    $selected = '';

				    if(isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]) && 
				       in_array($opcoesArr['cod_hm_opcao'],$row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']])){
					$selected = 'selected="selected"';
				    }
				    $htmlInput .= '<option value="' . $opcoesArr['cod_hm_opcao'] . '" '.$selected.'>' . $opcoesArr['descricao'] . '</option>';
				}
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

			    #NOME DO CAMPO PARA PERGUNTAS M�LTIPLAS (DIN�MICAS)
			    $name_ = 'data[HmResposta][date][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']';
			    $name_multiplo_ = '';
			    $id_ = 'HmProntuario'.$perguntaArr['HmPergunta']['cod_hm_pergunta'];
			    if($perguntaArr['HmPergunta']['multiplo'] == '1'){
				$name_ = 'data[HmResposta][date][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']['.$conta_exclusao.']';
				$name_multiplo_ = 'data[HmResposta][date][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][MULTIPLO_NUM]';
			    }

			    $htmlInput .= $this->Form->date($perguntaArr['HmPergunta']['cod_hm_pergunta'], array('name'=>$name_,'id'=>$id_ , "{$disabled_test}" , 'name_multiplo'=>$name_multiplo_,'dateFormat' => 'DMY', 'label' => false, 'div' => false, 'placeholder' => '','dateFormat' => 'DMY', 'class' => 'col3 margin-right-cadastre ', 'maxlength' => '10','value'=>$value));
			    $htmlInput .= '</label>';
			}
			#TEXTAREA    
			elseif ($perguntaArr['HmPergunta']['tipo'] == 'textarea') {
			    $value = '';
			    if(isset($row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]) && 
			       $row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['descritivo'] != ''){
				$value = $row['HmRespostasDinamicas'][$perguntaArr['HmPergunta']['cod_hm_pergunta']]['descritivo'];
			    }

			    #NOME DO CAMPO PARA PERGUNTAS M�LTIPLAS (DIN�MICAS)
			    $name_ = 'data[HmResposta][textarea][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']';
			    $name_multiplo_ = '';
			    $id_ = 'HmProntuario'.$perguntaArr['HmPergunta']['cod_hm_pergunta'];
			    if($perguntaArr['HmPergunta']['multiplo'] == '1'){
				$name_ = 'data[HmResposta][textarea][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . ']['.$conta_exclusao.']';
				$name_multiplo_ = 'data[HmResposta][textarea][' . $perguntaArr['HmPergunta']['cod_hm_pergunta'] . '][MULTIPLO_NUM]';
			    }
			    #'name'=>$name_ ,'name_multiplo'=>$name_multiplo_

			    $htmlInput .= '<label class="textarea">';
			    $htmlInput .= $this->Form->textarea($perguntaArr['HmPergunta']['cod_hm_pergunta'], array('name'=>$name_ ,'id'=>$id_ ,"{$disabled_test}",'name_multiplo'=>$name_multiplo_  ,'label' => false, 'div' => false, 'placeholder' => str_replace(':','',$perguntaArr['HmPergunta']['pergunta']), 'class' => 'custom-scroll', 'rows' => 3,'value'=>$value));
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



			    #NOME DO CAMPO PARA PERGUNTAS M�LTIPLAS (DIN�MICAS)
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
			    $htmlInput .=    $this->Form->input($perguntaArr['HmPergunta']['cod_hm_pergunta'], array_merge(array('name'=>$name_ ,'name_multiplo'=>$name_multiplo_,'label' => false, "{$disabled_test}", 'div' => false, 'class' => 'input_login '.$class),$conditions_add));
			    $htmlInput .= ' </label>';
			}

                        #DESCRITIVO DO CAMPO
                        if (!is_null($perguntaArr['HmPergunta']['descritivo'])) {
                            $htmlInput .= '<div class="note">' . $perguntaArr['HmPergunta']['descritivo'] . '</div>';
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
//				$htmlInput .= '   <div class="text-right " style=" margin-bottom: 10px; margin-right: 10px; ">
//						    <a class="btn btn-danger btn_exclusao" href="javascript:void(0);" ajaxmsg="Tem certeza que deseja excluir este registro?" style="padding:3px 7px 3px 5px; '.$display_none_excluir.'" data_value = "">
//							<i class="fa fa-trash-o"></i> <span class="hidden-mobile">Excluir</span>
//						    </a>
//						  </div>';

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
            echo '  </div>';
            echo '</div>';
	     
	    if(isset($conta_exclusao)){
		$contador_exclusao = $conta_exclusao;
		if(isset($row['conta_multiplo'])){
		    $contador_exclusao = $row['conta_multiplo'];
		}
		echo '<input type="hidden" value="'.$conta_exclusao.'" id="conta_exclusao">';
	    }
            
        }
        ?>

        <!-- END Timeline Content -->
    </div>
</div>
<?php
$nome_programa_ = '';
if (isset($caseArr['HmHistorico']['cod_hm_programa']) && isset($programa_sub_arr[$caseArr['HmHistorico']['cod_hm_programa']]['nome'])) {
    $nome_programa_ = $programa_sub_arr[$caseArr['HmHistorico']['cod_hm_programa']]['nome'];
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        //$('.timepicker').timepicker();
        $('.timepicker').timepicker({
            minuteStep: 15,
            showSeconds: true,
            showMeridian: false,
            defaultTime: false
        });

        $('.aviso_permissao').click(function() {
            em_execucao = <?php echo $em_execucao ?>;
            agendado = <?php echo $agendado ?>;
            nome_programa = "<?php echo $nome_programa_ ?>";
            aviso = 'Voc� N�O pode finalizar este ' + nome_programa + ', pois consta :\n';
            if (agendado > 0) {
                plural_ag = '';
                if (agendado > 1) {
                    plural_ag = 's';
                }
                //aviso += '- '+agendado+' Case'+plural_ag+' Agendado'+plural_ag;
                aviso += '- ' + agendado + ' ' + nome_programa + plural_ag + ' Agendado' + plural_ag;
            }
            if (em_execucao > 0 && agendado > 0) {
                aviso += '\n';
            }
            if (em_execucao > 0) {
                plural_exec = '�o';
                plural_ = '';
                if (em_execucao > 1) {
                    plural_exec = '�es';
                    plural_ = 's';
                }
                aviso += '- ' + em_execucao + ' ' + nome_programa + plural_ + ' em Execu�' + plural_exec + '';
            }
            plural_ = '';
            if ((em_execucao + agendado) > 1) {
                plural_ = 's'
            }

            aviso += ' \n\n Voc� deve concluir ou cancelar a' + plural_ + ' pend�ncia' + plural_ + '.\n';
            alert(aviso);
        });




        //atualiza telefone
        $('.btn_oculta_info').click(function() {
            $('.btn_oculta_info').hide();
            $('.btn_add_info').show();
            $('.oculta_info').hide(1000);
            $('.smart-timeline').removeClass('scroll');
        });
        $('.btn_add_info').click(function() {
            $('.btn_oculta_info').show();
            $('.btn_add_info').hide();
            $('.oculta_info').show(1000);
            $('.smart-timeline').addClass('scroll');
        });


        $('.abrir_cria_telefone').click(function() {
//           $('.atualiza_telefone').fadeIn('slow');
            var isVisible = $(".cria_telefone").is(":visible");
            if (isVisible) {
                $('.cria_telefone').hide(1000);
                $('.abrir_cria_telefone').html('<i class="fa fa-phone"></i> Incluir Telefone');
            } else {
                $('.cria_telefone').show(1000);
                $('.abrir_cria_telefone').html('<i class="fa fa-phone"></i> Fechar Incluis�o Telefone');
            }
        });


//        $('.abrir_cria_programa').click(function(){
////           $('.atualiza_telefone').fadeIn('slow');
//           var isVisible = $(".cria_programa").is( ":visible" );
//           if(isVisible){
//               $('.cria_programa').fadeOut('slow');
//               $('.abrir_cria_programa').html('<i class="fa fa-plus-circle"></i> Abrir Novo Programa');
//           }else{
//               $('.cria_programa').fadeIn('slow');
//               $('.abrir_cria_programa').html('<i class="fa fa-plus-circle"></i> Fechar Abertura de Programa');
//           }
//       });


        $('#HmProntuarioTelefoneCelular').click(function() {
            $('.aviso_telefone').attr('style', 'margin-top:10px; padding:10px 30px;');
        });
        $('#HmProntuarioTelefoneOutros').click(function() {
            $('.aviso_telefone').attr('style', 'margin-top:10px; padding:10px 30px;');
        });

        $('.criar_telefone_submit').click(function() {
            celular = $('#HmProntuarioTelefoneCelular').val().length;
            telefone = $('#HmProntuarioTelefoneOutros').val().length;

            if (celular == 0 && telefone == 0) {
                $('.aviso_telefone').attr('style', 'border:2px solid #bd0f0f; margin-top:10px; padding:10px 30px;');
                $('#HmProntuarioTelefoneCelular').focus();
                alert('Voc� deve preencher ao menos um n�mero.');
                return false;
            }
            if (celular > 0) {
                if (celular != 15) {
                    $('#HmProntuarioTelefoneCelular').attr('style', 'border:2px solid #bd0f0f;');
                    $('#HmProntuarioTelefoneCelular').focus();
                    alert('Erro na quantidade de caracteres do "Celular", favor verificar!');
                    return false;
                }
            }
            if (telefone > 0) {
                if (telefone != 14) {
                    $('#HmProntuarioTelefoneOutros').attr('style', 'border:2px solid #bd0f0f;');
                    $('#HmProntuarioTelefoneOutros').focus();
                    alert('Erro na quantidade de caracteres do "Telefone", favor verificar!');
                    return false;
                }
            }
        });

        setTimeout(function() {
            percent = $('.easy-pie-chart .percent-sign').attr('rel');
            $('.easy-pie-chart .percent-sign').html('' + percent + '');
        }, 2500);

        //BEGIN - FAZ EFEITO DE MOVER PARA O LADO OS ATENDIMENTOS ENCADEADAS
        action = '<?php echo $this->params['action']; ?>';
	//console.log(action);
        if (action == 'view' || action == 'gdc' || action == 'api') {
            $(".identa_atividades").each(function(key, value) {
                chave_tmp = $(this).attr('identa_chave');
                if (chave_tmp != '') {
                    class_tmp = $(this).attr('identa_class');
                    $("." + class_tmp).animate({
                        opacity: 1.00,
                        width: "-=100",
                        left: "+=100"
                    }, 1500, function() {
                    });
                    $("." + class_tmp + " .smart-timeline-content").attr('style', 'border-left: 1px solid #d3d3d3;padding-left: 40px;margin-left: 100px;');
                }
            });
        }
        //END - FAZ EFEITO DE MOVER PARA O LADO OS ATENDIMENTOS ENCADEADAS


        var action = '<?php echo $this->params['action']; ?>';
        var sinistroCount = '<?php echo isset($sinistroArr['data']) ? count($sinistroArr['data']) : 0; ?>';




        if (action == 'case_management' && sinistroCount > 0) {
            /* chart colors default */
            var $chrt_border_color = "#efefef";
            var $chrt_grid_color = "#DDD"
            var $chrt_main = "#E24913";
            /* red       */
            var $chrt_second = "#6595b4";
            /* blue      */
            var $chrt_third = "#FF9F01";
            /* orange    */
            var $chrt_fourth = "#7e9d3a";
            /* green     */
            var $chrt_fifth = "#BD362F";
            /* dark red  */
            var $chrt_mono = "#000";


            if ($("#saleschart").length) {
                var d = <?php echo isset($sinistroArr['data']) && count($sinistroArr['data']) > 0 ? $sinistroArr['data'] : 0; ?>;

                for (var i = 0; i < d.length; ++i)
                    d[i][0] += 60 * 60 * 1000;

                function weekendAreas(axes) {
                    var markings = [];
                    var d = new Date(axes.xaxis.min);
                    // go to the first Saturday
                    d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
                    d.setUTCSeconds(0);
                    d.setUTCMinutes(0);
                    d.setUTCHours(0);
                    var i = d.getTime();
                    do {
                        // when we don't set yaxis, the rectangle automatically
                        // extends to infinity upwards and downwards
                        markings.push({
                            xaxis: {
                                from: i,
                                to: i + 2 * 24 * 60 * 60 * 1000
                            }
                        });
                        i += 7 * 24 * 60 * 60 * 1000;
                    } while (i < axes.xaxis.max);

                    return markings;
                }

                var options = {
                    xaxis: {
                        mode: "time",
                        tickLength: 5
                    },
                    series: {
                        lines: {
                            show: true,
                            lineWidth: 1,
                            fill: true,
                            fillColor: {
                                colors: [{
                                        opacity: 0.1
                                    }, {
                                        opacity: 0.15
                                    }]
                            }
                        },
                        //points: { show: true },
                        shadowSize: 0
                    },
                    selection: {
                        mode: "x"
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: $chrt_border_color,
                        borderWidth: 0,
                        borderColor: $chrt_border_color,
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: "Utiliza��o no m�s <b>%x</b> foi <span>R$%y</span>",
                        dateFormat: "%0m/%y",
                        defaultTheme: false
                    },
                    colors: [$chrt_second],

                };

                var plot = $.plot($("#saleschart"), [d], options);
            }
            ;

            /* end sales chart */
        }
	
	if (action == 'gdc' || action == 'api' ){
	    
	    /* ATUALIZA��O DE PERGUNTAS NOVAS */
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
		   	linha_chave_tmp = '';
			$('.'+class_retorno+' .retorno_campos input').each(function (key1, val1) {
			    linha_chave = $(this).parent().attr('data_count_retorno');
			    id_retorno = $(this).attr('id');
			    val_retorno = $(this).val();
			    //console.log(id_retorno);
			    //console.log(val_retorno);
			    
			    
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
//			$('.'+class_retorno).html(''); //teste deixar descomentado


		    }
		});
	    },1000);



	    


	    $('.inclusao_dinamica').click(function(){


		id_categoria = $(this).attr('data_inclusao');
		conta_exclusao = parseInt($('#conta_exclusao').val());
		$('#conta_exclusao').val(conta_exclusao+1);


		//duplica o content
		content_div = '';
		$( '.duplica_categoria_'+id_categoria ).each(function( index ) {
		    content_div = content_div + $(this).html();
		});


		//determinar o n�mero do id para repeti��o
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

	    });


	    //fun��o de exclusao do bot�o da linha adicionada
	    $(document).on('click', '.btn_exclusao', function() {
		msg = $(this).attr('ajaxmsg');
		if(confirm(msg)){
		    id_exclusao = $(this).attr('data_value');
		    if(id_exclusao != ''){
			$('.duplicado_'+id_exclusao).remove();
		    }
		}
	    });


	
	    //REMOVENDO BOT�ES
	    $('.inclusao_dinamica').attr('disabled','disabled');
	    
	}
	
	if(action == 'programa'){
	    setInterval(function(){ 
		atualiza_pg();
	    }, 300000);
	}
	
	function atualiza_pg(){
	    $.ajax({
		url:"<?php echo Router::url('/usuario/atualiza_session/',true); ?>",
		data: {},
		dataType: "json",
		type: 'POST',
		async: false,
		cache: false,
		success: function(data) {
		    console.log('atualiza sess�o');
		}
	    });
	
	}

    });


</script>
