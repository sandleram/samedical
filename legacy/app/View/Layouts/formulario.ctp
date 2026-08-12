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
            <?php // echo $cakeDescription.':'; ?>
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

    


    <body id="login" >
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

    echo $this->Html->script($prefix . 'bootstrap/bootstrap.min.js'); #BOOTSTRAP JS
    echo $this->Html->script($prefix . 'notification/SmartNotification.min.js'); #CUSTOM NOTIFICATION
    echo $this->Html->script($prefix . 'smartwidgets/jarvis.widget.min.js'); #JARVIS WIDGETS
    echo $this->Html->script($prefix . 'plugin/easy-pie-chart/jquery.easy-pie-chart.min.js'); #EASY PIE CHARTS
    echo $this->Html->script($prefix . 'plugin/sparkline/jquery.sparkline.min.js'); #SPARKLINES
    echo $this->Html->script($prefix.'plugin/jquery-validate/jquery.validate.min.js');#JQUERY VALIDATE
    echo $this->Html->script($prefix.'plugin/masked-input/jquery.maskedinput.min.js');#JQUERY MASKED INPUT
    echo $this->Html->script($prefix.'plugin/masked-money/jquery.maskMoney.js');#JQUERY MASKED MONEY INPUT
    echo $this->Html->script($prefix . 'plugin/select2/select2.min.js'); #JQUERY SELECT2 INPUT
    echo $this->Html->script($prefix . 'plugin/bootstrap-slider/bootstrap-slider.min.js'); #JQUERY UI + Bootstrap Slider
    echo $this->Html->script($prefix . 'plugin/msie-fix/jquery.mb.browser.min.js'); #browser msie issue fix
    echo $this->Html->script($prefix . 'plugin/fastclick/fastclick.js'); #FastClick: For mobile devices
    echo '<!--[if IE 7]>';
    echo '<h1>Seu navegador está desatualizado, por favor atualize acesando www.microsoft.com/download</h1>';
    echo '<![endif]-->';
    echo $this->Html->script($prefix . 'app.js'); #MAIN APP JS FILE
    echo $this->Html->script($prefix . 'ajax_init.js'); #MAIN APP JS FILE

    echo $this->fetch('script');




?>





        <script type="text/javascript">
            runAllForms();
            $(function () {
                <?php     
                
                 #VALIDAÇÃO DOS CAMPOS DO VESTIBULAR
                 if($this->params['controller'] == 'usuario' && $this->params['action'] == 'cadastrese'): 
                ?>     
                    $("#usuario-form").validate({
                    rules: {
                        UsuarioNome: {required: true},
                        UsuarioCpf: {required: true},
                        UsuarioEmail: {required: true, email: true},
                        UsuarioTel1Tipo: {required: true},
                        UsuarioTel1: {required: true},
                        UsuarioPublicidade: {required: true},
                        UsuarioCursoId: {required: true},
                        UsuarioEmpresaId: {required: true}
                    },
                    messages: {
                        UsuarioNome: {required: 'Por favor, entre com o Nome'},
                        UsuarioCpf: {required: 'Por favor, entre com o CPF'},
                        UsuarioEmail: {required: 'Por favor, entre com o Email', email: 'Por favor, entre com um email válido!'},
                        UsuarioTel1Tipo: {required: 'Por favor, entre com o Tipo do Telefone'},
                        UsuarioTel1: {required: 'Por favor, entre com o Telefone'},
                        UsuarioPublicidade: {required: 'Por favor, selecione onde como nos conheceu?'},
                        UsuarioCursoId: {required: 'Por favor, selecione o Curso'},
                        UsuarioEmpresaId: {required: 'Por favor, selecione a Unidade'}

                    },

                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });


                 <?php  endif;?>     
            });


            /* BUSCA CURSOS NA TELA DE CADASTRO*/
            function busca_cursos(busca){
                $("#UsuarioEmpresaId").empty();
                $("#UsuarioEmpresaId").append('<option value="">carregando faculdades...</option>');
                if(busca != ''){
                    $.ajax({
                        url:"<?php echo Router::url('/usuario/busca_faculdades/',true); ?>",
                        data: {busca: busca, busca_faculdade:<?php echo $this->params['pass'][0];?>},
                        dataType: "json",
                        type: 'POST',
                        async: true,
                        cache: false,
                        statusCode: {
                            404: function() {alert(' Desculpe, a página solicitada não foi encontrada!'); },
                            500: function() {alert(' Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!'); }
                        },
                        success: function(data) {
                            $('#UsuarioEmpresaId').empty();
                            $("#UsuarioEmpresaId").append('<option value="">Faculdades...</option>');
                            jQuery.each(data, function(i, val) {
                                $("#UsuarioEmpresaId").append('<option value="'+i+'">'+val+'</option>');
                            });
                        },
                        error: function(result) {
                            alert('Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!');
                            $('#UsuarioEmpresaId').empty();
                            $("#UsuarioEmpresaId").append('<option value="">Selecione o Curso...</option>');
                        }
                    });
                }
            }

            /*CADASTRO E RETORNO DE ERRO PARA FACULDADE*/
            $(document).ready(function(){
                $("#UsuarioCursoId").change(function(){
                    busca = $(this).val();
                    busca_cursos(busca);
                });
                cursoId = $("#UsuarioCursoId").val();
                empresaId = $("#UsuarioEmpresa").val();
                if(cursoId != '' && empresaId != ''){
                    busca_cursos(cursoId);
                    setTimeout(function(){$("#UsuarioEmpresaId").val(empresaId);},1000);
                }
                
            });
            
           
        </script>
    </body>
</html>
