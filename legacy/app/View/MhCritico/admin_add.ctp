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
                    if (isset($this->data[$TABLE]['id'])) :
                    ?>
                        <div class="row">
                            <section class="col col-6">
                                <label class="Bold"><strong>ID: </strong></label>
                                <label> <?php echo $this->data[$TABLE]['id']; ?></label>
                            </section>

                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Prestador Principal? <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('principal', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login prestador_principal', 'options' => ['' => 'Selecione...', 0 => 'Não', 1 => 'Sim'], 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row list_prestador " style="display:none;">
                        <?php
                        $listPrincipalAtual = $listPrestadorSemUsados;
                        if (isset($this->data[$TABLE]['id'])) :
                            #ARRUMAR
                            $listPrincipalAtual = $listPrestadorAll;
                        endif;
                        ?>
                        <section class="col col-4">
                            <label class="label">Prestador Principal<?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('mh_prestador_principal', array('label' => false, 'div' => false, 'placeholder' => 'Prestador', 'class' => 'input_login', 'options' => $listPrincipalAtual, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row list_prestador_opcoes" style="display:none;">
                        <?php
                        $listPrincipalAtual = $listPrestadorSemUsados;
                        $listPrincipalOpcAtual = $listPrestadorSemUsados;
                        if (isset($this->data[$TABLE]['id'])) :
                            #ARRUMAR
                            $listPrincipalAtual = $listPrestadorAll;
                            $listPrincipalOpcAtual = $listPrestadorAll;
                        endif;
                        ?>
                        <section class="col col-4">
                            <label class="label">Prestador Principal<?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('mh_prestador_principal', array('label' => false, 'div' => false, 'placeholder' => 'Prestador', 'class' => 'input_login', 'options' => $listPrincipalAtual, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Prestador Opção<?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('mh_prestador_id', array('label' => false, 'div' => false, 'placeholder' => 'Prestador', 'class' => 'input_login', 'options' => $listPrincipalOpcAtual, 'default' => '')); ?>
                                <i></i>
                            </label>

                        </section>
                        <section class="col col-4">
                            <label class="label">Opção </label>
                            <label class="select">
                                <?php echo $this->Form->input('opcao', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => $ArrOpcao, 'default' => '')); ?>
                                <i></i>
                            </label>
                            <span class="note list_usados">1 - ALVORADA FISIOTERAPIA E PILATES </span>
                        </section>
                    </div>
<!-- 
                    <section>
                        <label class="label">Nome <?php echo $obrigatorio; ?></label>
                        <label class="input">
                            < ?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Nome', 'class' => 'input_login')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o nome</b>
                        </label>
                    </section> -->

                    <!-- <div class="row">
                        <section class="col col-4">
                            <label class="label">Opção </label>
                            <label class="select">
                            < ?php echo $this->Form->input('opcao', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => $ArrOpcao, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Ciclo </label>
                            <label class="select">
                                < ?php echo $this->Form->input('ciclo', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => $ArrCiclo, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        
                        <section class="col col-4">
                            <label class="label">Status Ciclo </label>
                            <label class="select">
                                < ?php echo $this->Form->input('status_ciclo', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => $ArrStatusCiclo, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div> -->
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
    $(document).ready(function() {
        $('.prestador_principal').change(function() {
            valor_principal = $(this).val();
            if (valor_principal == 1) {
                //SIM  Buscar todos prestadores da lista que não forma cadastrados 
                $('.list_prestador').show();
                $('.list_prestador_opcoes').hide();
            } else if (valor_principal == 0) {
                $('.list_prestador_opcoes').show();
                $('.list_prestador').hide();
            } else {
                //Não - Buscar todos prestadores da list aque 
                $('.list_prestador').hide();
                $('.list_prestador_opcoes').hide();
            }
        });


        // ajax 
        

    });
</script>