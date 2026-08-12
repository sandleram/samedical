<?php echo $this->Element('admin/breadcrumb'); ?>
<div id="content">
    <section id="widget-grid" class="">

        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blue" id="wid-id-11" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-list"></i> </span>
                        <h2>Todos <?php echo $this->Funcoes->titulos($this->params['controller'], true); ?></h2>
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

                        $s_id = $s_nome = $s_email_ = $s_usuario = $s_perfil = $s_cliente = $s_status = '';
                        if (isset($search['id_'])) {
                            $s_id = $search['id_'];
                        }
                        if (isset($search['nome'])) {
                            $s_nome = $search['nome'];
                        }
                        if (isset($search['email_'])) {
                            $s_email_ = $search['email_'];
                        }
                        if (isset($search['usuario'])) {
                            $s_usuario = $search['usuario'];
                        }
                        if (isset($search['perfil'])) {
                            $s_perfil = $search['perfil'];
                        }
                        if (isset($search['cliente'])) {
                            $s_cliente = $search['cliente'];
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
                            <section class="col col-2">
                                <label class="input">
                                    <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'placeholder' => 'Nome', 'class' => 'input_login', 'value' => $s_nome)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Nome</b>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <?php echo $this->Form->input('usuario', array('label' => false, 'div' => false, 'placeholder' => 'Usuário', 'class' => 'input_login', 'value' => $s_usuario)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Usuário</b>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="input">
                                    <?php echo $this->Form->input('email_', array('label' => false, 'div' => false, 'placeholder' => 'E-mail', 'class' => 'input_login', 'value' => $s_email_)); ?>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o E-mail</b>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label class="select">
                                    <?php echo $this->Form->input('perfil', array('label' => false, 'div' => false, 'placeholder' => 'Perfil', 'class' => 'input_login', 'options' => $perfilArr, 'default' => '1', 'value' => $s_perfil)); ?>
                                    <i></i>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Perfil</b></label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo $this->Form->input('status', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => $statusArr, 'default' => '', 'value' => $s_status)); ?>
                                    <i></i>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Status</b></label>
                            </section>
                            <section class="col col-4">
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
                        <div class="table-responsive">
                            <?php
                            echo $this->Form->msg($this->Session->flash());
                            echo $this->Funcoes->menus('geral', $permissao);
                            //                                krumo($rows);
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5"><?php echo $this->Form->input('ck_id_all', array('label' => false, 'type' => 'checkbox', 'name' => 'ck_id_all', 'class' => 'ck_select_all', 'style' => 'margin-top:5px', 'hiddenField' => false)); ?></th>
                                        <th width="35">ID</th>
                                        <th width="70" <?php echo $cel_hidden; ?>>Imagem</th>
                                        <?php
                                        if ($perfil_root == $perfil_id) {
                                            echo '<th>G.E.</th>';
                                        }
                                        ?>
                                        <th>Perfil</th>
                                        <th>Nome</th>
                                        <th>Usuário</th>
                                        <th>E-mail</th>
                                        <th <?php echo $cel_hidden; ?>>Data de Cadastro</th>
                                        <th>Status</th>
                                        <th class="actions"><?php echo __('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?php echo $this->Form->input('ck_id' . $row[$TABLE]['id'], array('label' => false, 'type' => 'checkbox', 'name' => 'ck_id', 'class' => 'ck_delete', 'value' => $row[$TABLE]['id'], 'hiddenField' => false)); ?></td>
                                            <td><?php echo $row[$TABLE]['id']; ?></td>
                                            <td <?php echo $cel_hidden; ?>>
                                                <?php


                                                if (isset($row[$TABLE]['imagem']) && $row[$TABLE]['imagem'] != ''):
                                                    $img = '/img/uploads/usuario/mini/' . $row[$TABLE]['imagem'];
                                                else:
                                                    if ($row[$TABLE]['sexo'] == 'Masculino'): $img = '/img/avatars/user_male.png';
                                                    elseif ($row[$TABLE]['sexo'] == 'Feminino'): $img = '/img/avatars/user_female.png';
                                                    else: $img = '/img/avatars/male.png';
                                                    endif;
                                                endif;

                                                echo $this->Html->image($img, array(
                                                    "alt" => "",
                                                    "title" => $row[$TABLE]['nome'],
                                                    "rel" => Router::url('/img/uploads/' . $this->params['controller'] . '/') . $row[$TABLE]['imagem'],
                                                    "class" => "link_image",
                                                    "style" => "width:28px;"
                                                ));
                                                ?>
                                            </td>

                                            <?php
                                            if ($perfil_root == $perfil_id) {
                                                echo '<td>' . $row['GrupoEmpresarial']['nome'] . '</td>';
                                            }
                                            ?>
                                            <td><?php echo $this->Funcoes->marcatexto($row['Perfil']['nome'], $s_nome); ?></td>
                                            <td><?php echo $this->Funcoes->marcatexto($row[$TABLE]['nome'], $s_nome); ?></td>
                                            <td><?php echo $this->Funcoes->marcatexto($row[$TABLE]['usuario'], $s_usuario); ?></td>
                                            <td><?php echo $this->Funcoes->marcatexto($row[$TABLE]['email'], $s_email_); ?></td>
                                            <td <?php echo $cel_hidden; ?>><?php echo $this->DateTime->dbToView($row[$TABLE]['data_cadastro']); ?></td>
                                            <td><?php echo $this->Funcoes->status($row[$TABLE]['status'], true); ?></td>
                                            <td class="actions">
                                                <?php
                                                echo $this->Funcoes->menus('lista', $permissao, $row[$TABLE]['id']);
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                    echo $this->Funcoes->semRegistro($rows, 11, $search);  ?>
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