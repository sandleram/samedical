<?php echo $this->Element('admin/breadcrumb');?>

<div id="content">
<div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blue" id="wid-id-12" data-widget-colorbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-list"></i> </span>
                        <h2>Relatório de Beneficiários</h2>
                    </header>
                    
<!--BEGIN - FILTRO -->
                    <div class="row" style="display:none;">
                         <?php
                            echo $this->Form->msg($this->Session->flash());

                            echo $this->Form->create(
                                'relatorio_afastados_form_busca', array(
                                'id' => 'relatorio_afastados_form_busca-search-form',
                                'url' => array(
                                    'controller' => 'relatorio',
                                    'action' => 'afastados'
                                ),
                                'method' => 'GET',
                                'class' => 'smart-form client-form form_ajax'
                                    )
                            );
                            
                            $s_id = $s_nome =  $s_matriz = $s_razao_social = $s_cnpj =  $s_status ='';
                            if(isset($search['id_'])){$s_id = $search['id_'];}
                            if(isset($search['nome'])){$s_nome = $search['nome'];}
                            if(isset($search['razao_social'])){$s_razao_social = $search['razao_social'];}
                            if(isset($search['cnpj'])){$s_cnpj = $search['cnpj'];}
                            if(isset($search['status'])){$s_status = $search['status'];}
                            ?>
                            <div class="row" >
                                <section class="col col-1">        
                                    <label class="input">
                                        <?php echo $this->Form->input('id_', array('label' => false, 'div' => false, 'placeholder' => 'ID', 'class' => 'input_login','value'=>$s_id)); ?>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Identificador</b>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="input"> 
                                        <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Empresa', 'class' => 'input_login','value'=>$s_nome)); ?>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Empresa</b>
                                    </label>
                                </section>                            
                                <section class="col col-2">
                                    <label class="input"> 
                                        <?php echo $this->Form->input('razao_social', array('label' => false, 'div' => false, 'placeholder' => 'Razão Social', 'class' => 'input_login','value'=>$s_razao_social)); ?>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Razão Social</b>
                                    </label>
                                </section>                            
                                <section class="col col-2">
                                    <label class="input"> 
                                        <?php echo $this->Form->input('cnpj', array('label' => false, 'div' => false, 'placeholder' => 'CNPJ', 'class' => 'cnpj_mask input_login','value'=>$s_cnpj)); ?>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o CNPJ</b>
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
                            //    krumo($rows);
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th width='5'>< ?php echo $this->Form->input('ck_id_all',array('label'=>false,'type'=>'checkbox','name'=>'ck_id_all','class'=>'ck_select_all','style'=>'margin-top:5px','hiddenField'=>false)); ?></th> -->
                                        <th width='10'>Cliente</th>
                                        <th width='5'>ID Beneficiario</th>
                                        <th width='10'>Timeline</th>
                                        <th>Beneficiario</th>
                                        <th>CPF</th>
                                        <th>Situação</th>
                                        <th>Data de Cadastro</th>
                                        <th>Via</th>
                                        <th>Status</th>
                                        <!-- <th class="actions">< ?php echo __('Actions'); ?></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($rows as $row):?>
                                        <tr>
                                            <td><?php echo $row['Cliente']['nome']; ?></td>
                                            <td><?php echo $row['Beneficiario']['id']; ?></td>
                                            <td><a href="<?php echo Router::url(array('controller'=>'beneficiario','action'=>'view',$row['Beneficiario']['id'])); ?>" class="btn btn-primary btn-xs"> <i class="fa  fa-user"></i> Acessar</a></td>
                                            <td><?php echo $row['Beneficiario']['nome']; ?></td>
                                            <td><?php echo $this->Funcoes->formata_cpf($row['Beneficiario']['cpf']); ?></td>
                                            <td><?php echo $row['Beneficiario']['situacao']; ?></td>
                                            <td <?php echo $cel_hidden; ?>><?php echo $this->DateTime->dbToView($row[$TABLE]['data_cadastro']); ?></td>
                                            <td><?php echo ($row[$TABLE]['importacao_id'] == '')? 'Entrada<br>Manual': 'Importação'; ?></td>
                                            <td><?php echo $this->Funcoes->status($row[$TABLE]['status'],true); ?></td>
                                           
                                        </tr>
                                    <?php endforeach; echo $this->Funcoes->semRegistro($rows,12,$search); ?>
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

</div>