<?php
/**
 *
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 */
$cakeDescription = __d('cake_dev', Configure::read('variaveis.project'));
$description = "Description of Site";
$keyworkds = "key,words,of,site";
$robots= "index, follow";
$copyright = "Copyright";
$language = "PT";


 
    
    
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
            #bloco de metatags padrões / unicas por página 
            echo $this->Html->meta('viewport', null, array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'));
            echo $this->Html->meta('description', null, array('name' => 'description', 'content' => $description));
            echo $this->Html->meta('keywords', null, array('name' => 'keywords', 'content' => $keyworkds));
            echo $this->Html->meta('robots', null, array('name' => 'robots', 'content' => $robots));
            echo $this->Html->meta('copyright', null, array('name' => 'copyright', 'content' => $copyright));
            echo $this->Html->meta('language', null, array('name' => 'language', 'content' => $language));
            
            #Facebook Open Graph Meta Tags
//            <meta property="og:title" content="Titulo da Pagina" />
//            <meta property="og:type" content="website" />
//            <meta property="og:url" content="http://www.url.com.br" />
//            <meta property="og:image" content="http://www.url.com.br/opengrapho-image.png" />
//            <meta property="og:site_name" content="Site Name" />
//            <meta property="fb:admins" content="120900901328752_id_aplicacao" />
//            <meta property="og:description" content="Descricao Open Grapho" />
//            <fb:like href="http://developers.facebook.com/" width="450" height="80" />
            
            
            #Includes CSS blocos gerais all pages 
            
            echo $this->Html->css('normalize.css');
            echo $this->Html->css('foundation.css');
            echo $this->Html->css('beatpicker/beatpicker.css');
            echo $this->Html->css('zozo-ui/zozo.tabs.css');
            echo $this->Html->css('slick/slick.css');
            echo $this->Html->css('bxslider/jquery.bxslider.css');
            echo $this->Html->css('base.css');
            
            echo $this->fetch('meta');
            echo $this->fetch('css');
            
            #Includes JS Padrões
            echo $this->Html->script('vendor/modernizr.js');
            echo $this->fetch('js');
        ?>
 
    
    </head>
    <body>
         <div id="toTop">^ Voltar ao Topo</div>
         <!--cabeçalho-->
         
        <?php
            #$cache = array('cache'=>true);#DESCOMENTAR
            $cache = array();#COMENTAR
            
            echo $this->element('front/header',array(),$cache); 
            echo $this->element('front/breadcrumb',array(),$cache); 
            echo $this->element('front/search',array(),$cache); 
            echo '<div class="row main-container">';
                    echo $this->Form->msg($this->Session->flash());
                    echo $this->fetch('content');
                    echo $this->element('front/sidebar',array(),$cache); 
            echo '</div>';
            echo $this->element('front/footer',array(),$cache); 
//            echo  $this->element('sql_dump');
            
//            <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
//            echo $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');

            #Includes Jquery Biblioteca Foundation 2.1.1
            echo $this->Html->script('vendor/jquery.js');
            
            #Includes Biblioteca Foundation 2.1.1
            echo $this->Html->script('foundation.min.js');
            
            #Includes Biblioteca Particulares 
            echo $this->Html->script('beatpicker/beatpicker.js');
            echo $this->Html->script('zozo-ui/zozo.tabs.js');#DESTINOS ESTÁ USANDO
            echo $this->Html->script('masonry.pkgd.min.js');
            echo $this->Html->script('slick/slick.js');
            echo $this->Html->script('bxslider/jquery.bxslider.min.js');
            echo $this->Html->script('jquery.autohide.updated.js');
            echo $this->Html->script('jquery-clockpicker.min.js');
            echo $this->Html->script('nouislider/jquery.nouislider.all.min.js');
            echo $this->Html->script('plugins.js');
            echo $this->Html->script('main.js');
            
            $fileCuston = new File(JS_URL . 'custom-'.$this->params['action'].'.js');            
            if ($fileCuston->exists()) {
                echo $this->Html->script('custom-'.$this->params['action'].'.js');
            }
            
            

            echo $this->fetch('script');
        ?>
         
         
         
        <script>
                
            
//            
//            function enviaForm(){
//                $("#formEnviar").submit(function(){
//                    var data = $("#formEnviar").serialize();
//                    $.ajax({
//                        url:'http://<dominio>.hypnobox.com.br/email.receber.php',
//                        data: data,
//                        crosDomain: true,
//                        dataType: 'jsonp',
//                        type:'POST',
//                        async: false,
//                        sucess: function(msg){
//                            window.location.href = 'sucesso.html';
//                        },
//                        error: function(msg){
//                            window.location.href = 'erro.html';
//                        }
//                    });
//                    return false;
//                });
//            }




      </script>
         
    </body>
</html>


