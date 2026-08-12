<?php // echo $this->Element('admin/breadcrumb');?>
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
                                    'action' => 'cadastrese',
                                    $this->params['pass'][0]
                                ),
                                'class' => 'smart-form client-form '
                        )
                );
                    echo $this->Form->msg($this->Session->flash());
//                echo $this->Funcoes->aviso_dinamico($this->Session->flash('front'));
//                    echo $this->Funcoes->menus('geral',$permissao);
                ?>
                <header>
                    Cadastro de Aluno
                </header>
                <fieldset>
                    <?php 
                        $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                        echo $this->Form->hidden('id');
                        if(isset($this->data[$TABLE]['id']) && $this->data[$TABLE]['id'] != ''):
                    ?>
                    <div class="row">
                        <section class="col col-6" >
                            <label class="label"><strong>ID:</strong> <?php echo $this->data[$TABLE]['id']; ?></label>
                        </section>
                        <section class="col col-6">
                            <label class="label" style="text-align: right;"><strong>Criado por:</strong> <i><?php echo $this->data['UsuarioCriador']['nome']; ?></i></label>
                        </section>
                    </div>
                    <?php endif; ?>
                    <?php // echo $this->Form->hidden('perfil_id', array('value'=>6)); ?>
                    <?php // echo $this->Form->hidden('empresa_id', array('value'=>1)); ?>
                    
                    
                    
                    
                    
                    <header style="margin-top:10px; margin-bottom: 15px;">
                        Informações Pessoais
                    </header>
                    <section>
                        <label class="label">Nome <?php echo $obrigatorio;?></label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Nome', 'class' => 'input_login', 'maxlength'=>'65')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com o Nome </b></label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label"> Sexo </label>
                            <label class="select">
                            <?php echo $this->Form->input('sexo', array('label' => false, 'div' => false, 'placeholder' => 'Sexo', 'class' => 'input_login', 'type' => 'select', 'options' => $sexoArr, 'default'=>'')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CPF <?php echo $obrigatorio;?></label>
                            <label class="input">
                                <?php echo $this->Form->input('cpf', array('label' => false, 'div' => false, 'placeholder' => '___.___.___-__', 'class' => 'cpf_mask col3 margin-right-cadastre', 'maxlength' => '15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Nascimento</b>
                            </label>
                        </section> 
                    </div>
                    
                    <section>
                        <label class="label">Email <?php echo $obrigatorio;?></label>
                        <label class="input"> <i class="icon-append fa fa-envelope"></i>
                            <?php echo $this->Form->input('email', array('label' => false, 'div' => false, 'placeholder' => 'Email', 'class' => 'input_login', 'maxlength'=>'120')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-blueLight"></i> Entre com seu Email</b></label>
                    </section>
                    
                    <!-- BEGIN - CONTATOS-->
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone 1 <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('tel1_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options'=>$telTipoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label"> <?php echo $obrigatorio;?></label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('tel1', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength'=>'15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone 1</b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone 2 </label>
                            <label class="select">
                                <?php echo $this->Form->input('tel2_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options'=>$telTipoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">&nbsp; </label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('tel2', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength'=>'15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone 2</b></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone 3 </label>
                            <label class="select">
                                <?php echo $this->Form->input('tel3_tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options'=>$telTipoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">&nbsp; </label>
                            <label class="input"> <i class="icon-append fa fa-phone"></i>
                                <?php echo $this->Form->input('tel3', array('label' => false, 'div' => false, 'placeholder' => 'Telefone', 'class' => 'input_login tel_mask', 'maxlength'=>'15')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-phone txt-color-blueLight"></i> Entre com seu Telefone 3</b></label>
                        </section>
                    </div>
                   
                    
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Como Chegou até nós? <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('origem', array('label' => false, 'div' => false, 'placeholder' => 'Contato Realizado?', 'class' => 'input_login', 'options'=>$origemArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <!-- END - CONTATOS-->
                    
                    
                    
                    
                    <header style="margin-top:20px; margin-bottom: 15px;">
                        Curso
                    </header>
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Curso <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('curso_id', array('label' => false, 'div' => false, 'placeholder' => 'Contato Realizado?', 'class' => 'input_login', 'options'=>$cursoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                        <section class="col col-3">
                            <label class="label">Faculdade (Unidade) <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php 
//                                    $value_empresa = '';
//                                    if(isset($this->data['Usuario']['empresa_id'])){
//                                        $value_empresa = $this->data['Usuario']['empresa_id'];
//                                    }
                                    echo $this->Form->hidden('empresa', array('value' => $this->params['pass'][0]));
                                    
                                ?>
                                <?php echo $this->Form->input('empresa_id', array('label' => false, 'div' => false, 'placeholder' => 'Contato Realizado?', 'class' => 'input_login', 'options'=>array(''=>'Selecione o Curso...'), 'default' => '0')); ?>
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

                <?php echo $this->Form->end();?>
            </div>

        </div>
    </div>
    
</div>




