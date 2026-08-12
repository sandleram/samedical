<?php echo $this->Element('admin/breadcrumb');?>

<style type="text/css">
    .filter_button{padding: 5px 10px;width:80px;}
</style>
<div id="content" style="">
    <section id="widget-grid" class="">
        
        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blue" id="wid-id-11" data-widget-colorbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-list"></i> </span>
                        <h2><?php echo $this->Funcoes->titulos(str_replace('admin_','',$this->params['action']));?></h2>
                    </header>
                    
                    <!--BEGIN - FILTRO -->
                    <div class="row" style="" >
                         <?php
                         
                            echo $this->Form->msg($this->Session->flash());

                            echo $this->Form->create(
                                    $this->params['controller'].'_form_busca', array(
                                'id' => $this->params['controller'].'-search-form',
                                'url' => array(
                                    'controller' => $this->params['controller'],
                                    'action' => 'leads_novos'
                                ),
                                'method' => 'GET',
                                'class' => 'smart-form client-form form_ajax')
                            );
                            
                            $s_tempo = $s_empresa = $s_tempo_dias = $s_status = '';
                            if(isset($search['tempo'])){$s_tempo = $search['tempo'];}
                            if(isset($search['tempo_dias'])){$s_tempo_dias = $search['tempo_dias'];}
                            if(isset($search['empresa'])){$s_empresa = $search['empresa'];}
                            if(isset($search['status'])){$s_status = $search['status'];}
                            
                            ?>
                           
                            <div class="row widget-body" >
                                <section class="col col-2">
                                    <label class="select">
                                        <?php echo $this->Form->input('tempo', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options'=>$tempoArr, 'default' => '', 'value'=>$s_tempo)); ?>
                                        <i></i>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Status</b></label>
                                </section>
                                    
<!--                                <section class="col col-2">
                                    <label class="input">
                                        <?php // echo $this->Form->input('tempo_dias', array('label' => false, 'div' => false, 'placeholder' => 'Escolha quantos Dias', 'class' => 'input_login','value'=>$s_tempo_dias)); ?>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Identificador</b>
                                    </label>
                                </section>-->
                                
                                <section class="col col-3">
                                    <label class="select">
                                        <?php echo $this->Form->input('empresa', array('label' => false, 'div' => false, 'placeholder' => 'Empresa', 'class' => 'input_login', 'options'=>$empresaArr, 'default' => '1', 'value'=>$s_empresa)); ?>
                                        <i></i>
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Unidade</b></label>
                                </section>
                                
                                <section class="col col-1" style="margin-bottom: 5px;">
                                    <button type="submit" class="btn btn-primary" style="padding: 4px 10px;">
                                        Filtrar
                                    </button>
                                </section> 
                                <section class="col col-2" >
                                    <label class="input"> 
                                        <?php
                                            if (isset($search) && is_array($search) && count($search) > 0):
                                                echo $this->Html->image("sys/filter-clear.png", array("alt" => "Limpar Filtros","title" => "Limpar Filtros", "url"=> array('action'=>'admin_busca_unset','ALL'))).' ';
                                                echo $this->Html->link('Limpar Filtros',array('controller'=> $this->params['controller'],'action'=>'admin_busca_unset','ALL',str_replace('admin_','',$this->params['action'])));
                                            endif;
                                        ?>
                                    </label>
                                </section> 
                                
                           </div>
                           
                            <?php
                                
                                echo $this->Form->end();
                            ?>
                        
                        <div class="row" style="float: right;">     
                            <?php echo $this->Html->image("sys/export_excel_g.png", array("alt" => "Limpar Filtros","title" => "Download", "url"=> array('action'=>'leads_novos_down'), array('target'=>'_blank'))).' ';?>
                       </div>
                    </div>
                    <!--END - FILTRO -->

                    <!--BEGIN - LISTA-->
                    <div>
                        <div class="widget-body ">
                            <?php
                                echo $this->Form->msg($this->Session->flash());
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>(ID) Nome</th>
                                        <th>CPF</th>
                                        <th>E-mail</th>
                                        <th <?php echo $cel_hidden; ?>>Faculdade</th>
                                        <th>Data de Cadastro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($rows as $row):
                                    ?>
                                        <tr>
                                            <td>
                                                <?php echo '('.$row['Aluno']['id'].')';?>
                                                <?php echo $row['Usuario']['nome'];?>
                                            </td>
                                            <td><?php echo $row['Usuario']['cpf'];?></td>
                                            <td><?php echo strtolower($row['Usuario']['email']);?></td>
                                            <td <?php echo $cel_hidden; ?>><?php echo $row['Empresa']['nome'];?></td>
                                            <td ><?php echo $this->DateTime->dbToView($row['Usuario']['data_cadastro']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
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