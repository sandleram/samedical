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
                krumo($this->data);
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
                        <li class="bg-color-red"><a href="<?php echo Router::url(array('controller'=>'beneficio_previdenciario', 'action'=>'delete',$this->params['pass'][0],$this->params['pass'][1]),true); ?>" class="ajaxMsg" ajaxmsg="Tem certeza que deseja excluir o parâmetro ID: 132" style="color:white">Excluir Beneficio Previdenciario</a></li>
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
                            <label class="label">Dias Calculados</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('dias_calculados', array('disabled'=>'disabled','label' => false, 'div' => false, 'placeholder' => 'Dias Calculados', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com os dias calculados</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">&nbsp;</label>
                            <label class="input"> 
                                <a class="btn btn-primary calcular_dias" href="javascript:void(0);" >Calcular</a>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Hora de Saída </label>
                            <label class="input"> 
                                <?php echo $this->Form->input('hora_saida', array('type'=>'text','label' => false, 'div' => false, 'placeholder' => 'Data de Saída', 'class' => '  margin-right-cadastre ')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a hora de saída</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Hora de Retorno </label>
                            <label class="input"> 
                                <?php echo $this->Form->input('hora_retorno', array('type'=>'text','label' => false, 'div' => false, 'placeholder' => 'Data de Saída', 'class' => '  margin-right-cadastre ')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a hora de retorno</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Horas Calculadas</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('horas_calculadas', array('type'=>'text','disabled'=>'disabled','label' => false, 'div' => false, 'placeholder' => 'Dias Calculados', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com os dias calculados</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">&nbsp;</label>
                            <label class="input"> 
                                <a class="btn btn-primary btn-sm calcular_horas" href="javascript:void(0);" >Calcular</a>
                                
                            </label>
                        </section>
                    </div>
                    
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Tipo de Absenteísmo <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('tipo_absenteismo_id', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Absenteísmo', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Cargo <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('cargo_id', array('label' => false, 'div' => false, 'placeholder' => 'Cargo', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Setor <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('setor_id', array('label' => false, 'div' => false, 'placeholder' => 'Setor', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Departamento <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('departamento_id', array('label' => false, 'div' => false, 'placeholder' => 'Departamento', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Motivo <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('motivo_id', array('label' => false, 'div' => false, 'placeholder' => 'Setor', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        
                    </div>
                    <div class="row">
                    <section class="col col-3">
                            <label class="label">Documento <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('documento_id', array('label' => false, 'div' => false, 'placeholder' => 'Departamento', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Especialista <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('especialista_id', array('label' => false, 'div' => false, 'placeholder' => 'Setor', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Emissor <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('emissor_id', array('label' => false, 'div' => false, 'placeholder' => 'Departamento', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Parte Corpo <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('parte_corpo_id', array('label' => false, 'div' => false, 'placeholder' => 'Setor', 'class' => 'input_login', 'options' => $empresaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Profissional </label>
                            <label class="input"> 
                                <?php echo $this->Form->input('profissional', array('label' => false, 'div' => false, 'placeholder' => 'NB', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com Profissional</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CRM</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('num_crm', array('label' => false, 'div' => false, 'placeholder' => 'NIT', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com CRM</b>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Hospital / Clínica</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('hospital_clinica', array('label' => false, 'div' => false, 'placeholder' => 'Hospital / Clinica', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com CRM</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">cid </label>
                            <label class="input"> 
                                <?php echo $this->Form->input('profissional', array('label' => false, 'div' => false, 'placeholder' => 'Profissional', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com Profissional</b>
                            </label>
                        </section>
                    </div>
                    
                    <section>
                        <label class="label">Observação</label>
                        <label class="textarea">
                            <?php echo $this->Form->textarea('observacao', array('rows' => 5, 'style' => 'width:100%;', 'label' => false, 'div' => false, 'placeholder' => 'Nexo Técnico', 'class' => 'input_login')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o Observação</b>
                        </label>
                    </section> 

                   

                    <div class="row" style="margin-top:20px;">
                        <section class="col col-2">
                            <label class="label">Situação <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => array(''=>'Situação','0'=>'Inativo','1'=>'Ativo'), 'default' => '')); ?>
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

