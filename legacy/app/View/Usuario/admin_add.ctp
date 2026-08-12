<?php echo $this->Element('admin/breadcrumb'); ?>
<div id="content">
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php
                echo $this->Form->msg($this->Session->flash());

                echo $this->Form->create(
                    $TABLE,
                    array(
                        'type' => 'file',
                        'id' => $this->params['controller'] . '-form',
                        'url' => array(
                            'controller' => $this->params['controller'],
                            'action' => 'add'
                        ),
                        'class' => 'smart-form client-form '
                    )
                );
                echo $this->Form->msg($this->Session->flash());
                echo $this->Funcoes->menus('geral', $permissao);
                ?>
                <header>
                    <?php echo (isset($this->params['pass'][0])) ? 'Edição' : 'Cadastro'; ?> de <?php echo $this->Funcoes->titulos($this->params['controller']); ?>
                </header>
                <fieldset>
                    <?php
                    $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                    echo $this->Form->hidden('id');
                    if (isset($this->data[$TABLE]['id']) && $this->data[$TABLE]['id'] != '') :
                    ?>
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> <?php echo $this->data[$TABLE]['id']; ?></label>
                            </section>
                            <section class="col col-6">
                                <label class="label" style="text-align: right;"><strong>Criado por:</strong> <i><?php echo $this->data['UsuarioCriador']['nome']; ?></i></label>
                            </section>
                        </div>
                    <?php endif; ?>



                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Perfil <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('perfil_id', array('label' => false, 'div' => false, 'placeholder' => 'Perfil', 'class' => 'input_login', 'options' => $perfilArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>

                    <?php
                    $display_exibe = 'style="display:none;"';
                    // if(isset($this->data[$TABLE]['perfil_id']) && in_array($this->data[$TABLE]['perfil_id'], array(3,4,5,6,7,8,9))){
                    //     $display_exibe = '';
                    // }
                    ?>

                    <div class="exibe_empresas_cliente row" <?php echo $display_exibe; ?>>
                        <div class="smart-form col col-6" style="margin-bottom:30px;">
                            <fieldset style=" margin:0 !important; padding:0 !important;">
                                <!-- <section  style="margin-bottom:5px !important;">
                                    <label class="select select-multiple"> 
                                      <?php
                                        #echo $this->Form->input('cliente_id', array('multiple' => 'multiple','type' => 'select','label' => false, 'div' => false, 'class' => 'input_login custom-scroll', 'options'=>$selectCliente)); 
                                        ?>
                                  </label>
                                </section> -->
                                <?php #krumo($this->data);
                                ?>
                                <label class="select select-multiple">
                                    <!-- <select multiple>
                                        <optgroup label="Swedish Cars">
                                            <option value="volvo">Volvo</option>
                                            <option value="saab">Saab</option>
                                        </optgroup>
                                        <optgroup label="German Cars">
                                            <option value="mercedes">Mercedes</option>
                                            <option value="audi">Audi</option>
                                        </optgroup>
                                    </select> -->

                                    <select name="data[Usuario][cliente_id][]" id="UsuarioClienteId" class="input_login " multiple="multiple" style="height:200px; ">';
                                        <?php


                                        if (count($selectClienteNew) > 0) {
                                            $list_client = [];
                                            if (count($this->data['UsuarioCliente']) > 0) {
                                                foreach ($this->data['UsuarioCliente'] as $cliente_id_arr) {
                                                    $list_client[] = $cliente_id_arr['cliente_id'];
                                                }
                                            }
                                            #$color_red = 'style="background-color:#f5b8b8;"';
                                            #$color_yellow = 'style="color:black; background-color:yellow;"';
                                            foreach ($selectClienteNew as $cliente_grupo_id => $cliente_grupo_arr) {
                                                echo '<optgroup label="' . $cliente_grupo_arr[0]['ge_nome'] . '" style="">';
                                                foreach ($cliente_grupo_arr as $cliente_grupo) {
                                                    $selected = '';
                                                    if (in_array($cliente_grupo['cliente_id'], $list_client)) {
                                                        $selected = 'selected = "selected"';
                                                    }
                                                    // if($this->Session->read("Auth.Usuario.cliente_id") == $cliente_grupo['cliente_id']){
                                                    //     $selected = 'selected = "selected"';
                                                    // }
                                                    $sts = '';
                                                    if ($cliente_grupo['cliente_status'] == 0) {
                                                        $sts = ' - (Inativo)'; #;$color_yellow;
                                                    } elseif ($cliente_grupo['cliente_status'] == 2) {
                                                        $sts = ' - (Excluído)'; #$color_red;
                                                    }
                                                    echo '<option value="' . $cliente_grupo['cliente_id'] . '" ' . $selected . ' style="margin-left:10px;">' . $cliente_grupo['cliente_nome'] . $sts . '</option>';
                                                }
                                                echo '</optgroup>';
                                            }
                                        }
                                        ?>

                                    </select>

                                </label>
                            </fieldset>
                        </div>
                    </div>


                    <?php

                    // krumo($this->data);
                    // krumo($selectBi);
                    // exit;

                    ?>
                    <div class="exibe_bi_usuario row" style="" <?php #echo $display_exibe;
                                                                ?>>
                        <div class="smart-form col col-6" style="margin-bottom:30px;">
                            <fieldset style=" margin:0 !important; padding:0 !important;">
                                <label class="select select-multiple">
                                    <select name="data[Usuario][bi_id][]" id="UsuarioBiId" class="input_login " multiple="multiple" style="height:200px; ">';
                                        <?php


                                        if (count($selectBi) > 0) {
                                            $list_bi = [];
                                            if (count($this->data['UsuarioBi']) > 0) {
                                                foreach ($this->data['UsuarioBi'] as $bi_id_arr) {
                                                    $list_bi[] = $bi_id_arr['bi_id'];
                                                }
                                            }
                                            #$color_red = 'style="background-color:#f5b8b8;"';
                                            #$color_yellow = 'style="color:black; background-color:yellow;"';
                                            foreach ($selectBi as $bi_grupo_id => $bi_grupo_arr) {

                                                #krumo($bi_grupo_arr);
                                                #exit;

                                                echo '<optgroup label="' . $bi_grupo_arr[0]['ge_nome'] . '" style="">';
                                                foreach ($bi_grupo_arr as $bi_grupo) {
                                                    $selected = '';
                                                    if (in_array($bi_grupo['bi_id'], $list_bi)) {
                                                        $selected = 'selected = "selected"';
                                                    }
                                                    // if($this->Session->read("Auth.Usuario.cliente_id") == $bi_grupo['cliente_id']){
                                                    //     $selected = 'selected = "selected"';
                                                    // }
                                                    $sts = ' - ' . $bi_grupo['subtitulo'];
                                                    // if($bi_grupo['status'] == 0){
                                                    //     $sts = ' - (Inativo)';#;$color_yellow;
                                                    // }elseif($bi_grupo['status'] == 2){
                                                    //     $sts = ' - (Excluído)';#$color_red;
                                                    // }
                                                    echo '<option value="' . $bi_grupo['bi_id'] . '" ' . $selected . ' style="margin-left:10px;">' . $bi_grupo['titulo'] . $sts . '</option>';
                                                }
                                                echo '</optgroup>';
                                            }
                                        }
                                        ?>

                                    </select>

                                </label>
                            </fieldset>
                        </div>
                    </div>


                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Apelido <?php echo $obrigatorio; ?></label>
                            <label class="input"> <i class="icon-append fa fa-smile-o"></i>
                                <?php echo $this->Form->input('apelido', array('label' => false, 'div' => false, 'placeholder' => 'Apelido', 'class' => 'input_login', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-smile-o txt-color-blueLight"></i> Entre com o Apelido</b></label>
                        </section>
                    </div>

                    <section>
                        <label class="label">Nome <?php echo $obrigatorio; ?></label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Nome', 'class' => 'input_login', 'maxlength' => '65')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Nome </b></label>
                    </section>

                    <section>
                        <label class="label">Usuário <?php echo $obrigatorio; ?></label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $this->Form->input('usuario', array('label' => false, 'div' => false, 'placeholder' => 'Usuário', 'class' => 'input_login', 'maxlength' => '60')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Usuário</b></label>
                    </section>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Email <?php echo $obrigatorio; ?></label>
                            <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                <?php echo $this->Form->input('email', array('label' => false, 'div' => false, 'placeholder' => 'Email', 'class' => 'input_login', 'maxlength' => '120')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-blueLight"></i> Entre com seu Email</b></label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Email Gestão </label>
                            <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                <?php echo $this->Form->input('email_gestao', array('label' => false, 'div' => false, 'placeholder' => 'Email Gestão', 'class' => 'input_login', 'maxlength' => '120')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-blueLight"></i> Entre com seu Email Gestão</b></label>
                        </section>
                    </div>



                    <?php
                    $exibe_senha = '';
                    if (!isset($this->params['pass'][0]) || $perfil_id == $perfil_root) :
                        if (isset($this->params['pass'][0]) && $perfil_id == $perfil_root) :
                            $exibe_senha = 'style="display:none;"';
                    ?>
                            <div class="rows click_exibe_senha" style="color:blue; text-decoration: underline; cursor:pointer;">
                                Exibir campos de senha?
                            </div>
                        <?php endif; ?>



                        <div class="row exibe_senha" <?php echo $exibe_senha; ?>>
                            <section class="col col-6">
                                <label class="label">Senha <?php echo $obrigatorio; ?></label>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <?php echo $this->Form->input('senha', array('type' => 'password', 'label' => false, 'div' => false, 'placeholder' => 'Senha', 'class' => 'input_login', 'value' => '')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-blueLight"></i> Entre com a Senha</b></label>
                            </section>
                            <section class="col col-6">
                                <label class="label">Confirmação de Senha <?php echo $obrigatorio; ?></label>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <?php echo $this->Form->input('retry_senha', array('type' => 'password', 'label' => false, 'div' => false, 'placeholder' => 'Repetir Senha', 'class' => 'input_login', 'value' => '')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-blueLight"></i> Repita a Senha</b></label>
                            </section>
                        </div>
                    <?php endif; ?>



                    <!-- IMAGEM -->
                    <div class="row" style="margin-top: 30px; margin-bottom: 10px; ">
                        <section class="col col-12" style="margin-bottom: 50px;">
                            <label class="label"><i class="fa fa-lg  fa-camera-retro"></i>
                                Imagem Principal
                                <?php // echo $obrigatorio;
                                ?>
                                <?php
                                $dataHelpContent = '<h5>Extensões Permitidas</h5> 
                                                        Somente imagems com extensão(.jpg / .gif / .png).
                                                        <br>
                                                        <h5>Tamanho Padrão</h5> 
                                                            O tamanho padrão para esta imagem é de: <br> 
                                                            196 pixels de Largura por <br>
                                                            106 pixels de Altura<br><br>
                                                            <b>Observação:</b> <i style="font-size:11px">Caso coloquem outra imagem de um tamanho diferente deste estipulados acima, 
                                                            lembre-se que o sistema adequará a imagem para que siga o padrão informado para não 
                                                            termos problemas com o layout, deixar a imagem achatada ou com qualidade inferior.</i>
                                                        <br>
                                                        ';
                                echo $this->Html->image('sys/help.png', array('width' => '22px', 'style' => 'cursor:help; margin-left:10px;', 'rel' => "popover-hover", 'data-placement' => "bottom", 'data-html' => 'true', 'data-content'  => $dataHelpContent));
                                ?>
                            </label>
                            <?php
                            $required = array();

                            if (!isset($this->data[$TABLE]['imagem'])) {
                                //                                    $required = array('required'=>'required');
                            }

                            echo '<label class=""> ' . $this->Form->input('arquivo_imagem', array_merge($required, array('label' => false, 'type' => 'file', 'div' => false, 'placeholder' => 'Imagem Principal', 'class' => 'btn btn-default'))) . '</label>';
                            echo $this->Form->hidden('imagem');
                            if (isset($this->data[$TABLE]['imagem']) && $this->data[$TABLE]['imagem'] != '' && file_exists('img/uploads/' . $this->params['controller'] . '/' . $this->data[$TABLE]['imagem'])) {
                                echo '<p style="margin-top:10px;">' . $this->Html->image('uploads/' . $this->params['controller'] . '/mini/' . $this->data[$TABLE]['imagem'], array('rel' => Router::url('/img/uploads/' . $this->params['controller'] . '/') . $this->data[$TABLE]['imagem'], 'class' => 'link_image')) . '</p>';
                            }
                            ?>
                        </section>
                    </div>



                    <header style="margin-top:20px; margin-bottom: 15px;">
                        DADOS PESSOAIS
                    </header>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label"> Sexo <?php // echo $obrigatorio;
                                                        ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('sexo', array('label' => false, 'div' => false, 'placeholder' => 'Sexo', 'class' => 'input_login', 'type' => 'select', 'options' => $sexoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">RG </label>
                            <label class="input">
                                <?php echo $this->Form->input('rg', array('label' => false, 'div' => false, 'placeholder' => '__.___.__-__', 'class' => 'rg_mask col3 margin-right-cadastre', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Nascimento</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CPF </label>
                            <label class="input">
                                <?php echo $this->Form->input('cpf', array('label' => false, 'div' => false, 'placeholder' => '___.___.___-__', 'class' => 'cpf_mask col3 margin-right-cadastre', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Nascimento</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Data de Nascimento </label>
                            <label class="input">
                                <?php echo $this->Form->date('data_nascimento', array('dateFormat' => 'DMY', 'minYear' => date('Y') - 99, 'maxYear' => date('Y') - 18, 'label' => 'Data de nascimento', 'div' => false, 'placeholder' => 'Data de Nascimento', 'dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Nascimento</b>
                            </label>
                        </section>

                    </div>






                    <!-- BEGIN - CONTATOS-->
                    <header style="margin-top:20px; margin-bottom: 15px;">
                        CONTATOS
                    </header>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone 1 </label>
                            <label class="select">
                                <?php echo $this->Form->input('tel1_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Telefone </label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('tel1', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone Comercial</b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone 2</label>
                            <label class="select">
                                <?php echo $this->Form->input('tel2_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Telefone</label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('tel2', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone 3</label>
                            <label class="select">
                                <?php echo $this->Form->input('tel3_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Telefone</label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('tel3', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone Comercial</b></label>
                        </section>
                    </div>
                    <!-- END - CONTATOS-->


                </fieldset>

                <fieldset>

                    <section style="margin-top:30px;">
                        <label class="label">
                            Observações
                            <?php
                            $dataHelpContent = '<h5> Tipo de arquivos</h5>Todas Imagens inseridas através do Editor, não pode ter acentuações e não podem ter espaços <br />
                                            <b>Exemplo</b>: "<i>Teste área .jpg</i>" <b>&rarr;</b> "<i>teste_area.jpg</i>"';
                            echo $this->Html->image('sys/help.png', array('width' => '22px', 'style' => 'cursor:help; margin-left:10px;', 'rel' => "popover-hover", 'data-placement' => "bottom", 'data-html' => 'true', 'data-content' => $dataHelpContent));
                            ?>
                        </label>
                        <label class="textarea">
                            <?php echo $this->Form->textarea('observacao', array('rows' => 2, 'style' => 'width:100%;', 'style' => 'width:100%;', 'label' => false, 'div' => false, 'placeholder' => 'Observações')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa  fa-chevron-right txt-color-blueLight"></i> Entre com a Observação</b>
                        </label>
                    </section>
                    <div class="row">
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
                    <button type="submit" class="btn btn-primary submit_ajax">
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
        //config_ckeditor (appcontroller)
        CKEDITOR.replace('data[Usuario][observacao]', {
            <?php echo str_replace('280px', '200px', $config_ckeditor); ?>
        });


        UsuarioPerfilId = $('#UsuarioPerfilId').val();
        if (UsuarioPerfilId != '') {
            exibe_empresa_cliente(UsuarioPerfilId);
            exibe_bi_usuario(UsuarioPerfilId);
        }
        $('#UsuarioPerfilId').change(function() {
            exibe_empresa_cliente($(this).val());
            exibe_bi_usuario($(this).val());
        });




        function exibe_empresa_cliente(id) {
            if (id != 1 && id != 2) {
                $('.exibe_empresas_cliente').fadeIn('slow');
            } else {
                $('.exibe_empresas_cliente').fadeOut('slow');
            }
        }

        function exibe_bi_usuario(id) {
            if (id != 1 && id != 2) {
                $('.exibe_bi_usuario').fadeIn('slow');
            } else {
                $('.exibe_bi_usuario').fadeOut('slow');
            }
        }

        $('.click_exibe_senha').click(function() {
            $('.click_exibe_senha').hide();
            $('.exibe_senha').show();
        })





        //        function valida_data(envio){
        //                if($("#UsuarioDataNascimento").val().length == 10){
        //                    var dtNasc = $("#UsuarioDataNascimento").val();
        //                    var dtNascArr = dtNasc.split('-');
        //                    var dtNow = new Date();
        //                    var anoAt = dtNow.getFullYear();
        //                    var indice;
        //                    
        //                         if(dtNascArr[0].length == 4){indice = 0;}
        //                    else if(dtNascArr[1].length == 4){indice = 1;}
        //                    else if(dtNascArr[2].length == 4){indice = 2;}
        //                    /* VALIDA DATA - MAIOR QUE 16 E MENOR QUE 100 ANOS */
        //                    if(dtNascArr[indice] > anoAt-16 || dtNascArr[indice] < anoAt-100){
        //                        alert('A Data de Nascimento está inválida!');
        //                        if(envio == true){return false;}
        //                    }else{
        //                        if(envio == true){return true;}
        //                    }
        //                }else{
        //                    alert('A Data de Nascimento está inválida!');
        //                    if(envio == true){return false;}
        //                }
        //            }
        //            
        //            $('#UsuarioDataNascimento').blur(function(){
        //                valida_data(false);
        //            });
    });
</script>