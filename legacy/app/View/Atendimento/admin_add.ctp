<?php echo $this->Element('admin/breadcrumb'); ?>

<div id="content">
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php
                echo $this->Form->msg($this->Session->flash());
                $beneficiario_id = $this->params['pass'][0];
                echo $this->Form->create(
                    $TABLE,
                    array(
                        'type' => 'file',
                        'id' => $this->params['controller'] . '-form',
                        'url' => array(
                            'controller' => $this->params['controller'],
                            'action' => 'add',
                            $beneficiario_id
                        ),
                        'class' => 'smart-form client-form '
                    )
                );
                echo $this->Form->msg($this->Session->flash());
                #echo $this->Funcoes->menus('geral',$permissao);
                ?>
                <div class="btn-group" style="float:right; margin-bottom: 10px;">
                    <button class="btn btn-primary btn-sm dropdown-toggle " data-toggle="dropdown">
                        Ações <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">

                        <li><a href="<?php echo Router::url(array('controller' => 'beneficiario', 'action' => 'view', $this->params['pass'][0]), true); ?>">Voltar para <?php echo explode(' ', $benef['Beneficiario']['nome'])[0]; ?></a></li>
                        <li><a href="<?php echo Router::url(array('controller' => 'beneficiario'), true); ?>">Lista de Beneficiários </a></li>
                        <?php if (isset($this->data[$TABLE]['id'])): ?>
                            <li class="divider"></li>
                            <li class="bg-color-red"><a href="<?php echo Router::url(array('controller' => 'beneficio_previdenciario', 'action' => 'delete', $this->params['pass'][0], $this->params['pass'][1]), true); ?>" class="ajaxMsg" ajaxmsg="Tem certeza que deseja excluir o parâmetro ID: 132" style="color:white">Excluir Beneficio Previdenciario</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <header>
                    <?php echo (isset($this->params['pass'][1])) ? 'Edição' : 'Cadastro'; ?> de <?php echo $this->Funcoes->titulos($this->params['controller']); ?>
                </header>
                <fieldset>
                    <?php
                    $obrigatorio = '<span class="campo_obrigatorio">*</span>';

                    echo $this->Form->hidden('beneficiario_id', array('value' => $beneficiario_id));
                    echo $this->Form->hidden('afastado_id', array('value' => $this->request->data['afastado_id']));
                    echo $this->Form->hidden('esta_afastado', array('value' => $this->request->data['esta_afastado']));
                    if (isset($this->data[$TABLE]['id'])):
                        echo $this->Form->hidden('id');
                    ?>
                        <div class="row">
                            <section class="col col-6">
                                <label class="Bold"><strong>ID: </strong></label>
                                <label> <?php echo $this->data[$TABLE]['id']; ?></label>
                            </section>
                        </div>
                    <?php endif; ?>


                    <div class="row" style="margin-bottom:20px; ">
                        <section class="col col-3">
                            <label class="label">Tipo do Atendimento <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('tipo_atendimento', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Acolhimento', 'class' => 'input_login', 'options' => $tipoAtendimentoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3 exibi_cid" style="display:none;">
                            <label class="label">CID - Classificação Internacional de Doenças</label>
                            <label class="input">
                                <?php echo $this->Form->input('cid', array('label' => false, 'div' => false, 'placeholder' => 'CID', 'class' => 'input_login', 'maxlength' => '6', 'default' => '')); ?>
                                <i></i>
                            </label>
                            <div class="note"><strong>Exemplo:</strong> F41.9</div>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Atendimento feito por: <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('forma_atendimento', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Atendimento', 'class' => 'input_login', 'options' => $formaAtendimentoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3 exibir_status_telefone" style="display: none;">
                            <label class="label">Status da Conversa: <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('status_atendimento', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Atendimento', 'class' => 'input_login', 'options' => $statusAtendimentoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>


                        <!-- <section class="col col-1 exibi_hora_atendimento" style="display: none;">
                            <label class="label">Tempo Total: <?php echo $obrigatorio; ?></label>
                            <label class="input">
                                < ?php echo $this->Form->input('at_horas', array('label' => false, 'div' => false, 'placeholder' => 'HH', 'class' => 'input_login', 'style' => 'width:90%', 'default' => '' ,'maxlength'=>'2')); ?>
                            </label>
                        </section> -->
                        <section class="col col-1 exibi_hora_atendimento" style="display: none;">
                            <label class="label">Tempo Total: <?php echo $obrigatorio; ?></label>
                            <label class="input">
                                <?php echo $this->Form->input('at_minutos', array('label' => false, 'div' => false, 'placeholder' => 'MM', 'class' => 'input_login', 'style' => 'width:90%', 'default' => '', 'maxlength' => '2')); ?>
                            </label>
                            <span class="note">Em minutos</span>
                        </section>

                    </div>
                    


                    <?php
                        if($this->data['Agendamento'][0]['descricao'] != ''){
                            echo '  <section style="margin-top:30px; margin-bottom:30px;">
                                        <p><b>Descrição do Agendamento:</b></p>
                                        <p>
                                            '.$this->data['Agendamento'][0]['descricao'].'
                                        </p>
                                    </section>';
                        }
                    ?>  
                   
                    <section style="margin-bottom:30px;">
                        <label class="label">Descrição Médica:</label>
                        <label class="textarea">
                            <?php echo $this->Form->textarea('descricao', array('rows' => 4, 'style' => 'width:100%;', 'style' => 'width:100%;', 'label' => false, 'div' => false, 'placeholder' => 'Introdução')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa  fa-chevron-right txt-color-blueLight"></i> Entre com a Descrição</b>
                        </label>
                    </section>

                    <div class="row" style="margin-top: 30px; margin-bottom: 10px;">
                        <section class="col col-12" style="margin-bottom: 50px;">
                            <label class="label"><i class="fa fa-file"></i>
                                Anexo:
                                <?php
                                //                                echo $obrigatorio;
                                $dataHelpContent = '    <h5>Regras</h5> 
                                                            - Tamanho máximo do anexo é de 5mb.<br>
                                                            - Permitido somente 1 arquivo por atendimento.<br>
                                                            - Caso precise anexar mais de um arquivo, compacte todos em um único arquivo.
                                                            <br>
                                                        
                                                        ';
                                echo $this->Html->image('sys/help.png', array('width' => '22px', 'style' => 'cursor:help; margin-left:10px;', 'rel' => "popover-hover", 'data-placement' => "bottom", 'data-html' => 'true', 'data-content' => $dataHelpContent));
                                ?>
                            </label>
                            <?php
                            $required = array();

                            echo '<label class=""> ' . $this->Form->input('arquivo', array_merge($required, array('label' => false, 'type' => 'file', 'div' => false, 'placeholder' => 'Logo', 'class' => 'btn btn-default'))) . '</label>';


                            if (isset($this->data['Atendimento']['arquivo']) && $this->data['Atendimento']['arquivo']['error'] != 1) {
                                echo $this->Form->hidden('arquivo');
                            }


                            if (isset($this->data[$TABLE]['anexo']) && $this->data[$TABLE]['anexo'] != '' && file_exists('files/uploads/atendimento/' . $this->data[$TABLE]['anexo'])) {

                                echo '<p><br>
                                        <b>Arquivo Carregado:</b><br>
                                        <a href="' . Router::url('/files/uploads/atendimento/' . $this->data[$TABLE]['anexo']) . '" target="_blank" ">
                                            <img src="' . Router::url('/img/icons/attach.png', true) . '" width="20"/>
                                            ' . $this->data[$TABLE]['anexo'] . '
                                        </a></p>';
                            }


                            if (isset($this->data[$TABLE]['blob_id']) && $this->data[$TABLE]['blob_id'] != '' && $this->Session->read('Auth.Usuario.perfil_id') == 1) {
                                echo '<p><br>
                                        <b>Arquivo bob:</b><br>
                                        <a href="' . Router::url(array('controller' => 'blob', 'action' => 'download', md5($this->data[$TABLE]['blob_id']), 'admin' => true)) . '" target="_blank" ">
                                            <img src="' . Router::url('/img/icons/attach.png', true) . '" width="20"/>
                                            ' . $this->data[$TABLE]['anexo'] . '
                                        </a></p>';
                            }
                            ?>
                        </section>
                    </div>

                    <div class="row" style="border-radius:12px; margin:20px 10px 40px 10px; padding: 10px 2px; box-shadow: 4px 5px 20px 2px rgba(123, 123, 123, 0.5); -webkit-box-shadow: 4px 5px 20px 2px rgba(122, 122, 122, 0.5); display: ;" >
                        <h5>
                            Agendamento
                        </h5>
                        
                        <script>
                            

                            $(document).ready(function() {
                                
                                $('#AtendimentoNovoAgendamento').change(function() {
                                    // if($(this).val() === '1') {
                                    //     $('.exibir_descricao_agendamento').fadeIn('slow');
                                        
                                    // }else 
                                    if($(this).val() === '0' && $('#AtendimentoBeneficiarioRetorno option:selected').val() === '0') {
                                        alert('Beneficiário está afastado, é necessário fazer um novo agendamento!');
                                        $('#AtendimentoNovoAgendamento').val('');
                                        // $('.exibir_descricao_agendamento').fadeOut('slow');
                                    } 
                                    // else {
                                    //     $('.exibir_descricao_agendamento').fadeOut('slow');
                                    // }

                                    if($(this).attr('rel_esta_afastado') === '1' ){
                                        $('.exibir_afastamento').fadeIn('slow');
                                    }

                                })


                                $('#AtendimentoBeneficiarioRetorno').change(function() {
                                    if ($(this).val() === '1') {
                                        $('.exibir_data_retorno').fadeIn('slow');
                                    } else if ($(this).val() === '0') {
                                        $('.exibir_data_retorno').fadeOut('slow');
                                        $('#AtendimentoDataRetornoAfastamento').val('');
                                        if($('#AtendimentoNovoAgendamento option:selected').val() === '0'){
                                            $('#AtendimentoNovoAgendamento').val('');
                                            alert('Beneficiário está afastado, é necessário fazer um novo agendamento! Favor selecionar "Sim" no campo "Deseja fazer um novo agendamento?"');
                                        }
                                    } else {
                                        $('.exibir_data_retorno').fadeOut('slow');
                                    }
                                })
                         
                            });
                        </script>
                        <div class="row" style=" margin:0;">
                            <section class="col col-4 ">
                                <label class="label">Deseja fazer um novo agendamento? <?php echo $obrigatorio; ?></label>
                                <label class="select">
                                    <?php echo $this->Form->input('novo_agendamento', array('label' => false, 'div' => false, 'placeholder' => 'Novo Agendamento', 'class' => 'input_login ', 'rel_esta_afastado'=> $this->request->data['esta_afastado'], 'options' => $simNaoArr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                                <span class="note">Criar novo agendamento ao finalizar o atendimento! </span>
                            </section>
                            <section class="col col-4 exibir_afastamento" style="display:none;">
                                <label class="label">Este beneficiário está <span style="color: red; font-weight: bold;">afastado</span>! Teve retorno ao trabalho? <?php echo $obrigatorio; ?></label>
                                <label class="select">
                                    <?php echo $this->Form->input('beneficiario_retorno', array('label' => false, 'div' => false, 'placeholder' => 'Beneficiário teve retorno ao trabalho?', 'class' => 'input_login','options' => $simNaoArr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                                <span class="note">Beneficiário teve retorno ao trabalho? </span>
                            </section>
                            <section class="col col-4 exibir_data_retorno" style="display:none;">
                                <label class="label">Qual a data do retorno? <?php echo $obrigatorio; ?></label>
                                <label class="input">
                                    <?php $data_hora_default =  date('Y-m-d 8:00:00', strtotime('0 day')); ?>
                                    <?php echo $this->Form->date('data_retorno_afastamento', array('type' => 'date', 'label' => false, 'div' => false, 'placeholder' => 'Data Hora ', 'class' => 'input_login', 'minYear' => date('Y') - 5, 'maxYear' => date('Y'), 'dateFormat' => 'DMY', 'timeFormat' => '24', 'selected' => $data_hora_default)); ?>

                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data e Hora do retorno</b>
                                    <?php
                                    if (!isset($this->params['pass'][1])) {
                                        echo '<br><br> <span class="note">Lembrete: Data de Hoje "' . date('d/m/Y') . '"</span>';
                                    }
                                    ?>
                                </label>
                            </section>
                        </div>
                      
                    </div>

                    <div class="row" style="margin-top:20px; display:none;">
                        <section class="col col-2">
                            <label class="label">Status <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('status', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => $this->Funcoes->status(), 'default' => '1')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>



                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary ">
                        Salvar
                    </button>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer>

                <?php echo $this->Form->end(); ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // $('#BeneficioPrevidenciarioEspecieBpId').change(function(){

        //     if($(this).val() == 'x'){
        //         $('.especie_div').fadeIn('slow');
        //     }else{
        //         $('.especie_div').fadeOut('slow');
        //         $('#BeneficioPrevidenciarioEspecieBpIdNew').val('');
        //         $('#BeneficioPrevidenciarioEspecieNew').val('');
        //     }
        // })

        //config_ckeditor (appcontroller)
        //CKEDITOR.replace('data[BeneficioPrevidenciario][nexo_tecnico]', {< ?php echo $config_ckeditor; ?>});

    });
    $(document).ready(function() {
        CKEDITOR.replace('data[Atendimento][descricao]', {
            <?php echo $config_ckeditor; ?>
        });


        $('#AtendimentoFormaAtendimento').change(function() {
            liberaformaatendimento(false);
        });

        function liberaformaatendimento(retorno) {
            tipo = $('#AtendimentoFormaAtendimento').val();


            if (tipo != '' && (tipo == 0 || tipo == 3)) {
                $('.exibi_hora_atendimento').hide();
                if (retorno == false) {
                    $('#AtendimentoAtHoras').val('');
                    $('#AtendimentoAtMinutos').val('');
                }
                $('.exibir_status_telefone').fadeIn('slow');
            } else if (tipo == 1 || tipo == 2) {
                $('.exibir_status_telefone').fadeOut('slow');
                $('#AtendimentoStatusAtendimento').val('');
                $('.exibi_hora_atendimento').fadeIn('slow');
            } else {
                if (retorno == false) {
                    $('#AtendimentoAtHoras').val('');
                    $('#AtendimentoAtMinutos').val('');
                    $('.exibir_status_telefone').fadeOut('slow');
                    $('#AtendimentoStatusAtendimento').val('');
                    $('#AtendimentoNovoAgendamento').val('');
                    $('.exibi_hora_atendimento').fadeOut('slow');
                }
            }
        }
        liberaformaatendimento(true);




        $('#AtendimentoTipoAtendimento').change(function() {
            liberacid(false);
        });

        function liberacid(retorno) {
            tipo = $('#AtendimentoTipoAtendimento').val();
            if (tipo != '' && (tipo == 2 || tipo == 3 || tipo == 4)) {
                $('.exibi_cid').fadeIn('slow');
            } else {
                $('.exibi_cid').fadeOut('slow');
                if (retorno == false) {
                    $('#AtendimentoCid').val('');
                }
            }
        }
        liberacid(true);



        $('#AtendimentoStatusAtendimento').change(function() {
            liberastatusatendimento(false);
        });

        function liberastatusatendimento(retorno) {
            status = $('#AtendimentoStatusAtendimento').val();
            if (status != '') {
                $('.exibi_hora_atendimento').fadeIn('slow');
            } else {
                if (retorno == false) {
                    $('.exibi_hora_atendimento').fadeOut('slow');
                    $('#AtendimentoAtHoras').val('');
                    $('#AtendimentoAtMinutos').val('');
                }
            }
        }
        liberastatusatendimento(true)


        $('.onTopStep').click(function() {
            scroll();
        })
        $('.onTop').click(function() {
            scroll();
        })


    });
    $(window).scroll(function() {

        if ($(this).scrollTop() < 550) {
            $('.onTop').fadeOut('slow');
        } else {
            $('.onTop').fadeIn('slow');
        }
    });

    function scroll() {
        //$(window).scrollTop(330);
        $("html, body").animate({
            scrollTop: 330
        }, 400);
    }
</script>