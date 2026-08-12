<?php echo $this->Element('admin/breadcrumb'); ?>
<style>
    .exibir_mais_contatos,
    .ocultar_mais_contatos {
        color: blue;
    }

    .exibir_mais_contatos:hover,
    .ocultar_mais_contatos:hover {
        text-decoration: underline;
        cursor: pointer
    }
</style>

<?php

#TRATAMENTO DO RETORNO
$data['altura'] = array('value' => '');
$data['peso'] = array('value' => '');
if (isset($this->data['Beneficiario']['altura']) && $this->data['Beneficiario']['altura'] != '') {
    if ($this->data['Beneficiario']['altura'] > 100) {
        $altura = $this->data['Beneficiario']['altura'] / 100;
        $altura =  str_pad($altura, 4, 0, STR_PAD_RIGHT);
        $data['altura'] = array('value' => $altura);
    }
}
if (isset($this->data['Beneficiario']['peso']) && $this->data['Beneficiario']['peso'] != '') {
    $peso = str_replace('.', ',', $this->data['Beneficiario']['peso']);
    $data['peso'] = array('value' => $peso);
}

?>

<div id="content" style="padding-top: 50px !important;">
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

                echo $this->Funcoes->menus('geral', $permissao);
                ?>
                <header>
                    <?php echo (isset($this->params['pass'][0])) ? 'Edição' : 'Cadastro'; ?> de <?php echo $this->Funcoes->titulos($this->params['controller']); ?>
                </header>
                <fieldset>
                    <?php
                    $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                    echo $this->Form->hidden('id');
                    if (isset($this->data[$TABLE]['id']) && $this->data[$TABLE]['id'] != ''):
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


                    <!-- <div class="row">
                        <section class="col col-2">
                            <label class="label">Protocolo < ?php echo $obrigatorio;?></label>
                            <label class="input"> <i class="icon-append fa fa-info-circle"></i>
                                < ?php echo $this->Form->input('protocolo', array('label' => false, 'div' => false, 'placeholder' => 'Protocolo', 'class' => 'input_login', 'maxlength'=>'35')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-smile-o txt-color-blueLight"></i> Entre com o Protocolo</b></label>
                        </section>
                    </div> -->
                    <section>
                        <label class="label">Nome <?php echo $obrigatorio; ?></label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Nome', 'class' => 'input_login', 'maxlength' => '65')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Nome </b></label>
                    </section>
                    <section>
                        <label class="label">Nome Social</label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $this->Form->input('nome_social', array('label' => false, 'div' => false, 'placeholder' => 'Nome Social', 'class' => 'input_login', 'maxlength' => '65')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Nome Social </b></label>
                    </section>
                    <section>
                        <label class="label">Email <?php echo $obrigatorio; ?></label>
                        <label class="input"> <i class="icon-append fa fa-envelope"></i>
                            <?php echo $this->Form->input('email', array('label' => false, 'div' => false, 'placeholder' => 'Email', 'class' => 'input_login', 'maxlength' => '120')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-blueLight"></i> Entre com seu Email</b></label>
                    </section>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Empresa <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('empresa_id', array('label' => false, 'div' => false, 'placeholder' => 'Situação', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Situação </label>
                            <label class="select">
                                <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'placeholder' => 'Situação', 'class' => 'input_login', 'options' => array('' => 'Situação...', 'Ativo' => 'Ativo', 'Inativo' => 'Inativo'), 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>


                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Informações do Beneficio</h4>
                    <section>
                        <label class="label">Benefício </label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $this->Form->input('beneficio', array('label' => false, 'div' => false, 'placeholder' => 'Benefício', 'class' => 'input_login', 'maxlength' => '150')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Benefício </b></label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Valor (R$)</label>
                            <label class="input">
                                <?php echo $this->Form->input('valor_do_seguro', array('type' => 'text', 'data-thousands' => '.', 'data-decimal' => ',', 'label' => false, 'div' => false, 'placeholder' => 'Valor', 'class' => 'input_login money_mask', 'maxlength' => '20')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Valor</b>
                            </label>
                        </section>
                    </div>


                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Dados Pessoais</h4>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">CPF <?php echo $obrigatorio; ?></label>
                            <label class="input">

                                <?php
                                $rel_cpf = '';
                                if (isset($this->data['Beneficiario']['cpf']) && $this->data['Beneficiario']['cpf'] != '') {
                                    $rel_cpf = $this->data['Beneficiario']['cpf'];
                                }
                                echo $this->Form->input('cpf', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '___.___.___-__', 'class' => 'col3 margin-right-cadastre cpf_mask valida_cpf', 'rel_cpf' => $rel_cpf, 'maxlength' => '15'));
                                ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o CPF</b>
                            </label>
                            <spam class="note cpf_invalido_aviso" style="color:red;"></spam>
                        </section>
                        <section class="col col-2">
                            <label class="label">RG </label>
                            <label class="input">
                                <?php echo $this->Form->input('rg', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '__.___.__-__', 'class' => 'col3 margin-right-cadastre rg_mask', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Nascimento</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label"> Sexo <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('sexo', array('label' => false, 'div' => false, 'placeholder' => 'Sexo', 'class' => 'input_login', 'type' => 'select', 'options' => $sexoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label"> Estado Civil </label>
                            <label class="select">
                                <?php echo $this->Form->input('estado_civil', array('label' => false, 'div' => false, 'placeholder' => 'Sexo', 'class' => 'input_login', 'type' => 'select', 'options' => $estadoCivilArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>

                        <section class="col col-2">
                            <label class="label">Data de Nascimento <?php echo $obrigatorio; ?></label>
                            <label class="input">
                                <?php echo $this->Form->date('data_nascimento', array('dateFormat' => 'DMY', 'minYear' => date('Y') - 99, 'maxYear' => date('Y') - 18, 'label' => 'Data de nascimento', 'div' => false, 'placeholder' => 'Data de Nascimento', 'dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Nascimento</b>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Altura </label>
                            <label class="input"> <i class="icon-append fa fa-male"></i>
                                <?php echo $this->Form->input('altura', array_merge(array('type' => 'text', 'name' => 'data[Beneficiario][altura]', 'label' => false, 'div' => false, 'placeholder' => '_,__', 'class' => 'altura_mask col3', 'maxlength' => '4'), $data['altura'])); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Altura</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Peso </label>
                            <label class="input"> <i class="icon-append fa fa-dashboard"></i>
                                <?php echo $this->Form->input('peso', array_merge(array('type' => 'text', 'name' => 'data[Beneficiario][peso]', 'label' => false, 'div' => false, 'placeholder' => '___,_', 'class' => 'peso_mask col3', 'maxlength' => '6'), $data['peso'])); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Peso</b>
                            </label>
                        </section>
                    </div>


                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Endereço</h4>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Endereço Completo </label>
                            <label class="input">
                                <?php echo $this->Form->input('endereco', array('label' => false, 'div' => false, 'placeholder' => 'Endereço', 'class' => 'input_login', 'maxlength' => '200')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Endereço </b></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Bairro </label>
                            <label class="input">
                                <?php echo $this->Form->input('bairro', array('label' => false, 'div' => false, 'placeholder' => 'Bairro', 'class' => 'input_login', 'maxlength' => '150')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Bairro </b></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Cidade </label>
                            <label class="input">
                                <?php echo $this->Form->input('cidade', array('label' => false, 'div' => false, 'placeholder' => 'Cidade', 'class' => 'input_login', 'maxlength' => '60')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com a Cidade </b></label>
                        </section>
                        <section class="col col-1">
                            <label class="label">Estado </label>
                            <label class="input">
                                <?php echo $this->Form->input('estado', array('label' => false, 'div' => false, 'placeholder' => 'Estado', 'class' => 'input_login', 'maxlength' => '2')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Estado </b></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CEP </label>
                            <label class="input">
                                <?php echo $this->Form->input('cep', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => 'CEP', 'class' => 'input_login cep_mask', 'maxlength' => '9')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o CEP </b></label>
                        </section>

                    </div>

                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Dados Bancários</h4>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Agência </label>
                            <label class="input">
                                <?php echo $this->Form->input('agencia', array('label' => false, 'div' => false, 'placeholder' => 'Agência', 'class' => 'input_login', 'maxlength' => '50')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com a Agência </b></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Conta </label>
                            <label class="input">
                                <?php echo $this->Form->input('conta', array('label' => false, 'div' => false, 'placeholder' => 'Conta', 'class' => 'input_login', 'maxlength' => '50')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com a Conta </b></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Tipo de Conta </label>
                            <label class="input">
                                <?php echo $this->Form->input('tipo_de_conta', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Conta', 'class' => 'input_login', 'maxlength' => '50')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Tipo de Conta </b></label>
                        </section>
                    </div>


                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Informações Profissionais</h4>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Profissão </label>
                            <label class="input">
                                <?php echo $this->Form->input('profissao', array('label' => false, 'div' => false, 'placeholder' => 'Profissão', 'class' => 'input_login', 'maxlength' => '50')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com a Profissão </b></label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Ocupação </label>
                            <label class="input">
                                <?php echo $this->Form->input('ocupacao', array('label' => false, 'div' => false, 'placeholder' => 'Ocupação', 'class' => 'input_login', 'maxlength' => '50')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com a Ocupação </b></label>
                        </section>

                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Pessoa Politicamente Exposta? </label>
                            <label class="input">
                                <?php echo $this->Form->input('pessoa_politicamente_exposta', array('label' => false, 'div' => false, 'placeholder' => 'Pessoa Politicamente Exposta', 'class' => 'input_login', 'maxlength' => '5')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com a se é Politicamente Exposta </b></label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Realiza alguma atividade perigosa na profissao? </label>
                            <label class="input">
                                <?php echo $this->Form->input('realiza_alguma_atividade_perigosa_na_profissao', array('label' => false, 'div' => false, 'placeholder' => 'Realiza Atividade Perigosa', 'class' => 'input_login', 'maxlength' => '5')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com a se é Politicamente Exposta </b></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Possui deficiência física? </label>
                            <label class="input">
                                <?php echo $this->Form->input('possui_deficiencia_fisica', array('label' => false, 'div' => false, 'placeholder' => 'Possui deficiência física', 'class' => 'input_login', 'maxlength' => '5')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com a informação se Possui deficiência física </b></label>
                        </section>
                    </div>



                    <!-- BEGIN - CONTATOS-->
                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Contatos</h4>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone Principal <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('telefone_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Telefone Principal <?php echo $obrigatorio; ?></label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('telefone', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone 1 </label>
                            <label class="select">
                                <?php echo $this->Form->input('telefone1_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Telefone 1 </label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('telefone1', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone 2</label>
                            <label class="select">
                                <?php echo $this->Form->input('telefone2_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Telefone 2</label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('telefone2', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu </b></label>
                        </section>
                    </div>


                    <?php
                    $display_mais = 'display:none;';
                    if (
                        @$this->data[$TABLE]['telefone3'] != '' ||
                        @$this->data[$TABLE]['telefone4'] != '' ||
                        @$this->data[$TABLE]['telefone5'] != '' ||
                        @$this->data[$TABLE]['telefone6'] != '' ||
                        @$this->data[$TABLE]['telefone7'] != '' ||
                        @$this->data[$TABLE]['telefone8'] != '' ||
                        @$this->data[$TABLE]['telefone9'] != ''
                    ) {
                        $display_mais = '';
                    } else {
                        echo '<div class="rows"><span class="exibir_mais_contatos">Abrir mais contatos...</span></div>';
                    }
                    ?>

                    <div class="mais_contatos" style="<?php echo $display_mais; ?>">
                        <div class="row">
                            <section class="col col-2">
                                <label class="label">Telefone 3</label>
                                <label class="select">
                                    <?php echo $this->Form->input('telefone3_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label">Telefone 3</label>
                                <label class="input"> <i class="icon-append fa fa-phone"></i>
                                    <?php echo $this->Form->input('telefone3', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-2">
                                <label class="label">Telefone 4</label>
                                <label class="select">
                                    <?php echo $this->Form->input('telefone4_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label">Telefone 4</label>
                                <label class="input"> <i class="icon-append fa fa-phone"></i>
                                    <?php echo $this->Form->input('telefone4', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-2">
                                <label class="label">Telefone 5</label>
                                <label class="select">
                                    <?php echo $this->Form->input('telefone5_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label">Telefone 5</label>
                                <label class="input"> <i class="icon-append fa fa-phone"></i>
                                    <?php echo $this->Form->input('telefone5', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-2">
                                <label class="label">Telefone 6</label>
                                <label class="select">
                                    <?php echo $this->Form->input('telefone6_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label">Telefone 6</label>
                                <label class="input"> <i class="icon-append fa fa-phone"></i>
                                    <?php echo $this->Form->input('telefone6', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-2">
                                <label class="label">Telefone 7</label>
                                <label class="select">
                                    <?php echo $this->Form->input('telefone7_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label">Telefone 7</label>
                                <label class="input"> <i class="icon-append fa fa-phone"></i>
                                    <?php echo $this->Form->input('telefone7', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-2">
                                <label class="label">Telefone 8</label>
                                <label class="select">
                                    <?php echo $this->Form->input('telefone8_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label">Telefone 8</label>
                                <label class="input"> <i class="icon-append fa fa-phone"></i>
                                    <?php echo $this->Form->input('telefone8', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-2">
                                <label class="label">Telefone 9</label>
                                <label class="select">
                                    <?php echo $this->Form->input('telefone9_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options' => $telTipoArr, 'default' => '')); ?>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label">Telefone 9</label>
                                <label class="input"> <i class="icon-append fa fa-phone"></i>
                                    <?php echo $this->Form->input('telefone9', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength' => '15')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone </b></label>
                            </section>
                        </div>
                    </div>
                    <!-- END - CONTATOS-->


                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Beneficiários</h4>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Beneficiario 1</label>
                            <label class="input"> <i class="icon-append fa fa-user"></i>
                                <?php echo $this->Form->input('beneficiario1', array('label' => false, 'div' => false, 'placeholder' => 'Beneficiário', 'class' => 'input_login', 'maxlength' => '150')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Beneficiário </b></label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Parentesco 1</label>
                            <label class="input">
                                <?php echo $this->Form->input('parentesco1', array('label' => false, 'div' => false, 'placeholder' => 'Parentesco', 'class' => 'input_login', 'maxlength' => '60')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Parentesco </b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Beneficiario 2</label>
                            <label class="input"> <i class="icon-append fa fa-user"></i>
                                <?php echo $this->Form->input('beneficiario2', array('label' => false, 'div' => false, 'placeholder' => 'Beneficiário', 'class' => 'input_login', 'maxlength' => '150')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Beneficiário </b></label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Parentesco 2</label>
                            <label class="input">
                                <?php echo $this->Form->input('parentesco2', array('label' => false, 'div' => false, 'placeholder' => 'Parentesco', 'class' => 'input_login', 'maxlength' => '60')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Parentesco </b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Beneficiario 3</label>
                            <label class="input"> <i class="icon-append fa fa-user"></i>
                                <?php echo $this->Form->input('beneficiario3', array('label' => false, 'div' => false, 'placeholder' => 'Beneficiário', 'class' => 'input_login', 'maxlength' => '150')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Beneficiário </b></label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Parentesco 3</label>
                            <label class="input">
                                <?php echo $this->Form->input('parentesco3', array('label' => false, 'div' => false, 'placeholder' => 'Parentesco', 'class' => 'input_login', 'maxlength' => '60')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Parentesco </b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Beneficiario 4</label>
                            <label class="input"> <i class="icon-append fa fa-user"></i>
                                <?php echo $this->Form->input('beneficiario4', array('label' => false, 'div' => false, 'placeholder' => 'Beneficiário', 'class' => 'input_login', 'maxlength' => '150')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Beneficiário </b></label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Parentesco 4</label>
                            <label class="input">
                                <?php echo $this->Form->input('parentesco4', array('label' => false, 'div' => false, 'placeholder' => 'Parentesco', 'class' => 'input_login', 'maxlength' => '60')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Parentesco </b></label>
                        </section>
                    </div>


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

                    <?php if (isset($this->data[$TABLE]['id']) && $this->data[$TABLE]['id'] != ''): ?>
                        <?php if (isset($this->data['UsuarioAtualizacao']['nome'])): ?>
                            <section>
                                <label>
                                    <strong>Criado por:</strong> <i><?php echo $this->data['UsuarioCriador']['nome']; ?></i> <strong>data:</strong> <?php echo $this->DateTime->dbToView($this->data[$TABLE]['data_atualizacao']); ?>
                                </label>
                            </section>
                        <?php endif; ?>
                        <?php if (isset($this->data['UsuarioAtualizacao']['nome'])): ?>
                            <section>
                                <label>
                                    <strong>Atualizado por:</strong> <i><?php echo $this->data['UsuarioAtualizacao']['nome']; ?></i> <strong>data:</strong> <?php echo $this->DateTime->dbToView($this->data[$TABLE]['data_atualizacao']); ?>
                                </label>
                            </section>
                        <?php endif; ?>
                    <?php endif; ?>
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
        //       CKEDITOR.replace( 'data[Beneficiario][observacao]', {< ?php echo str_replace('280px','200px',$config_ckeditor);?>});
        $('.exibir_mais_contatos').click(function() {
            $('.mais_contatos').fadeIn('slow');
            $(this).parent().html('');
        });


        function valida_cpf(cpf = '', rel_cpf = '', atribute = '.valida_cpf', atribute_msg = '.cpf_invalido_aviso') {
            return new Promise(async (resolve) => {
                try {
                    $(atribute).attr('style', '');
                    $(atribute).removeClass('invalid');
                    $(atribute_msg).html('');
                    cpf_original = cpf;
                    cpf = cpf.replace(/[.-]/g, '');
                    rel_cpf = rel_cpf.replace(/[.-]/g, '');
                    let retorno = false;

                    if (isValidNumber(cpf) && cpf !== rel_cpf) {
                        const cod_return = await busca_beneficiario(cpf);
                        const codigos_validos = [0, 1, 2];

                        if (codigos_validos.includes(cod_return.status)) {
                            if (cod_return.status > 0) {
                                $(atribute).attr('style', 'background-color:rgb(255 240 240);');
                                $(atribute).addClass('invalid');
                                let msg = cod_return.message;
                                $(atribute_msg).html(msg);
                                $(atribute).focus();
                                resolve(false); // CPF inválido
                            } else {
                                resolve(true); // CPF válido
                            }
                        } else {
                            $(atribute).attr('style', 'background-color:rgb(255 240 240);');
                            $(atribute).addClass('invalid');
                            let msg = 'Erro ao verificar o CPF, tente novamente mais tarde!';
                            $(atribute_msg).html(msg);
                            $(atribute).focus();
                            resolve(false);
                        }
                    }else if (isValidNumber(cpf) && cpf === rel_cpf) {
                        resolve(true); // CPF válido
                    } else {
                        if (cpf !== '') {
                            $(atribute).attr('style', 'background-color:rgb(255 240 240);');
                            $(atribute).addClass('invalid');
                            let msg = 'CPF inválido';
                            $(atribute_msg).html(msg);
                            $(atribute).focus();
                        }
                        resolve(false);
                    }
                } catch (error) {
                    $(atribute).attr('style', 'background-color:rgb(255 240 240);');
                    $(atribute).addClass('invalid');
                    let msg = 'Erro na verificação do CPF!';
                    $(atribute_msg).html(msg);
                    $(atribute).focus();
                    resolve(false);
                }
            });
        }



        function isValidNumber(variable) {
            return /^[0-9]{11}$/.test(variable);
        }

        function busca_beneficiario(cpf) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "<?php echo Router::url(array('action' => 'busca_beneficiario'), true); ?>",
                    data: {
                        'cpf': cpf
                    },
                    dataType: "json",
                    type: 'POST',
                    cache: false,
                    statusCode: {
                        403: function() {
                            alert(' Desculpe, você perdeu sua sessão, logue-se novamente!');
                            location.reload(true);
                        },
                        404: function() {
                            alert(' Desculpe, a página solicitada não foi encontrada!');
                        },
                        500: function() {
                            alert(' Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!');
                            location.reload(true);
                        }
                    },
                    success: function(data) {
                        resolve(data); // Retorna o valor na promessa
                    },
                    error: function(err) {
                        reject(err); // Retorna o erro na promessa
                    }
                });
            });
        }


        //chamada via blur
        $('.valida_cpf').blur(function() {
            let cpf = $(this).val();
            let rel_cpf = $('.valida_cpf').attr('rel_cpf');
            let retorno = valida_cpf(cpf, rel_cpf, '.valida_cpf');
        })




        //chamada no SubmitEvent
        $('#beneficiario-form').submit(async function(event) {
            event.preventDefault(); // Evita o envio imediato do formulário

            if (!$(this).valid()) {
                return false; // Validação do formulário usando o jQuery Validate
            }

            let cpf = $('.valida_cpf').val();
            let rel_cpf = $('.valida_cpf').attr('rel_cpf');

            try {
                let retorno = await valida_cpf(cpf, rel_cpf, '.valida_cpf'); // Aguarda a validação do CPF
                console.log(retorno);
                if (retorno === false) {
                    
                    return false; // CPF inválido, impede o envio
                } else {
                    this.submit(); // Envia o formulário
                }
            } catch (error) {
                console.error('Erro ao validar o CPF:', error);
                return false;
            }
        });


        /*VALIDAR CPF NOVAMENTE*/
        // $('#relatorio-form').submit(function(){
        //     mes            = $('#RelatorioMes').val();
        //     ano            = $('#RelatorioAno').val();
        //     if(mes != '' && ano != ''){
        //         mes_ano = ano+'-'+mes+'-1';
        //         retorno = verifica_competencia(mes_ano);
        //         return retorno;
        //     }else{
        //         return false;
        //     }

        // });


    });









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
    //     });
</script>