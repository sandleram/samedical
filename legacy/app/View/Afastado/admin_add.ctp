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
                        <li class="bg-color-red"><a href="<?php echo Router::url(array('controller'=>'afastado', 'action'=>'delete',$this->params['pass'][0],$this->params['pass'][1]),true); ?>" class="ajaxMsg" ajaxmsg="Tem certeza que deseja excluir?" style="color:white">Excluir Afastado</a></li>
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


                    <h3 >
                        Afastado
                    </h3>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Situação <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'placeholder' => 'Situação', 'class' => 'input_login', 'options' => array(''=>'Situação...','A'=>'Afastado','RT'=>'Retorno ao Trabalho' ), 'default' => '')); ?>
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
                    

                    <div class="row" >
                        <section class="col col-2">
                            <label class="label">Data Início Afastamento <?php  #echo $obrigatorio;?></label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_inicio_afastamento', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data Início Afastamento', 'div' => false, 'placeholder' => 'Data de Entrada do Requerimento','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data Início Afastamento</b>
                            </label>
                        </section> 
                        
                        <section class="col col-2 exibe_fim_afastamento" style="display:none;" >
                            <label class="label">Data Fim Afastamento <?php  #echo $obrigatorio;?></label>
                            <label class="input"> 
                                <?php echo $this->Form->date('data_fim_afastamento', array('dateFormat' => 'DMY','minYear' => date('Y') -5, 'maxYear' => date('Y') +1, 'label' => 'Data Fim Afastamento', 'div' => false, 'placeholder' => 'Data de Início','dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data Fim Afastamento</b>
                            </label>
                        </section> 
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">CID <?php #echo $obrigatorio; ?></label>
                            <label class="input"> 
                            <?php echo $this->Form->input('cid', array('label' => false, 'div' => false, 'placeholder' => 'CID', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com CID</b>
                            </label>
                        </section>
                       
                        <section class="col col-4">
                            <label class="label">Tipo Afastamento <?php #echo $obrigatorio; ?></label>
                            <label class="input">
                                <?php echo $this->Form->text('tipo_afastamento', array('label' => false, 'div' => false, 'placeholder' => 'Tipo Afastamento', 'class' => 'input_login','maxlenght')); ?>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Assistencia Médica <?php #echo $obrigatorio; ?></label>
                            <label class="input"> 
                            <?php echo $this->Form->input('assistencia_medica', array('label' => false, 'div' => false, 'placeholder' => 'Assistencia Médica', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com Assistencia Médica</b>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Plano Assistencia Médica <?php #echo $obrigatorio; ?></label>
                            <label class="input"> 
                            <?php echo $this->Form->input('plano_assistencia_medica', array('label' => false, 'div' => false, 'placeholder' => 'Plano Assistencia Médica', 'class' => 'input_login')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com Assistencia Médica</b>
                            </label>
                        </section>
                    </div>

                    <div class="row" style="margin-top: 30px; margin-bottom: 10px;" > 
                        <section class="col col-12" style="margin-bottom: 50px;">
                            <label class="label"><i class="fa fa-file"></i>
                                Anexo:
                                <?php
//                                echo $obrigatorio;
                                $dataHelpContent = '    <h5>Regras</h5> 
                                                            - Tamanho máximo do anexo é de 5mb.<br>
                                                            - Permitido somente 1 arquivo por atendimento.<br>
                                                            - Caso precise anexar mais de um arquivo, compacte todos em um único arquivo.
                                                            <br>
                                                        
                                                        ';
                                echo $this->Html->image('sys/help.png', array('width' => '22px', 'style' => 'cursor:help; margin-left:10px;', 'rel' => "popover-hover", 'data-placement' => "bottom", 'data-html' => 'true', 'data-content' => $dataHelpContent));
                                ?>
                            </label>
                            <?php
                            $required = array();
                            
                            echo '<label class=""> ' . $this->Form->input('arquivo', array_merge($required, array('label' => false, 'type' => 'file', 'div' => false, 'placeholder' => 'Logo', 'class' => 'btn btn-default'))) . '</label>';
                            echo $this->Form->hidden('arquivo');
                            
                           
                            if (isset($this->data[$TABLE]['anexo']) && $this->data[$TABLE]['anexo'] != '' && file_exists('files/uploads/afastado/' . $this->data[$TABLE]['anexo'])) {
                                
                                echo '<p><br>
                                        <b>Arquivo Carregado:</b><br>
                                        <a href="'.Router::url('/files/uploads/afastado/' . $this->data[$TABLE]['anexo']).'" target="_blank" ">
                                            <img src="'.Router::url('/img/icons/attach.png',true).'" width="20"/>
                                            '.$this->data[$TABLE]['anexo'].'
                                        </a></p>';

                               

                                
                            }
                            if (isset($this->data[$TABLE]['blob_id']) && $this->data[$TABLE]['blob_id'] != ''  && $this->Session->read('Auth.Usuario.perfil_id') == 1) {
                                
                                
                                echo '<p><br>
                                        <b>Arquivo bob:</b><br>
                                        <a href="'.Router::url(array('controller' => 'blob', 'action' => 'download', md5($this->data[$TABLE]['blob_id']), 'admin' => true)).'" target="_blank" ">
                                            <img src="'.Router::url('/img/icons/attach.png',true).'" width="20"/>
                                            '.$this->data[$TABLE]['anexo'].'
                                        </a></p>';

                                
                            }
                            ?>
                        </section>
                    </div>
                    
                    <div class="row libera_perguntas">
                        <section class="col col-3">
                            <label class="label">Possui ação trabalhista? <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('acao_trabalhista', array('label' => false, 'div' => false, 'placeholder' => '', 'class' => 'input_login', 'options' => $simNaoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Possui ação contra o INSS? <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('acao_inss', array('label' => false, 'div' => false, 'placeholder' => '', 'class' => 'input_login', 'options' => $simNaoAcaoInssArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Limbo previdenciário?  <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('limbo_previdenciario', array('label' => false, 'div' => false, 'placeholder' => '', 'class' => 'input_login', 'options' => $simNaoArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>


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

        $('#AfastadoSituacao').change(function(){
            libera_fim_afastamento(false);
            libera_perguntas(false)
        });
	    
        
	    
        function libera_fim_afastamento(retorno){
            tipo = $('#AfastadoSituacao').val();
            
            if(tipo == 'RT'){
                $('.exibe_fim_afastamento').fadeIn('slow');
            }else{
                $('.exibe_fim_afastamento').fadeOut('slow');
            }

        }
        libera_fim_afastamento(true);


        function libera_perguntas(retorno){
            tipo = $('#AfastadoSituacao').val();
            
            if(tipo == 'A'){
                $('.libera_perguntas').fadeIn('slow');
            }else{
                $('.libera_perguntas').fadeOut('slow');
            }

        }
        libera_perguntas(true);












        //config_ckeditor (appcontroller)
        //CKEDITOR.replace('data[BeneficioPrevidenciario][nexo_tecnico]', {< ?php echo $config_ckeditor; ?>});
        
    });
</script>

