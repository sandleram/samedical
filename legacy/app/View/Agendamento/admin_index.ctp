<?php echo $this->Element('admin/breadcrumb');?>

<div id="content">
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false">
                
                <!--BEGIN - FILTRO -->
                <div class="row" style="display:;">
                    <?php
                    echo $this->Form->msg($this->Session->flash());

                    echo $this->Form->create(
                            $this->params['controller'] . '_form_busca', array(
                        'id' => $this->params['controller'] . '-search-form',
                        'url' => array(
                            'controller' => $this->params['controller'],
                            'action' => 'index'
                        ),
                        'method' => 'GET',
                        'class' => 'smart-form client-form form_ajax'
                            )
                    );
                    
                        
                    $s_cod = $s_cod_pergunta = $s_cod_hm_programa = $s_usuario_agendamento_id = $s_search = $s_status = '';
                    if(isset($search['cod_'])){$s_cod = $search['cod_'];}
                    if(isset($search['usuario_agendamento_id'])){$s_usuario_agendamento_id = $search['usuario_agendamento_id'];}
                    if(isset($search['search'])){$s_search = $search['search'];}
                    if(isset($search['status'])){$s_status = $search['status'];}
                    
                    ?>
                    <div class="row" >
                        <section class="col col-1">        
                            <label class="input">
                                <?php echo $this->Form->hidden('search', array('label' => false, 'div' => false, 'placeholder' => 'search', 'class' => 'input_login', 'value' => $s_search)); ?>
                                <?php echo $this->Form->input('cod_', array('label' => false, 'div' => false, 'placeholder' => 'Código', 'class' => 'input_login', 'value' => $s_cod)); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o identificador</b>
                            </label>
                        </section>
                        <?php if(in_array($this->Session->read('Auth.Usuario.perfil_id'), [1,2,3,13])){  ?>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo $this->Form->input('usuario_agendamento_id', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => $usrList, 'default' => '', 'value' => $s_usuario_agendamento_id)); ?>
                                    <i></i>
                            </section>
                        <?php } ?>
                       
                        <section class="col col-2">
                            <label class="select">
                                <?php echo $this->Form->input('status', array('label' => false, 'div' => false, 'placeholder' => 'Status', 'class' => 'input_login', 'options' => array('' => 'Todos...',0=>'Aguardando',1=>'Concluído'), 'default' => '', 'value' => $s_status)); ?>
                                <i></i>
                        </section>
                        <section class="col col-1" style=" padding-left: 0px; padding-right: 0px; "> 
                            <label class="input" > 
                                <?php
                                if (isset($search) && is_array($search) && count($search) > 0):
                                    $class_msg = '';
                                    $ajaxmsg = '';
                                    
                                    if($search['search'] == 'atribuidos'){
                                        $class_msg = 'ajaxMsg';
                                        $ajaxmsg = 'Se limpar o filtro, removerá a visualização dos agendamentos atribuídos.';                                       
                                    }
                                    echo $this->Html->image("sys/filter-clear.png", array("alt" => "Limpar Filtros", "title" => "Limpar Filtros", "class" => $class_msg, "ajaxmsg" => $ajaxmsg, "url" => array('action' => 'busca_unset', 'ALL'))) . ' ';
                                    echo $this->Html->link('Limpar Filtros', array('controller' => $this->params['controller'], 'action' => 'busca_unset', 'ALL'),array('class' => $class_msg, 'ajaxmsg' => $ajaxmsg));
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
                                        <td><?php echo $row['Agendamento']['id']; ?></td>
                                        <td width="10"><a href="<?php echo Router::url('/admin/beneficiario/view/'.$row['Beneficiario']['id'],true); ?>" class="btn btn-primary btn-xs"> <i class="fa  fa-user"></i> Acessar</a></td>
                                        <td>
                                            <?php  echo $row['Beneficiario']['nome']; ?>
                                            <?php if($row['Beneficiario']['cpf']){?>
                                            <br><span class="note" style="font-size:10px;">(<?php echo $this->Funcoes->formata_cpf($row['Beneficiario']['cpf']); ?>)</span>
                                            <?php }?>
                                        </td>
                                        <td ><?php 
                                                $dataAtual = date('Y-m-d H:i:s');
                                                $datamais2 = date('Y-m-d H:i:s', strtotime('+2 days', strtotime(date('Y-m-d H:i:s'))));
                                                
                                                $style = '';
                                                if($row['Agendamento']['status'] != '1'){
                                                    $style = 'background-color:green; color:white; padding: 2px 4px';
                                                    if(strtotime($dataAtual) > strtotime($row['Agendamento']['data_hora'])){
                                                        $style = 'background-color:red; color:white; padding: 2px 4px;';
                                                    }elseif(strtotime($datamais2) > strtotime($row['Agendamento']['data_hora'])){
                                                        $style = 'background-color:yellow; padding: 2px 4px';
                                                    }
                                                }
                                                echo '<span style="'.$style.'">'.$this->DateTime->dbToView($row['Agendamento']['data_hora']).'</span>';
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                echo $row['UsuarioAgendamento']['nome'];
                                                
                                                if($row['Agendamento']['usuario_id'] != $row['Agendamento']['usuario_agendamento_id']){
                                                    echo '<br><span class="note" style="font-size:10px;">('.$row['Usuario']['nome'].')</span>';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $this->DateTime->dbToView($row['Agendamento']['data_cadastro']);?></td>
                                        <td align="center"><?php echo $this->Funcoes->status_agenda($row['Agendamento']['status'],true); ?></td>
                                        <td>
                                            <?php  
                                                if($row['Agendamento']['status'] == '1'){
                                                    echo '  <a href="'.Router::url('/admin/beneficiario/view/'.$row['Beneficiario']['id'],true).'" class="btn btn-primary btn-xs " style="margin-top: 2px;">
                                                            <i class="fa fa-user"></i> Acessar
                                                        </a>';
                                                    echo '<a href="'.Router::url('/admin/agendamento/add/'.$row['Beneficiario']['id'],true).'" class="btn btn-success btn-xs abrir_cria_programa " style="margin: 5px;">
                                                            <i class="fa fa-plus-circle"></i> Abrir Novo Agendamento
                                                        </a>';

                                                }else{
                                                    echo '  <a href="'.Router::url('/admin/atendimento/add/'.$row['Beneficiario']['id'].'/'.$row['Atendimento']['id'],true).'" class="ajaxMsg btn btn-success btn-xs abrir_cria_programa " style="margin: 2px;" ajaxmsg="Tem certeza que deseja Assumir o atendimento `'.$row['Beneficiario']['nome'].'´ deste Beneficiário?">
                                                            <i class="fa fa-user-md"></i> Iniciar Atendimento
                                                        </a>';
                                                    echo '  <a href="'.Router::url('/admin/agendamento/cancelar_agendamento/'.$row['Beneficiario']['id'].'/'.$row['Atendimento']['id'],true).'" class="ajaxMsg btn btn-danger btn-xs " style="margin: 2px;" ajaxmsg="Tem certeza que deseja CANCELAR o programa `'.$row['Beneficiario']['nome'].'´ deste Beneficiário?">
                                                            <i class="fa fa-remove"></i> Cancelar
                                                        </a>';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                            }
                            echo $this->Funcoes->semRegistro($rows,8,$search); 
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