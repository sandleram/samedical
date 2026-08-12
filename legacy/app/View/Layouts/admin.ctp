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
    //                    echo $this->Html->css($prefix.'smartadmin-production-plugins.min.css');
    //                    echo $this->Html->css($prefix.'smartadmin-production.min.css');
    echo $this->Html->css($prefix . 'smartadmin-production.css');
    echo $this->Html->css($prefix . 'smartadmin-skins.css');
    echo $this->Html->css($prefix . 'plugin/tag-it/jquery.tagit.css');
    echo $this->Html->css($prefix . 'plugin/tag-it/tagit.ui-zendesk.css');
    echo $this->Html->css($prefix . 'plugin/uploadfile/uploadfile.min.css');




    //                    echo $this->Html->css('demo.css');

    #GOOGLE FONT
    echo $this->Html->css($prefix . 'font-google.css');

    echo $this->fetch('meta');
    echo $this->fetch('css');

    #PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices
    echo $this->Html->script($prefix . 'plugin/pace/pace.min.js', array('data-pace-options' => "{ \"restartOnRequestAfter\": true }"));
    echo $this->Html->script($prefix . 'jquery.min.js');
    ?>
</head>
<!--<body class=" fixed-header fixed-ribbon fixed-navigation smart-style-3">-->
<!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width, fixed-navigation-->
<?php
//           $class_default = 'fixed-header fixed-ribbon fixed-navigation smart-style-3';
$class_default = 'fixed-header fixed-ribbon fixed-navigation smart-style-3';
if (isset($_SESSION['menu_geral'])) {
    $class_default .= ' ' . $_SESSION['menu_geral'];
}

?>

<body class=" <?php echo $class_default; ?> " rel_url="<?php echo $link_geral; ?>" rel_controller="<?php echo $this->params['controller']; ?>" rel_action="<?php echo $this->params['action']; ?>">

    <style type="text/css">

    </style>
    <div id="boxes">
        <div id="dialog2" class="window"></div>
        <div id="mask"></div>


        <?php
        #$cache = array('cache'=>true);#DESCOMENTAR
        $cache = array(); #COMENTAR
        echo $this->element('admin/header', array(), $cache);
        echo $this->element('admin/menu', array(), $cache);

        echo '<div id="main" role="main">';
        echo    $this->fetch('content');
        if ($usuario_id == $uRoot):
        #echo  '<div style="font-size:12px; color:#060;  margin:15px; padding:0 10px 10px 10px; border:#060 2px dotted;"> <h3 style="text-align:center;"><u>DEBUG</u></h3>'.$this->element('sql_dump').'</div>';
        endif;

        echo '</div>';
        echo $this->element('admin/profile_menu', array(), array('cache' => false));
        //                    echo "<script data-pace-options='{ \"restartOnRequestAfter\": true }' src=\"plugin/pace/pace.min.js\"></script>";

        echo "  <script>  
                                if (!window.jQuery) { documento.write('" . str_replace('</script>', '<\/script>', $this->Html->script($prefix . 'libs/jquery-2.0.2.min.js')) . "');}
                            </script>";
        echo $this->Html->script($prefix . 'jquery-ui.min.js');
        echo "  <script>  
                                if (!window.jQuery.ui) { documento.write('" . str_replace('</script>', '<\/script>', $this->Html->script($prefix . 'libs/jquery-ui-1.10.3.min.js')) . "');}
                                </script>";

        /**
         * <!-- JS TOUCH : include this plugin for mobile drag / drop touch events
                            <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->
         */

        echo $this->Html->script($prefix . 'jquery.maskedinput.js'); #MÁSCARA DE CAMPOS JS
        //                    echo $this->Html->script('plugin/bootstrap-tags/bootstrap-tagsinput.min.js');#tags
        echo $this->Html->script($prefix . 'plugin/tag-it/tag-it.js'); #tags
        echo $this->Html->script($prefix . 'plugin/ckeditor/ckeditor.js'); #editor texto
        echo $this->Html->script($prefix . 'plugin/superbox/superbox.min.js'); #editor texto
        echo $this->Html->script($prefix . 'plugin/uploadfile/jquery.form.js'); #editor texto
        echo $this->Html->script($prefix . 'plugin/uploadfile/uploadfile.min.js'); #editor texto
        echo $this->Html->script($prefix . 'ajax_init.js'); #AJAX JS
        //                    echo $this->Html->script($prefix.'ajax_all.js');#AJAX JS
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
        //                    echo $this->Html->script('demo.js');#Demo purpose only
        echo $this->Html->script($prefix . 'app.js'); #MAIN APP JS FILE

        #PAGE RELATED PLUGIN(S)
        #Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip
        echo $this->Html->script($prefix . 'plugin/flot/jquery.flot.cust.js');
        echo $this->Html->script($prefix . 'plugin/flot/jquery.flot.resize.js');
        echo $this->Html->script($prefix . 'plugin/flot/jquery.flot.tooltip.js');

        echo $this->Html->script($prefix . 'plugin/morris/raphael.2.1.0.min.js');
        echo $this->Html->script($prefix . 'plugin/morris/morris.min.js');


        //                    <script src="js/plugin/morris/raphael.2.1.0.min.js"></script>
        //                    <script src="js/plugin/morris/morris.min.js"></script>

        //                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.cust.js');
        //                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.resize.js');
        //                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.fillbetween.js');
        //                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.orderBar.js');
        //                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.pie.js');
        //                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.tooltip.js');


        #Vector Maps Plugin: Vectormap engine, Vectormap language
        echo $this->Html->script($prefix . 'plugin/vectormap/jquery-jvectormap-1.2.2.min.js');
        echo $this->Html->script($prefix . 'plugin/vectormap/jquery-jvectormap-world-mill-en.js');

        #Full Calendar
        echo $this->Html->script($prefix . 'plugin/fullcalendar/jquery.fullcalendar.min.js');

        #progress
        echo $this->Html->script($prefix . 'plugin/bootstrap-progressbar/bootstrap-progressbar.js');

        echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';


        echo $this->fetch('script');
        ?>

        <?php
        $controller = $this->params['controller'];
        $action     = $this->params['action'];

        #INCLUSÃO DE COR DINAMICA
        if ($corGE != '') {

        ?>
            <script type="text/javascript">
                var corGE = '<?php echo $corGE; ?>';
                $(".smart-style-3 #header").attr("style", "background-color: " + corGE + " ;background-image: linear-gradient(to bottom, #ffffff, " + corGE);
                $('.smart-style-3 nav>ul>li>a>i').attr('style', "color:" + corGE);
                $('.smart-style-3 nav>ul ul li a i').attr('style', "color:" + corGE);
                $('.smart-style-3 .btn-header>:first-child>a, .smart-style-3 #logo-group ').attr('style', "background-image: linear-gradient(to bottom, #ffffff, " + corGE + ");border-color:" + corGE + ";");
                //      //$(".smart-style-3 nav>ul>li>a>i").attr("style","color: '.$corGE.' ;font-size:20px;");
                //     //  $(document).ready(function(){
                //     //      setTimeout(function(){
                //     //      },1000);
                //     //  }) 
            </script>


        <?php    }





        #VALIDAÇÃO DOS CAMPOS DO USUÁRIO
        if ($controller == 'usuario' && $action == 'admin_add'):
        ?>

            <script type="text/javascript">
                /** BEGIN - USUÁRIO */
                $("#usuario-form").validate({
                    rules: {
                        UsuarioApelido: {
                            required: true
                        },
                        UsuarioNome: {
                            required: true
                        },
                        UsuarioPerfilId: {
                            required: true
                        },
                        UsuarioUsuario: {
                            required: true
                        },
                        UsuarioSenha: {
                            required: true
                        },
                        UsuarioRetrySenha: {
                            required: true,
                            equalTo: "#UsuarioSenha"
                        },
                        UsuarioEmail: {
                            required: true,
                            email: true
                        },
                        // UsuarioTel1Tipo: {
                        //     required: true
                        // },
                        // UsuarioTel1: {
                        //     required: true
                        // },
                        UsuarioStatus: {
                            required: true
                        },
                    },
                    messages: {
                        UsuarioApelido: {
                            required: 'Por favor, entre com o Apelido'
                        },
                        UsuarioNome: {
                            required: 'Por favor, entre com o Nome'
                        },
                        UsuarioPerfilId: {
                            required: 'Por favor, entre com o Perfil'
                        },
                        UsuarioUsuario: {
                            required: 'Por favor, entre com seu Nome de Usuário'
                        },
                        UsuarioSenha: {
                            required: 'O campo senha é obrigatorio'
                        },
                        UsuarioRetrySenha: {
                            required: 'O campo confirmação de senha é obrigatorio',
                            equalTo: "O campo confirmação de senha deve ser idêntico ao campo senha"
                        },
                        UsuarioEmail: {
                            required: 'Por favor, entre com o Email',
                            email: 'Por favor, entre com um email válido!'
                        },
                        // UsuarioTel1Tipo: {
                        //     required: 'Por favor, entre com o Tipo do Telefone'
                        // },
                        // UsuarioTel1: {
                        //     required: 'Por favor, entre com o Telefone'
                        // },
                        UsuarioStatus: {
                            required: 'Por favor, entre com o Status'
                        },
                    },
                    //                submitHandler: function(form) {
                    //                    submit_ajax('save');
                    //                },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });

                /**
                 * BUSCA CIDADES 
                 */



                function busca_cidade(id_estado, cidade_nome) {
                    if (id_estado != '' && cidade_nome != '') {

                        $('#' + cidade_nome).empty();
                        $("#" + cidade_nome).append('<option value="">carregando cidades...</option>');
                        $.ajax({
                            url: "<?php echo Router::url('/usuario/busca_cidades/', true); ?>",
                            data: {
                                busca: id_estado
                            },
                            dataType: "json",
                            type: 'POST',
                            async: true,
                            cache: false,
                            statusCode: {
                                404: function() {
                                    alert(' Desculpe, a página solicitada não foi encontrada!');
                                },
                                500: function() {
                                    alert(' Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!');
                                }
                            },
                            success: function(data) {
                                $('#' + cidade_nome).empty();
                                $("#" + cidade_nome).append('<option value="">Cidades...</option>');
                                jQuery.each(data, function(i, val) {
                                    $("#" + cidade_nome).append('<option value="' + i + '">' + val + '</option>');
                                });
                            },
                            error: function(result) {
                                alert('Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!');
                                $('#' + cidade_nome).empty();
                                $("#" + cidade_nome).append('<option value="">Selecione o Estado...</option>');
                            }
                        });
                        return true;
                    } else {
                        return false;
                    }
                }

                /*BUSCANDO CIDADES*/
                //            $('#UsuarioEstadoId').change(function(){
                //                busca_cidade($(this).val(),'UsuarioCidadeId');
                //            });

                /*BUSCANDO CIDADES JA CADASTRADA*/
                $(document).ready(function() {
                    //                retorno = busca_cidade($('#UsuarioEstado').val(),'UsuarioCidadeId');
                    //                if(retorno == true){
                    //                    setTimeout(function(){
                    //                        $('#UsuarioCidadeId').val($('#UsuarioCidade').val());
                    //                    },1000);
                    //                }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS DO BENEFÍCIO PREVIDENCIARIO
        if ($controller == 'atendimento' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - BENEFÍCIO PREVIDENCIARIO */
                $("#atendimento-form").validate({
                    rules: {
                        // BeneficioPrevidenciarioSituacao: {required: true},
                        AtendimentoTipoAtendimento: {
                            required: true
                        },
                        AtendimentoFormaAtendimento: {
                            required: true
                        },
                        AtendimentoStatusAtendimento: {
                            required: true
                        },
                        AtendimentoAtHoras: {
                            required: true
                        },
                        AtendimentoAtMinutos: {
                            required: true
                        },
                        AtendimentoNovoAgendamento: {
                            required: true
                        },
                        AtendimentoBeneficiarioRetorno: {
                            required: true
                        },
                        AtendimentoDataRetornoAfastamento: {
                            required: true
                        }

                    },
                    messages: {
                        AtendimentoTipoAtendimento: {
                            required: 'Por favor, entre com o tipo de atendimento'
                        },
                        AtendimentoFormaAtendimento: {
                            required: 'Por favor, entre com a forma de atendimento'
                        },
                        AtendimentoStatusAtendimento: {
                            required: 'Por favor, entre com o Status'
                        },
                        AtendimentoAtHoras: {
                            required: 'Por favor, entre com a Hora'
                        },
                        AtendimentoAtMinutos: {
                            required: 'Por favor, entre com o Minuto'
                        },
                        AtendimentoNovoAgendamento: {
                            required: 'Por favor, idenfique se deseja fazer um novo agendamento'
                        },
                        AtendimentoBeneficiarioRetorno: {
                            required: 'Por favor, identifique se o(a) beneficiário(a) retornou ao trabalho.'
                        },
                        AtendimentoDataRetornoAfastamento: {
                            required: 'Por favor, entre com a data do retorno ao trabalho.'
                        }
                    },
                    //                submitHandler: function(form) {
                    //                    submit_ajax('save');
                    //                },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS DO BENEFÍCIO PREVIDENCIARIO
        if ($controller == 'beneficio_previdenciario' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - BENEFÍCIO PREVIDENCIARIO */
                $("#beneficio_previdenciario-form").validate({
                    rules: {
                        // BeneficioPrevidenciarioSituacao: {required: true},
                        BeneficioPrevidenciarioNb: {
                            required: true
                        },
                        BeneficioPrevidenciarioEmpresaId: {
                            required: true
                        },
                        BeneficioPrevidenciarioEspecieBpId: {
                            required: true
                        },
                        BeneficioPrevidenciarioEspecieNew: {
                            required: true
                        },
                        BeneficioPrevidenciarioEspecieBpIdNew: {
                            required: true
                        },
                        BeneficioPrevidenciarioContestadoProtocolo: {
                            required: true
                        },
                        BeneficioPrevidenciarioCatTipoAcidente: {
                            required: true
                        },
                        BeneficioPrevidenciarioStatus: {
                            required: true
                        },
                    },
                    messages: {
                        // BeneficioPrevidenciarioSituacao: {required: 'Por favor, entre com a Situação'},
                        BeneficioPrevidenciarioNb: {
                            required: 'Por favor, entre com o Número do Benefício'
                        },
                        BeneficioPrevidenciarioEmpresaId: {
                            required: 'Por favor, entre com a Empresa'
                        },
                        BeneficioPrevidenciarioEspecieBpId: {
                            required: 'Por favor, entre com ID da Espécie'
                        },
                        BeneficioPrevidenciarioEspecieNew: {
                            required: 'Por favor, entre com o Nome da Espécie'
                        },
                        BeneficioPrevidenciarioEspecieBpIdNew: {
                            required: 'Por favor, entre com ID da Espécie'
                        },
                        BeneficioPrevidenciarioContestadoProtocolo: {
                            required: 'Por favor, entre com o protocolo'
                        },
                        BeneficioPrevidenciarioCatTipoAcidente: {
                            required: 'Por favor, entre com o tipo de acidente'
                        },
                        BeneficioPrevidenciarioStatus: {
                            required: 'Por favor, entre com o Status'
                        },
                    },
                    //                submitHandler: function(form) {
                    //                    submit_ajax('save');
                    //                },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS DO AGENDAMENTO
        if ($controller == 'agendamento' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - BENEFÍCIO PREVIDENCIARIO */
                $("#agendamento-form").validate({
                    rules: {
                        AgendamentoUsuarioAgendamentoProprioId: {
                            required: true
                        },
                        AgendamentoUsuarioAgendamentoId: {
                            required: true
                        },
                    },
                    messages: {
                        AgendamentoUsuarioAgendamentoProprioId: {
                            required: 'Por favor, escolha se o agendamento é para você!'
                        },
                        AgendamentoUsuarioAgendamentoId: {
                            required: 'Por favor, escolha para quem será agendado.'
                        },
                    },
                    //                submitHandler: function(form) {
                    //                    submit_ajax('save');
                    //                },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;



        #VALIDAÇÃO DOS CAMPOS DO AGENDAMENTO
        if ($controller == 'mh_critico_historico' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - BENEFÍCIO PREVIDENCIARIO */
                $("#mh_critico_historico-form").validate({
                    rules: {
                        MhCriticoHistoricoCiclo: {
                            required: true
                        },
                        MhCriticoHistoricoStatusCiclo: {
                            required: true
                        },
                        MhCriticoHistoricoStatus: {
                            required: true
                        }
                    },
                    messages: {
                        MhCriticoHistoricoCiclo: {
                            required: 'Por favor, escolha o ciclo.'
                        },
                        MhCriticoHistoricoStatusCiclo: {
                            required: 'Por favor, escolha Status do Ciclo.'
                        },
                        MhCriticoHistoricoStatus: {
                            required: 'Por favor, escolha o status'
                        }
                    },
                    //                submitHandler: function(form) {
                    //                    submit_ajax('save');
                    //                },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;



        #VALIDAÇÃO DOS CAMPOS DO ABSENTEISMO
        if ($controller == 'absenteismo' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - ABSENTEISMO */
                $("#absenteismo-form").validate({
                    rules: {
                        AbsenteismoSituacao: {
                            required: true
                        },
                        AbsenteismoEmpresaId: {
                            required: true
                        },
                        AbsenteismoDocumentoId: {
                            required: true
                        },
                        AbsenteismoMotivoId: {
                            required: true
                        },
                        AbsenteismoEspecialidadeId: {
                            required: true
                        },
                        AbsenteismoEmissorId: {
                            required: true
                        },
                        AbsenteismoTipoAbsenteismoId: {
                            required: true
                        },
                        BeneficioPrevidenciarioStatus: {
                            required: true
                        },
                    },
                    messages: {
                        AbsenteismoSituacao: {
                            required: 'Por favor, entre com a Situação'
                        },
                        AbsenteismoEmpresaId: {
                            required: 'Por favor, entre com a Empresa'
                        },
                        AbsenteismoDocumentoId: {
                            required: 'Por favor, entre com o Documento'
                        },
                        AbsenteismoMotivoId: {
                            required: 'Por favor, entre com o Motivo'
                        },
                        AbsenteismoEspecialidadeId: {
                            required: 'Por favor, entre coma Especialidade'
                        },
                        AbsenteismoEmissorId: {
                            required: 'Por favor, entre coma Especialidade'
                        },
                        AbsenteismoTipoAbsenteismoId: {
                            required: 'Por favor, entre coma Especialidade'
                        },
                        BeneficioPrevidenciarioStatus: {
                            required: 'Por favor, entre com o Status'
                        },
                    },
                    //                submitHandler: function(form) {
                    //                    submit_ajax('save');
                    //                },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;

        #VALIDAÇÃO DOS CAMPOS DO AFASTADO
        if ($controller == 'afastado' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - AFASTADO */
                $("#afastado-form").validate({
                    rules: {
                        AfastadoSituacao: {
                            required: true
                        },
                        AfastadoEmpresaId: {
                            required: true
                        },
                        AfastadoAcaoTrabalhista: {
                            required: true
                        },
                        AfastadoAcaoInss: {
                            required: true
                        },
                        AfastadoLimboPrevidenciario: {
                            required: true
                        },
                        AfastadoStatus: {
                            required: true
                        },
                    },
                    messages: {
                        AfastadoSituacao: {
                            required: 'Por favor, entre com a Situação'
                        },
                        AfastadoEmpresaId: {
                            required: 'Por favor, entre com a Empresa'
                        },
                        AfastadoAcaoTrabalhista: {
                            required: 'Por favor, entre se possui ação trabalhista'
                        },
                        AfastadoAcaoInss: {
                            required: 'Por favor, entre se possui ação contra o INSS'
                        },
                        AfastadoLimboPrevidenciario: {
                            required: 'Por favor, entre com a opção Limbo previdenciário'
                        },
                        AfastadoStatus: {
                            required: 'Por favor, entre com o Status'
                        },
                    },
                    //                submitHandler: function(form) {
                    //                    submit_ajax('save');
                    //                },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;



        #VALIDAÇÃO DOS CAMPOS DO USUÁRIO
        if ($controller == 'importacao' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - USUÁRIO */
                $("#importacao-form").validate({
                    rules: {
                        ImportacaoTipoImportacao: {
                            required: true
                        },
                        ImportacaoBeneficioId: {
                            required: true
                        },
                        ImportacaoCompetenciaMes: {
                            required: true
                        },
                        ImportacaoCompetenciaAno: {
                            required: true
                        },
                        ImportacaoArquivo: {
                            required: true
                        },
                    },
                    messages: {
                        ImportacaoTipoImportacao: {
                            required: 'Por favor, entre com o Tipo de Importação'
                        },
                        ImportacaoBeneficioId: {
                            required: 'Por favor, entre com o Beneficio'
                        },
                        ImportacaoCompetenciaMes: {
                            required: 'Por favor, entre com o Mês da Competência'
                        },
                        ImportacaoCompetenciaAno: {
                            required: 'Por favor, entre com o Ano da Competência'
                        },
                        ImportacaoArquivo: {
                            required: 'Por favor, entre com o Arquivo'
                        },
                    },
                    submitHandler: function(form) {
                        if (window.SamedLoadingOverlay) {
                            SamedLoadingOverlay.show('Importando base, aguarde...');
                        }
                        $(form).find('button[type="submit"]').prop('disabled', true);
                        form.submit();
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS DO BENEFICIARIO
        if ($controller == 'beneficiario' && in_array($action, array('admin_add', 'admin_operador'))):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#beneficiario-form").validate({
                    rules: {
                        BeneficiarioProtocolo: {
                            required: true
                        },
                        BeneficiarioNome: {
                            required: true
                        },
                        BeneficiarioEmpresaId: {
                            required: true
                        },
                        // BeneficiarioBeneficio: {required: true},
                        BeneficiarioCpf: {
                            required: true
                        },
                        BeneficiarioSexo: {
                            required: true
                        },
                        BeneficiarioDataNascimento: {
                            required: true
                        },
                        BeneficiarioStatus: {
                            required: true
                        }
                    },
                    messages: {
                        BeneficiarioProtocolo: {
                            required: 'Por favor, entre com o Protocolo'
                        },
                        BeneficiarioNome: {
                            required: 'Por favor, entre com o Nome'
                        },
                        BeneficiarioEmpresaId: {
                            required: 'Por favor, entre com a Empresa'
                        },
                        // BeneficiarioBeneficio: {required: 'Por favor, entre com o Benefício'},
                        BeneficiarioCpf: {
                            required: 'Por favor, entre com o CPF'
                        },
                        BeneficiarioSexo: {
                            required: 'Por favor, entre com o Sexo'
                        },
                        BeneficiarioDataNascimento: {
                            required: 'Por favor, entre com a Data de Nascimento'
                        },
                        BeneficiarioStatus: {
                            required: 'Por favor, entre com o Status'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS DO EMPRESA
        if ($controller == 'empresa' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#empresa-form").validate({
                    rules: {
                        EmpresaNome: {
                            required: true
                        },
                        EmpresaCnpj: {
                            required: true
                        },
                        EmpresaStatus: {
                            required: true
                        },
                    },
                    messages: {
                        EmpresaNome: {
                            required: 'Por favor, entre com o Nome da Empresa'
                        },
                        EmpresaCnpj: {
                            required: 'Por favor, entre com o CNPJ da Empresa'
                        },
                        EmpresaStatus: {
                            required: 'Por favor, entre com o Status'
                        },
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS DO GRUPO EMPRESARIAL
        if ($controller == 'grupo_empresarial' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#grupo_empresarial-form").validate({
                    rules: {
                        GrupoEmpresarialNome: {
                            required: true
                        },
                        GrupoEmpresarialStatus: {
                            required: true
                        },
                    },
                    messages: {
                        GrupoEmpresarialNome: {
                            required: 'Por favor, entre com o Nome do Grupo Empresarial'
                        },
                        GrupoEmpresarialStatus: {
                            required: 'Por favor, entre com o Status'
                        },
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS DO GRUPO EMPRESARIAL
        if ($controller == 'cliente' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#cliente-form").validate({
                    rules: {
                        ClienteNome: {
                            required: true
                        },
                        ClienteStatus: {
                            required: true
                        }
                    },
                    messages: {
                        ClienteNome: {
                            required: 'Por favor, entre com o Nome do Cliente'
                        },
                        ClienteStatus: {
                            required: 'Por favor, entre com o Status'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS DO Operadora
        if ($controller == 'operadora' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#operadora-form").validate({
                    rules: {
                        OperadoraNome: {
                            required: true
                        },
                        OperadoraStatus: {
                            required: true
                        }
                    },
                    messages: {
                        OperadoraNome: {
                            required: 'Por favor, entre com o Nome da Operadora'
                        },
                        OperadoraStatus: {
                            required: 'Por favor, entre com o Status'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;



        #VALIDAÇÃO DOS CAMPOS Da Subfatura
        if ($controller == 'subfatura' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#subfatura-form").validate({
                    rules: {
                        SubfaturaBeneficioId: {
                            required: true
                        },
                        SubfaturaDescricao: {
                            required: true
                        },
                        SubfaturaCodigo: {
                            required: true
                        },
                        SubfaturaStatus: {
                            required: true
                        }
                    },
                    messages: {
                        SubfaturaBeneficioId: {
                            required: 'Por favor, entre com o Beneficio'
                        },
                        SubfaturaDescricao: {
                            required: 'Por favor, entre com a Descrição da Subfatura'
                        },
                        SubfaturaCodigo: {
                            required: 'Por favor, entre com o Código'
                        },
                        SubfaturaStatus: {
                            required: 'Por favor, entre com o Status'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;



        #VALIDAÇÃO DOS CAMPOS Beneficio
        if ($controller == 'beneficio' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#beneficio-form").validate({
                    rules: {
                        BeneficioDescricao: {
                            required: true
                        },
                        BeneficioOperadoraId: {
                            required: true
                        },
                        BeneficioTipoBeneficioId: {
                            required: true
                        },
                        BeneficioStatus: {
                            required: true
                        }
                    },
                    messages: {
                        BeneficioDescricao: {
                            required: 'Por favor, entre com a Descrição do Benefício'
                        },
                        BeneficioOperadoraId: {
                            required: 'Por favor, entre com a Operadora'
                        },
                        BeneficioTipoBeneficioId: {
                            required: 'Por favor, entre com o Tipo de Beneficio'
                        },
                        BeneficioStatus: {
                            required: 'Por favor, entre com o Status'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS plano
        if ($controller == 'plano' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#plano-form").validate({
                    rules: {
                        PlanoNome: {
                            required: true
                        },
                        PlanoOrdem: {
                            required: true,
                            positiveNumber: "#PlanoOrdem"
                        },
                        PlanoStatus: {
                            required: true
                        }
                    },
                    messages: {
                        PlanoNome: {
                            required: 'Por favor, entre com o Nome do Plano'
                        },
                        PlanoOrdem: {
                            required: 'Por favor, entre com a Ordenação',
                            positiveNumber: 'Por Favor, entre com um Número Positivo'
                        },
                        PlanoStatus: {
                            required: 'Por favor, entre com o Status'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;



        #VALIDAÇÃO DOS CAMPOS plano
        if ($controller == 'procedimento' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#procedimento-form").validate({
                    rules: {
                        ProcedimentoTuss: {
                            required: true
                        },
                        ProcedimentoDescricao: {
                            required: true
                        },
                        ProcedimentoStatus: {
                            required: true
                        }
                    },
                    messages: {
                        ProcedimentoTuss: {
                            required: 'Por favor, entre com o TUSS'
                        },
                        ProcedimentoDescricao: {
                            required: 'Por favor, entre com a Descrição'
                        },
                        ProcedimentoStatus: {
                            required: 'Por favor, entre com o Status'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS plano
        if ($controller == 'tipo_beneficio' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#tipo_beneficio-form").validate({
                    rules: {
                        TipoBeneficioDescricao: {
                            required: true
                        },
                        TipoBeneficioStatus: {
                            required: true
                        }
                    },
                    messages: {
                        TipoBeneficioDescricao: {
                            required: 'Por favor, entre com a Descrição do Tipo de Beneficio'
                        },
                        TipoBeneficioStatus: {
                            required: 'Por favor, entre com o Status'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS plano
        if ($controller == 'perfil' && $action == 'admin_add'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#perfil-form").validate({
                    rules: {
                        PerfilNome: {
                            required: true
                        },
                        PerfilTipo: {
                            required: true
                        },
                        PerfilStatus: {
                            required: true
                        }
                    },
                    messages: {
                        PerfilNome: {
                            required: 'Por favor, entre com o Nome'
                        },
                        PerfilTipo: {
                            required: 'Por favor, entre com o Tipo'
                        },
                        PerfilStatus: {
                            required: 'Por favor, entre com o Status'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;



        #VALIDAÇÃO DOS CAMPOS plano
        if ($controller == 'relatorio' && $action == 'admin_exportacao'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#relatorio-form").validate({
                    rules: {
                        RelatorioTipo: {
                            required: true
                        },
                        RelatorioBeneficioId: {
                            required: true
                        },
                        RelatorioDataReferencia: {
                            required: true
                        },
                        RelatorioMesAno: {
                            required: true
                        },
                        RelatorioPeriodo: {
                            required: true
                        }
                    },
                    messages: {
                        RelatorioTipo: {
                            required: 'Por favor, entre com o Tipo de Exportação'
                        },
                        RelatorioBeneficioId: {
                            required: 'Por favor, entre com o Beneficio'
                        },
                        RelatorioDataReferencia: {
                            required: 'Por favor, entre com a Data de Referência'
                        },
                        RelatorioMesAno: {
                            required: 'Por favor, entre com o Data Referência'
                        },
                        RelatorioPeriodo: {
                            required: 'Por favor, entre com o Período'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            </script>
        <?php endif;


        #VALIDAÇÃO DOS CAMPOS plano
        if ($controller == 'relatorio' && $action == 'admin_gerencial'):
        ?>
            <script type="text/javascript">
                /** BEGIN - EMPRESA */
                $("#relatorio-form").validate({
                    rules: {
                        RelatorioBeneficioId: {
                            required: true
                        },
                        RelatorioSubfaturaId: {
                            required: true
                        },
                        RelatorioPlanoId: {
                            required: true
                        },
                        RelatorioDataReferencia: {
                            required: true
                        },
                        RelatorioMes: {
                            required: true
                        },
                        RelatorioAno: {
                            required: true
                        },
                        RelatorioPeriodo: {
                            required: true
                        },
                        RelatorioCopart: {
                            required: true
                        },
                        RelatorioMaioresUtilizadores: {
                            required: true
                        },
                        RelatorioMaioresPrestadores: {
                            required: true
                        },
                        RelatorioQtdConsultasHiper: {
                            required: true
                        }
                    },
                    messages: {
                        RelatorioBeneficioId: {
                            required: 'Por favor, entre com o Beneficio'
                        },
                        RelatorioSubfaturaId: {
                            required: 'Por favor, entre com a Subfatura'
                        },
                        RelatorioPlanoId: {
                            required: 'Por favor, entre com o Plano'
                        },
                        RelatorioDataReferencia: {
                            required: 'Por favor, entre com a Data de Referência'
                        },
                        RelatorioMes: {
                            required: 'Por favor, entre com o Mês da Data Inicial.'
                        },
                        RelatorioAno: {
                            required: 'Por favor, entre com o Ano da Data Inicial.'
                        },
                        RelatorioPeriodo: {
                            required: 'Por favor, entre com o Periodo.'
                        },
                        RelatorioCopart: {
                            required: 'Por favor, entre com a Coparticipação.'
                        },
                        RelatorioMaioresUtilizadores: {
                            required: 'Por favor, entre com a Quantidade de Maiores Utilizadores.'
                        },
                        RelatorioMaioresPrestadores: {
                            required: 'Por favor, entre com a Quantidade de Maiores Prestadores.'
                        },
                        RelatorioQtdConsultasHiper: {
                            required: 'Por favor, entre com a Quantidade de Hiper Consultadores.'
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });




                function valida_paginas() {
                    var paginas = '';
                    i = 1;
                    check = 0;
                    $('.ck_pagina').each(function(key, val) {
                        if ($(this).is(':checked')) {
                            id = $(this).val();
                            paginas += "{" + i + ":" + id + "}";
                            i++;
                            check = 1;
                        }
                    });

                    if (check == 0) {
                        $('#aviso_paginas').slideDown().html('<em style="color: #D56161;font-style: normal;font-weight: normal; font-size:11px; margin-left:10px; marginoo-top:10px;">Por favor, escolha ao menos uma página</em>');
                        $('.aviso_paginas').attr('style', 'border: 1px #D56161 solid;background:#fff0f0; margin:5px; padding:10px;');
                        $('#aviso_paginas').focus();
                        return false;
                    } else {
                        $('#aviso_paginas').slideDown().html('');
                        $('.aviso_paginas').attr('style', 'margin:5px; padding:10px;');
                        return true;
                    }

                }

                $('.ck_pagina').click(function() {
                    retorno = valida_paginas();
                });

                $('#relatorio-form').submit(function() {
                    retorno = valida_paginas();
                    if (retorno == false) {
                        return false;
                    }
                });
            </script>
        <?php endif;



        #ATUALIZAÇÃO DASHBOARD
        if ($controller == 'home' && $action == 'admin_index'):

        ?>

            <script type="text/javascript">
                if ($('#sales-graph').length) {

                    Morris.Area({
                        element: 'sales-graph',
                        data: [{
                            period: '2010 Q1',
                            iphone: 2666,
                            ipad: null,
                            itouch: 2647
                        }, {
                            period: '2010 Q2',
                            iphone: 2778,
                            ipad: 2294,
                            itouch: 2441
                        }, {
                            period: '2010 Q3',
                            iphone: 4912,
                            ipad: 1969,
                            itouch: 2501
                        }, {
                            period: '2010 Q4',
                            iphone: 3767,
                            ipad: 3597,
                            itouch: 5689
                        }, {
                            period: '2011 Q1',
                            iphone: 6810,
                            ipad: 1914,
                            itouch: 2293
                        }, {
                            period: '2011 Q2',
                            iphone: 5670,
                            ipad: 4293,
                            itouch: 1881
                        }, {
                            period: '2011 Q3',
                            iphone: 4820,
                            ipad: 3795,
                            itouch: 1588
                        }, {
                            period: '2011 Q4',
                            iphone: 15073,
                            ipad: 5967,
                            itouch: 5175
                        }, {
                            period: '2012 Q1',
                            iphone: 10687,
                            ipad: 4460,
                            itouch: 2028
                        }, {
                            period: '2012 Q2',
                            iphone: 8432,
                            ipad: 5713,
                            itouch: 1791
                        }],
                        xkey: 'period',
                        ykeys: ['iphone', 'ipad', 'itouch'],
                        labels: ['iPhone', 'iPad', 'iPod Touch'],
                        pointSize: 2,
                        hideHover: 'auto'
                    });

                }



                // bar graph color
                if ($('#bar-graph').length) {

                    Morris.Bar({
                        element: 'bar-graph',
                        data: [{
                            x: '2011 Q1',
                            y: 0
                        }, {
                            x: '2011 Q2',
                            y: 1
                        }, {
                            x: '2011 Q3',
                            y: 2
                        }, {
                            x: '2011 Q4',
                            y: 3
                        }, {
                            x: '2012 Q1',
                            y: 4
                        }, {
                            x: '2012 Q2',
                            y: 5
                        }, {
                            x: '2012 Q3',
                            y: 6
                        }, {
                            x: '2012 Q4',
                            y: 7
                        }, {
                            x: '2013 Q1',
                            y: 8
                        }],
                        xkey: 'x',
                        ykeys: ['y'],
                        labels: ['Y'],
                        barColors: function(row, series, type) {
                            if (type === 'bar') {
                                var red = Math.ceil(150 * row.y / this.ymax);
                                return 'rgb(' + red + ',0,0)';
                            } else {
                                return '#000';
                            }
                        }
                    });

                }



                // donut
                if ($('#donut-graph').length) {
                    Morris.Donut({
                        element: 'donut-graph',
                        data: [{
                            value: 70,
                            label: 'foo'
                        }, {
                            value: 15,
                            label: 'bar'
                        }, {
                            value: 10,
                            label: 'baz'
                        }, {
                            value: 5,
                            label: 'A really really long label'
                        }],
                        formatter: function(x) {
                            return x + "%"
                        }
                    });
                }
            </script>



        <?php endif; ?>
        <script type="text/javascript">
            $(document).ready(function() {
                pageSetUp();


                $('#select_cliente_id').select2({
                    width: 'resolve', // Ajusta a largura dinamicamente
                    dropdownAutoWidth: true, // Garante que o dropdown se ajusta
                    //minimumResultsForSearch: Infinity // Remove a barra de pesquisa se não for necessária
                });

            })
        </script>

    </div>
</body>

</html>