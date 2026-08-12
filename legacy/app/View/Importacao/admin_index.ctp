<?php echo $this->Element('admin/breadcrumb');?>
<div id="content">
    <section id="widget-grid" class="">
        
        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blue" id="wid-id-11" data-widget-colorbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-list"></i> </span>
                        <h2>Todos <?php echo $this->Funcoes->titulos($this->params['controller'],true);?></h2>
                    </header>
                    
                    <!--BEGIN - FILTRO -->
                    <div class="row">
                         <?php
                            echo $this->Form->msg($this->Session->flash());

                            echo $this->Form->create(
                                    $this->params['controller'].'_form_busca', array(
                                'id' => $this->params['controller'].'-search-form',
                                'url' => array(
                                    'controller' => $this->params['controller'],
                                    'action' => 'index'
                                ),
                                'method' => 'GET',
                                'class' => 'smart-form client-form form_ajax'
                                    )
                            );
                            
                            $s_id = $s_nome = $s_cliente = $s_beneficio = $s_tipo_importacao = $s_status = $s_data_termino = $s_data_inicio = '';
                            if(isset($search['id_'])){$s_id = $search['id_'];}
                            if(isset($search['cliente'])){$s_cliente = $search['cliente'];}
                            if(isset($search['tipo_importacao'])){$s_tipo_importacao = $search['tipo_importacao'];}
                            if(isset($search['status'])){$s_status = $search['status'];}
                            ?>
                            <div class="row">
                                <section class="col col-1">        
                                    <label class="input">
                                        <?php echo $this->Form->input('id_', array('label' => false, 'div' => false, 'placeholder' => 'ID', 'class' => 'input_login','value'=>$s_id)); ?>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Identificador</b>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="select">
                                        <?php echo $this->Form->input('tipo_importacao', array('label' => false, 'div' => false, 'placeholder' => 'Tipo de Importação', 'class' => 'input_login', 'options'=>$tipoImportacaoArr, 'default' => '', 'value'=>$s_tipo_importacao)); ?>
                                        <i></i>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Tipo de Importação</b></label>
                                </section>
                                 
                                <!-- <section class="col col-1">
                                    <label class="select">
                                        < ?php echo $this->Form->input('status', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$statusArr, 'default' => '', 'value'=>$s_status)); ?>
                                        <i></i>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Status</b></label>
                                </section> -->
                                <section class="col col-1">
                                    <button type="submit" class="btn btn-primary" style="padding: 4px 10px;">
                                        Filtrar
                                    </button>
                                </section> 
                                <section class="col col-2">
                                    <label class="input"> 
                                        <?php
                                            if (isset($search) && is_array($search) && count($search) > 0):
                                                echo $this->Html->image("sys/filter-clear.png", array("alt" => "Limpar Filtros","title" => "Limpar Filtros", "url"=> array('action'=>'admin_busca_unset','ALL'))).' ';
                                                echo $this->Html->link('Limpar Filtros',array('controller'=> $this->params['controller'],'action'=>'admin_busca_unset','ALL'));
                                            endif;
                                        ?>
                                    </label>
                                </section> 
                                
                           </div>
                            <?php
                                
                                echo $this->Form->end();
                            ?>
                    </div>
                    <!--END - FILTRO -->

                    <!--BEGIN - LISTA-->
                    <div>
                        <div class="widget-body ">
                            <?php
                                echo $this->Form->msg($this->Session->flash());
                                echo $this->Funcoes->menus('geral',$permissao);
//                                krumo($rows);
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <?php if(in_array($perfil_id,array($perfil_root))){?>
                                        <th width="15"><?php echo $this->Form->input('ck_id_all',array('label'=>false,'type'=>'checkbox','name'=>'ck_id_all','class'=>'ck_select_all','style'=>'margin-top:5px','hiddenField'=>false)); ?></th>
                                        <?php }?>
                                        <th>ID</th>
                                        <?php if(in_array($perfil_id,$perfil_adm)){?><th>Cliente</th><?php }?>
                                        <th>Tipo de Importação</th>
                                        <th <?php echo $cel_hidden; ?>>Data de Cadastro</th>
                                        
                                        <th class="actions" width="70"><?php echo __('Actions'); ?></th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach ($rows as $row):
                                        ?>
                                        <tr>
                                            <?php if(in_array($perfil_id,array($perfil_root))){?>
                                            <td><?php echo $this->Form->input('ck_id'.$row[$TABLE]['id'],array('label'=>false,'type'=>'checkbox','name'=>'ck_id', 'class'=>'ck_delete','value'=>$row[$TABLE]['id'],'hiddenField'=>false)); ?></td>
                                            <?php }?>
                                            <td><?php echo $row[$TABLE]['id']; ?></td>
                                            <?php if(in_array($perfil_id,$perfil_adm)){?>
                                            <td><?php echo $row['Cliente']['nome'];?></td>
                                            <?php } ?>
                                            <td><?php echo $tipoImportacaoArr[$row[$TABLE]['tipo_importacao']]; ?></td>
                                            <td <?php echo $cel_hidden; ?>><?php echo $this->DateTime->dbToView($row[$TABLE]['data_cadastro']); ?></td>
                                           
                                            <td class="actions">
                                                <?php 
                                                    #krumo($permissao);
                                                    #krumo($row[$TABLE]['id']);
                                                    echo $this->Funcoes->menus('lista',$permissao, $row[$TABLE]['id'],$row);
                                                    
                                                    ?>
                                            </td>
                                            
                                        </tr>
                                    <?php endforeach; echo $this->Funcoes->semRegistro($rows,10,$search);  ?>
                                </tbody>
                            </table>
                            <?php
                                /**
                                 * Pagination
                                 */
                                echo $this->Element('admin/pagination');
                            ?>
                        </div>
                    </div>
                <!--END - LISTA-->
                
                </div>
            </article>
        </div>
    </section>

</div>