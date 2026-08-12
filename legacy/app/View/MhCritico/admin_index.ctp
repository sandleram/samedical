<?php echo $this->Element('admin/breadcrumb'); ?>

<style>
    .tree li.parent_li>span:hover {
        background-color: #4c96ed;
        border: 1px solid #5453fb;
        color: #fff;
    }
 
    .tree li.parent_li>span:hover+ul li::before {
        border-left-color: #4c96ed
    }

    .tree li.parent_li>span:hover+ul li::after {
        border-top-color: #4c96ed
    }
    .tree li.parent_li>span:hover+ul li span {
    background: #d6edff!important;
    border: 1px solid #8f8f8f;
    color: #000
}

</style>
<div id="content">
    <section id="widget-grid" class="">

        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blue" id="wid-id-12" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-list"></i> </span>
                        <h2>Todas <?php echo $this->Funcoes->titulos($this->params['controller'], true); ?></h2>
                    </header>

                    <!--BEGIN - FILTRO -->
                    <div class="row">
                        <?php
                        echo $this->Form->msg($this->Session->flash());

                        echo $this->Form->create(
                            $this->params['controller'] . '_form_busca',
                            array(
                                'id' => $this->params['controller'] . '-search-form',
                                'url' => array(
                                    'controller' => $this->params['controller'],
                                    'action' => 'index'
                                ),
                                'method' => 'GET',
                                'class' => 'smart-form client-form form_ajax'
                            )
                        );

                        $s_id = $s_nome = $s_status = '';
                        if (isset($search['id_'])) {
                            $s_id = $search['id_'];
                        }
                        if (isset($search['nome'])) {
                            $s_nome = $search['nome'];
                        }
                        if (isset($search['status'])) {
                            $s_status = $search['status'];
                        }
                        ?>
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <?php echo $this->Form->input('id_', array('label' => false, 'div' => false, 'placeholder' => 'ID', 'class' => 'input_login', 'value' => $s_id)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Identificador</b>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="input">
                                    <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Prestador Principal', 'class' => 'input_login', 'value' => $s_nome)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Prestador Principal</b>
                                </label>
                            </section>


                            <section class="col col-2">
                                <label class="select">
                                    <?php echo $this->Form->input('status', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => $this->Funcoes->status(null, false, array('' => 'Status...')), 'default' => '', 'value' => $s_status)); ?>
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
                                    if (isset($search) && is_array($search) && count($search) > 0) :
                                        echo $this->Html->image("sys/filter-clear.png", array("alt" => "Limpar Filtros", "title" => "Limpar Filtros", "url" => array('action' => 'admin_busca_unset', 'ALL'))) . ' ';
                                        echo $this->Html->link('Limpar Filtros', array('controller' => $this->params['controller'], 'action' => 'admin_busca_unset', 'ALL'));
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

                            echo $this->Funcoes->menus('geral', $permissao);

                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th width='5'>< ?php echo $this->Form->input('ck_id_all', array('label' => false, 'type' => 'checkbox', 'name' => 'ck_id_all', 'class' => 'ck_select_all', 'style' => 'margin-top:5px', 'hiddenField' => false)); ?></th> -->
                                        <!-- <th width='5'>ID</th> -->
                                        <th>Prestado Princiapal</th>
                                        <th>SubPrestadores</th>
                                        <th>Ciclo</th>
                                        <th>Status Ciclo</th>
                                        <th <?php echo $cel_hidden; ?>>Data de Cadastro</th>
                                        <th>Status</th>
                                        <!-- <th class="actions">< ?php echo __('Actions'); ?></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                   
                                    foreach ($rows as $row) : 
                                    ?>
                                        <tr>
                                            <!-- <td>< ?php echo $this->Form->input('ck_id' . $row[$TABLE]['id'], array('label' => false, 'type' => 'checkbox', 'name' => 'ck_id', 'class' => 'ck_delete', 'value' => $row[$TABLE]['id'], 'hiddenField' => false)); ?></td> -->
                                            <!-- <td>< ?php echo $row[$TABLE]['id']; ?></td> -->
                                            <td>
                                                <div class="tree" style="font-size:9px;">
                                                    <ul style="margin-bottom:0 !important;">
                                                        <li>
                                                            <?php
                                                                
                                                                $total = 0;
                                                                $link = Router::url(['controller'=>'mh_critico_historico',$row[$TABLE]['id']],true);
                                                                
                                                                if (isset($row['MhCriticoHistorico'])) {
                                                                    $total = count($row['MhCriticoHistorico']);
                                                                }
                                                                
                                                                #SE TIVER SUB
                                                                if(isset($rowsSub[$row[$TABLE]['mh_prestador_id']]) && count($rowsSub[$row[$TABLE]['mh_prestador_id']]) > 0){
                                                                    
                                                                    echo '<span style="padding-left:20px; min-width:260px;">
                                                                            <i class="fa fa-lg fa-plus-circle" style="float:left;margin-left:-15px;"></i> 
                                                                            <b>' . $row['MhPrestador']['nome'] . '</b> <br> 
                                                                            <b>Cidade:</b> ' . $row['MhPrestador']['cidade'] . ' &nbsp;&nbsp;
                                                                            <b>Estado:</b> ' . $row['MhPrestador']['estado'] . '<br><br>
                                                                            <a href="'.$link.'" style="">Histórico ('.$total.')</a>
                                                                            
                                                                        </span>';

                                                                        echo '<ul>';
                                                                            foreach ($rowsSub[$row[$TABLE]['mh_prestador_principal_id']] as $rowSub) {
                                                                                $total = 0;
                                                                                $link = Router::url(['controller'=>'mh_critico_historico','action'=>'index',$rowSub[$TABLE]['id']],true);
                                                                                if (isset($rowSub['MhCriticoHistorico'])) {
                                                                                    $total = count($rowSub['MhCriticoHistorico']);
                                                                                }
                                                                                
                                                                                echo '  <li style="display:none">
                                                                                            <span style="min-width:260px;"> 
                                                                                                <b>Opção:</b> ' . $rowSub[$TABLE]['opcao'] . ' <br>
                                                                                                <b>' . $rowSub['MhPrestador']['nome'] . '</b> <br>  
                                                                                                <b>Ciclo:</b> ' . $ArrCiclo[$rowSub[$TABLE]['ciclo']] . '  &nbsp;&nbsp;
                                                                                                <b>Cidade:</b> ' . $rowSub['MhPrestador']['cidade'] . ' &nbsp;&nbsp;
                                                                                                <b>Estado:</b> ' . $rowSub['MhPrestador']['estado'] . '<br><br>
                                                                                                <a href="'.$link.'">Histórico ('.$total.')</a>
                                                                                            </span>
                                                                                        </li>';
                                                                            }
                                                                        echo '</ul>';

                                                                }else{
                                                                    #SOMENTE O PRINCIPAL
                                                                    echo '<span style="min-width:260px;"> 
                                                                                <b>' . $row['MhPrestador']['nome'] . '</b> <br> 
                                                                                <b>Cidade:</b> ' . $row['MhPrestador']['cidade'] . ' &nbsp;&nbsp;
                                                                                <b>Estado:</b> ' . $row['MhPrestador']['estado'] . '<br><br>
                                                                                <a href="'.$link.'" style="">Histórico ('.$total.')</a>
                                                                        </span>';
                                                                        
                                                                }
                                                            ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $total = 0;
                                                if (isset($rowsSub[$row[$TABLE]['mh_prestador_principal_id']])) {
                                                    $total = count($rowsSub[$row[$TABLE]['mh_prestador_principal_id']]);
                                                }
                                                echo $total;
                                                ?>

                                            </td>
                                            <td><?php echo $ArrCiclo[$row[$TABLE]['ciclo']]; ?></td>
                                            <td><?php echo $ArrStatusCiclo[$row[$TABLE]['status_ciclo']]; ?></td>
                                            <td <?php echo $cel_hidden; ?>><?php echo $this->DateTime->dbToView($row[$TABLE]['data_cadastro']); ?></td>
                                            <td><?php echo $this->Funcoes->status($row[$TABLE]['status'], true); ?></td>
                                            <!-- <td class="actions">
                                                < ?php
                                                echo $this->Funcoes->menus('lista', $permissao, $row[$TABLE]['id']);
                                                ?>
                                            </td> -->
                                        </tr>
                                    <?php
                                    endforeach;
                                   
                                    echo $this->Funcoes->semRegistro($rows, 6, $search); ?>
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

<script>
    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function() {



        // PAGE RELATED SCRIPTS

        $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
        $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(':visible')) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
            } else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
            }
            e.stopPropagation();
        });

    })
</script>