<div id="main" role="main">
    <div id="content" class="">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 ">
                <div class="well no-padding">
                    <?php
                    echo $this->Form->create(
                        'Usuario',
                        array(
                            'id' => 'forgot-form',
                            'url' => array(
                                'controller' => 'usuario',
                                'action'     => 'forgot'
                            ),
                            'class' => 'smart-form client-form'
                        )
                    );


                    $message_session = $this->Session->flash();

                    if ($message_session != false) {
                        $class = 'danger';
                        if (preg_match('/novamente/', $message_session) || preg_match('/Novamente/', $message_session)) {
                            $class = 'warning';
                        } elseif (preg_match('/sucesso/', $message_session) || preg_match('/Sucesso/', $message_session)) {
                            $class = 'success';
                        }

                        $message_session = '    <div id="authMessage" class=""><div class="alert adjusted alert-' . $class . ' fade in">
                                                    <button class="close" data-dismiss="alert">
                                                        ×
                                                    </button>
                                                    <i class="fa-fw fa fa-warning"></i> <strong>Aviso:</strong> ' . $message_session . '</div></div>';
                    }


                    ?>
                    <header>
                        <!-- < ?php echo $this->Html->image("logo-med.png", array("alt" => "SAMed", "style" => "width:100px;", "title" => "SAMed",  "url" => "/usuario/login")); ?> -->
                        <?php echo $this->Html->image("logo_samed_pp.png", array("alt" => "Sistema Médico", "style" => "width:123px; ", "title" => "Sistema Médico",  "url" => "/"));
                        ?>
                        <span style="margin-left:30px; font-size: 16px;"><strong>Requisição de nova senha</strong></span>
                    </header>

                    <fieldset>

                        <?php echo str_replace('message', '', $message_session); ?>
                        <?php #http://localhost/litoralverde/be/usuario/renew/token:e294ab7cd8864506eb8187b8312bd08c


                        ?>
                        <section>
                            <label class="label">Endereço de Email</label>
                            <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                <?php echo $this->Form->input('email', array('label' => false, 'div' => false, 'placeholder' => 'Email', 'class' => 'input_login', 'value' => $email)); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-blueLight"></i> Entre com seu email de cadastro</b></label>
                            <div class="note">
                                <?php echo $this->Html->link('Voltar para Tela de Login', '/usuario/login'); ?>
                            </div>
                        </section>

                    </fieldset>
                    <footer>
                        <button type="submit" class="btn btn-primary">
                            Enviar Requisição
                        </button>
                    </footer>
                    <?php echo $this->Form->end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>