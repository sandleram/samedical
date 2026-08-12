<?php echo $this->Element('admin/breadcrumb'); ?>

<div id="content">
    <section id="widget-grid" class="">
        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blue" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false">

                    <!--BEGIN - FILTRO -->
                    <div class="row" style="display:;">
                        <?php
                        echo $this->Form->msg($this->Session->flash());

                        echo $this->Form->create(
                            'relatorio_atendimentos_pendentes_form_busca',
                            array(
                                'id' => 'relatorio_atendimentos_pendentes_form_busca-search-form',
                                'url' => array(
                                    'controller' => 'relatorio',
                                    'action' => 'atendimentos_pendentes'
                                ),
                                'method' => 'GET',
                                'class' => 'smart-form client-form form_ajax'
                            )
                        );


                        $s_cod = $s_cod_pergunta = $s_cod_hm_programa = $s_usuario_agendamento_id = $s_status = '';
                        if (isset($search['cod_'])) {
                            $s_cod = $search['cod_'];
                        }
                        if (isset($search['usuario_agendamento_id'])) {
                            $s_usuario_agendamento_id = $search['usuario_agendamento_id'];
                        }
                        if (isset($search['status'])) {
                            $s_status = $search['status'];
                        }

                        ?>
                        <div class="row">

                            <?php if (in_array($this->Session->read('Auth.Usuario.perfil_id'), [1, 2, 3, 13])) {  ?>
                                <section class="col col-2">
                                    <label class="select">
                                        <?php echo $this->Form->input('usuario_agendamento_id', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => $usrList, 'default' => '', 'value' => $s_usuario_agendamento_id)); ?>
                                        <i></i>
                                </section>
                            <?php } ?>

                            <section class="col col-1" style=" padding-left: 0px; padding-right: 0px; ">
                                <label class="input">
                                    <?php
                                    if (isset($search) && is_array($search) && count($search) > 0):
                                        echo $this->Html->image("sys/filter-clear.png", array("alt" => "Limpar Filtros", "title" => "Limpar Filtros", "url" => array('action' => 'busca_unset', 'ALL'))) . ' ';
                                        echo $this->Html->link('Limpar Filtros', array('controller' => $this->params['controller'], 'action' => 'busca_unset', 'ALL'));
                                    endif;
                                    ?>
                                </label>
                            </section>
                            <section class="col col-1">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px; float: right;">
                                    Filtrar
                                </button>
                            </section>

                        </div>
                        <?php
                        echo $this->Form->end();
                        ?>
                    </div>
                    <!--END - FILTRO -->

                    <div>
                        <div class="table-responsive">
                            <?php
                            echo $this->Form->msg($this->Session->flash());
                            echo $this->Funcoes->menus('geral', 3);
                            ?>

                            <table class="table table-bordered">
                                <thead>
                                    <tr valign="middle">
                                        <th width="5">ID</th>
                                        <th>Cliente</th>
                                        <th width="10">Timeline</th>
                                        <th>Beneficiário <span class="note" style="font-size:10px;">(CPF)</span></th>
                                        <th>Data do Agendamento</th>
                                        <th>Responsável <span class="note" style="font-size:10px;">(Quem Criou)</span></th>
                                        <th>Data da Criação</th>
                                        <th>Status</th>
                                        <th width="260">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($rows as $row) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row[$TABLE]['id']; ?></td>
                                            <td><?php echo $row['Cliente']['nome']; ?></td>
                                            <td width="10"><a href="<?php echo Router::url('/admin/beneficiario/view/' . $row['Atendimento']['beneficiario_id'], true); ?>" class="btn btn-primary btn-xs"> <i class="fa  fa-user"></i> Acessar</a></td>
                                            <td>
                                                <?php echo $row['Beneficiario']['nome']; ?>
                                                <?php if ($row['Beneficiario']['cpf']) { ?>
                                                    <br><span class="note" style="font-size:10px;">(<?php echo $this->Funcoes->formata_cpf($row['Beneficiario']['cpf']); ?>)</span>
                                                <?php } ?>
                                            </td>
                                            <td><?php
                                                $dataAtual = date('Y-m-d H:i:s');
                                                $datamais2 = date('Y-m-d H:i:s', strtotime('+2 days', strtotime(date('Y-m-d H:i:s'))));

                                                $style = '';
                                                if ($row[$TABLE]['status'] != '1') {
                                                    $style = 'background-color:green; color:white; padding: 2px 4px';
                                                    if (strtotime($dataAtual) > strtotime($row[$TABLE]['data_hora'])) {
                                                        $style = 'background-color:red; color:white; padding: 2px 4px;';
                                                    } elseif (strtotime($datamais2) > strtotime($row[$TABLE]['data_hora'])) {
                                                        $style = 'background-color:yellow; padding: 2px 4px';
                                                    }
                                                }
                                                echo '<span style="' . $style . '">' . $this->DateTime->dbToView($row[$TABLE]['data_hora']) . '</span>';
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $row['UsuarioAgendamento']['nome'];

                                                if ($row[$TABLE]['usuario_id'] != $row[$TABLE]['usuario_agendamento_id']) {
                                                    echo '<br><span class="note" style="font-size:10px;">(' . $row['Usuario']['nome'] . ')</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $this->DateTime->dbToView($row[$TABLE]['data_cadastro']); ?></td>
                                            <td align="center"><?php echo $this->Funcoes->status_agenda($row[$TABLE]['status'], true); ?></td>
                                            <td>
                                                <?php
                                                if ($row[$TABLE]['status'] == '1') {
                                                    echo '  <a href="' . Router::url('/admin/beneficiario/view/' . $row['Beneficiario']['id'], true) . '" class="btn btn-primary btn-xs " style="margin-top: 2px;">
                                                            <i class="fa fa-user"></i> Acessar
                                                        </a>';
                                                    echo '<a href="' . Router::url('/admin/agendamento/add/' . $row['Beneficiario']['id'], true) . '" class="btn btn-success btn-xs abrir_cria_programa " style="margin: 5px;">
                                                            <i class="fa fa-plus-circle"></i> Abrir Novo Agendamento
                                                        </a>';
                                                } else {
                                                    echo '  <a href="' . Router::url('/admin/atendimento/add/' . $row['Beneficiario']['id'] . '/' . $row['Atendimento']['id'], true) . '" class="ajaxMsg btn btn-success btn-xs abrir_cria_programa " style="margin: 2px;" ajaxmsg="Tem certeza que deseja Assumir o atendimento `' . $row['Beneficiario']['nome'] . '´ deste Beneficiário?">
                                                            <i class="fa fa-user-md"></i> Iniciar Atendimento
                                                        </a>';
                                                    echo '  <a href="' . Router::url('/admin/agendamento/cancelar_agendamento/' . $row['Beneficiario']['id'] . '/' . $row['Atendimento']['id'], true) . '" class="ajaxMsg btn btn-danger btn-xs " style="margin: 2px;" ajaxmsg="Tem certeza que deseja CANCELAR o programa `' . $row['Beneficiario']['nome'] . '´ deste Beneficiário?">
                                                            <i class="fa fa-remove"></i> Cancelar
                                                        </a>';
                                                }





                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    echo $this->Funcoes->semRegistro($rows, 8, $search);
                                    ?>
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


                </div>
            </article>
        </div>
    </section>


</div>