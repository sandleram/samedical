<?php echo $this->Element('admin/breadcrumb'); ?>

<style>
    h3{
        margin-bottom: 20px !important;
        border-bottom: 1px dotted #d3d3d3;
    }
</style>

<div id="content">
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php
                echo $this->Form->msg($this->Session->flash());
                $beneficiario_id = $this->params['pass'][0];
                echo $this->Form->create(
                        $TABLE, array(
                    'type' => 'file',
                    'id' => $this->params['controller'] . '-form',
                    'url' => array(
                        'controller' => $this->params['controller'],
                        'action' => 'add',
                        $beneficiario_id
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


                    <h3>
                        Marcação do Benefício 
                    </h3>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Data Próxima Perícia  <?php  #echo $obrigatorio;?></label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_proxima_pericia', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data de Entrada do Requerimento', 'div' => false, 'placeholder' => 'Data de Entrada do Requerimento','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data da Próxima Perícia</b>
                            </label>
                        </section> 
                        <section class="col col-2">
                            <label class="label">Número do Requerimento </label>
                            <label class="input"> 
                                <?php echo $this->Form->input('num_requerimento', array('label' => false, 'div' => false, 'placeholder' => 'Número do Requerimento', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com Número do Requerimento</b>
                            </label>
                        </section>
                        
                    </div>


                    <h3 style="margin-top:40px;">
                        Benefício 
                    </h3>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Situação </label>
                            <label class="select">
                                <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'placeholder' => 'Situação', 'class' => 'input_login', 'options' => array(''=>'Situação...','A'=>'Ativo','C'=>'Cessado','S'=>'Suspenso','AN'=>'Em Análise' ), 'default' => '')); ?>
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
                            <label class="label">NB <?php echo $obrigatorio; ?></label>
                            <label class="input"> 
                                <?php echo $this->Form->input('nb', array('label' => false, 'div' => false, 'placeholder' => 'NB', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com NB</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">NIT</label>
                            <label class="input"> 
                                <?php echo $this->Form->input('nit', array('label' => false, 'div' => false, 'placeholder' => 'NIT', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com nit</b>
                            </label>
                        </section>
                    </div>

                    <div class="row" style="margin-top:10px; margin-bottom:10px;">
                        <section class="col col-4">
                            <label class="label">Espécie <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('especie_bp_id', array('label' => false, 'div' => false, 'placeholder' => 'Situação', 'class' => 'input_login', 'options' => $especieBpArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <div class="especie_div" style="display:none;">
                            <section class="col col-2">
                                <label class="label">Código da Nova Espécie <?php echo $obrigatorio; ?></label>
                                <label class="input"> 
                                    <?php echo $this->Form->text('especie_bp_id_new', array('type'=>'number','label' => false, 'div' => false, 'placeholder' => 'Cod. Espécie', 'class' => 'input_login')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o código da espécie</b>
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="label">Nova Espécie <?php echo $obrigatorio; ?></label>
                                <label class="input"> 
                                    <?php echo $this->Form->input('especie_new', array('label' => false, 'div' => false, 'placeholder' => 'Espécie', 'class' => 'input_login')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o nome da Espécie</b>
                                </label>
                            </section>
                        </div> 

                    </div>

                    
                    <div class="row" >
                        <section class="col col-2">
                            <label class="label">Contestado <?php  #echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('contestado', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => [''=>'Selecione...',0=>'Não',1=>'Sim'], 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section> 
                        <section class="col col-2 contestado_exibe" style="display:none;">
                            <label class="label">Protocolo <?php echo $obrigatorio;?></label>
                            <label class="input"> 
                                <?php echo $this->Form->input('contestado_protocolo', array('label' => false, 'div' => false, 'placeholder' => 'Protocolo', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i>Entre com o protocolo</b>
                            </label>
                        </section>
                    </div>
                    <div class="row" >
                        <section class="col col-2">
                            <label class="label">Possui CAT Vinculada?<?php  #echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('cat', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => [''=>'Selecione...',0=>'Não',1=>'Sim'], 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section> 
                        <section class="col col-2 cat_exibe" style="display:none;">
                            <label class="label">Tipo de Acidente <?php echo $obrigatorio;?></label>
                            <label class="select">
                                <?php echo $this->Form->input('cat_tipo_acidente', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => [''=>'Selecione...','Típico'=>'Típico','Trajeto'=>'Trajeto'], 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row" >
                        <section class="col col-2">
                            <label class="label">Data Entrada do Requerimento <?php  #echo $obrigatorio;?></label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_entrada_requerimento', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data de Entrada do Requerimento', 'div' => false, 'placeholder' => 'Data de Entrada do Requerimento','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data do Requerimento</b>
                            </label>
                        </section> 
                        
                        <section class="col col-2">
                            <label class="label">Data de Início <?php  #echo $obrigatorio;?></label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_inicio', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data de Início', 'div' => false, 'placeholder' => 'Data de Início','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Início</b>
                            </label>
                        </section> 
                        
                        <section class="col col-2">
                            <label class="label">Data de Despacho </label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_despacho', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data de Despacho', 'div' => false, 'placeholder' => 'Data de Despacho','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Despacho</b>
                            </label>
                        </section> 
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Data Realização de Perícia</label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_realizacao_pericia', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data Realização de Perícia', 'div' => false, 'placeholder' => 'Data Realização de Perícia','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data Realização de Perícia</b>
                            </label>
                        </section> 
                        <section class="col col-10">
                            <label class="label">Conclusão da Perícia Médica</label>
                            <label class="textarea"> 
                                <?php echo $this->Form->textarea('conclusao_pericia_medica', array('label' => false, 'div' => false, 'placeholder' => 'Conclusão da Perícia Médica', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com a Conclusão da Perícia Médica</b>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Data Limite <?php  #echo $obrigatorio;?></label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_limite', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data Limite', 'div' => false, 'placeholder' => 'Data Limite','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data Limite</b>
                            </label>
                        </section> 
                        
                        <section class="col col-2">
                            <label class="label">Data de Indeferimento <?php  #echo $obrigatorio;?></label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_indeferimento', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data de Indeferimento', 'div' => false, 'placeholder' => 'Data de Indeferimento','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Indeferimento</b>
                            </label>
                        </section> 
                        
                        <section class="col col-2">
                            <label class="label">Data de Cessação </label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_cessacao', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data de Cessação', 'div' => false, 'placeholder' => 'Data de Cessação','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data de Cessação</b>
                            </label>
                        </section> 
                    </div>


                    <section>
                        <label class="label">Nexo Técnico</label>
                        <label class="textarea">
                            <?php echo $this->Form->textarea('nexo_tecnico', array('rows' => 5, 'style' => 'width:100%;', 'label' => false, 'div' => false, 'placeholder' => 'Nexo Técnico', 'class' => 'input_login')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o Nexo Técnico</b>
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
        $('#BeneficioPrevidenciarioEspecieBpId').change(function(){
            
            if($(this).val() == 'x'){
                $('.especie_div').fadeIn('slow');
            }else{
                $('.especie_div').fadeOut('slow');
                $('#BeneficioPrevidenciarioEspecieBpIdNew').val('');
                $('#BeneficioPrevidenciarioEspecieNew').val('');
            }
        })





        $('#BeneficioPrevidenciarioContestado').change(function(){
            liberacontestado(false);
        });
            
        function liberacontestado(retorno){
            status = $('#BeneficioPrevidenciarioContestado').val();
            if(status == 1){
                $('.contestado_exibe').fadeIn('slow');
            }else{
                $('.contestado_exibe').fadeOut('slow');
                $('#BeneficioPrevidenciarioContestadoProtocolo').val('') ;
            }
        }
        liberacontestado(true)



        $('#BeneficioPrevidenciarioCat').change(function(){
            liberacat(false);
        });
            
        function liberacat(retorno){
            status = $('#BeneficioPrevidenciarioCat').val();
            if(status == 1){
                $('.cat_exibe').fadeIn('slow');
            }else{
                $('.cat_exibe').fadeOut('slow');
                $('#BeneficioPrevidenciarioCatTipoAcidente').val('') ;
            }
        }
        liberacat(true)

        

        //config_ckeditor (appcontroller)
        //CKEDITOR.replace('data[BeneficioPrevidenciario][nexo_tecnico]', {< ?php echo $config_ckeditor; ?>});
        



    });
</script>

