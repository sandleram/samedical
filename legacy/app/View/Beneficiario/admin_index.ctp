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

                        $s_id = $s_nome = $s_status = $s_cpf = $s_nome_social = $s_situacao = '';
                        if (isset($search['id_'])) {
                            $s_id = $search['id_'];
                        }
                        if (isset($search['nome'])) {
                            $s_nome = $search['nome'];
                        }
                        if (isset($search['nome_social'])) {
                            $s_nome_social = $search['nome_social'];
                        }
                        if (isset($search['cpf'])) {
                            $s_cpf = $search['cpf'];
                        }
                        if (isset($search['status'])) {
                            $s_status = $search['status'];
                        }
                        if (isset($search['situacao'])) {
                            $s_situacao = $search['situacao'];
                        }
                        if (isset($this->params['named']['situacao'])) {
                            $s_situacao = $this->params['named']['situacao'];
                        }
                        ?>
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <?php echo $this->Form->input('id_', array('label' => false, 'div' => false, 'placeholder' => 'ID', 'class' => 'input_login', 'value' => $s_id)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Identificador</b>
                                </label>
                            </section>

                            <section class="col col-1">
                                <label class="input">
                                    <?php echo $this->Form->input('cpf', array('label' => false, 'div' => false, 'placeholder' => 'CPF', 'class' => 'cpf_mask input_login', 'value' => $s_cpf)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o CPF</b>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Nome', 'class' => 'input_login', 'value' => $s_nome)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Nome</b>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <?php echo $this->Form->input('nome_social', array('label' => false, 'div' => false, 'placeholder' => 'Nome Social', 'class' => 'input_login', 'value' => $s_nome_social)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Nome Social</b>
                                </label>
                            </section>

                            <section class="col col-1">
                                <label class="select">
                                    <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => array('' => 'Situação...', 'Ativo' => 'Ativo', 'Inativo' => 'Inativo'), 'default' => '', 'value' => $s_situacao)); ?>
                                    <i></i>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Status</b></label>
                            </section>
                            <section class="col col-1">
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
                                    if (isset($search) && is_array($search) && count($search) > 0):
                                        echo $this->Html->image("sys/filter-clear.png", array("alt" => "Limpar Filtros", "title" => "Limpar Filtros", "url" => array('action' => 'admin_busca_unset', 'ALL'))) . ' ';
                                        echo $this->Html->link('Limpar Filtros', array('controller' => $this->params['controller'], 'action' => 'admin_busca_unset', 'ALL'));
                                    elseif (isset($this->params['named']['situacao'])):
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
                        <div class="table-responsive ">
                            <?php
                            echo $this->Form->msg($this->Session->flash());

                            echo $this->Funcoes->menus('geral', $permissao);
                            //                                krumo($rows);
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width='5'><?php echo $this->Form->input('ck_id_all', array('label' => false, 'type' => 'checkbox', 'name' => 'ck_id_all', 'class' => 'ck_select_all', 'style' => 'margin-top:5px', 'hiddenField' => false)); ?></th>
                                        <th width='5'>ID</th>
                                        <th width='10'>Timeline</th>
                                        <th width='110'>CPF</th>
                                        <th>Nome</th>
                                        <th>Nome Social</th>
                                        <th <?php echo $cel_hidden; ?> width='150'>Data de Cadastro</th>
                                        <th>Situação</th>
                                        <?php if (in_array($perfil_id, $perfil_adm) || $permissao == 3): ?>
                                            <th width='20'>Status</th>
                                            <th class="actions" width='20'><?php echo __('Actions'); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $qtd_colunas = 13;
                                    foreach ($rows as $row):
                                    ?>
                                        <tr>
                                            <td><?php echo $this->Form->input('ck_id' . $row[$TABLE]['id'], array('label' => false, 'type' => 'checkbox', 'name' => 'ck_id', 'class' => 'ck_delete', 'value' => $row[$TABLE]['id'], 'hiddenField' => false)); ?></td>
                                            <td><?php echo $row[$TABLE]['id']; ?></td>
                                            <td><a href="<?php echo Router::url(array('action' => 'view', $row[$TABLE]['id'])); ?>" class="btn btn-primary btn-xs"> <i class="fa  fa-user"></i> Acessar</a></td>
                                            <td><?php echo $this->Funcoes->formata_cpf($row[$TABLE]['cpf']); ?></td>
                                            <td><?php echo $this->Funcoes->marcatexto($row[$TABLE]['nome'], $s_nome); ?></td>
                                            <td><?php echo $this->Funcoes->marcatexto($row[$TABLE]['nome_social'], $s_nome_social); ?></td>
                                            <td <?php echo $cel_hidden; ?>><?php echo $this->DateTime->dbToView($row[$TABLE]['data_cadastro']); ?></td>
                                            <td><?php


                                                if ($row[$TABLE]['situacao'] != '') {
                                                    $style_benef = "border-radius:6px; color:white;background-color:red; padding:2px 4px;";
                                                    if ($row[$TABLE]['situacao'] == 'Ativo') {
                                                        $style_benef = "border-radius:6px; color:white;background-color:green; padding:2px 4px;";
                                                    }

                                                    echo $sitBenef = '<span style="' . $style_benef . '">' . @$row[$TABLE]['situacao'] . '</span><br>';
                                                }

                                                ?>
                                            </td>
                                            <?php
                                            # krumo($permissao);
                                            if (in_array($perfil_id, $perfil_adm)  || $permissao == 3):
                                                $qtd_colunas = 12;
                                            ?>

                                                <td><?php echo $this->Funcoes->status($row[$TABLE]['status'], true); ?></td>
                                                <td class="actions">
                                                    <?php
                                                    echo $this->Funcoes->menus('lista', $permissao, $row[$TABLE]['id']);
                                                    ?>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach;
                                    echo $this->Funcoes->semRegistro($rows, $qtd_colunas, $search);
                                    ?>
                                </tbody>
                            </table>
                            <div class="note" style="margin:10px;">* O acesso ao dashboard só terá efeito sobre a empresa clicada se estiver deslogado!</div>
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