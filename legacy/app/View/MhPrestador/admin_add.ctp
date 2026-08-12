<?php echo $this->Element('admin/breadcrumb'); ?>

<div id="content">
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php
                echo $this->Form->msg($this->Session->flash());

                echo $this->Form->create(
                        $TABLE, array(
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
                echo $this->Funcoes->menus('geral',$permissao);
                ?>

                <header>
                    <?php echo (isset($this->params['pass'][0])) ? 'Edição' : 'Cadastro'; ?> de <?php echo $this->Funcoes->titulos($this->params['controller']); ?>
                </header>
                <fieldset>
                    <?php
                    $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                    echo $this->Form->hidden('id');
                    if (isset($this->data[$TABLE]['id'])):
                        ?>
                        <div class="row">
                            <section class="col col-6" >
                                <label class="Bold"><strong>ID: </strong></label>
                                <label> <?php echo $this->data[$TABLE]['id']; ?></label>
                            </section>
                            
                        </div>
                    <?php endif; ?>
                    <div class=" row" >
                        <div class="smart-form col col-4" style="margin-bottom:30px;">
                            <fieldset style=" margin:0 !important; padding:0 !important;">
                               
                                <label class="select select-multiple">
                                    <label class="Bold">Tipo Negócio:</label>
                                    <select name="data[MhPrestador][tipo_negocio][]" id="MhPrestadorTipoNegocioId" class="input_login " multiple="multiple" style="height:120px; ">';
                                        <?php
                                            if (count($ArrTipoNegocio) > 0) {
                                                $list_negocio = [];
                                                if (count($this->data['MhPrestador']['tipo_negocio']) > 0) {
                                                    $list_negocio = explode(',',$this->data['MhPrestador']['tipo_negocio']);
                                                    
                                                }
                                                foreach ($ArrTipoNegocio as $k => $v) {
                                                    $selected = '';
                                                    if (in_array($k, $list_negocio)) {
                                                        $selected = 'selected = "selected"';
                                                    }
                                                    echo '<option value="' . $k . '" ' . $selected . ' style="margin-left:10px;">' . $v . '</option>';
                                                }                                            
                                            }
                                        ?>

                                    </select>

                                </label>
                            </fieldset>
                        </div>
                    </div>
                   
                    <section>
                        <label class="label">Nome <?php echo $obrigatorio; ?></label>
                        <label class="input"> 
                            <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Cliente', 'class' => 'input_login')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com nome da sua Cliente</b>
                        </label>
                    </section>
                    <section>
                        <label class="label">CNPJ <?php echo $obrigatorio; ?></label>
                        <label class="input"> 
                            <?php echo $this->Form->input('cnpj', array('label' => false, 'div' => false, 'placeholder' => 'CNPJ', 'class' => 'input_login mask_cnpj')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com o CNPJ</b>
                        </label>
                    </section>
                    
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
    $(document).ready(function () {
 
       
    });
</script>

