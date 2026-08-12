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

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Beneficio <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('beneficio_id', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$beneficioArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <section>
                        <label class="label">Descrição <?php echo $obrigatorio;?></label>
                        <label class="input"> 
                            <?php echo $this->Form->input('descricao', array('label' => false, 'div' => false, 'placeholder' => 'Descrição', 'class' => 'input_login', 'maxlength' => '450')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Descrição </b>
                        </label>
                    </section>
                    <section>
                        <label class="label">Código <?php echo $obrigatorio;?></label>
                        <label class="input"> 
                            <?php echo $this->Form->input('codigo', array('label' => false, 'div' => false, 'placeholder' => 'Código', 'class' => 'input_login', 'maxlength' => '45')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Código </b>
                        </label>
                    </section>
                    
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
