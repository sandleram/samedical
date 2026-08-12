<?php echo $this->Element('admin/breadcrumb'); ?>

<div id="content">
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php
                echo $this->Form->msg($this->Session->flash());

                echo $this->Form->create(
                        $TABLE, array(
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
                echo $this->Funcoes->menus('geral',$permissao);
                ?>

                <header>
                    <?php echo (isset($this->params['pass'][0])) ? 'Edição' : 'Cadastro'; ?> de <?php echo $this->Funcoes->titulos($this->params['controller']); ?>
                </header>
                <fieldset>
                    <?php
                    $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                    echo $this->Form->hidden('id');
                    if (isset($this->data[$TABLE]['id'])):
                        ?>
                        <div class="row">
                            <section class="col col-6" >
                                <label class="Bold"><strong>ID: </strong></label>
                                <label> <?php echo $this->data[$TABLE]['id']; ?></label>
                            </section>
                        </div>
                    <?php endif; ?>

                    
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Nome <?php echo $obrigatorio; ?></label>
                            <label class="input"> 
                                <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Empresa', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com nome da sua Empresa</b>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Razao Social</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('razao_social', array('label' => false, 'div' => false, 'placeholder' => 'Razao Social', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com sua razao social</b>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Nome Fantasia</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('nome_fantasia', array('label' => false, 'div' => false, 'placeholder' => 'Nome Fantasia', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o Nome Fantasia</b>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">CNPJ <?php echo $obrigatorio; ?></label>
                            <label class="input"> 
                                <?php echo $this->Form->input('cnpj', array('label' => false, 'div' => false, 'placeholder' => 'CNPJ', 'class' => 'cnpj_mask input_login' , 'maxlength'=>'19') ); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu CNPJ</b>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-5">
                            <label class="label">Inscrição Estadual</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('inscricao_estadual', array('label' => false, 'div' => false, 'placeholder' => 'Inscrição Estadual', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a Inscrição Estadual</b>
                            </label>
                        </section>
                        <section class="col col-5">
                            <label class="label">Inscrição Municipal</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('inscricao_municipal', array('label' => false, 'div' => false, 'placeholder' => 'Inscrição Municipal', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a Inscrição Municipal</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Número de Funcionários</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('numero_funcionarios', array('label' => false, 'div' => false, 'placeholder' => 'Número de Funcionários', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o Número de Funcionários</b>
                            </label>
                        </section>
                    </div>

                    <section>
                        <label class="label">Descrição</label>
                        <label class="input">
                            <?php echo $this->Form->textarea('descricao', array('rows' => 5, 'style' => 'width:100%;', 'label' => false, 'div' => false, 'placeholder' => 'Descrição', 'class' => 'input_login')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a Descrição</b>
                        </label>
                    </section> 

                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Tipo:</label>
                            <label class="select">
                                <?php echo $this->Form->input('tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'type' => 'select', 'options' => $tipoArr, 'default' => '')); ?>
                                <i></i>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a Tipo</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Porte:</label>
                            <label class="select">
                                <?php echo $this->Form->input('porte', array('label' => false, 'div' => false, 'placeholder' => 'Porte', 'class' => 'input_login', 'type' => 'select', 'options' => $porteArr, 'default' => '')); ?>
                                <i></i>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o Porte</b>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Faturamento:</label>
                            <label class="select">
                                <?php echo $this->Form->input('faturamento', array('label' => false, 'div' => false, 'placeholder' => 'Faturamento', 'class' => 'input_login', 'type' => 'select', 'options' => $faturamentoArr, 'default' => '')); ?>
                                <i></i>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o Faturamento</b>
                            </label>
                        </section>

                    </div>

                    <div class="row">
                        <section class="col col-8">
                            <label class="label">Endereço</label>
                            <label class="input"> <i class="icon-append fa fa-home"></i>
                                <?php echo $this->Form->input('endereco', array('label' => false, 'div' => false, 'placeholder' => 'Endereço', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu endereco</b></label>
                        </section>
                        <section class="col col-1">
                            <label class="label">Número</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('numero', array('label' => false, 'div' => false, 'placeholder' => 'Número', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu numero</b></label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Complemento</label>
                            <label class="input">
                                <?php echo $this->Form->input('complemento', array('label' => false, 'div' => false, 'placeholder' => 'Complemento', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu complemento</b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Bairro</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('bairro', array('label' => false, 'div' => false, 'placeholder' => 'Bairro', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu bairro</b></label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Cidade</label>
                            <label class="input">
                                <?php echo $this->Form->input('cidade', array('label' => false, 'div' => false, 'placeholder' => 'Cidade', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu cidade</b></label>
                        </section>
                        <section class="col col-1">
                            <label class="label">Estado</label>
                            <label class="input">
                                <?php echo $this->Form->input('estado', array('label' => false, 'div' => false, 'placeholder' => 'Estado', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu estado</b></label>
                        </section>
                        <section class="col col-3">
                            <label class="label">CEP</label>
                            <label class="input">
                                <?php echo $this->Form->input('cep', array('label' => false, 'div' => false, 'placeholder' => 'CEP', 'class' => 'input_login cep_mask')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu cep</b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone</label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('telefone', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu telefone</b></label>
                        </section>
                        <section class="col col-4">
                            <label class="label">E-mail</label>
                            <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                <?php echo $this->Form->input('email', array('label' => false, 'div' => false, 'placeholder' => 'E-mail', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu email</b></label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Site</label>
                            <label class="input"> <i class="icon-append fa fa-home"></i>
                                <?php echo $this->Form->input('site', array('label' => false, 'div' => false, 'placeholder' => 'Site', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com seu site</b></label>
                        </section>
                    </div>

                  





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
    $(document).ready(function () {
        //config_ckeditor (appcontroller)
        CKEDITOR.replace('data[Empresa][descricao]', {<?php echo $config_ckeditor; ?>});
        
    });
</script>

