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

                   
                    <section>
                        <label class="label">Nome <?php echo $obrigatorio; ?></label>
                        <label class="input"> 
                            <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Cliente', 'class' => 'input_login')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Entre com nome da sua Cliente</b>
                        </label>
                    </section>
                    
                   <div class="row" style="margin-top: 30px; margin-bottom: 10px;" > 
                        <section class="col col-12" style="margin-bottom: 50px;">
                            <label class="label"><i class="fa fa-camera"></i>
                                Logo:
                                <?php
//                                echo $obrigatorio;
                                $dataHelpContent = '<h5>Extensões Permitidas</h5> 
                                                        Somente imagems com extensão(.jpg / .gif / .png).
                                                        <br>
                                                        <h5>Tamanho Padrão</h5> 
                                                            O tamanho padrão para esta imagem é de: <br> 
                                                            690 pixels de Largura por <br>
                                                            300 pixels de Altura<br><br>
                                                            <b>Observação:</b> <i style="font-size:11px">Caso coloquem outra imagem de um tamanho diferente deste estipulados acima, 
                                                            lembre-se que o sistema adequará a imagem para que siga o padrão informado para não 
                                                            termos problemas com o layout, deixar a imagem achatada ou com qualidade inferior.</i>
                                                        <br>
                                                        ';
                                echo $this->Html->image('sys/help.png', array('width' => '22px', 'style' => 'cursor:help; margin-left:10px;', 'rel' => "popover-hover", 'data-placement' => "bottom", 'data-html' => 'true', 'data-content' => $dataHelpContent));
                                ?>
                            </label>
                            <?php
                            $required = array();

//                            if (!isset($this->data[$TABLE]['img_logo'])) {
//                                $required = array('required' => 'required');
//                            }
                            
                            echo '<label class=""> ' . $this->Form->input('arquivo_logo', array_merge($required, array('label' => false, 'type' => 'file', 'div' => false, 'placeholder' => 'Logo', 'class' => 'btn btn-default'))) . '</label>';
                            echo $this->Form->hidden('img_logo');
                            
                            if (isset($this->data[$TABLE]['img_logo']) && $this->data[$TABLE]['img_logo'] != '' && file_exists('img/uploads/' . $this->params['controller'] . '/' . $this->data[$TABLE]['img_logo'])) {
                                echo '<p style="margin-top:10px;">' . $this->Html->image('uploads/' . $this->params['controller'] . '/mini/' . $this->data[$TABLE]['img_logo'], array('style'=>'width:100px;','rel' => Router::url('/img/uploads/' . $this->params['controller'] . '/') . $this->data[$TABLE]['img_logo'], 'class' => 'link_image')) . '</p>';
                            }
                            ?>
                        </section>
                    </div>

           

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

