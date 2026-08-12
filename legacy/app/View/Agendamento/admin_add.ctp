<?php echo $this->Element('admin/breadcrumb'); ?>

<style>
    h3 {
        margin-bottom: 20px !important;
        border-bottom: 1px dotted #d3d3d3;
    }
</style>

<div id="content">
    <?php
    echo $this->Form->msg($this->Session->flash());

    echo $this->Form->create(
        $TABLE,
        array(
            'type' => 'file',
            'id' => $this->params['controller'] . '-form',
            'url' => array(
                'controller' => $this->params['controller'],
                'action' => 'add',
                $this->params['pass'][0]
            ),
            'class' => 'smart-form client-form '
        )
    );
    echo $this->Form->msg($this->Session->flash());
    ?>
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding">
                <?php // echo $this->Funcoes->menus('geral', $permissao); 
                ?>
                <header>
                    <?php echo (isset($this->params['pass'][1])) ? 'Edição' : 'Cadastro'; ?> de <?php echo $this->Funcoes->titulos($this->params['controller']); ?>
                </header>
                <fieldset>
                    <?php
                    $obrigatorio = '<span class="campo_obrigatorio">*</span>';
                    echo $this->Form->hidden('beneficiario_id', array('value' => $beneficiario_id));
                    $disabled = array();
                    ?>



                    <div class="row">
                        <section class="col col-2">
                            <label class="label">
                                <strong>Beneficiário:</strong> <?php echo $nome; ?>

                            </label>
                        </section>
                        <section class="col col-10">
                            <label class="label" style="text-align: right;">
                                <?php
                                echo '  <a href="' . Router::url('/admin/beneficiario/view/' . $beneficiario_id, true) . '" class="" style="margin-top: 2px;">
                                                <i class="fa fa-medkit"></i> Voltar para o Beneficiário
                                            </a>';
                                ?>
                            </label>
                        </section>
                    </div>




                    <div class="row exibe_btn_acesso" style="display: none; padding:10px;">
                        <section class=" ">
                            <label class="label" local_titulo_acesso></label>
                            <label class="input local_bnt_acesso"></label>
                        </section>
                    </div>


                    <div class="row abrir_agendamento" style="display:;">
                        <section class="col col-3">
                            <label class="label">Data Hora do Agendamento: </label>
                            <label class="input">
                                <?php $data_hora_default =  date('Y-m-d 8:00:00', strtotime('+1 day')); ?>
                                <?php echo $this->Form->input('data_hora', array('type' => 'datetime', 'label' => false, 'div' => false, 'placeholder' => 'Data Hora ', 'class' => 'input_login', 'minYear' => date('Y'), 'maxYear' => date('Y') + 3, 'dateFormat' => 'DMY', 'timeFormat' => '24', 'selected' => $data_hora_default)); ?>

                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a Data e Hora Vestibular</b>
                                <?php
                                if (!isset($this->params['pass'][1])) {
                                    echo '<br><br> <span class="note">Lembrete: Data de Hoje "' . date('d/m/Y') . '"</span>';
                                }
                                ?>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Deseja Agendar para você? <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('usuario_agendamento_proprio_id', array('label' => false, 'div' => false, 'placeholder' => 'Agendamento', 'class' => 'input_login', 'options' => array('' => 'Selecione...', '0' => 'Não', '1' => 'Sim'), 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-3 exibir_atribuir_para" style="display:none;">
                            <label class="label">Atribuir para: <?php echo $obrigatorio; ?></label>
                            <label class="select">
                                <?php echo $this->Form->input('usuario_agendamento_id', array('label' => false, 'div' => false, 'placeholder' => 'Agendamento', 'class' => 'input_login', 'options' => $usuariosAgendaArr, 'default' => '')); ?>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row abrir_agendamento" style="display:;">
                        <section class="col col-6">
                            <label class="label">Descrição: </label>
                            <label class="input">
                                <?php echo $this->Form->input('descricao', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '', 'class' => 'input_login')); ?>

                                <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com a descrição</b>
                               
                            </label>
                        </section>
                       
                    </div>
                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary btn_submit ">
                        Agendar
                    </button>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer> 
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>


<script type="text/javascript">
    $(document).ready(function() {


        //VERIFICA SE IRÁ CADASTRAR O PRÓPRIO USUÁRIO OU UM OUTRO PARA O AGENDAMENTO
        $('#AgendamentoUsuarioAgendamentoProprioId').change(function() {
            agendamento = $(this).val();
            if (agendamento == '0') {
                $('.exibir_atribuir_para').fadeIn('slow');
            } else {
                $('.exibir_atribuir_para').fadeOut('slow');
                $('#AgendamentoUsuarioAgendamentoId').val('');
            }
        })

    });
</script>