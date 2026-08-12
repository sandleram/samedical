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
                        <label class="label">Nome <?php echo $obrigatorio;?></label>
                        <label class="input"> 
                            <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Nome', 'class' => 'input_login', 'maxlength' => '100')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Nome </b>
                        </label>
                    </section>
                    <?php /*
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Tipo <?php echo $obrigatorio;?>
                            <?php 
                                $dataHelpContent = '<h5>Tipo</h5> O tipo é a referência usada nas buscas destas informações, então deve-se deixar o nome igual a um dos que já existe
                                                    </i><br>
                                                    ';
                                echo $this->Html->image('sys/help.png', array(  'width'=>'22px', 'style'=>'cursor:help; margin-left:10px;', 'rel'=>"popover-hover", 'data-placement'=>"bottom", 'data-html'=>'true','data-content'  =>$dataHelpContent));
                            ?>
                            </label>
                            <?php if($this->Session->read('Auth.Usuario.id') == $uRoot):?>
                                <label class="input"> 
                                    <?php echo $this->Form->input('tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'maxlength' => '20')); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Tipo </b>
                                </label>
                            <?php else:?>
                                <label class="select">
                                    <?php echo $this->Form->input('tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options'=>array_merge(array(''=>'Selecione...'),$tipoArr), 'default' => '')); ?>
                                    <i></i>
                                </label>
                            <?php endif;?>
                        </section>
                    </div>
                     */ ?>
                     
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Tipo <?php echo $obrigatorio;?>
                            <?php 
                                $dataHelpContent = '<h5>Tipo</h5> O tipo é a referência usada nas buscas destas informações, então deve-se deixar o nome igual a um dos que já existe
                                                    </i><br>
                                                    ';
                                echo $this->Html->image('sys/help.png', array(  'width'=>'22px', 'style'=>'cursor:help; margin-left:10px;', 'rel'=>"popover-hover", 'data-placement'=>"bottom", 'data-html'=>'true','data-content'  =>$dataHelpContent));
                            ?>
                            </label>
                            <label class="select tipo_old" >
                                <?php echo $this->Form->input('tipo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'options'=>array_merge(array(''=>'Selecione...'),$tipoArr), 'default' => '')); ?>
                                <i></i>
                            </label>
                            <?php
                                if($this->Session->read('Auth.Usuario.id') == $uRoot):
                            ?>
                            <label class="label margin-top-5 " >
                                <a href="javascrip:void(0);" class="link_tipo_novo">Modificar</a>
                            </label>
                            
                            <label class="input tipo_new" style="display:none;" > 
                                <?php echo $this->Form->input('tipo_novo', array('label' => false, 'div' => false, 'placeholder' => 'Tipo', 'class' => 'input_login', 'maxlength' => '40', 'value'=>(isset($this->data['Parametro']['tipo'])? $this->data['Parametro']['tipo'] : ''))); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Tipo </b>
                            </label>
                            <?php
                                endif;
                            ?>
                        </section>
                    </div>
                    
                    
                    
                    
                    <section>
                       <label class="label">Valor <?php echo $obrigatorio;?></label>
                       <label class="input"> 
                           <?php echo $this->Form->input('valor', array('label' => false, 'div' => false, 'placeholder' => 'Valor', 'class' => 'input_login', 'maxlength' => '100')); ?>
                           <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Valor </b>
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

<?php
    #ALIMENTANDO TAGS
    $tipo_text = '';
    if(isset($tipoArr) && count($tipoArr)>0){
        $tipo_text = "'".implode("','",$tipoArr)."'";
    }
?>
<script type="text/javascript">
    $(document).ready(function() {
      var tipoAll = [<?php echo $tipo_text;?>];
      $( "#ParametroTipoNovo" ).autocomplete({
        source: tipoAll
      });
      
      
      $('.link_tipo_novo').click(function(){
          $('.link_tipo_novo').fadeOut('slow');
          $('.tipo_old').fadeOut('slow');
          $('.tipo_new').fadeIn('slow');
          
      });
    });
</script>
