<div id="main" role="main">
    <div id="content" class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                <div class="well no-padding">
                    <?php
                    echo $this->Form->create(
                        'Usuario',
                        array(
                            'id' => 'renew-form',
                            'url' => array(
                                'controller' => 'usuario',
                                'action'     => 'renew'
                            ),
                            'class' => 'smart-form client-form'
                        )
                    );
                    ?>
                    <header>
                        <?php echo $this->Html->image("logo_samed_pp.png", array("alt" => "Sistema Médico", "style" => "width:123px; ", "title" => "Sistema Médico",  "url" => "/"));
                        ?><br />
                        Requisição de nova senha
                    </header>

                    <fieldset>
                        <?php // echo str_replace('message','', $this->Session->flash());
                        ?>
                        <section>
                            <label class="label">Nova Senha</label>
                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                <?php echo $this->Form->hidden('token', array('value' => $this->params['named']['token'])); ?>
                                <?php echo $this->Form->input('senha', array('type' => 'password', 'label' => false, 'div' => false, 'placeholder' => 'Senha', 'class' => 'input_login', 'value' => '')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-blueLight"></i> Entre com sua Nova Senha</b></label>
                        </section>
                        <section>
                            <label class="label">Repetir Nova Senha</label>
                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                <?php echo $this->Form->input('retry_senha', array('type' => 'password', 'label' => false, 'div' => false, 'placeholder' => 'Repetir Senha', 'class' => 'input_login', 'value' => '')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-blueLight"></i> Repita a Nova Senha</b></label>
                        </section>
                    </fieldset>
                    <footer>
                        <button type="submit" class="btn btn-primary">
                            Salvar
                        </button>
                    </footer>
                    <?php echo $this->Form->end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>