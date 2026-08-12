<?php echo $this->Element('admin/breadcrumb'); ?>

<div id="content">
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php
                echo $this->Form->msg($this->Session->flash());
                $beneficiario_id = $this->params['pass'][0];
                $id = '';
                if(isset($this->params['pass'][1])){
                    $id = $this->params['pass'][1];

                }
                #krumo($this->data);
                echo $this->Form->create(
                        $TABLE, array(
                    'type' => 'file',
                    'id' => $this->params['controller'] . '-form',
                    'url' => array(
                        'controller' => $this->params['controller'],
                        'action' => 'add',
                        $beneficiario_id,
                        $id
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
                        
                        <li><a href="<?php echo Router::url(array('controller'=>'beneficiario', 'action'=>'view',$this->params['pass'][0]),true); ?>">Voltar para  <?php echo explode(' ',$benef['Beneficiario']['nome'])[0];?></a></li>
                        <li><a href="<?php echo Router::url(array('controller'=>'beneficiario'),true); ?>">Lista de Beneficiários </a></li>
                        <?php if (isset($this->data[$TABLE]['id'])):?>
                        <li class="divider"></li>
                        <li class="bg-color-red"><a href="<?php echo Router::url(array('controller'=>'absenteismo', 'action'=>'delete',$this->params['pass'][0],$this->params['pass'][1]),true); ?>" class="ajaxMsg" ajaxmsg="Tem certeza que deseja excluir o absenteísmo ID: <?php echo $this->params['pass'][0];?>" style="color:white">Excluir Absenteísmo</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <header>
                    <?php echo (isset($this->params['pass'][1])) ? 'Edição' : 'Cadastro'; ?> de <?php echo $this->Funcoes->titulos($this->params['controller']); ?>
                </header>
                <fieldset>
                    <?php
                    $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                    
                    echo $this->Form->hidden('importacao_id');
                    echo $this->Form->hidden('beneficiario_id',array('value'=>$beneficiario_id));
                    if (isset($this->data[$TABLE]['id'])):
                        echo $this->Form->hidden('id');
                        ?>
                        <div class="row">
                            <section class="col col-6" >
                                <label class="Bold"><strong>ID: </strong></label>
                                <label> <?php echo $this->data[$TABLE]['id']; ?></label>
                            </section>
                        </div>
                    <?php endif; ?> 

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Situação <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'placeholder' => 'Situação', 'class' => 'input_login', 'options' => array(''=>'Situação','0'=>'Inativo','1'=>'Ativo'), 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
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
                            <label class="label">Matrícula</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('matricula', array('label' => false, 'div' => false, 'placeholder' => 'Matrícula', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com Matrícula</b>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                    <section class="col col-3">
                            <label class="label">Documento <?php echo $obrigatorio; ?></label>
                            <label class="input"> 
                                <?php echo $this->Form->input('documento_id', array('type'=>'text','label' => false, 'div' => false, 'placeholder' => 'Documento', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com Documento</b>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Motivo <?php echo $obrigatorio; ?></label>
                            <label class="input">
                                <?php 
                                    echo $this->Form->input('motivo_id', array('type'=>'text','label' => false, 'div' => false, 'placeholder' => 'Motivo', 'class' => 'input_login')); 
                                    #echo $this->Form->input('motivo_id', array('label' => false, 'div' => false, 'placeholder' => 'Motivo', 'class' => 'input_login', 'options' => $motivoArr, 'default' => '')); 
                                ?>
                            </label>
                        </section>
                    </div>
                    <section>
                        <label class="label">Hospital / Clínica</label>
                        <label class="input"> 
                            <?php echo $this->Form->input('hospital_clinica', array('label' => false, 'div' => false, 'placeholder' => 'Hospital / Clinica', 'class' => 'input_login')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com CRM</b>
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Data de Saída </label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_saida', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data de saída', 'div' => false, 'placeholder' => 'Data de Saída','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a data de saída</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Data de Retorno </label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_retorno', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data de Retorno', 'div' => false, 'placeholder' => 'Data de Retorno','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a data de retorno</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Qtde Dias de Atestado</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('qtde_dias_atestado', array('label' => false, 'div' => false, 'placeholder' => 'Qtd Dias Atestado', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com os dias calculados</b>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Hora de Saída </label>
                            <label class="input"> 
                                <?php echo $this->Form->input('hora_saida', array('type'=>'text','label' => false, 'div' => false, 'placeholder' => 'Hora de Saída', 'class' => '  margin-right-cadastre ')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a hora de saída</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Hora de Retorno </label>
                            <label class="input"> 
                                <?php echo $this->Form->input('hora_retorno', array('type'=>'text','label' => false, 'div' => false, 'placeholder' => 'Hora de Retorno', 'class' => '  margin-right-cadastre ')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a hora de retorno</b>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">CID</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('cid', array('label' => false, 'div' => false, 'placeholder' => 'CID', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com CID</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Especialidade <?php echo $obrigatorio; ?></label>
                            <label class="input">
                                <?php 
                                    echo $this->Form->input('especialidade_id', array('type'=>'text','label' => false, 'div' => false, 'placeholder' => 'Especialidade', 'class' => 'input_login')); 
                                    #echo $this->Form->input('especialidade_id', array('label' => false, 'div' => false, 'placeholder' => 'Cargo', 'class' => 'input_login', 'options' => $especialidadeArr, 'default' => '')); 
                                ?>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Emissor <?php echo $obrigatorio; ?></label>
                            <label class="input">
                                <?php 
                                    echo $this->Form->input('emissor_id', array('type'=>'text','label' => false, 'div' => false, 'placeholder' => 'Emissor', 'class' => 'input_login')); 
                                    #echo $this->Form->input('emissor_id', array('label' => false, 'div' => false, 'placeholder' => 'Setor', 'class' => 'input_login', 'options' => $emissorArr, 'default' => '')); 
                                ?>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Profissional </label>
                            <label class="input"> 
                                <?php echo $this->Form->input('profissional', array('label' => false, 'div' => false, 'placeholder' => 'Profissional', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com Profissional</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CRM</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('num_crm', array('label' => false, 'div' => false, 'placeholder' => 'Número CRM', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com CRM</b>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Tipo de Absenteísmo <?php echo $obrigatorio; ?></label>
                            <label class="input">
                                <?php 
                                    echo $this->Form->input('tipo_absenteismo_id', array('type'=>'text','label' => false, 'div' => false, 'placeholder' => 'Tipo Absenteísmo', 'class' => 'input_login')); 
                                    #echo $this->Form->input('tipo_absenteismo_id', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Absenteísmo', 'class' => 'input_login', 'options' => $tipoAbsenteismoArr, 'default' => '')); 
                                ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <section>
                        <label class="label">Observação</label>
                        <label class="textarea">
                            <?php echo $this->Form->textarea('observacao', array('rows' => 5, 'style' => 'width:100%;', 'label' => false, 'div' => false, 'placeholder' => 'Observação', 'class' => 'input_login')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o Observação</b>
                        </label>
                    </section> 
                    <div class="row" style="margin-top:20px;">
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
        
        // DIAS
        $('.calcular_dias').click(function(){
            
        });


        // Horas
        $('.calcular_horas').click(function(){

        });


    });
</script>

