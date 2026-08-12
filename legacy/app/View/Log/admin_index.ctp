<?php echo $this->Element('admin/breadcrumb'); ?>

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

                        $s_id = $s_tabela = $s_campo = $s_ref_tabela = $s_log = $s_data_inicio  = $s_data_fim =  $s_description = '';
                        if (isset($search['id_'])) {
                            $s_id = $search['id_'];
                        }
                        if (isset($search['log'])) {
                            $s_log = $search['log'];
                        }
                        if (isset($search['description'])) {
                            $s_description = $search['description'];
                        }
                        if (isset($search['data_inicio'])) {
                            $s_data_inicio = $search['data_inicio'];
                        }
                        if (isset($search['data_fim'])) {
                            $s_data_fim = $search['data_fim'];
                        }

                        ?>
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <?php echo $this->Form->input('id_', array('label' => false, 'div' => false, 'placeholder' => 'ID', 'class' => 'input_login', 'value' => $s_id)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Identificador</b>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <?php echo $this->Form->input('log', array('label' => false, 'div' => false, 'placeholder' => 'Ação', 'class' => 'input_login', 'value' => $s_log)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Ação</b>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <?php echo $this->Form->input('description', array('label' => false, 'div' => false, 'placeholder' => 'Descrição', 'class' => 'input_login', 'value' => $s_description)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Descrição</b>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <?php echo $this->Form->date('data_inicio', array('dateFormat' => 'DMY', 'minYear' => date('Y') - 99, 'maxYear' => date('Y') - 18, 'label' => 'Data Inicio', 'div' => false, 'placeholder' => 'Data de Nascimento', 'dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10', 'value' => $s_data_inicio)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data Inicio</b>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <?php echo $this->Form->date('data_fim', array('dateFormat' => 'DMY', 'minYear' => date('Y') - 99, 'maxYear' => date('Y') - 18, 'label' => 'Data Fim', 'div' => false, 'placeholder' => 'Data de Nascimento', 'dateFormat' => 'DMY', 'class' => ' col3 margin-right-cadastre ', 'maxlength' => '10', 'value' => $s_data_fim)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data Fim</b>
                                </label>
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
                            //                                echo $this->Funcoes->menus('geral',$permissao);
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width='5'>ID</th>
                                        <th>Ação</th>
                                        <th>Usuário</th>
                                        <th style="width: 200px;">Mensagem</th>
                                        <th style="width: 200px;">Description</th>
                                        <th style="width: 200px;">Server Description</th>
                                        <th>Data de Cadastro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?php echo $row[$TABLE]['id']; ?></td>
                                            <td><?php echo $this->Funcoes->marcatexto($row[$TABLE]['log'], $s_log); ?></td>
                                            <td><?php echo $row['Usuario']['nome']; ?></td>
                                            <td><span class="note" style="word-wrap: break-word; width: 200px;"><?php echo $row[$TABLE]['mensagem']; ?></span></td>
                                            <td>
                                                <div class="note" style="word-wrap: break-word; width: 200px;"><?php echo $row[$TABLE]['description']; ?></div>
                                            </td>
                                            <td><span class="note" style="word-wrap: break-word; width: 200px;"><?php echo $row[$TABLE]['server_description']; ?></span></td>
                                            <td><?php echo $this->DateTime->dbToView($row[$TABLE]['data_cadastro']); ?></td>
                                        </tr>
                                    <?php endforeach;
                                    echo $this->Funcoes->semRegistro($rows, 7, $search); ?>
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