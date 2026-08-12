<style type="text/css">
    body {
        /*background-image: url('img/bg_login.jpg') !important;*/

    }

    .imagem {
        /* background: url(< ?php echo $this->params->base?>/img/bg_acesso.jpg) no-repeat center center fixed;  */
        background: url(<?php echo $this->params->base ?>/img/bg_samed.jpg);
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        width: 100%;
        height: 100%;
        position: fixed;
    }

    .client-form {
        background: none;
    }

    .smart-form {
        background: none;
    }

    footer {
        background: rgba(248, 248, 248, .9);
    }

    .well {
        background: none;
    }

    .content {
        background: none;
    }

    .toggle-senha {
        cursor: pointer;
        right: 30px;
        /* afasta do ícone de lock */
    }

    .g-recaptcha {
        transform: scale(0.85);
        transform-origin: 0 0;
        margin-bottom: -10px;
    }
</style>
<div class="imagem"></div>
<div id="main" role="main">
    <div id="content" class="" style="">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 hidden-xs hidden-sm" style="color:#fff;margin-left: 10%; ">
                <div class="row" style="margin-bottom:40px; margin-right:30px; ">
                    <?php echo $this->Html->image("logo_samed_tp.png", array("alt" => "Sistema Médico", "style" => "", "title" => "Sistema Médico",  "url" => "/")); ?>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-family: system-ui;font-size:14px;background-color:#0064a782; margin-top:20px;  border-radius:12px; padding:10px 20px 20px;">
                        <h1 style="font-weight: bold; font-size: 24px;">SAMED - Sistema Médico </h1>
                        <br>
                        <p> O SAMED é uma plataforma desenvolvida para integrar e gerenciar informações de beneficiários de forma centralizada, segura e inteligente.<br></p>
                        <p>Voltado para médicos, enfermeiros e equipes de saúde, o sistema facilita o acompanhamento clínico e apoia a tomada de decisões baseada em dados.<br></p>
                        <p>Com uma estrutura unificada, o SAMED transforma informações em insights que ajudam a otimizar processos, ganhar eficiência operacional e melhorar a qualidade do cuidado em saúde.<br></p>
                        <p>A inovação faz parte da essência do SAMED, oferecendo uma solução moderna, confiável e preparada para apoiar a evolução da gestão em saúde.</p>
                    </div>
                </div>


            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 " style="margin-top:30px; ">
                <div class="well no-padding ">
                    <?php

                    $message_flash = $this->Session->flash();
                    $message_session = $this->Session->flash('auth');

                    if ($message_flash != false) {
                        $class = 'danger';
                        if (preg_match('/novamente/', $message_flash) || preg_match('/Novamente/', $message_flash)) {
                            $class = 'warning';
                        } elseif (preg_match('/sucesso/', $message_flash) || preg_match('/Sucesso/', $message_flash)) {
                            $class = 'success';
                        }

                        $message_session = '    <div id="authMessage" class=""><div class="alert adjusted alert-' . $class . ' fade in">
                                                    <button class="close" data-dismiss="alert">
                                                        ×
                                                    </button>
                                                    <i class="fa-fw fa-lg fa fa-exclamation"></i> <strong>Aviso:</strong> ' . $message_flash . '</div></div>';
                    }

                    #type = flipInY / shake / bounce / tada / swing / wobble / pulse / flip / flipOutX / flipInY / hinge / flash
                    $animated = 'animated bounce';
                    if ($message_session != false):
                        $animated = 'animated tada';
                    //                            $animated = ''; 
                    endif;
                    echo $this->Form->create(
                        'Usuario',
                        array(
                            'id' => 'login-form',
                            'url' => array(
                                'controller' => 'usuario',
                                'action'     => 'login'
                            ),
                            'class' => 'smart-form client-form ' . $animated
                        )
                    );
                    ?>



                    <header style="padding: 15px  13px 15px 13px; text-align: center;">
                        <div class="show-for-small hidden-lg hidden-md">
                            <?php echo $this->Html->image("logo_samed_pp.png", array("alt" => "Sistema Médico", "style" => "width:123px; ", "title" => "Sistema Médico",  "url" => "/"));
                            ?>
                            <span style="margin-left:30px; font-size: 16px;"><strong>Acesso ao Sistema</strong></span>
                        </div>
                        <div class="hidden-sm hidden-xs">
                            <b> ACESSO AO SISTEMA</b>
                        </div>
                    </header>

                    <fieldset>
                        <?php echo str_replace('message', '', $message_session); ?>
                        <section>
                            <label class="label">Usuário</label>
                            <label class="input">
                                <?php echo $this->Form->input('usuario', array('label' => false, 'div' => false, 'placeholder' => 'Usuário', 'class' => 'input_login verifica_capslock')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-blueLight"></i> Entre com seu usuário</b></label>
                        </section>
                        <section>
                            <label class="label">Senha</label>
                            <label class="input">
                                <i class="icon-append fa fa-eye toggle-senha"
                                    onclick="toggleSenha(this)"></i>
                                <?php echo $this->Form->input('senha', array('type' => 'password', 'label' => false, 'div' => false, 'placeholder' => 'Senha', 'class' => 'input_login verifica_capslock')); ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Entre com a sua senha </b> </label>
                            <div class="note">
                                <?php echo $this->Html->link('Esqueceu a senha?', '/usuario/forgot'); ?>
                            </div>
                        </section>
                        <section>
                            <div class="g-recaptcha" data-sitekey="6LeuUFgqAAAAAPW5-fbBjwERz2W2fcYDXj1gLIDX"></div>
                        </section>

                    </fieldset>
                    <footer>
                        <div id="fa fa-warning" class="aviso_capslock " style="margin-bottom:10px; color:#DA8028; float:left; display:none;">
                            <i class="fa fa-warning" style="color:#DB8028;"></i> O Caps Lock esta ativado!
                        </div>
                        <?php
                        $localhost = explode(':', $_SERVER['HTTP_HOST']);
                        if (in_array($localhost[0], array('localhost'))) {
                            echo '<button type="submit" class="btn btn-primary">
                                        Acessar
                                    </button>';
                        } else {
                            echo '<button type="submit" class="btn btn-primary">
                                        Acessar
                                    </button>';
                            // echo '<button type="submit"
                            //             class="g-recaptcha btn btn-primary" 
                            //             data-sitekey="6Lf56XIaAAAAAJWTrRwzNERfz_dnM61x0P4h4jOc" 
                            //             data-callback="onSubmit" 
                            //             data-action="submit"
                            //             style="float:left !important;">Acessar</button>';
                        }
                        ?>


                    </footer>
                    <?php echo $this->Form->end(); ?>

                </div>
            </div>
        </div>
        <script>
            function onSubmit(token) {
                document.getElementById("login-form").submit();
            }

            function toggleSenha(el) {
                const input = document.getElementById('UsuarioSenha');

                if (input.type === 'password') {
                    input.type = 'text';
                    el.classList.remove('fa-eye');
                    el.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    el.classList.remove('fa-eye-slash');
                    el.classList.add('fa-eye');
                }
            }
        </script>



    </div>
</div>
<div style="bottom: 0;  height: 20px; width: 100%; position: fixed; z-index: 1000000;">
    Todos os Direitos Reservados samed.app.br © 2017-<?php echo date('Y'); ?>
</div>