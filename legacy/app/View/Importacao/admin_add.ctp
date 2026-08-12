<?php echo $this->Element('admin/breadcrumb'); ?>
<?php echo $this->Element('admin/loading_overlay'); ?>
<div id="content">
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php
                echo $this->Form->msg($this->Session->flash());

                echo $this->Form->create(
                    'Importacao',
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
                    <?php echo (isset($this->params['pass'][0])) ? 'Edição' : 'Cadastro'; ?> de <?php echo $this->Funcoes->titulos($this->params['controller']);  ?>
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label"><strong>Cliente:</strong> <?php echo $_SESSION['selectCliente'][$cliente_id]; ?></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Tipo de Importação <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('tipo_importacao', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Importacao', 'class' => 'input_login', 'options' => $tipoImportacaoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row exibe_sinistro" style="display:none;">
                        <section class="col col-4">
                            <label class="label">Beneficio <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('beneficio_id', array('label' => false, 'div' => false, 'placeholder' => 'Benefício', 'class' => 'input_login', 'options' => $beneficioArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Mês <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('competencia_mes', array('label' => false, 'div' => false, 'placeholder' => 'Competência Mês', 'class' => 'input_login', 'options' => $mesArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Ano <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('competencia_ano', array('label' => false, 'div' => false, 'placeholder' => 'Competência Ano', 'class' => 'input_login', 'options' => $anoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Valor (R$)</label>
                            <label class="input">
                                <?php echo $this->Form->input('valor', array('type' => 'text', 'data-thousands' => '.', 'data-decimal' => ',', 'label' => false, 'div' => false, 'placeholder' => 'Valor', 'class' => 'input_login money_mask', 'maxlength' => '20')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Valor</b>
                            </label>
                        </section>
                    </div>
                    <div class="row exibe_fatura" style="display:none;">

                    </div>
                    <div class="row" style="margin-top: 30px; margin-bottom: 10px; ">
                        <section class="col col-12" style="margin-bottom: 50px;">
                            <label class="label"><i class="fa fa-lg  fa-file-o"></i>
                                Arquivo <?php echo $obrigatorio; ?>
                            </label>
                            <?php
                            echo '<label class=""> ' . $this->Form->input('arquivo', array_merge(array('label' => false, 'type' => 'file', 'div' => false, 'placeholder' => 'Arquivo XLS', 'class' => 'btn btn-default'))) . '</label>';
                            echo $this->Form->hidden('file');
                            ?>
                        </section>
                    </div>
                    <div class="row" style="">
                        <section class="col col-12" style="margin-bottom: 50px;">
                            <label class="label">
                                Download: <?php echo $this->Html->link('Modelo', Router::url('/files/uploads/importacao/modelo/todos_layouts_atualizado_v9.xlsx', true)); ?>

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
        // Loading da importacao e acionado no submitHandler do jQuery Validate (admin.ctp),
        // somente apos campos obrigatorios validos.

        $('#ImportacaoTipoImportacao').change(function() {
            tipo = $(this).val();
            if (tipo == 'sinistro') {
                $('.exibe_sinistro').fadeIn('slow');
            } else if (tipo == 'fatura') {
                $('.exibe_sinistro').fadeIn('slow');
            } else {
                $('.exibe_sinistro').fadeOut('slow');
            }
        })
    });
</script>