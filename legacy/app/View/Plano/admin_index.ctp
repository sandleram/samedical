<?php echo $this->Element('admin/breadcrumb');?>
<div id="content">
    <section id="widget-grid" class="">
        
        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blue" id="wid-id-15" data-widget-colorbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false">
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
                            
                            $s_id = $s_nome = $s_valor = $s_tipo = $s_status = $s_tipo_beneficio_id = $s_operadora_id =  '';
                            if(isset($search['id_'])){$s_id = $search['id_'];}
                            if(isset($search['nome'])){$s_nome = $search['nome'];}
                            if(isset($search['tipo_beneficio_id'])){$s_tipo_beneficio_id = $search['tipo_beneficio_id'];}
                            if(isset($search['operadora_id'])){$s_operadora_id = $search['operadora_id'];}
                            if(isset($search['status'])){$s_status = $search['status'];}
                            ?>
                            <div class="row">
                                <section class="col col-1">        
                                    <label class="input">
                                        <?php echo $this->Form->input('id_', array('label' => false, 'div' => false, 'placeholder' => 'ID', 'class' => 'input_login','value'=>$s_id)); ?>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o identificador</b>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="input"> 
                                        <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Nome', 'class' => 'input_login','value'=>$s_nome)); ?>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Nome</b>
                                    </label>
                                </section> 
                                <section class="col col-2">
                                    <label class="select">
                                        <?php echo $this->Form->input('tipo_beneficio_id', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$tipoBeneficioArr, 'default' => '', 'value'=>$s_tipo_beneficio_id)); ?>
                                        <i></i>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="select">
                                        <?php echo $this->Form->input('operadora_id', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$operadoraArr, 'default' => '', 'value'=>$s_operadora_id)); ?>
                                        <i></i>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="select">
                                        <?php echo $this->Form->input('status', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$this->Funcoes->status(null,false,array(''=>'Status...')), 'default' => '', 'value'=>$s_status)); ?>
                                        <i></i>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Status</b></label>
                                </section>
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
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width='5'><?php echo $this->Form->input('ck_id_all',array('label'=>false,'type'=>'checkbox','name'=>'ck_id_all','class'=>'ck_select_all','style'=>'margin-top:5px','hiddenField'=>false)); ?></th>
                                        <th width='5'>ID</th>
                                        <th>Nome</th>
                                        <th>Código Operadora</th>
                                        <th>Operadora</th>
                                        <th>Tipo Beneficio</th>
                                        <th>Ordem</th>
                                        <th <?php echo $cel_hidden; ?>>Data de Cadastro</th>
                                        <th>Status</th>
                                        <th class="actions"><?php echo __('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?php echo $this->Form->input('ck_id'.$row[$TABLE]['id'],array('label'=>false,'type'=>'checkbox','name'=>'ck_id', 'class'=>'ck_delete','value'=>$row[$TABLE]['id'],'hiddenField'=>false)); ?></td>
                                            <td><?php echo $row[$TABLE]['id']; ?></td>
                                            <td><?php echo $this->Funcoes->marcatexto($row[$TABLE]['nome'],$s_nome);?></td>
                                            <td><?php echo $row[$TABLE]['codigo_operadora'];?></td>
                                            <td><?php echo $row['Operadora']['nome'];?></td>
                                            <td><?php echo $row['TipoBeneficio']['descricao']; ?></td>
                                            <td><?php 
                                                if($row[$TABLE]['operadora_id'] != ''){
                                                    echo '('.$row[$TABLE]['ordem'].')';
                                                ?> &nbsp; &nbsp; &nbsp; 
                                                    <a href="<?php echo Router::url('ordenacao/'.$row[$TABLE]['id'].'/1');?>">
                                                        <i class="fa fa-caret-down icon-color-bad"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?php echo Router::url('ordenacao/'.$row[$TABLE]['id'].'/0');?>">
                                                        <i class="fa fa-caret-up icon-color-good"></i>
                                                    </a>
                                                <?php 
                                                    }
                                                ?>
                                            </td>
                                            <td <?php echo $cel_hidden; ?>><?php echo $this->DateTime->dbToView($row[$TABLE]['data_cadastro']); ?></td>
                                            <td><?php echo $this->Funcoes->status($row[$TABLE]['status'],true); ?></td>
                                            <td class="actions">
                                                <?php
                                                   echo $this->Funcoes->menus('lista',$permissao, $row[$TABLE]['id']);
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; echo $this->Funcoes->semRegistro($rows,7,$search); ?>
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