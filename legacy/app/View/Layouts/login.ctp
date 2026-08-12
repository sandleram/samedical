<?php

/**
 *
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 */

$cakeDescription = __d('cake_dev', Configure::read('variaveis.project'));
$prefix = 'admin/';
?>
<!DOCTYPE html>
<html>

<head>
        <?php echo $this->Html->charset(); ?>
        <title>
                <?php echo $cakeDescription ?>:
                <?php echo $title_for_layout; ?>
        </title>

        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->meta('viewport', null, array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'));

        #BASIC CSS
        echo $this->Html->css($prefix . 'bootstrap.min.css');
        echo $this->Html->css($prefix . 'font-awesome.min.css');

        #SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables
        echo $this->Html->css($prefix . 'smartadmin-production.css');
        echo $this->Html->css($prefix . 'smartadmin-skins.css');
        echo $this->Html->css($prefix . 'demo.css');
        if ($this->params['action'] == 'lock'):
                echo $this->Html->css($prefix . 'lockscreen.css');
        endif;

        #GOOGLE FONT
        echo $this->Html->css($prefix . 'font-google.css');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        ?>
</head>

<?php

$class = '';
if ($this->params['action'] != 'lock'):
        $class = 'class="animated fadeInDown"';
endif;
?>


<body id="login" <?php echo $class; ?>>
        <?php
        echo $this->fetch('content');

        echo $this->Html->script($prefix . 'plugin/pace/pace.min.js'); #PACE LOADER

        #Link to Google CDN's jQuery + jQueryUI; fall back to local
        echo $this->Html->script($prefix . 'jquery.min.js');
        echo "  <script>  
                    if (!window.jQuery) { documento.write('" . str_replace('</script>', '<\/script>', $this->Html->script($prefix . 'libs/jquery-2.0.2.min.js')) . "');}
                </script>";

        echo $this->Html->script($prefix . 'jquery-ui.min.js');
        echo "  <script>  
                    if (!window.jQuery.ui) { documento.write('" . str_replace('</script>', '<\/script>', $this->Html->script($prefix . 'libs/jquery-ui-1.10.3.min.js')) . "');}
                </script>";

        echo $this->Html->script($prefix . 'jquery.maskedinput.js'); #MÁSCARA DE CAMPOS JS
        echo $this->Html->script($prefix . 'ajax_init.js'); #AJAX JS
        echo $this->Html->script($prefix . 'bootstrap/bootstrap.min.js'); #BOOTSTRAP JS
        echo $this->Html->script($prefix . 'notification/SmartNotification.min.js'); #CUSTOM NOTIFICATION
        echo $this->Html->script($prefix . 'smartwidgets/jarvis.widget.min.js'); #JARVIS WIDGETS
        echo $this->Html->script($prefix . 'plugin/easy-pie-chart/jquery.easy-pie-chart.min.js'); #EASY PIE CHARTS
        echo $this->Html->script($prefix . 'plugin/sparkline/jquery.sparkline.min.js'); #SPARKLINES
        echo $this->Html->script($prefix . 'plugin/jquery-validate/jquery.validate.min.js'); #JQUERY VALIDATE
        echo $this->Html->script($prefix . 'plugin/masked-input/jquery.maskedinput.min.js'); #JQUERY MASKED INPUT
        echo $this->Html->script($prefix . 'plugin/masked-money/jquery.maskMoney.js'); #JQUERY MASKED MONEY INPUT
        echo $this->Html->script($prefix . 'plugin/select2/select2.min.js'); #JQUERY SELECT2 INPUT
        echo $this->Html->script($prefix . 'plugin/bootstrap-slider/bootstrap-slider.min.js'); #JQUERY UI + Bootstrap Slider
        echo $this->Html->script($prefix . 'plugin/msie-fix/jquery.mb.browser.min.js'); #browser msie issue fix
        echo $this->Html->script($prefix . 'plugin/fastclick/fastclick.js'); #FastClick: For mobile devices
        echo '<!--[if IE 7]>';
        echo '<h1>Seu navegador está desatualizado, por favor atualize acesando www.microsoft.com/download</h1>';
        echo '<![endif]-->';
        echo $this->Html->script($prefix . 'app.js'); #MAIN APP JS FILE


        #BEGIN - FOCUS INICIAL
        $script = '<script>
                        $(document).ready(function(){
                            $("[NOME]").focus();
                        });
                   </script>';

        if ($this->params['action'] == 'lock'):
                echo str_replace('[NOME]', '#UsuarioSenha', $script);
        elseif ($this->params['action'] == 'login'):
                echo str_replace('[NOME]', '#UsuarioUsuario', $script);
        endif;
        #END - FOCUS INICIAL


        echo $this->fetch('script');


        ?>


        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <!-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
                async defer>
        </script>
        <script type="text/javascript">
                var onloadCallback = function() {
                        grecaptcha.render('html_element', {
                                'sitekey': '6LeuUFgqAAAAAPW5-fbBjwERz2W2fcYDXj1gLIDX'
                        });
                };
        </script> -->

        <script type="text/javascript">
                runAllForms();
                $(function() {
                        // Validation
                        $("#login-form").validate({
                                // Rules for form validation
                                rules: {
                                        UsuarioUsuario: {
                                                required: true,
                                                email: false
                                        },
                                        UsuarioSenha: {
                                                required: true,
                                                minlength: 3,
                                                maxlength: 20
                                        }
                                },
                                messages: {
                                        UsuarioUsuario: {
                                                required: 'Por favor, entre com seu email',
                                                email: 'Por favor, entre com um endereço de email válido'
                                        },
                                        UsuarioSenha: {
                                                required: 'Por favor, entre com sua senha'
                                        }
                                },
                                errorPlacement: function(error, element) {
                                        error.insertAfter(element.parent());
                                }
                        });
                        $("#forgot-form").validate({
                                // Rules for form validation
                                rules: {
                                        UsuarioEmail: {
                                                required: true,
                                                email: true
                                        }
                                },
                                messages: {
                                        UsuarioEmail: {
                                                required: 'Por favor, entre com seu email',
                                                email: 'Por favor, entre com um endereço de email válido'
                                        }
                                },
                                errorPlacement: function(error, element) {
                                        error.insertAfter(element.parent());
                                }
                        });
                        $("#renew-form").validate({
                                // Rules for form validation
                                rules: {
                                        UsuarioSenha: {
                                                required: true
                                        },
                                        UsuarioRetrySenha: {
                                                required: true,
                                                equalTo: "#UsuarioSenha"
                                        },
                                },
                                messages: {
                                        UsuarioSenha: {
                                                required: 'O campo de nova senha é obrigatorio'
                                        },
                                        UsuarioRetrySenha: {
                                                required: 'O campo confirmação de nova senha é obrigatorio',
                                                equalTo: "O campo confirmação de nova senha deve ser idêntico ao campo senha"
                                        },
                                },
                                errorPlacement: function(error, element) {
                                        error.insertAfter(element.parent());
                                }
                        });
                        $("#prova-acesso-form").validate({
                                // Rules for form validation
                                rules: {
                                        ProvaCpf: {
                                                required: true
                                        },

                                },
                                messages: {
                                        ProvaCpf: {
                                                required: 'O campo de CPF é obrigatorio'
                                        },
                                },
                                errorPlacement: function(error, element) {
                                        error.insertAfter(element.parent());
                                }
                        });


                });


                /**
                 * @description VERIFICA SE O CAPS LOCK ESTÁ ATIVADO PELA CLASSE E LIBERA A EXIBIÇÃO DO TEXTO
                 * @author SANDLER ALMEIDA MATOS
                 * @param {type} param
                 */
                $(document).ready(function() {
                        $('.verifica_capslock').keypress(function(e) {
                                kc = e.keyCode ? e.keyCode : e.which;
                                sk = e.shiftKey ? e.shiftKey : ((kc == 16) ? true : false);
                                if (((kc >= 65 && kc <= 90) && !sk) || ((kc >= 97 && kc <= 122) && sk)) {
                                        $('.aviso_capslock').show();
                                } else {
                                        $('.aviso_capslock').hide();
                                }
                        });

                });
        </script>
</body>

</html>