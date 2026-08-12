<?php echo $this->Element('admin/breadcrumb');?>

<div id="content">
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php
                echo $this->Form->msg($this->Session->flash());

                echo $this->Form->create(
                        $TABLE, array(
                                'type' => 'file',
                                'id' => $this->params['controller'].'-form',
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
                    <?php echo (isset($this->params['pass'][0]))? 'Edição' : 'Cadastro' ;?> de <?php echo $this->Funcoes->titulos($this->params['controller']);?>
                </header>
                <fieldset>
                    <?php 
                        $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                        echo $this->Form->hidden('id');
                        if(isset($this->data[$TABLE]['id'])):
                    ?>
                    <div class="row">
                        <section class="col col-6" >
                            <label class="label"><strong>ID:</strong> <?php echo $this->data[$TABLE]['id']; ?></label>
                        </section>
                    </div>
                    <?php endif; ?>
                    
                    <section>
                        <label class="label">Descrição <?php echo $obrigatorio;?></label>
                        <label class="input"> 
                            <?php echo $this->Form->input('descricao', array('label' => false, 'div' => false, 'placeholder' => 'Descrição', 'class' => 'input_login', 'maxlength' => '100')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Descrição </b>
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Breakeven</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('breakeven', array('label' => false, 'div' => false, 'placeholder' => 'Breakeven', 'class' => 'input_login', 'maxlength' => '2')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com Breakeven </b>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Contrato</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('contrato', array('label' => false, 'div' => false, 'placeholder' => 'Contrato', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com Contrato </b>
                            </label>
                        </section>
                    </div>
                    
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Operadora <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('operadora_id', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$operadoraArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Tipo de Beneficio <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('tipo_beneficio_id', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$tipoBeneficioArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Data de Cancelamento </label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_cancelamento', array('dateFormat' => 'DMY','label' => 'Data de Cancelamento', 'div' => false, 'placeholder' => 'Data de Nascimento','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Cancelamento</b>
                            </label>
                        </section> 
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Status <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('status', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$this->Funcoes->status(), 'default' => '1')); ?>
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

                <?php echo $this->Form->end();?>
            </div>

        </div>
    </div>
    
</div>
